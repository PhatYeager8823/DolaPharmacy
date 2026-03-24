<?php
$vnp_TmnCode = "36MOOCAH"; 
$vnp_HashSecret = "8QTFI4X6PEK03Q75ADDQXPP5KP0ZXJAX";
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "http://127.0.0.1:8000/thanh-toan/vnpay-return";
$vnp_TxnRef = "DH" . time();
$vnp_OrderInfo = "Thanh_toan_don_hang_" . $vnp_TxnRef;
$vnp_OrderType = 'billpayment';
$vnp_Amount = 500000 * 100;
$vnp_Locale = 'vn';
$vnp_IpAddr = "127.0.0.1";
date_default_timezone_set('Asia/Ho_Chi_Minh');

$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $vnp_Amount,
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_Locale" => $vnp_Locale,
    "vnp_OrderInfo" => $vnp_OrderInfo,
    "vnp_OrderType" => $vnp_OrderType,
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => $vnp_TxnRef,
);

ksort($inputData);
$query = "";
$i = 0;
$hashdata = "";

foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

$vnp_Url = $vnp_Url . "?" . $query;
$vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
$vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

echo "HASH_DATA: " . $hashdata . "\n";
echo "URL_GENERATED: " . $vnp_Url . "\n";
