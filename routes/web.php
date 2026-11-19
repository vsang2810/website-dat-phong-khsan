<?php
$router->get('/', [DashboardController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);
$router->get('/admin', [DashboardController::class, 'index']);

$router->get('/admin/bookings', [BookingController::class, 'index']);
$router->get('/admin/bookings/show', [BookingController::class, 'show']);
$router->post('/admin/bookings/update', [BookingController::class, 'update']);

$router->get('/admin/hotels', [HotelController::class, 'index']);
$router->get('/admin/hotels/create', [HotelController::class, 'create']);
$router->post('/admin/hotels/store', [HotelController::class, 'store']);
$router->get('/admin/hotels/edit', [HotelController::class, 'edit']);
$router->post('/admin/hotels/update', [HotelController::class, 'update']);
$router->post('/admin/hotels/toggle', [HotelController::class, 'toggle']);

$router->get('/admin/room-types', [RoomTypeController::class, 'index']);
$router->get('/admin/room-types/create', [RoomTypeController::class, 'create']);
$router->post('/admin/room-types/store', [RoomTypeController::class, 'store']);
$router->get('/admin/room-types/edit', [RoomTypeController::class, 'edit']);
$router->post('/admin/room-types/update', [RoomTypeController::class, 'update']);
$router->post('/admin/room-types/toggle', [RoomTypeController::class, 'toggle']);

$router->get('/admin/rooms', [RoomController::class, 'index']);
$router->get('/admin/rooms/create', [RoomController::class, 'create']);
$router->post('/admin/rooms/store', [RoomController::class, 'store']);
$router->get('/admin/rooms/edit', [RoomController::class, 'edit']);
$router->post('/admin/rooms/update', [RoomController::class, 'update']);
$router->post('/admin/rooms/status', [RoomController::class, 'status']);

$router->get('/admin/customers', [CustomerController::class, 'index']);
$router->get('/admin/customers/show', [CustomerController::class, 'show']);
$router->get('/admin/customers/edit', [CustomerController::class, 'edit']);
$router->post('/admin/customers/update', [CustomerController::class, 'update']);
$router->post('/admin/customers/status', [CustomerController::class, 'status']);

$router->get('/admin/payments', [PaymentController::class, 'index']);
$router->get('/admin/payments/show', [PaymentController::class, 'show']);
$router->post('/admin/payments/confirm-hotel', [PaymentController::class, 'confirmHotelPayment']);
