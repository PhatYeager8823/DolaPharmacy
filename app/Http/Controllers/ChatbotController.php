<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thuoc;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function reply(Request $request)
    {
        $userMessage = $request->input('message');

        try {
            // 1. CHUẨN BỊ DỮ LIỆU THUỐC (CONTEXT)
            // Lấy thuốc đang hoạt động để "mớm" cho AI (Tăng giới hạn lên 20 để AI biết nhiều hơn)
            $products = Thuoc::where('is_active', 1)->limit(20)->get();
            $productListText = "";
            foreach ($products as $p) {
                $price = number_format($p->gia_ban);
                // Cung cấp thêm công dụng để AI tư vấn bệnh tốt hơn
                $productListText .= "- Tên: {$p->ten_thuoc} | Giá: {$price}đ | Đơn vị: {$p->don_vi_tinh} | Công dụng: {$p->cong_dung}\n";
            }

            // 2. TẠO CÂU LỆNH NHẮC (SYSTEM PROMPT)
            $systemPrompt = "
                Bạn là Dược sĩ ảo của nhà thuốc Dola Pharmacy.

                Thông tin nhà thuốc:
                - Hotline/Zalo: 0919.795.426
                - Ship hàng: Nội thành 15k, Ngoại thành 30k. Freeship đơn > 300k.
                - Địa chỉ: Đường Trần Huỳnh, Phường Bạc Liêu, Tỉnh Cà Mau.

                Nhiệm vụ của bạn:
                - Trả lời ngắn gọn, thân thiện, xưng là 'Dola'.
                - Dựa vào danh sách thuốc dưới đây để báo giá hoặc tư vấn công dụng.
                - Nếu khách hỏi thuốc KHÔNG có trong danh sách, hãy khuyên họ gọi hotline hoặc chat Zalo để được hỗ trợ.
                - Tuyệt đối không bịa đặt giá hoặc thông tin thuốc không có trong danh sách.

                Danh sách thuốc hiện có:
                {$productListText}

                Câu hỏi của khách hàng: {$userMessage}
            ";

            // 3. GỌI API GEMINI 2.5 (REST API)
            $apiKey = env('GEMINI_API_KEY');

            // Sử dụng Model mới nhất: gemini-2.5-flash
            $model = 'gemini-2.5-flash';
            $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            $response = Http::withoutVerifying() // Tắt kiểm tra SSL cho Localhost
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($apiUrl, [
                    "contents" => [
                        [
                            "parts" => [
                                ["text" => $systemPrompt]
                            ]
                        ]
                    ]
                ]);

            // 4. XỬ LÝ KẾT QUẢ TRẢ VỀ
            if ($response->successful()) {
                $data = $response->json();
                // Lấy nội dung text từ cấu trúc JSON của Gemini
                $botReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Dola đang bị rối một chút, bạn hỏi lại nhé!';
            } else {
                Log::error('Gemini Error: ' . $response->body());
                $botReply = "Xin lỗi, hệ thống tư vấn đang quá tải. Bạn vui lòng gọi Hotline 0919.795.426 nhé.";
            }

        } catch (\Exception $e) {
            Log::error('Chatbot Error: ' . $e->getMessage());
            $botReply = "Xin lỗi, có lỗi kết nối xảy ra. Vui lòng thử lại sau.";
        }

        return response()->json(['reply' => $botReply]);
    }

    // Thêm hàm này vào ChatbotController
    public function checkModels()
    {
        $apiKey = env('GEMINI_API_KEY');
        $response = Http::withoutVerifying()->get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");

        return $response->json();
    }
}
