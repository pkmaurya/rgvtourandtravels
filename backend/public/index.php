<?php

// CORS Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle Preflight Requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Autoload (Simple requires for now, or we can build a proper autoloader later)
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../routes/Router.php';

// Instantiate Router
$router = new Router();

// Define Routes
// Auth
$router->post('/api/auth/register', 'AuthController', 'register');
$router->post('/api/auth/login', 'AuthController', 'login');
$router->get('/api/auth/profile', 'AuthController', 'getProfile'); 
$router->put('/api/auth/profile', 'AuthController', 'updateProfile');
$router->post('/api/auth/forgot-password', 'AuthController', 'sendOtp');
$router->post('/api/auth/verify-otp', 'AuthController', 'verifyOtp');
$router->post('/api/auth/reset-password', 'AuthController', 'resetPassword');

// Upload Route
require_once __DIR__ . '/../controllers/UploadController.php';
$router->post('/api/upload', 'UploadController', 'upload');

require_once __DIR__ . '/../controllers/OfferController.php';

// Destinations
$router->get('/api/destinations/featured', 'DestinationController', 'getFeatured');
$router->get('/api/destinations', 'DestinationController', 'getAll');
$router->get('/api/destinations/{id}', 'DestinationController', 'getOne');
$router->post('/api/destinations', 'DestinationController', 'create');
$router->put('/api/destinations/{id}', 'DestinationController', 'update');
$router->delete('/api/destinations/{id}', 'DestinationController', 'delete');

// Tours
$router->get('/api/tours/featured', 'TourController', 'getFeatured');
$router->get('/api/tours/search', 'TourController', 'search'); // Search before ID too
$router->get('/api/tours', 'TourController', 'getAll');
$router->get('/api/tours/{id}', 'TourController', 'getOne');
$router->post('/api/tours', 'TourController', 'create');
$router->put('/api/tours/{id}', 'TourController', 'update');
$router->delete('/api/tours/{id}', 'TourController', 'delete');

// Bookings
$router->post('/api/bookings', 'BookingController', 'create');
$router->get('/api/bookings', 'BookingController', 'index');
$router->get('/api/user/bookings', 'BookingController', 'getUserBookings'); // New
$router->get('/api/bookings/{id}', 'BookingController', 'show');

// Uploads
$router->post('/api/upload', 'UploadController', 'upload');

// Users (Admin)
$router->get('/api/users', 'UserController', 'index');
$router->put('/api/users/{id}', 'UserController', 'update');
$router->delete('/api/users/{id}', 'UserController', 'delete');

// Dashboard (Admin)
$router->get('/api/dashboard/stats', 'DashboardController', 'getStats');
$router->get('/api/dashboard/recent-bookings', 'DashboardController', 'getRecentBookings');
$router->get('/api/dashboard/chart', 'DashboardController', 'getChartData');

// Offers
$router->get('/api/offers', 'OfferController', 'index'); // Admin: Get all
$router->get('/api/offers/active', 'OfferController', 'getActive'); // Public: Get active
$router->get('/api/offers/{id}', 'OfferController', 'getOne'); // Admin: Get single
$router->post('/api/offers', 'OfferController', 'create'); // Admin: Create
$router->put('/api/offers/{id}', 'OfferController', 'update'); // Admin: Update
$router->delete('/api/offers/{id}', 'OfferController', 'delete'); // Admin: Delete
$router->post('/api/offers/verify', 'OfferController', 'verifyCoupon'); // Public: Verify Coupon

// Payments
require_once __DIR__ . '/../controllers/PaymentController.php';
$router->post('/api/payment/razorpay/create-order', 'PaymentController', 'createRazorpayOrder');
$router->post('/api/payment/razorpay/verify', 'PaymentController', 'verifyRazorpayPayment');
$router->post('/api/payment/stripe/create-intent', 'PaymentController', 'createStripePaymentIntent');
$router->post('/api/payment/stripe/webhook', 'PaymentController', 'handleStripeWebhook');
$router->get('/api/admin/payments', 'PaymentController', 'index'); // Admin List

// Contact
require_once __DIR__ . '/../controllers/ContactController.php';
$router->post('/api/contact', 'ContactController', 'submit');
$router->get('/api/admin/contacts', 'ContactController', 'index');
$router->delete('/api/admin/contacts/{id}', 'ContactController', 'delete');

// Settings
require_once __DIR__ . '/../controllers/SettingController.php';
$router->get('/api/settings', 'SettingController', 'getAll'); // Admin
$router->get('/api/settings/public', 'SettingController', 'getPublic'); // Public
$router->put('/api/settings', 'SettingController', 'update');

// Chatbot
require_once __DIR__ . '/../controllers/ChatbotController.php';
$router->get('/api/chatbot', 'ChatbotController', 'index');
$router->post('/api/chatbot/ask', 'ChatbotController', 'ask');
$router->post('/api/admin/chatbot', 'ChatbotController', 'create');
$router->put('/api/admin/chatbot/{id}', 'ChatbotController', 'update');
$router->delete('/api/admin/chatbot/{id}', 'ChatbotController', 'delete');

// Dispatch
$router->dispatch();
