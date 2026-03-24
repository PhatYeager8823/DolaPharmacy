<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::latest()->paginate(10);
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cau_hoi' => 'required|string|max:500',
            'tra_loi' => 'required|string',
        ]);

        Faq::create([
            'cau_hoi' => $request->cau_hoi,
            'tra_loi' => $request->tra_loi,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'Thêm câu hỏi thành công!');
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cau_hoi' => 'required|string|max:500',
            'tra_loi' => 'required|string',
        ]);

        $faq = Faq::findOrFail($id);

        $faq->update([
            'cau_hoi' => $request->cau_hoi,
            'tra_loi' => $request->tra_loi,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        Faq::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa câu hỏi.');
    }
}
