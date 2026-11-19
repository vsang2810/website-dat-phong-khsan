<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
define('BASE_URL', ($scriptDir === '/' || $scriptDir === '.') ? '' : rtrim($scriptDir, '/'));

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
]);
session_start();

require BASE_PATH . '/app/Core/Database.php';
require BASE_PATH . '/app/Core/Controller.php';
require BASE_PATH . '/app/Core/Model.php';
require BASE_PATH . '/app/Core/Router.php';
require BASE_PATH . '/app/Core/Auth.php';
require BASE_PATH . '/app/Core/Csrf.php';

require BASE_PATH . '/app/Models/Dashboard.php';
require BASE_PATH . '/app/Models/Booking.php';
require BASE_PATH . '/app/Models/Hotel.php';
require BASE_PATH . '/app/Models/RoomType.php';
require BASE_PATH . '/app/Models/Room.php';
require BASE_PATH . '/app/Models/Customer.php';
require BASE_PATH . '/app/Models/Payment.php';
require BASE_PATH . '/app/Controllers/AuthController.php';
require BASE_PATH . '/app/Controllers/DashboardController.php';
require BASE_PATH . '/app/Controllers/BookingController.php';
require BASE_PATH . '/app/Controllers/HotelController.php';
require BASE_PATH . '/app/Controllers/RoomTypeController.php';
require BASE_PATH . '/app/Controllers/RoomController.php';
require BASE_PATH . '/app/Controllers/CustomerController.php';
require BASE_PATH . '/app/Controllers/PaymentController.php';

$router = new Router();
require BASE_PATH . '/routes/web.php';
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
