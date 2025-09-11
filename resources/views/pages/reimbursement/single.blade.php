@extends('layouts.vertical', ['title' => 'Buat Klaim Reimbursement'])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Detail Reimbursement',
        'subtitle' => 'Reimbursement'
    ])

    <div class="row">
        <!-- Kolom Detail Klaim -->
        <div class="col-lg-8">
            <!-- Card Klaim -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0">💳 Detail Klaim</h5>
                    <span class="badge px-3 py-2 rounded-pill 
                        @if($reimbursement['status'] == 'SUBMITTED') bg-warning text-dark
                        @elseif($reimbursement['status'] == 'APPROVED') bg-success
                        @elseif($reimbursement['status'] == 'REJECTED') bg-danger
                        @else bg-secondary @endif">
                        {{ ucfirst(strtolower($reimbursement['status'])) }}
                    </span>
                </div>
                <div class="card-body">
                    <h5 class="fw-bold mb-1">{{ $reimbursement['title'] }}</h5>
                    <p class="text-muted fst-italic">{{ $reimbursement['description'] ?? 'Tidak ada deskripsi' }}</p>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <p class="mb-1 text-muted small">Nominal</p>
                                <h6 class="fw-bold text-dark">Rp {{ number_format($reimbursement['amount'], 0, ',', '.') }}</h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <p class="mb-1 text-muted small">Diajukan Oleh</p>
                                <h6 class="fw-bold text-dark">{{ $user['name'] ?? '-' }}</h6>
                                <small class="text-muted">{{ $user['email'] ?? '' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Detail SPPD -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">🚌 Detail SPPD</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">No. SPPD</span>
                            <span>{{ $sppd['nomor_sppd'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Tujuan</span>
                            <span>{{ $sppd['tujuan'] }} ({{ $sppd['lokasi_tujuan'] }})</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Tanggal</span>
                            <span>{{ $sppd['tanggal_berangkat'] }} → {{ $sppd['tanggal_pulang'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Transportasi</span>
                            <span>{{ $sppd['transportasi'] }}</span>
                        </li>
                    </ul>
                    <div class="mt-3">
                        <p class="fw-semibold mb-1">Keperluan</p>
                        <p class="text-muted">{{ $sppd['keperluan'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Card Lampiran -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">📂 Lampiran</h5>
                </div>
                <div class="card-body">
                    @forelse ($files as $file)
                        <div class="d-flex align-items-center p-2 bg-light rounded mb-2 shadow-sm">
                            <i class="bi bi-file-earmark-pdf text-danger fs-4 me-2"></i>
                            <a href="{{ asset($file['file_path']) }}" target="_blank" class="fw-semibold text-decoration-none">
                                Dokumen #{{ $file['id'] }} ({{ strtoupper($file['file_type']) }})
                            </a>
                        </div>
                    @empty
                        <p class="text-muted fst-italic">Tidak ada file lampiran.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Kolom Histori -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">📝 Histori Persetujuan</h5>
                </div>
                <div class="card-body">
                    @if(!empty($approvals))
                        <div class="timeline">
                            @foreach($approvals as $approval)
                                <div class="mb-3 p-2 rounded border-start border-3 
                                    @if($approval['status'] == 'APPROVED') border-success
                                    @elseif($approval['status'] == 'REJECTED') border-danger
                                    @else border-warning @endif">
                                    <strong>{{ $approval['user']['name'] ?? '-' }}</strong>
                                    <span class="badge rounded-pill ms-2
                                        @if($approval['status'] == 'APPROVED') bg-success
                                        @elseif($approval['status'] == 'REJECTED') bg-danger
                                        @else bg-warning text-dark @endif">
                                        {{ $approval['status'] }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ $approval['created_at'] }}</small>
                                    <p class="mb-0">{{ $approval['notes'] ?? '' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted fst-italic">Belum ada histori persetujuan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#sppd_id').select2({
                placeholder: "Pilih SPPD",
                allowClear: true
            });
        });
    </script>
@endsection
