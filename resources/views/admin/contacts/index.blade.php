@extends('layouts.admin')
@section('title', 'Quản lý Liên hệ')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Tiêu đề trang --}}
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Nội dung /</span> Liên hệ từ khách
    </h4>

    {{-- Bảng danh sách --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách tin nhắn</h5>
            <small class="text-muted">Hiển thị {{ $contacts->count() }} tin mới nhất</small>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th width="200">Người gửi</th>
                        <th width="250">Thông tin liên hệ</th>
                        <th>Nội dung tin nhắn</th> {{-- Cột này chứa cả Tiêu đề + Nội dung --}}
                        <th width="150">Ngày gửi</th>
                        <th width="100">Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($contacts as $contact)
                    <tr>
                        <td><strong>#{{ $contact->id }}</strong></td>

                        {{-- Tên khách hàng --}}
                        <td>
                            <strong class="text-primary">{{ $contact->ho_ten ?? $contact->ten }}</strong>
                        </td>

                        {{-- Email & SĐT --}}
                        <td>
                            <div class="d-flex flex-column">
                                <a href="mailto:{{ $contact->email }}" class="fw-semibold mb-1 text-body" title="Gửi mail phản hồi">
                                    <i class="bx bx-envelope me-1"></i> {{ $contact->email }}
                                </a>
                                @if($contact->sdt)
                                    <span class="text-muted small">
                                        <i class="bx bx-phone me-1"></i> {{ $contact->sdt }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- Tiêu đề & Nội dung (Quan trọng) --}}
                        <td>
                            <div style="white-space: normal; max-width: 400px;">
                                {{-- Hiển thị Tiêu đề in đậm (nếu có) --}}
                                @if(!empty($contact->tieu_de))
                                    <div class="fw-bold text-dark mb-1">
                                        {{ \Illuminate\Support\Str::limit($contact->tieu_de, 60) }}
                                    </div>
                                @else
                                    <div class="fst-italic text-muted mb-1 small">(Không có tiêu đề)</div>
                                @endif

                                {{-- Hiển thị Nội dung mờ hơn chút --}}
                                <small class="text-muted">
                                    {{ \Illuminate\Support\Str::limit($contact->noi_dung, 80) }}
                                </small>
                            </div>
                        </td>

                        {{-- Ngày gửi --}}
                        <td>
                            <span title="{{ $contact->created_at }}">
                                {{ $contact->created_at->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">{{ $contact->created_at->format('H:i') }}</small>
                            </span>
                        </td>

                        {{-- Nút Xóa --}}
                        <td>
                             <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-icon btn-label-danger delete-confirm-btn" 
                                        data-message="Bạn có chắc muốn xóa tin nhắn liên hệ này không?" title="Xóa tin nhắn">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">Chưa có tin nhắn liên hệ nào.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        @if($contacts->hasPages())
        <div class="card-footer d-flex justify-content-end">
            {{ $contacts->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
