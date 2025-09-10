@extends('layouts.vertical', ['title' => 'Preview SPPD'])

@section('css')
<style>
/* Timeline Style */
.timeline {
    border-left: 2px solid #e9ecef;
    margin-left: 15px;
    padding-left: 10px;
}
.timeline-item {
    position: relative;
}
.timeline-marker {
    left: -5px;
}
</style>
@endsection

@section('content')
@include('layouts.partials.page-title', ['subtitle' => 'SPPD', 'title' => 'Preview SPPD'])

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                {{-- Header --}}
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <img src="/images/ias.png" alt="logo" height="65">
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary-subtle text-primary px-2 fs-12 mb-2">{{ $sppd['status'] ?? 'DRAFT' }}</span>
                        <h3 class="fw-bold fs-20 m-0">SPPD: {{ $sppd['nomor_sppd'] ?? '-' }}</h3>
                    </div>
                </div>
                {{-- Info Pegawai & Perjalanan --}}
                <div class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <h5 class="fw-semibold fs-14">Diberikan Kepada:</h5>
                        <p class="mb-1">{{ $sppd['user']['name'] ?? '-' }}</p>
                        <h5 class="fw-semibold fs-14">NIK:</h5>
                        <p class="mb-1">{{ $sppd['user']['employee']['employee_number'] ?? '-' }}</p>
                        <h5 class="fw-semibold fs-14">Divisi & Jabatan :</h5>
                        <p class="mb-1">{{ $sppd['user']['employee']['division']['name'] ?? '-' }} - {{ $sppd['user']['employee']['position']['name'] ?? '-' }}</p>
                    </div>
                    <div class="col-md-5 mb-2">
                        <h5 class="fw-semibold fs-14">Tujuan Perjalanan:</h5>
                        <p class="mb-1">{{ $tujuan[0]['province']['name'] ?? '-' }} - {{ $tujuan[0]['village']['name'] ?? '-' }}</p>
                        <h5 class="fw-semibold fs-14">Keperluan Perjalanan:</h5>
                        <p class="text-muted">
                            {{ $sppd['keperluan'] ?? '-' }}
                        </p>
                        @php 
                            $start = !empty($sppd['tanggal_berangkat']) ? \Carbon\Carbon::parse($sppd['tanggal_berangkat']) : null;
                            $end   = !empty($sppd['tanggal_pulang']) ? \Carbon\Carbon::parse($sppd['tanggal_pulang']) : null;
                            $days  = ($start && $end) ? $start->diffInDays($end) + 1 : null;
                        @endphp
                        <h5 class="fw-semibold fs-14">Periode Perjalanan:</h5>
                        <p class="text-muted mb-0">
                            {{ $start ? $start->translatedFormat('d F Y') : '-' }}
                            →
                            {{ $end ? $end->translatedFormat('d F Y') : '-' }}
                            @if($days)
                                ({{ $days }} hari)
                            @endif
                        </p>
                    </div>

                    <div class="col-md-3 text-center text-md-end">
                        <img src="/images/png/qr-code.png" alt="QR Code" height="100">
                    </div>
                </div>

                {{-- Tabel Ringkasan Biaya --}}
                @php
                    function rupiahToInt($val) {
                        if(empty($val)) return 0;
                        return (int) str_replace(['Rp','.', ' '], '', $val);
                    }

                    // Ambil semua komponen harga
                    $hotel_price   = rupiahToInt($sppd['hotel_price'] ?? 0);
                    $ticket_depart = rupiahToInt($sppd['ticket_price_depart'] ?? 0);
                    $ticket_return = rupiahToInt($sppd['ticket_price_return'] ?? 0);
                    $transportasi  = rupiahToInt($sppd['transport_price'] ?? 0);

                    $addons_price = 0;
                    if(!empty($sppd['addons']) && is_array($sppd['addons'])){
                        foreach($sppd['addons'] as $addon){
                            if(isset($addon['price'])) $addons_price += rupiahToInt($addon['price']);
                        }
                    }

                    // Total semua biaya
                    $total = $hotel_price + $ticket_depart + $ticket_return + $addons_price + $transportasi;
                @endphp

                <div class="mt-2 mb-2 border-top border-dashed">
                    <h5>Rincian Pengeluaran</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-start">Pengeluaran</th>
                                <th class="text-start">Kategori</th>
                                <th class="text-end">Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $no = 1;
                                $total = collect($expense)->sum('jumlah');
                            @endphp
                            @foreach($expense as $ex)
                            <tr>
                                <td class="text-center">{{$no++}}</td>
                                <td>{{$ex['kategori']}}</td>
                                <td>{{$ex['deskripsi']}}</td>
                                <td class="text-end">Rp{{ number_format($ex['jumlah'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end fw-bold fs-5">Total Keseluruhan</th>
                                <th class="text-end fw-bold fs-5">Rp{{ number_format($total,0,',','.' ?? 0) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                

                {{-- Footer --}}
                <div class="bg-light p-3 mt-4 rounded-2">
                    <p class="mb-0"><strong>Catatan:</strong> SPPD ini digunakan sebagai bukti perjalanan dinas resmi. Simpan dokumen untuk administrasi.</p>
                </div>

                {{-- Tombol Print / Download --}}
                <div class="d-print-none mt-4 text-center">
                    <a href="javascript:window.print()" class="btn btn-primary me-2"><i class="ti ti-printer me-1"></i> Print</a>
                    <a href="#" class="btn btn-info"><i class="ti ti-download me-1"></i> Download</a>
                </div>
            </div>
        </div>

        @php

            $baseUrl = str_replace('/api', '', env('SPPD_API_URL'));
            $fileUrl = $baseUrl . '/' . 'storage'. '/' . $file['file_path'];
        @endphp
        <div class="card">
            <div class="card-header border-bottom border-dashed">
                <h5 class="mb-0">Surat Tugas Perjalanan Dinas</h5>
            </div>
            <div class="card-body">
                @if($fileUrl)
                    <iframe src="{{ $fileUrl }}" width="100%" height="600px" style="border:none;"></iframe>
                @else
                    <p class="text-muted">Tidak ada file PDF untuk ditampilkan.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- History Card -->
    <div class="col-md-4">
        <div class="card mb-2">
            <div class="card-header border-bottom border-dashed">
                <h5 class="mb-0">Approval</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @foreach($approval as $index => $a)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $a['approver']['name'] }}</strong>
                                <span class="text-muted">
                                    ({{ $a['approver']['employee']['division']['name'] ?? '-' }} - 
                                    {{ $a['approver']['employee']['position']['name'] ?? '-' }})
                                </span><br>

                                <small>Status: 
                                    <span class="@if($a['status']=='Approved') text-success 
                                                @elseif($a['status']=='Rejected') text-danger 
                                                @else text-warning @endif">
                                        {{ $a['status'] }}
                                    </span>
                                </small>
                                @if($a['catatan'])
                                    <p class="mb-0 small text-muted">{{ $a['catatan'] }}</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>

                @php
                    // cari approval step yang sesuai user sekarang
                    $userApproval = collect($approval)->firstWhere('approver_id', $currentUserId);

                    // cek indexnya
                    $userIndex = $userApproval ? collect($approval)->search(fn($ap) => $ap['id'] == $userApproval['id']) : null;

                    // validasi apakah boleh approve (semua step sebelum dia sudah Approved)
                    $canApprove = false;
                    if ($userApproval && $userApproval['status'] == 'Pending') {
                        $canApprove = true;
                        for ($i = 0; $i < $userIndex; $i++) {
                            if ($approval[$i]['status'] != 'Approved') {
                                $canApprove = false;
                                break;
                            }
                        }
                    }
                @endphp

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <form class="approval-form approve-form" method="POST">
                        @csrf
                        <input type="hidden" name="approval_id" value="{{ $userApproval['id'] ?? '' }}">
                        <button type="submit" class="btn btn-sm btn-success" @if(!$canApprove) disabled @endif>
                            Approve
                        </button>
                    </form>
                    <form class="approval-form reject-form" method="POST">
                        @csrf
                        <input type="hidden" name="approval_id" value="{{ $userApproval['id'] ?? '' }}">
                        <button type="submit" class="btn btn-sm btn-danger" @if(!$canApprove) disabled @endif>
                            Reject
                        </button>
                    </form>
                </div>

                <input type="hidden" id="sppd-id" value="{{ $sppd['id'] }}">
            </div>

        </div>
        
        <div class="card mb-2">
            <div class="card-header border-bottom border-dashed">
                <h5 class="mb-0">Payment</h5>
            </div>
            <div class="card-body text-center">
                @if(!empty($payment))
                    <p>Status Pembayaran:
                        <span class="badge 
                            @if($payment['payment_type'] === 'reimbursement') bg-info
                            @elseif($payment['status'] === 'PENDING') bg-warning
                            @elseif($payment['status'] === 'PAID') bg-success
                            @else bg-secondary @endif">
                            {{ strtoupper($payment['payment_type'] === 'reimbursement' ? 'REIMBURSEMENT' : $payment['status']) }}
                        </span>
                    </p>

                    @if($payment['status'] === 'PENDING')
                        @if(session('user.role') == 'finance')
                            <a href="{{ $payment['invoice_url'] }}" target="_blank" class="btn btn-primary">
                                <i class="ti ti-credit-card me-1"></i> Bayar Sekarang
                            </a>
                        @endif
                    @elseif($payment['status'] === 'PAID')
                        <p class="text-success fw-bold">Pembayaran berhasil</p>
                    @endif
                @else
                    @if(session('user.role') == 'finance')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card shadow-sm border-primary mb-3 payment-option" onclick="selectPayment('digital')">
                                    <div class="card-body text-center">
                                        <i class="ti ti-credit-card fs-1 text-primary"></i>
                                        <h5 class="mt-2">Digital Payment</h5>
                                        <p class="text-muted">Bayar langsung via Xendit</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card shadow-sm border-success mb-3 payment-option" onclick="selectPayment('reimbursement')">
                                    <div class="card-body text-center">
                                        <i class="ti ti-wallet fs-1 text-success"></i>
                                        <h5 class="mt-2">Invoicing</h5>
                                        <p class="text-muted">Tagihan Oleh Pihak Travel</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- FORM Digital Payment --}}
                        <div id="digital-payment-form" class="d-none mt-3">
                            <form action="{{ route('sppd.pay', $sppd['id']) }}" method="POST">
                                @csrf
                                <input type="hidden" name="amount" value="{{ $total }}">
                                <button type="submit" class="btn btn-success">
                                    Bayar via Xendit
                                </button>
                            </form>
                        </div>

                        {{-- FORM Reimbursement --}}
                        <div id="reimbursement-form" class="d-none mt-3">
                            <form action="{{ route('sppd.pay', $sppd['id']) }}" method="POST">
                                @csrf
                                <input type="hidden" name="payment_type" value="invoicing">
                                <input type="hidden" name="amount" value="{{ $total }}">
                                <button type="submit" class="btn btn-outline-success">
                                    Pilih Invoicing
                                </button>
                            </form>
                        </div>
                    @else
                        <p>Pembayaran Belum dilakukan</p>
                    @endif
                @endif
            </div>
        </div>


        <div class="card">
            <div class="card-header border-bottom border-dashed">
                <h5 class="mb-0">History</h5>
            </div>
            <div class="card-body">
                 @if(!empty($history) && count($history) > 0)
                    <ul class="timeline list-unstyled">
                        @foreach($history as $h)
                            <li class="timeline-item mb-4 position-relative ps-4">
                                <div class="timeline-marker position-absolute top-0 start-0 translate-middle bg-primary rounded-circle" style="width:12px; height:12px;"></div>
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1 text-dark">
                                        {{ $h['status_awal'] }} → <span class="text-primary">{{ $h['status_akhir'] }}</span>
                                    </h6>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($h['created_at'])->format('d M Y H:i') }}
                                    </small>
                                </div>
                                <p class="mb-0 text-secondary small">
                                    {{ $h['user']['name'] ?? 'Unknown User' }}
                                </p>
                                <p class="mb-0 text-secondary small">
                                    {{ $h['catatan'] ?? '-' }}
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">Belum ada riwayat</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    // Fungsi untuk mengupdate status approval
    const API_BASE_URL = "{{ env('SPPD_API_URL') }}";
    const TOKEN = "{{ Session::get('jwt_token') }}";
    function updateApprovalStatus(approvalId, status, catatan = '') {
        // Tampilkan loading indicator jika diperlukan
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
        button.disabled = true;
        
        // Kirim request Ajax
        fetch(`${API_BASE_URL}/sppd/update-status`, {
            method: 'POST',
            headers: {
                "Authorization": `Bearer ${TOKEN}`,
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                approval_id: approvalId,
                status: status,
                catatan: catatan
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh daftar approval atau update UI
                showSuccessAlert('Status berhasil diupdate');
                setTimeout(() => {
                    location.reload(); // reload otomatis
                }, 1500);; // Fungsi untuk refresh daftar approval
            } else {
                showErrorAlert(data.message || 'Terjadi kesalahan');
                button.innerHTML = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorAlert('Terjadi kesalahan jaringan');
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }

    // Fungsi untuk refresh daftar approval
    function refreshApprovalList() {
        // Dapatkan sppd_id dari elemen tersembunyi atau data attribute
        const sppdId = document.getElementById('sppd-id').value;
        
        fetch(`${API_BASE_URL}/approval/status/${sppdId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI dengan data approval terbaru
                    updateApprovalUI(data.data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Event listener untuk form approval
    document.addEventListener('DOMContentLoaded', function() {
        const approvalForms = document.querySelectorAll('.approval-form');
        
        approvalForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const approvalId = this.querySelector('input[name="approval_id"]').value;
                const isApprove = this.classList.contains('approve-form');
                const status = isApprove ? 'Approved' : 'Rejected';
                
                // Jika reject, minta catatan
                if (status === 'Rejected') {
                    Swal.fire({
                        title: 'Alasan Penolakan',
                        input: 'textarea',
                        inputLabel: 'Masukkan alasan penolakan',
                        inputPlaceholder: 'Ketik alasan penolakan di sini...',
                        inputAttributes: {
                            'aria-label': 'Ketik alasan penolakan di sini'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        cancelButtonText: 'Batal',
                        preConfirm: (catatan) => {
                            if (!catatan) {
                                Swal.showValidationMessage('Harap masukkan alasan penolakan');
                            }
                            return catatan;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            updateApprovalStatus(approvalId, status, result.value);
                        }
                    });
                } else {
                    updateApprovalStatus(approvalId, status);
                }
            });
        });
    });
    </script>
    <script>
        function showSuccessAlert(message) {
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: message,
            timer: 2000,
            showConfirmButton: false
        });
    }

    // Fungsi untuk menampilkan alert error
    function showErrorAlert(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message
        });
    }
</script>
<script>
    function selectPayment(type) {
        document.getElementById('digital-payment-form').classList.add('d-none');
        document.getElementById('reimbursement-form').classList.add('d-none');

        if(type === 'digital') {
            document.getElementById('digital-payment-form').classList.remove('d-none');
        } else if(type === 'reimbursement') {
            document.getElementById('reimbursement-form').classList.remove('d-none');
        }
    }
</script>
@endsection
