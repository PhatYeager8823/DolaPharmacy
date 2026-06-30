<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MomoService
{
    private string $partnerCode;
    private string $accessKey;
    private string $secretKey;
    private string $endpoint;
    private string $returnUrl;
    private string $notifyUrl;
    private string $requestType;

    public function __construct()
    {
        $this->partnerCode = config('momo.partner_code');
        $this->accessKey   = config('momo.access_key');
        $this->secretKey   = config('momo.secret_key');
        $this->endpoint    = config('momo.endpoint');
        $this->returnUrl   = config('momo.return_url');
        $this->notifyUrl   = config('momo.notify_url');
        $this->requestType = config('momo.request_type', 'payWithMethod');
    }

    /**
     * Tạo yêu cầu thanh toán MoMo, trả về payUrl để redirect.
     *
     * @param  string $orderId     Mã đơn hàng (ma_don_hang)
     * @param  int    $amount      Số tiền (VND, không có thập phân)
     * @param  string $orderInfo   Nội dung mô tả đơn hàng
     * @return array               ['success' => bool, 'payUrl' => string, 'message' => string]
     */
    public function createPayment(string $orderId, int $amount, string $orderInfo): array
    {
        $requestId  = $this->partnerCode . '_' . time() . '_' . Str::random(6);
        $extraData  = '';
        $autoCapture = true;
        $lang       = 'vi';

        // Build chuỗi raw để tạo chữ ký HMAC-SHA256
        $rawHash = implode('&', [
            "accessKey={$this->accessKey}",
            "amount={$amount}",
            "extraData={$extraData}",
            "ipnUrl={$this->notifyUrl}",
            "orderId={$orderId}",
            "orderInfo={$orderInfo}",
            "partnerCode={$this->partnerCode}",
            "redirectUrl={$this->returnUrl}",
            "requestId={$requestId}",
            "requestType={$this->requestType}",
        ]);

        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);

        $payload = [
            'partnerCode' => $this->partnerCode,
            'accessKey'   => $this->accessKey,
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $this->returnUrl,
            'ipnUrl'      => $this->notifyUrl,
            'extraData'   => $extraData,
            'requestType' => $this->requestType,
            'autoCapture' => $autoCapture,
            'lang'        => $lang,
            'signature'   => $signature,
        ];

        try {
            $response = Http::withoutVerifying()  // Bỏ verify SSL khi test local
                ->timeout(30)
                ->post($this->endpoint, $payload);

            $result = $response->json();

            if ($response->successful() && isset($result['payUrl']) && $result['resultCode'] == 0) {
                return [
                    'success' => true,
                    'payUrl'  => $result['payUrl'],
                    'message' => 'Tạo thanh toán MoMo thành công',
                ];
            }

            return [
                'success' => false,
                'payUrl'  => '',
                'message' => $result['message'] ?? 'Lỗi kết nối MoMo: ' . $response->status(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'payUrl'  => '',
                'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Xác thực chữ ký callback/return từ MoMo.
     *
     * @param  array  $data  Toàn bộ dữ liệu trả về từ MoMo (query params hoặc POST body)
     * @return bool
     */
    public function verifySignature(array $data): bool
    {
        // MoMo gửi về các field này trong callback
        $rawHash = implode('&', [
            "accessKey={$this->accessKey}",
            "amount={$data['amount']}",
            "extraData={$data['extraData']}",
            "message={$data['message']}",
            "orderId={$data['orderId']}",
            "orderInfo={$data['orderInfo']}",
            "orderType={$data['orderType']}",
            "partnerCode={$data['partnerCode']}",
            "payType={$data['payType']}",
            "requestId={$data['requestId']}",
            "responseTime={$data['responseTime']}",
            "resultCode={$data['resultCode']}",
            "transId={$data['transId']}",
        ]);

        $expectedSignature = hash_hmac('sha256', $rawHash, $this->secretKey);

        return hash_equals($expectedSignature, $data['signature'] ?? '');
    }
}
