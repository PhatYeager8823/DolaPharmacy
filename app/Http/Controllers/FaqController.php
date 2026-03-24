<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        // Lấy danh sách câu hỏi, sắp xếp theo thứ tự
        $faqs = Faq::where('is_active', 1)->orderBy('thu_tu', 'asc')->get();
        return view('pages.faq', compact('faqs'));
    }
}
