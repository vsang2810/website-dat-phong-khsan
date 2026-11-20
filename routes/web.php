<?php
declare(strict_types=1);

// Client / Public Routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/services', [HomeController::class, 'services']);
$router->get('/promotions', [HomeController::class, 'promotions']);
$router->get('/contact', [HomeController::class, 'contact']);
$router->post('/contact', [HomeController::class, 'submitContact']);

// Rooms & Availability
$router->get('/rooms', [ClientRoomController::class, 'index']);
$router->get('/room-detail', [ClientRoomController::class, 'detail']);
$router->get('/api/check-availability', [ClientRoomController::class, 'checkAvailability']);
$router->post('/api/wishlist-toggle', [ClientRoomController::class, 'toggleWishlist']);

// Booking Flow & Lookup
$router->get('/booking', [ClientBookingController::class, 'index']);
$router->post('/booking/checkout', [ClientBookingController::class, 'store']);
$router->get('/booking/success', [ClientBookingController::class, 'success']);
$router->get('/booking/lookup', [ClientBookingController::class, 'lookup']);
$router->post('/booking/cancel', [ClientBookingController::class, 'cancel']);
$router->post('/api/check-promotion', [ClientBookingController::class, 'checkPromotion']);

// Client Auth & Account
$router->get('/login', [ClientAuthController::class, 'showLogin']);
$router->post('/login', [ClientAuthController::class, 'login']);
$router->get('/register', [ClientAuthController::class, 'showRegister']);
$router->post('/register', [ClientAuthController::class, 'register']);
$router->post('/logout', [ClientAuthController::class, 'logout']);

$router->get('/account', [ClientAuthController::class, 'account']);
$router->post('/account/profile', [ClientAuthController::class, 'updateProfile']);
$router->post('/account/password', [ClientAuthController::class, 'updatePassword']);
$router->get('/my-bookings', [ClientAuthController::class, 'myBookings']);
$router->get('/wishlist', [ClientAuthController::class, 'wishlist']);

// Admin Management Portal
$router->get('/admin', [DashboardController::class, 'index']);
$router->get('/admin/login', [AuthController::class, 'showLogin']);
$router->post('/admin/login', [AuthController::class, 'login']);

$router->get('/admin/bookings', [BookingController::class, 'index']);
$router->get('/admin/bookings/show', [BookingController::class, 'show']);
$router->post('/admin/bookings/update', [BookingController::class, 'update']);

$router->get('/admin/hotels', [HotelController::class, 'index']);
$router->get('/admin/hotel-info', [HotelController::class, 'settings']);
$router->post('/admin/hotel-info', [HotelController::class, 'updateSettings']);
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
