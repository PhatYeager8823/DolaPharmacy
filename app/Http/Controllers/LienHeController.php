<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LienHe;

class LienHeController extends Controller
{
    // 1. Hiển thị form liên hệ
    public function index()
    {
        return view('pages.contact');
    }

    // 2. Xử lý lưu tin nhắn
    public function store(Request $request)
    {
        $request->validate([
            'ho_ten'   => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'sdt'      => 'nullable|string|max:20',
            'tieu_de'  => 'nullable|string|max:255', 
            'noi_dung' => 'required|string',
        ], [
            'ho_ten.required' => 'Vui lòng nhập họ tên.',
            'email.required'  => 'Vui lòng nhập email.',
            'noi_dung.required' => 'Vui lòng nhập nội dung tin nhắn.',
        ]);

        LienHe::create($request->all());

        return back()->with('success', 'Cảm ơn bạn! Tin nhắn đã được gửi thành công.');
    }
}
