<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiaChi;
use Illuminate\Support\Facades\Auth;

class DiaChiController extends Controller
{
    // 1. Xem danh sách
    public function index()
    {
        // [SỬA LẠI] Lấy user ra trước để tính hạng thành viên
        $user = Auth::user();

        // [THÊM MỚI] Tính hạng thành viên cho Sidebar
        $membership = $user->getMembershipData();

        $addresses = DiaChi::where('nguoi_dung_id', $user->id) // Dùng $user->id cho đồng bộ
                           ->orderBy('mac_dinh', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->get();

        // [QUAN TRỌNG] Truyền thêm 'user' và 'membership' sang View
        return view('account.addresses', compact('addresses', 'user', 'membership'));
    }

    // 2. Thêm địa chỉ mới (SỬA)
    public function store(Request $request)
    {
        // Validate các trường mới
        $request->validate([
            'ten_nguoi_nhan'   => 'required',
            'sdt_nguoi_nhan'   => 'required',
            'tinh_thanh'       => 'required', // <--- Mới
            'dia_chi_chi_tiet' => 'required', // <--- Mới
        ]);

        $userId = Auth::id();

        // Gộp địa chỉ
        $fullAddress = $request->dia_chi_chi_tiet . ', ' . $request->tinh_thanh;

        $isFirst = DiaChi::where('nguoi_dung_id', $userId)->doesntExist();

        DiaChi::create([
            'nguoi_dung_id'  => $userId,
            'ten_nguoi_nhan' => $request->ten_nguoi_nhan,
            'sdt_nguoi_nhan' => $request->sdt_nguoi_nhan,
            'dia_chi_cu_the' => $fullAddress, // <--- Lưu chuỗi đã gộp
            'mac_dinh'       => $isFirst ? 1 : ($request->mac_dinh ? 1 : 0),
        ]);

        if ($request->mac_dinh && !$isFirst) {
            $newId = DiaChi::latest()->first()->id;
            $this->resetDefault($userId, $newId);
        }

        return back()->with('success', 'Thêm địa chỉ thành công!');
    }

    // 3. Cập nhật địa chỉ (SỬA)
    public function update(Request $request, $id)
    {
        $address = DiaChi::findOrFail($id);
        if($address->nguoi_dung_id != Auth::id()) abort(403);

        // Gộp địa chỉ
        $fullAddress = $request->dia_chi_chi_tiet . ', ' . $request->tinh_thanh;

        $address->update([
            'ten_nguoi_nhan' => $request->ten_nguoi_nhan,
            'sdt_nguoi_nhan' => $request->sdt_nguoi_nhan,
            'dia_chi_cu_the' => $fullAddress, // <--- Lưu chuỗi đã gộp
            'mac_dinh'       => $request->mac_dinh ? 1 : 0,
        ]);

        if ($request->mac_dinh) {
            $this->resetDefault(Auth::id(), $id);
        }

        return back()->with('success', 'Cập nhật địa chỉ thành công!');
    }

    // ... (Các hàm destroy, setDefault, resetDefault giữ nguyên như cũ) ...
    public function destroy($id)
    {
        $address = DiaChi::findOrFail($id);
        if($address->nguoi_dung_id != Auth::id()) abort(403);
        $address->delete();
        return back()->with('success', 'Đã xóa địa chỉ.');
    }

    public function setDefault($id)
    {
        $address = DiaChi::findOrFail($id);
        if($address->nguoi_dung_id != Auth::id()) abort(403);
        $address->update(['mac_dinh' => 1]);
        $this->resetDefault(Auth::id(), $id);
        return back()->with('success', 'Đã đặt làm địa chỉ mặc định.');
    }

    private function resetDefault($userId, $currentId)
    {
        DiaChi::where('nguoi_dung_id', $userId)
              ->where('id', '!=', $currentId)
              ->update(['mac_dinh' => 0]);
    }
}
