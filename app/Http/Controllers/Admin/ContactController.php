<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LienHe;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Chỉ cần hàm xem danh sách
    public function index()
    {
        // Lấy tin nhắn mới nhất lên đầu
        $contacts = LienHe::latest()->paginate(10);
        return view('admin.contacts.index', compact('contacts'));
    }

    // Hàm xóa tin nhắn (spam hoặc đã xử lý xong)
    public function destroy($id)
    {
        $contact = LienHe::findOrFail($id);
        $contact->delete();

        return back()->with('success', 'Đã xóa tin nhắn liên hệ.');
    }
}
