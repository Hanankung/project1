{{-- resources/views/member/cart.blade.php --}}
@extends('member.layout')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            :root {
                --brand: #6b4e2e;
                --gold-1: #d4af37;
                --gold-2: #f6e27a;
                --ink: #1f2937;
                --muted: #6b7280;
            }
            * { font-family: "Prompt", system-ui, -apple-system, "Segoe UI", Roboto, "Noto Sans Thai", Arial, sans-serif }
            body { background: linear-gradient(180deg, #faf7f2 0%, #fff 60%) }

            .page-title{
                font-weight: 800; letter-spacing:.2px; margin:.25rem 0 1rem;
                background: linear-gradient(120deg, var(--gold-1), var(--gold-2), var(--gold-1));
                -webkit-background-clip:text; background-clip:text; color:transparent;
            }

            .card{ border:1px solid rgba(139,106,70,.12); border-radius:18px !important; box-shadow:0 18px 44px rgba(0,0,0,.08); overflow:hidden; background:#fff; }
            .card-header{ background:linear-gradient(180deg,#fff,#fff8e6); border-bottom:1px solid rgba(212,175,55,.25)!important; font-weight:700; color:#444; }
            .table thead th{ color:#475467!important }
            .table tbody tr:hover{ background:#fffdf5 }
            .thumb{ width:64px; height:64px; object-fit:cover; border-radius:12px; border:1px solid #eee; }

            .qty-stepper{ display:inline-flex; align-items:center; gap:6px; border:1px solid #e5e7eb; border-radius:10px; padding:2px; background:#fff; }
            .qty-stepper .btn-qty{ width:34px; height:34px; border:none; border-radius:8px; background:#f3f4f6; color:#111827; display:grid; place-items:center; font-weight:800; line-height:1; cursor:pointer; }
            .qty-stepper .btn-qty:hover{ background:#e5e7eb }
            .qty-stepper .btn-qty:disabled{ opacity:.4; cursor:not-allowed }
            .qty-stepper input.qty-input{ width:56px; height:34px; border:none; text-align:center; font-weight:700; background:transparent; color:#111827; }
            td.cell-qty{ min-width:150px }

            .price-lg{ font-size:1.6rem; font-weight:800; line-height:1.1 }
            .btn-danger{ box-shadow:0 10px 24px rgba(220,53,69,.15) }
            .btn-brand{ background:#2e7d32!important; border-color:#2e7d32!important; color:#fff!important; font-weight:800; border-radius:12px; box-shadow:0 14px 28px rgba(46,125,50,.25); padding:.8rem 1rem; }
            .btn-brand:hover{ filter:brightness(.95) }
            .muted{ color:var(--muted)!important }
        </style>
    </head>

    <div class="container my-4">
        <h2 class="page-title">{{ __('messages.Shopping cart') }}</h2>

        @if (count($cartItems) > 0)
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">{{ __('messages.Items in Cart') ?? 'รายการสินค้าในตะกร้า' }}</div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:80px">{{ __('messages.product') }}</th>
                                            <th>{{ __('messages.product name') }}</th>
                                            <th class="text-end">{{ __('messages.price') }}</th>
                                            <th class="text-center">{{ __('messages.quantity') }}</th>
                                            <th class="text-end">{{ __('messages.line_total') ?? __('messages.Total price') }}</th>
                                            <th class="text-center" style="width:80px">{{ __('messages.delete') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $total = 0; @endphp
                                        @foreach ($cartItems as $item)
                                            @php
                                                $rowId = $item->id;
                                                $price = (float) $item->product->price;
                                                $qty   = (int) $item->quantity;
                                                $line  = $price * $qty;
                                                $total += $line;
                                                $maxStock = (int) ($item->product->quantity ?? 99);
                                            @endphp
                                            <tr data-row-id="{{ $rowId }}">
                                                <td><img class="thumb" src="{{ asset('storage/' . $item->product->product_image) }}" alt=""></td>
                                                <td>
                                                    @php
                                                        $locale = app()->getLocale();
                                                        echo $locale === 'en'
                                                            ? ($item->product->product_name_ENG ?: $item->product->product_name)
                                                            : ($locale === 'ms'
                                                                ? ($item->product->product_name_MS ?: $item->product->product_name)
                                                                : $item->product->product_name);
                                                    @endphp
                                                </td>
                                                <td class="text-end price-each" data-price="{{ $price }}">
                                                    {{ number_format($price, 2) }} {{ __('messages.baht') }}
                                                </td>
                                                <td class="text-center cell-qty">
                                                    <div class="qty-stepper"
                                                         data-update-url="{{ route('member.cart.update', $rowId) }}"
                                                         data-item-id="{{ $rowId }}"
                                                         data-stock="{{ $maxStock }}" data-price="{{ $price }}">
                                                        <button type="button" class="btn-qty minus" aria-label="decrease">−</button>
                                                        <input type="number" class="qty-input" value="{{ $qty }}" min="1" max="{{ $maxStock }}">
                                                        <button type="button" class="btn-qty plus" aria-label="increase">+</button>
                                                    </div>
                                                    <form action="{{ route('member.cart.update', $rowId) }}" method="POST" class="d-none" id="form-{{ $rowId }}">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="quantity" value="{{ $qty }}">
                                                    </form>
                                                </td>
                                                <td class="text-end line-total">{{ number_format($line, 2) }} {{ __('messages.baht') }}</td>
                                                <td class="text-center">
                                                    {{-- ไม่มี onsubmit แล้ว ใช้ SweetAlert2 ยืนยันก่อนลบ --}}
                                                    <form action="{{ route('member.cart.delete', $rowId) }}" method="POST"
                                                          class="delete-item-form"
                                                          data-product-name="@php
                                                              $loc = app()->getLocale();
                                                              echo $loc === 'en' ? ($item->product->product_name_ENG ?: $item->product->product_name)
                                                                   : ($loc === 'ms' ? ($item->product->product_name_MS ?: $item->product->product_name)
                                                                   : $item->product->product_name);
                                                          @endphp">
                                                        @csrf @method('DELETE')
                                                        <button type="button" class="btn btn-outline-danger btn-sm delete-item-btn">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- FOOTER: ราคารวม + ปุ่มสั่งซื้อ/ย้อนกลับ --}}
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-baseline gap-2">
                                    <span class="muted">{{ __('messages.Total price') }}</span>
                                    <span class="price-lg text-primary" id="grand">
                                        {{ number_format($total, 2) }} {{ __('messages.baht') }}
                                    </span>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('member.product') ?? '#' }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i>{{ __('messages.back') ?? 'ย้อนกลับ' }}
                                    </a>
                                    <form action="{{ route('checkout.from_cart') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-brand">
                                            <i class="bi bi-bag-check me-1"></i>{{ __('messages.buy_now') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- จบ footer --}}
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center">
                    <div class="display-6 mb-2">🛒</div>
                    <h5 class="mb-1">{{ __('messages.warn2') }}</h5>
                    <div class="text-muted mb-3">{{ __('messages.keep_shopping') ?? 'ยังไม่มีสินค้าในตะกร้า ลองเลือกชมสินค้าเพิ่มเติม' }}</div>
                    <a href="{{ route('member.product') ?? '#' }}" class="btn btn-brand">
                        <i class="bi bi-bag"></i> {{ __('messages.go_shopping') ?? 'เลือกซื้อสินค้า' }}
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- ========== SCRIPTS ========== --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const CSRF = '{{ csrf_token() }}';
            const money = (n) => Number(n || 0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});

            function recalcTotals(){
                let total = 0;
                document.querySelectorAll('td.line-total').forEach(td=>{
                    const val = parseFloat(String(td.innerText).replace(/[^\d.]/g,'') || 0);
                    total += val;
                });
                const grand = document.getElementById('grand');
                if (grand) grand.textContent = money(total) + ' {{ __('messages.baht') }}';
            }

            async function pushQty(updateUrl, qty){
                try{
                    await fetch(updateUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8',
                            'X-CSRF-TOKEN': CSRF,
                            'Accept':'application/json'
                        },
                        body: new URLSearchParams({_method:'PUT', quantity:String(qty)})
                    });
                }catch(e){ console.warn('update failed', e); }
            }

            function bindStepper(step){
                const minus = step.querySelector('.minus');
                const plus  = step.querySelector('.plus');
                const input = step.querySelector('.qty-input');
                const price = parseFloat(step.dataset.price || '0');
                const max   = parseInt(step.dataset.stock || '99', 10);
                const updateUrl = step.dataset.updateUrl;
                const row = step.closest('tr');
                const lineCell = row.querySelector('.line-total');
                const hiddenForm = document.getElementById('form-' + step.dataset.itemId);

                const setQty = (q)=>{
                    let v = Math.max(1, Math.min(max, parseInt(q || '1', 10)));
                    input.value = v;
                    if (hiddenForm) hiddenForm.querySelector('input[name="quantity"]').value = v;
                    if (lineCell) lineCell.innerText = money(price * v) + ' {{ __('messages.baht') }}';
                    minus.disabled = (v <= 1);
                    plus.disabled  = (v >= max);
                    recalcTotals();
                };

                minus.addEventListener('click', ()=>{ setQty(parseInt(input.value,10)-1); pushQty(updateUrl, input.value); });
                plus .addEventListener('click', ()=>{ setQty(parseInt(input.value,10)+1); pushQty(updateUrl, input.value); });
                input.addEventListener('change',   ()=>{ setQty(input.value); pushQty(updateUrl, input.value); });

                setQty(input.value);
            }

            document.querySelectorAll('.qty-stepper').forEach(bindStepper);
        });
    </script>

    {{-- SweetAlert2 + confirm delete (วางท้ายไฟล์ ก่อน @endsection) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('click', function (e) {
      const btn = e.target.closest('.delete-item-btn');
      if (!btn) return;

      e.preventDefault();
      const form = btn.closest('form.delete-item-form');
      const name = (form && form.dataset && form.dataset.productName) ? form.dataset.productName : '';

      Swal.fire({
        icon: 'warning',
        title: @json(__('messages.confirm_delete_title')),
        html: @json(__('messages.confirm_delete')) + (name ? '<br><b>'+name+'</b>' : ''),
        showCancelButton: true,
        confirmButtonText: @json(__('messages.delete')),
        cancelButtonText: @json(__('messages.cancel')),
        confirmButtonColor: '#d33',
        reverseButtons: true
      }).then((res) => {
        if (res.isConfirmed) form.submit();
      });
    });
    </script>

@endsection
