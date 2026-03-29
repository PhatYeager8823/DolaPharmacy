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
                        <th width="150">Trạng thái</th>
                        <th width="200">Người gửi</th>
                        <th width="250">Thông tin liên hệ</th>
                        <th>Nội dung tin nhắn</th>
                        <th width="150">Ngày gửi</th>
                        <th width="120">Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($contacts as $contact)
                    <tr>
                        <td><strong>#{{ $contact->id }}</strong></td>

                        {{-- Trạng thái --}}
                        <td>
                            @if($contact->trang_thai == 1)
                                <span class="badge bg-label-success">Đã xử lý</span>
                            @else
                                <span class="badge bg-label-warning">Tin mới</span>
                            @endif
                        </td>

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

                        {{-- Tiêu đề & Nội dung --}}
                        <td>
                            <div style="white-space: normal; max-width: 350px;">
                                @if(!empty($contact->tieu_de))
                                    <div class="fw-bold text-dark mb-1">
                                        {{ \Illuminate\Support\Str::limit($contact->tieu_de, 60) }}
                                    </div>
                                @else
                                    <div class="fst-italic text-muted mb-1 small">(Không có tiêu đề)</div>
                                @endif

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

                        {{-- Hành động --}}
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                {{-- Nút Xem chi tiết --}}
                                <button type="button" class="btn btn-sm btn-icon btn-label-info view-contact-btn" 
                                        data-bs-toggle="modal" data-bs-target="#viewContactModal"
                                        data-name="{{ $contact->ho_ten ?? $contact->ten }}"
                                        data-email="{{ $contact->email }}"
                                        data-sdt="{{ $contact->sdt }}"
                                        data-title="{{ $contact->tieu_de }}"
                                        data-content="{{ $contact->noi_dung }}"
                                        title="Xem chi tiết">
                                    <i class="bx bx-show"></i>
                                </button>

                                {{-- Nút Đánh dấu Đã đọc / Chưa đọc --}}
                                <form action="{{ route('admin.contacts.toggle', $contact->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-icon {{ $contact->trang_thai == 1 ? 'btn-label-secondary' : 'btn-label-success' }}" 
                                            title="{{ $contact->trang_thai == 1 ? 'Đánh dấu tin mới' : 'Đánh dấu đã xử lý' }}">
                                        <i class="bx {{ $contact->trang_thai == 1 ? 'bx-redo' : 'bx-check' }}"></i>
                                    </button>
                                </form>

                                {{-- Nút Xóa --}}
                                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-icon btn-label-danger delete-confirm-btn" 
                                            data-message="Bạn có chắc muốn xóa tin nhắn liên hệ này không?" title="Xóa tin nhắn">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
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

{{-- MODAL XEM CHI TIẾT --}}
<div class="modal fade" id="viewContactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content overflow-hidden" style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1);">
            <div class="modal-header border-bottom border-light">
                <h5 class="modal-title text-white" id="modalName">Chi tiết tin nhắn liên hệ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase">Người gửi</label>
                        <h5 class="text-white mb-0" id="detailName">...</h5>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase">Thông tin liên hệ</label>
                        <div class="text-white">
                            <i class="bx bx-envelope me-1"></i> <span id="detailEmail">...</span><br>
                            <i class="bx bx-phone me-1"></i> <span id="detailSdt">...</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small text-uppercase">Tiêu đề</label>
                        <h6 class="text-info fw-bold" id="detailTitle">...</h6>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small text-uppercase">Nội dung tin nhắn</label>
                        <div class="p-3 rounded border border-secondary text-white" style="background: rgba(255,255,255,0.05); min-height: 150px; white-space: pre-wrap;" id="detailContent">...</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top border-light">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Đóng</button>
                <a href="#" id="replyEmailBtn" class="btn btn-primary">Phản hồi qua Email</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý nạp dữ liệu vào Modal khi bấm nút Xem
        const viewButtons = document.querySelectorAll('.view-contact-btn');
        const modal = document.getElementById('viewContactModal');
        
        viewButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const name = this.getAttribute('data-name');
                const email = this.getAttribute('data-email');
                const sdt = this.getAttribute('data-sdt') || 'Không có';
                const title = this.getAttribute('data-title') || '(Không có tiêu đề)';
                const content = this.getAttribute('data-content');

                document.getElementById('detailName').innerText = name;
                document.getElementById('detailEmail').innerText = email;
                document.getElementById('detailSdt').innerText = sdt;
                document.getElementById('detailTitle').innerText = title;
                document.getElementById('detailContent').innerText = content;
                document.getElementById('replyEmailBtn').href = `mailto:${email}`;
            });
        });
    });
</script>
@endpush
