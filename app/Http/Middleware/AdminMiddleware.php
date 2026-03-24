<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
// 👇 QUAN TRỌNG: Phải có dòng này mới dùng được Auth::check()
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Kiểm tra đã đăng nhập chưa
        if (Auth::check()) {
            $user = Auth::user();

            // 2. Kiểm tra vai trò (vai_tro) có phải là 'admin' không
            // (Lưu ý: Trong DB bạn lưu là 'admin' hay số 1 thì sửa cho khớp nhé)
            if ($user->vai_tro === 'admin') {
                return $next($request); // Cho qua
            } else {
                // Nếu là khách hàng -> Đá về trang chủ hoặc báo lỗi
                return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang quản trị!');
            }
        }

        // Chưa đăng nhập -> Về trang login
        return redirect()->route('login');
    }
}
