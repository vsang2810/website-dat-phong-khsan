<?php
declare(strict_types=1);

final class Booking extends Model
{
    private const BOOKING_BLOCK_STATUSES = "('pending_payment','pending_hotel_payment','confirmed','checked_in')";

    public function paginate(array $f, int $page = 1, int $perPage = 12): array
    {
        $where = [];
        $params = [];
        if ($f['q'] !== '') {
            $where[] = '(b.booking_code LIKE :q1 OR b.guest_name LIKE :q2 OR b.guest_email LIKE :q3 OR b.guest_phone LIKE :q4)';
            $term = '%' . $f['q'] . '%';
            $params['q1'] = $term;
            $params['q2'] = $term;
            $params['q3'] = $term;
            $params['q4'] = $term;
        }
        if ($f['status'] !== '') {
            $where[] = 'b.booking_status=:status';
            $params['status'] = $f['status'];
        }
        if ($f['method'] !== '') {
            $where[] = 'b.payment_method=:method';
            $params['method'] = $f['method'];
        }
        if ($f['from'] !== '') {
            $where[] = 'b.check_in>=:date_from';
            $params['date_from'] = $f['from'];
        }
        if ($f['to'] !== '') {
            $where[] = 'b.check_in<=:date_to';
            $params['date_to'] = $f['to'];
        }
        $clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $st = $this->db->prepare("SELECT COUNT(*) FROM bookings b $clause");
        $st->execute($params);
        $total = (int)$st->fetchColumn();
        $pages = max(1, (int)ceil($total / $perPage));
        $page = min(max(1, $page), $pages);
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT b.id,b.booking_code,b.guest_name,b.guest_email,b.guest_phone,b.check_in,b.check_out,b.total_guests,b.total_amount,b.payment_method,b.booking_status,b.payment_status,b.created_at,h.name hotel_name,EXISTS(SELECT 1 FROM payments p WHERE p.booking_id=b.id AND p.payment_status='success') has_success_payment FROM bookings b LEFT JOIN hotels h ON h.id=b.hotel_id $clause ORDER BY b.created_at DESC,b.id DESC LIMIT $perPage OFFSET $offset";
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return ['items' => $st->fetchAll(), 'total' => $total, 'page' => $page, 'pages' => $pages];
    }

    public function find(int $id): ?array
    {
        $st = $this->db->prepare(
            "SELECT b.*,h.name hotel_name,h.address hotel_address,h.phone hotel_phone,h.email hotel_email,
                    EXISTS(SELECT 1 FROM payments p WHERE p.booking_id=b.id AND p.payment_status='success') has_success_payment,
                    u.full_name confirmer_name, prm.name promotion_name, prm.code promotion_code
             FROM bookings b
             LEFT JOIN hotels h ON h.id=b.hotel_id
             LEFT JOIN users u ON u.id=b.confirmed_by
             LEFT JOIN promotions prm ON prm.id=b.promotion_id
             WHERE b.id=:id"
        );
        $st->execute(['id' => $id]);
        $b = $st->fetch();
        if (!$b) return null;

        $st = $this->db->prepare(
            'SELECT br.*,r.room_number,rt.name room_type_name,rt.thumbnail room_thumbnail,rt.bed_type,rt.area
             FROM booking_rooms br
             LEFT JOIN rooms r ON r.id=br.room_id
             LEFT JOIN room_types rt ON rt.id=r.room_type_id
             WHERE br.booking_id=:id'
        );
        $st->execute(['id' => $id]);
        $b['rooms'] = $st->fetchAll();

        $st = $this->db->prepare(
            'SELECT bs.*,s.name service_name,s.image service_image
             FROM booking_services bs
             INNER JOIN services s ON s.id=bs.service_id
             WHERE bs.booking_id=:id'
        );
        $st->execute(['id' => $id]);
        $b['services'] = $st->fetchAll();

        $st = $this->db->prepare(
            'SELECT p.*,u.full_name confirmer_name
             FROM payments p
             LEFT JOIN users u ON u.id=p.confirmed_by
             WHERE p.booking_id=:id
             ORDER BY p.id DESC'
        );
        $st->execute(['id' => $id]);
        $b['payments'] = $st->fetchAll();

        return $b;
    }

    public function findByCode(string $code): ?array
    {
        $st = $this->db->prepare('SELECT id FROM bookings WHERE booking_code = :code LIMIT 1');
        $st->execute(['code' => trim($code)]);
        $id = $st->fetchColumn();
        return $id ? $this->find((int)$id) : null;
    }

    public function findByCodeAndContact(string $code, string $contact): ?array
    {
        $contact = trim($contact);
        $st = $this->db->prepare(
            "SELECT id FROM bookings
             WHERE booking_code = :code
               AND (guest_email = :contact1 OR guest_phone = :contact2)
             LIMIT 1"
        );
        $st->execute([
            'code' => trim($code),
            'contact1' => $contact,
            'contact2' => $contact,
        ]);
        $id = $st->fetchColumn();
        return $id ? $this->find((int)$id) : null;
    }

    public function getByUser(int $userId): array
    {
        $sql = "SELECT b.id, b.booking_code, b.check_in, b.check_out, b.number_of_nights,
                       b.total_guests, b.total_amount, b.payment_method, b.booking_status,
                       b.payment_status, b.created_at, h.name AS hotel_name,
                       (SELECT br.room_name_snapshot FROM booking_rooms br WHERE br.booking_id = b.id LIMIT 1) AS room_name,
                       (SELECT rt.thumbnail FROM booking_rooms br
                        INNER JOIN rooms r ON r.id = br.room_id
                        INNER JOIN room_types rt ON rt.id = r.room_type_id
                        WHERE br.booking_id = b.id LIMIT 1) AS room_thumbnail
                FROM bookings b
                LEFT JOIN hotels h ON h.id = b.hotel_id
                WHERE b.user_id = :user_id
                ORDER BY b.id DESC";
        $st = $this->db->prepare($sql);
        $st->execute(['user_id' => $userId]);
        return $st->fetchAll();
    }

    public function createCustomerBooking(array $data): array
    {
        $this->db->beginTransaction();
        try {
            $roomTypeId = (int)$data['room_type_id'];
            $checkIn = $data['check_in'];
            $checkOut = $data['check_out'];
            $totalGuests = max(1, (int)($data['total_guests'] ?? 1));
            $guestName = trim($data['guest_name']);
            $guestEmail = trim($data['guest_email']);
            $guestPhone = trim($data['guest_phone']);
            $specialRequest = trim($data['special_request'] ?? '');
            $paymentMethod = $data['payment_method'] === 'online' ? 'online' : 'pay_at_hotel';
            $userId = !empty($data['user_id']) ? (int)$data['user_id'] : null;

            // If user is guest (not logged in), check if user exists by email, or create customer
            if ($userId === null) {
                $userStmt = $this->db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
                $userStmt->execute(['email' => $guestEmail]);
                $existingUserId = $userStmt->fetchColumn();
                if ($existingUserId) {
                    $userId = (int)$existingUserId;
                } else {
                    $insertUser = $this->db->prepare(
                        "INSERT INTO users (full_name, email, phone, role, status)
                         VALUES (:name, :email, :phone, 'customer', 'active')"
                    );
                    $insertUser->execute([
                        'name' => $guestName,
                        'email' => $guestEmail,
                        'phone' => $guestPhone,
                    ]);
                    $userId = (int)$this->db->lastInsertId();
                }
            }

            // Fetch RoomType
            $rtStmt = $this->db->prepare("SELECT * FROM room_types WHERE id = :id AND status = 'active' LIMIT 1");
            $rtStmt->execute(['id' => $roomTypeId]);
            $roomType = $rtStmt->fetch();
            if (!$roomType) {
                throw new RuntimeException('Loại phòng được chọn không khả dụng.');
            }

            $hotelId = (int)$roomType['hotel_id'];

            // Find an available physical room
            $blockStatuses = self::BOOKING_BLOCK_STATUSES;
            $findRoomSql = "SELECT r.* FROM rooms r
                            WHERE r.room_type_id = :room_type_id
                              AND r.status = 'available'
                              AND NOT EXISTS (
                                  SELECT 1 FROM booking_rooms br
                                  INNER JOIN bookings b ON b.id = br.booking_id
                                  WHERE br.room_id = r.id
                                    AND b.booking_status IN $blockStatuses
                                    AND (b.check_in < :check_out AND b.check_out > :check_in)
                              )
                            ORDER BY r.floor_number ASC, r.room_number ASC
                            LIMIT 1
                            FOR UPDATE";
            $findRoomStmt = $this->db->prepare($findRoomSql);
            $findRoomStmt->execute([
                'room_type_id' => $roomTypeId,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
            ]);
            $assignedRoom = $findRoomStmt->fetch();

            if (!$assignedRoom) {
                throw new RuntimeException('Rất tiếc! Hạng phòng ' . $roomType['name'] . ' hiện đã hết phòng trống trong khoảng thời gian quý khách chọn.');
            }

            // Calculate nights
            $inDate = new DateTimeImmutable($checkIn);
            $outDate = new DateTimeImmutable($checkOut);
            $diff = $inDate->diff($outDate);
            $numberOfNights = max(1, (int)$diff->days);

            // Subtotal
            $basePrice = (float)$roomType['base_price'];
            $roomSubtotal = $basePrice * $numberOfNights;

            // Additional services
            $serviceAmount = 0.0;
            $selectedServices = $data['services'] ?? [];
            $validServices = [];

            if (!empty($selectedServices) && is_array($selectedServices)) {
                $serviceIds = array_keys($selectedServices);
                $placeholders = implode(',', array_fill(0, count($serviceIds), '?'));
                $srvStmt = $this->db->prepare("SELECT * FROM services WHERE id IN ($placeholders) AND status = 'active'");
                $srvStmt->execute(array_values($serviceIds));
                $allServices = $srvStmt->fetchAll();

                foreach ($allServices as $srv) {
                    $srvId = (int)$srv['id'];
                    $qty = max(1, (int)($selectedServices[$srvId] ?? 1));
                    $srvUnitPrice = (float)$srv['price'];
                    $srvSub = $srvUnitPrice * $qty;
                    $serviceAmount += $srvSub;
                    $validServices[] = [
                        'service_id' => $srvId,
                        'quantity' => $qty,
                        'unit_price' => $srvUnitPrice,
                        'subtotal' => $srvSub,
                    ];
                }
            }

            // Promotion discount
            $discountAmount = 0.0;
            $promotionId = null;
            if (!empty($data['promotion_code'])) {
                $promoCode = trim($data['promotion_code']);
                $promoStmt = $this->db->prepare(
                    "SELECT * FROM promotions
                     WHERE UPPER(code) = UPPER(:code)
                       AND status = 'active'
                       AND NOW() BETWEEN start_date AND end_date
                       AND (usage_limit IS NULL OR used_count < usage_limit)
                     LIMIT 1"
                );
                $promoStmt->execute(['code' => $promoCode]);
                $promo = $promoStmt->fetch();

                if ($promo && $roomSubtotal >= (float)$promo['min_order_value']) {
                    $promotionId = (int)$promo['id'];
                    if ($promo['discount_type'] === 'percentage') {
                        $discountAmount = ($roomSubtotal * (float)$promo['discount_value']) / 100;
                        if ($promo['max_discount'] !== null && (float)$promo['max_discount'] > 0) {
                            $discountAmount = min($discountAmount, (float)$promo['max_discount']);
                        }
                    } else {
                        $discountAmount = min((float)$promo['discount_value'], $roomSubtotal);
                    }
                }
            }

            // Total amount
            $totalAmount = max(0.0, $roomSubtotal - $discountAmount + $serviceAmount);

            // Generate unique booking code
            do {
                $bookingCode = 'LOTTE' . date('Ym') . rand(1000, 9999);
                $checkCode = $this->db->prepare('SELECT COUNT(*) FROM bookings WHERE booking_code = :c');
                $checkCode->execute(['c' => $bookingCode]);
            } while ((int)$checkCode->fetchColumn() > 0);

            // Determine status based on payment method
            if ($paymentMethod === 'online') {
                $bookingStatus = 'confirmed';
                $paymentStatus = 'paid';
            } else {
                $bookingStatus = 'pending_hotel_payment';
                $paymentStatus = 'unpaid';
            }

            // Insert into bookings
            $insBooking = $this->db->prepare(
                "INSERT INTO bookings (
                    booking_code, user_id, hotel_id, promotion_id, guest_name, guest_email,
                    guest_phone, check_in, check_out, total_guests, number_of_nights,
                    subtotal, discount_amount, service_amount, total_amount,
                    payment_method, booking_status, payment_status, special_request, confirmed_at
                ) VALUES (
                    :booking_code, :user_id, :hotel_id, :promotion_id, :guest_name, :guest_email,
                    :guest_phone, :check_in, :check_out, :total_guests, :number_of_nights,
                    :subtotal, :discount_amount, :service_amount, :total_amount,
                    :payment_method, :booking_status, :payment_status, :special_request, :confirmed_at
                )"
            );
            $insBooking->execute([
                'booking_code' => $bookingCode,
                'user_id' => $userId,
                'hotel_id' => $hotelId,
                'promotion_id' => $promotionId,
                'guest_name' => $guestName,
                'guest_email' => $guestEmail,
                'guest_phone' => $guestPhone,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'total_guests' => $totalGuests,
                'number_of_nights' => $numberOfNights,
                'subtotal' => $roomSubtotal,
                'discount_amount' => $discountAmount,
                'service_amount' => $serviceAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $paymentMethod,
                'booking_status' => $bookingStatus,
                'payment_status' => $paymentStatus,
                'special_request' => $specialRequest,
                'confirmed_at' => $paymentMethod === 'online' ? date('Y-m-d H:i:s') : null,
            ]);
            $bookingId = (int)$this->db->lastInsertId();

            // Insert into booking_rooms
            $insRoom = $this->db->prepare(
                "INSERT INTO booking_rooms (
                    booking_id, room_id, room_name_snapshot, room_number_snapshot,
                    price_per_night, quantity, guests, subtotal
                ) VALUES (
                    :booking_id, :room_id, :room_name, :room_number,
                    :price, 1, :guests, :subtotal
                )"
            );
            $insRoom->execute([
                'booking_id' => $bookingId,
                'room_id' => $assignedRoom['id'],
                'room_name' => $roomType['name'],
                'room_number' => $assignedRoom['room_number'],
                'price' => $basePrice,
                'guests' => $totalGuests,
                'subtotal' => $roomSubtotal,
            ]);

            // Insert into booking_services
            if (!empty($validServices)) {
                $insService = $this->db->prepare(
                    "INSERT INTO booking_services (booking_id, service_id, quantity, unit_price, subtotal)
                     VALUES (:booking_id, :service_id, :quantity, :unit_price, :subtotal)"
                );
                foreach ($validServices as $vs) {
                    $insService->execute([
                        'booking_id' => $bookingId,
                        'service_id' => $vs['service_id'],
                        'quantity' => $vs['quantity'],
                        'unit_price' => $vs['unit_price'],
                        'subtotal' => $vs['subtotal'],
                    ]);
                }
            }

            // Insert payment record
            $insPayment = $this->db->prepare(
                "INSERT INTO payments (
                    booking_id, transaction_code, payment_method, amount,
                    payment_status, payment_gateway, paid_at, note
                ) VALUES (
                    :booking_id, :txn_code, :method, :amount,
                    :status, :gateway, :paid_at, :note
                )"
            );
            $txnCode = 'TXN-' . date('Ymd') . '-' . rand(100000, 999999);
            if ($paymentMethod === 'online') {
                $insPayment->execute([
                    'booking_id' => $bookingId,
                    'txn_code' => $txnCode,
                    'method' => 'online',
                    'amount' => $totalAmount,
                    'status' => 'success',
                    'gateway' => 'Cổng thanh toán trực tuyến Lotte Pay / QR Banking',
                    'paid_at' => date('Y-m-d H:i:s'),
                    'note' => 'Khách hàng thanh toán trực tuyến thành công.',
                ]);
            } else {
                $insPayment->execute([
                    'booking_id' => $bookingId,
                    'txn_code' => $txnCode,
                    'method' => 'pay_at_hotel',
                    'amount' => $totalAmount,
                    'status' => 'pending',
                    'gateway' => 'Thanh toán tại quầy',
                    'paid_at' => null,
                    'note' => 'Đơn thanh toán tại quầy khi nhận phòng. Chờ nhân viên thu tiền và xác nhận.',
                ]);
            }

            // If promotion used, update count
            if ($promotionId !== null) {
                $this->db->prepare("UPDATE promotions SET used_count = used_count + 1 WHERE id = :pid")
                    ->execute(['pid' => $promotionId]);
            }

            $this->db->commit();
            return [
                'booking_id' => $bookingId,
                'booking_code' => $bookingCode,
                'room_number' => $assignedRoom['room_number'],
                'total_amount' => $totalAmount,
            ];
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    public function cancelByCustomer(int $bookingId, int $userId, string $reason): void
    {
        $this->db->beginTransaction();
        try {
            $st = $this->db->prepare(
                "SELECT * FROM bookings WHERE id = :id AND user_id = :user_id FOR UPDATE"
            );
            $st->execute(['id' => $bookingId, 'user_id' => $userId]);
            $b = $st->fetch();

            if (!$b) {
                throw new RuntimeException('Không tìm thấy đơn đặt phòng hoặc bạn không có quyền thao tác.');
            }

            if (!in_array($b['booking_status'], ['pending_payment', 'pending_hotel_payment', 'confirmed'], true)) {
                throw new RuntimeException('Đơn đặt phòng này không thể hủy ở trạng thái hiện tại.');
            }

            $today = date('Y-m-d');
            if ($b['check_in'] <= $today) {
                throw new RuntimeException('Không thể hủy đơn đặt phòng vào hoặc sau ngày nhận phòng theo chính sách khách sạn.');
            }

            $upd = $this->db->prepare(
                "UPDATE bookings
                 SET booking_status = 'cancelled', cancellation_reason = :reason
                 WHERE id = :id"
            );
            $upd->execute([
                'id' => $bookingId,
                'reason' => 'Khách hàng tự hủy: ' . ($reason !== '' ? $reason : 'Không có lý do cụ thể'),
            ]);

            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    public function updateWorkflow(int $id, string $action, int $staff): void
    {
        $this->db->beginTransaction();
        try {
            $st = $this->db->prepare('SELECT * FROM bookings WHERE id=:id FOR UPDATE');
            $st->execute(['id' => $id]);
            $b = $st->fetch();
            if (!$b) throw new RuntimeException('Không tìm thấy đơn đặt phòng.');
            $status = $b['booking_status'];
            $method = $b['payment_method'];

            if ($action === 'confirm_online') {
                $paid = $b['payment_status'] === 'paid';
                if (!$paid) {
                    $st = $this->db->prepare("SELECT COUNT(*) FROM payments WHERE booking_id=:id AND payment_status='success'");
                    $st->execute(['id' => $id]);
                    $paid = (int)$st->fetchColumn() > 0;
                }
                if ($method !== 'online' || $status !== 'pending_payment' || !$paid) {
                    throw new RuntimeException('Chỉ xác nhận đơn thanh toán online đã thanh toán thành công.');
                }
                $this->db->prepare("UPDATE bookings SET payment_status='paid',booking_status='confirmed',confirmed_by=:staff,confirmed_at=NOW() WHERE id=:id")
                    ->execute(['staff' => $staff, 'id' => $id]);
            } elseif ($action === 'confirm_hotel_payment') {
                if ($method !== 'pay_at_hotel' || $status !== 'pending_hotel_payment') {
                    throw new RuntimeException('Đơn không ở trạng thái chờ thanh toán tại khách sạn.');
                }
                $st = $this->db->prepare("SELECT id FROM payments WHERE booking_id=:id AND payment_method='pay_at_hotel' ORDER BY id DESC LIMIT 1");
                $st->execute(['id' => $id]);
                $pid = $st->fetchColumn();
                if ($pid) {
                    $this->db->prepare("UPDATE payments SET payment_status='success',paid_at=NOW(),confirmed_by=:staff,note=CONCAT(COALESCE(note,''),' | Admin xác nhận đã thu tại quầy') WHERE id=:pid")
                        ->execute(['staff' => $staff, 'pid' => $pid]);
                } else {
                    $this->db->prepare("INSERT INTO payments(booking_id,payment_method,amount,payment_status,paid_at,confirmed_by,note) VALUES(:id,'pay_at_hotel',:amount,'success',NOW(),:staff,'Admin xác nhận đã thu tại quầy')")
                        ->execute(['id' => $id, 'amount' => $b['total_amount'], 'staff' => $staff]);
                }
                $this->db->prepare("UPDATE bookings SET payment_status='paid',booking_status='confirmed',confirmed_by=:staff,confirmed_at=NOW() WHERE id=:id")
                    ->execute(['staff' => $staff, 'id' => $id]);
            } elseif ($action === 'check_in' && $status === 'confirmed') {
                $this->setStatus($id, 'checked_in');
            } elseif ($action === 'complete' && $status === 'checked_in') {
                $this->setStatus($id, 'completed');
            } elseif ($action === 'cancel' && in_array($status, ['pending_payment', 'pending_hotel_payment', 'confirmed'], true)) {
                $this->setStatus($id, 'cancelled');
            } else {
                throw new RuntimeException('Thao tác không hợp lệ với trạng thái đơn hiện tại.');
            }
            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $e;
        }
    }

    private function setStatus(int $id, string $status): void
    {
        $this->db->prepare('UPDATE bookings SET booking_status=:status WHERE id=:id')
            ->execute(['status' => $status, 'id' => $id]);
    }
}
