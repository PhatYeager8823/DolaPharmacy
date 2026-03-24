<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NhaCungCap;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // 1. Danh sách nhà cung cấp
    public function index()
    {
        // Lấy danh sách mới nhất, phân trang 10
        $suppliers = NhaCungCap::latest()->paginate(10);
        return view('admin.suppliers.index', compact('suppliers'));
    }

    // 2. Form thêm mới
    public function create()
    {
        return view('admin.suppliers.create');
    }

    // 3. Xử lý lưu
    public function store(Request $request)
    {
        $request->validate([
            'ten_nha_cung_cap' => 'required|string|max:255',
            'email' => 'nullable|email',
            'sdt' => 'nullable|numeric',
        ], [
            'ten_nha_cung_cap.required' => 'Tên nhà cung cấp không được để trống',
        ]);

        NhaCungCap::create([
            'ten_nha_cung_cap' => $request->ten_nha_cung_cap,
            'dia_chi' => $request->dia_chi,
            'sdt' => $request->sdt,
            'email' => $request->email,
            'trang_thai' => $request->has('trang_thai') ? 1 : 0,
        ]);

        return redirect()->route('admin.suppliers.index')->with('success', 'Thêm nhà cung cấp thành công!');
    }

    // 4. Form chỉnh sửa
    public function edit($id)
    {
        $supplier = NhaCungCap::findOrFail($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    // 5. Xử lý cập nhật
    public function update(Request $request, $id)
    {
        $request->validate([
            'ten_nha_cung_cap' => 'required|string|max:255',
        ]);

        $supplier = NhaCungCap::findOrFail($id);

        $supplier->update([
            'ten_nha_cung_cap' => $request->ten_nha_cung_cap,
            'dia_chi' => $request->dia_chi,
            'sdt' => $request->sdt,
            'email' => $request->email,
            'trang_thai' => $request->has('trang_thai') ? 1 : 0,
        ]);

        return redirect()->route('admin.suppliers.index')->with('success', 'Cập nhật thành công!');
    }

    // 6. Xóa
    public function destroy($id)
    {
        $supplier = NhaCungCap::findOrFail($id);

        // Kiểm tra xem nhà cung cấp này có đang cung cấp thuốc nào không?
        if($supplier->thuocs()->count() > 0) {
            return back()->with('error', 'Không thể xóa! Nhà cung cấp này đang có sản phẩm trong hệ thống.');
        }

        $supplier->delete();
        return back()->with('success', 'Đã xóa nhà cung cấp.');
    }
}
