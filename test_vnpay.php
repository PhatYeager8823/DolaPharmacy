<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = App\Models\Order::latest()->first();
if(!$order) {
    die("No order found");
}

$controller = new App\Http\Controllers\CheckoutController();

echo "TmnCode: " . trim(config('vnpay.vnp_TmnCode')) . "\n";
echo "HashSecret: " . trim(config('vnpay.vnp_HashSecret')) . "\n";
echo "Amount: " . (int)($order->tong_tien * 100) . "\n";
echo "Order: " . $order->ma_don_hang . "\n";

$response = $controller->createVnPayPayment($order);
echo "\nTarget URL:\n";
echo $response->getTargetUrl();
echo "\n";
