@extends('layouts.vertical', ['title' => 'Invoice Payment'])

@section('css')
<style>
    .invoice-shell {
        min-height: 70vh;
    }

    .invoice-meta-card {
        position: sticky;
        top: 90px;
    }

    .invoice-frame {
        width: 100%;
        min-height: 78vh;
        border: 1px solid #e7edf5;
        border-radius: 18px;
        background: #fff;
    }
</style>
@endsection

@section('content')
@include('layouts.partials.page-title', ['subtitle' => 'SPPD', 'title' => 'Invoice Payment'])

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row invoice-shell">
    <div class="col-xl-3 col-lg-4">
            <div class="card invoice-meta-card">
            <div class="card-body">
                <h4 class="mb-3">Ringkasan Invoice</h4>

                <div class="mb-3">
                    <div class="text-muted small">Payment ID</div>
                    <div class="fw-semibold">{{ $invoice['id'] ?? '-' }}</div>
                </div>

                <div class="mb-3">
                    <div class="text-muted small">Status</div>
                    <span class="badge bg-{{ ($invoice['status'] ?? '') === 'PAID' ? 'success' : (($invoice['status'] ?? '') === 'PENDING' ? 'warning' : 'secondary') }}">
                        {{ $invoice['status'] ?? '-' }}
                    </span>
                </div>

                <div class="mb-3">
                    <div class="text-muted small">Jenis Pembayaran</div>
                    <div class="fw-semibold">{{ strtoupper($invoice['payment_type'] ?? '-') }}</div>
                </div>

                @if(!empty($isPlaceholderInvoice))
                    <div class="alert alert-warning">
                        Invoice ini masih memakai URL dummy `example.test`, jadi belum ada halaman invoice nyata yang bisa ditampilkan.
                    </div>
                @endif

                <div class="d-grid gap-2">
                    <a href="{{ $invoiceUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                        Buka di Tab Baru
                    </a>
                    <a href="{{ url()->previous() }}" class="btn btn-light border">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-9 col-lg-8">
        <div class="card">
            <div class="card-body p-2 p-md-3">
                @if(!empty($isPlaceholderInvoice))
                    <div class="invoice-frame d-flex align-items-center justify-content-center text-center p-4">
                        <div>
                            <h4 class="mb-2">Invoice Dummy</h4>
                            <p class="text-muted mb-3">
                                Data ini berasal dari seeder/demo sehingga `invoice_url` masih mengarah ke `example.test`.
                            </p>
                            <p class="text-muted mb-0">
                                Untuk invoice nyata, buat payment baru dari environment yang sudah terhubung ke provider atau update `invoice_url` di tabel `payments`.
                            </p>
                        </div>
                    </div>
                @else
                    <iframe
                        src="{{ $invoiceUrl }}"
                        title="Invoice Viewer"
                        class="invoice-frame"
                    ></iframe>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
