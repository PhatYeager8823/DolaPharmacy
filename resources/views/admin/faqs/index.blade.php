@extends('layouts.admin')
@section('title', 'Quản lý FAQ')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Nội dung /</span> FAQ</h4>
        <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Thêm câu hỏi
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Danh sách câu hỏi thường gặp</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Câu hỏi</th>
                        <th>Câu trả lời</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($faqs as $faq)
                    <tr>
                        <td>
                            <strong class="text-primary">{{ \Illuminate\Support\Str::limit($faq->cau_hoi, 50) }}</strong>
                        </td>
                        <td>
                            <small class="text-muted">{{ \Illuminate\Support\Str::limit($faq->tra_loi, 60) }}</small>
                        </td>
                        <td>
                            @if($faq->is_active)
                                <span class="badge bg-label-success">Hiển thị</span>
                            @else
                                <span class="badge bg-label-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.faqs.edit', $faq->id) }}"><i class="bx bx-edit-alt me-1"></i> Sửa</a>
                                    <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Xóa câu hỏi này?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger"><i class="bx bx-trash me-1"></i> Xóa</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-end">
            {{ $faqs->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
