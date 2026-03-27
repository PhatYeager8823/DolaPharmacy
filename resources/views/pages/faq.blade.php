@extends('layouts.app')
@section('title', 'Câu hỏi thường gặp')

@section('content')
<div class="bg-light py-5">
    <div class="container">

        {{-- Header --}}
        <div class="text-center mb-5">
            <h5 class="text-primary fw-bold text-uppercase">Hỗ trợ khách hàng</h5>
            <h1 class="fw-bold">Câu hỏi thường gặp</h1>
            <p class="text-muted" style="max-width: 600px; margin: 0 auto;">
                Tổng hợp những thắc mắc phổ biến nhất của khách hàng khi mua sắm tại {{ $global_setting->ten_website }}.
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- Accordion --}}
                <div class="accordion shadow-sm" id="faqAccordion">

                    @foreach($faqs as $index => $faq)
                        <div class="accordion-item border-0 mb-3 rounded overflow-hidden">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }} fw-bold py-3"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $index }}"
                                        aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                        aria-controls="collapse{{ $index }}">
                                    <span class="me-3 text-primary fw-bold">0{{ $index + 1 }}.</span> {{ $faq->cau_hoi }}
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}"
                                 class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                 aria-labelledby="heading{{ $index }}"
                                 data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-secondary" style="line-height: 1.6;">
                                    {{ $faq->tra_loi }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

                {{-- Box hỗ trợ thêm --}}
                <div class="text-center mt-5">
                    <p class="mb-3">Bạn vẫn chưa tìm thấy câu trả lời?</p>
                    <a href="tel:{{ str_replace(['.', ' '], '', $global_setting->hotline ?? '0123.456.789') }}" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fa fa-phone-alt me-2"></i> Gọi ngay {{ $global_setting->hotline ?? '0123.456.789' }}
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

<style>
    /* Tùy chỉnh Accordion cho đẹp hơn */
    .accordion-item {
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .accordion-button:not(.collapsed) {
        color: #0d6efd;
        background-color: #e7f1ff;
        box-shadow: none;
    }
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }
</style>
@endsection
