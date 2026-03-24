<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    // Chỉ có 1 hàm index để hiện form và lưu luôn
    public function index()
    {
        // Lấy dòng setting đầu tiên, nếu chưa có thì tạo mới
        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
            $setting->ten_website = 'Dola Pharmacy';
            $setting->save();
        }
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::first();

        $data = $request->all();

        // Xử lý upload Logo
        if ($request->hasFile('logo')) {
            // Xóa ảnh cũ
            if ($setting->logo && File::exists(public_path('images/settings/' . $setting->logo))) {
                File::delete(public_path('images/settings/' . $setting->logo));
            }
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/settings'), $filename);
            $data['logo'] = $filename;
        }

        // Xử lý upload Favicon
        if ($request->hasFile('favicon')) {
            if ($setting->favicon && File::exists(public_path('images/settings/' . $setting->favicon))) {
                File::delete(public_path('images/settings/' . $setting->favicon));
            }
            $file = $request->file('favicon');
            $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/settings'), $filename);
            $data['favicon'] = $filename;
        }

        $setting->update($data);

        return redirect()->back()->with('success', 'Đã cập nhật cấu hình hệ thống!');
    }
}
