<?php
declare(strict_types=1);
final class Booking extends Model {
 public function paginate(array $f,int $page=1,int $perPage=12):array {
  $where=[];$params=[];
  if($f['q']!==''){$where[]='(b.booking_code LIKE :q1 OR b.guest_name LIKE :q2 OR b.guest_email LIKE :q3 OR b.guest_phone LIKE :q4)';$term='%'.$f['q'].'%';$params['q1']=$term;$params['q2']=$term;$params['q3']=$term;$params['q4']=$term;}
  if($f['status']!==''){$where[]='b.booking_status=:status';$params['status']=$f['status'];}
  if($f['method']!==''){$where[]='b.payment_method=:method';$params['method']=$f['method'];}
  if($f['from']!==''){$where[]='b.check_in>=:date_from';$params['date_from']=$f['from'];}
  if($f['to']!==''){$where[]='b.check_in<=:date_to';$params['date_to']=$f['to'];}
  $clause=$where?'WHERE '.implode(' AND ',$where):'';
  $st=$this->db->prepare("SELECT COUNT(*) FROM bookings b $clause");$st->execute($params);$total=(int)$st->fetchColumn();
  $pages=max(1,(int)ceil($total/$perPage));$page=min(max(1,$page),$pages);$offset=($page-1)*$perPage;
  $sql="SELECT b.id,b.booking_code,b.guest_name,b.guest_email,b.guest_phone,b.check_in,b.check_out,b.total_guests,b.total_amount,b.payment_method,b.booking_status,b.payment_status,b.created_at,h.name hotel_name,EXISTS(SELECT 1 FROM payments p WHERE p.booking_id=b.id AND p.payment_status='success') has_success_payment FROM bookings b LEFT JOIN hotels h ON h.id=b.hotel_id $clause ORDER BY b.created_at DESC,b.id DESC LIMIT $perPage OFFSET $offset";
  $st=$this->db->prepare($sql);$st->execute($params);
  return ['items'=>$st->fetchAll(),'total'=>$total,'page'=>$page,'pages'=>$pages];
 }
 public function find(int $id):?array {
  $st=$this->db->prepare("SELECT b.*,h.name hotel_name,h.address hotel_address,EXISTS(SELECT 1 FROM payments p WHERE p.booking_id=b.id AND p.payment_status='success') has_success_payment,u.full_name confirmer_name FROM bookings b LEFT JOIN hotels h ON h.id=b.hotel_id LEFT JOIN users u ON u.id=b.confirmed_by WHERE b.id=:id");$st->execute(['id'=>$id]);$b=$st->fetch();if(!$b)return null;
  $st=$this->db->prepare('SELECT br.*,r.room_number,rt.name room_type_name FROM booking_rooms br LEFT JOIN rooms r ON r.id=br.room_id LEFT JOIN room_types rt ON rt.id=r.room_type_id WHERE br.booking_id=:id');$st->execute(['id'=>$id]);$b['rooms']=$st->fetchAll();
  $st=$this->db->prepare('SELECT p.*,u.full_name confirmer_name FROM payments p LEFT JOIN users u ON u.id=p.confirmed_by WHERE p.booking_id=:id ORDER BY p.id DESC');$st->execute(['id'=>$id]);$b['payments']=$st->fetchAll();return $b;
 }
 public function updateWorkflow(int $id,string $action,int $staff):void {
  $this->db->beginTransaction();try{
   $st=$this->db->prepare('SELECT * FROM bookings WHERE id=:id FOR UPDATE');$st->execute(['id'=>$id]);$b=$st->fetch();if(!$b)throw new RuntimeException('Không tìm thấy đơn đặt phòng.');
   $status=$b['booking_status'];$method=$b['payment_method'];
   if($action==='confirm_online'){
    $paid=$b['payment_status']==='paid';if(!$paid){$st=$this->db->prepare("SELECT COUNT(*) FROM payments WHERE booking_id=:id AND payment_status='success'");$st->execute(['id'=>$id]);$paid=(int)$st->fetchColumn()>0;}
    if($method!=='online'||$status!=='pending_payment'||!$paid)throw new RuntimeException('Chỉ xác nhận đơn thanh toán online đã thanh toán thành công.');
    $this->db->prepare("UPDATE bookings SET payment_status='paid',booking_status='confirmed',confirmed_by=:staff,confirmed_at=NOW() WHERE id=:id")->execute(['staff'=>$staff,'id'=>$id]);
   }elseif($action==='confirm_hotel_payment'){
    if($method!=='pay_at_hotel'||$status!=='pending_hotel_payment')throw new RuntimeException('Đơn không ở trạng thái chờ thanh toán tại khách sạn.');
    $st=$this->db->prepare("SELECT id FROM payments WHERE booking_id=:id AND payment_method='pay_at_hotel' ORDER BY id DESC LIMIT 1");$st->execute(['id'=>$id]);$pid=$st->fetchColumn();
    if($pid){$this->db->prepare("UPDATE payments SET payment_status='success',paid_at=NOW(),confirmed_by=:staff,note=CONCAT(COALESCE(note,''),' | Admin xác nhận đã thu tại quầy') WHERE id=:pid")->execute(['staff'=>$staff,'pid'=>$pid]);}
    else{$this->db->prepare("INSERT INTO payments(booking_id,payment_method,amount,payment_status,paid_at,confirmed_by,note) VALUES(:id,'pay_at_hotel',:amount,'success',NOW(),:staff,'Admin xác nhận đã thu tại quầy')")->execute(['id'=>$id,'amount'=>$b['total_amount'],'staff'=>$staff]);}
    $this->db->prepare("UPDATE bookings SET payment_status='paid',booking_status='confirmed',confirmed_by=:staff,confirmed_at=NOW() WHERE id=:id")->execute(['staff'=>$staff,'id'=>$id]);
   }elseif($action==='check_in'&&$status==='confirmed'){$this->setStatus($id,'checked_in');}
   elseif($action==='complete'&&$status==='checked_in'){$this->setStatus($id,'completed');}
   elseif($action==='cancel'&&in_array($status,['pending_payment','pending_hotel_payment','confirmed'],true)){$this->setStatus($id,'cancelled');}
   else throw new RuntimeException('Thao tác không hợp lệ với trạng thái đơn hiện tại.');
   $this->db->commit();
  }catch(Throwable $e){if($this->db->inTransaction())$this->db->rollBack();throw $e;}
 }
 private function setStatus(int $id,string $status):void{$this->db->prepare('UPDATE bookings SET booking_status=:status WHERE id=:id')->execute(['status'=>$status,'id'=>$id]);}
}
