@extends('layouts.vertical', ['title' => 'Preview SPPD'])

@section('content')
@include('layouts.partials.page-title', ['subtitle' => 'SPPD', 'title' => 'Preview SPPD'])

<div class="row">
    <div class="col-12">
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
                <div class="row mb-3">
                    <div class="col-md-4 mb-2">
                        <h5 class="fw-semibold fs-14">Diberikan Kepada:</h5>
                        <p class="mb-1">{{ $sppd['user']['name'] ?? '-' }}</p>
                        <p class="text-muted mb-0">{{ $sppd['lokasi_tujuan'] ?? '-' }}</p>
                    </div>

                    <div class="col-md-4 mb-2">
                        <h5 class="fw-semibold fs-14">Tujuan Perjalanan:</h5>
                        <p class="mb-1">{{ $sppd['tujuan'] ?? '-' }}</p>
                        <p class="text-muted mb-0">
                            Berangkat: {{ !empty($sppd['tanggal_berangkat']) ? \Carbon\Carbon::parse($sppd['tanggal_berangkat'])->translatedFormat('d F Y') : '-' }}
                        </p>
                        <p class="text-muted mb-0">
                            Kembali: {{ !empty($sppd['tanggal_pulang']) ? \Carbon\Carbon::parse($sppd['tanggal_pulang'])->translatedFormat('d F Y') : '-' }}
                        </p>
                    </div>

                    <div class="col-md-4 text-center text-md-end">
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

                <div class="table-responsive border-top border-dashed mt-3 pt-3">
    <table class="table table-bordered table-striped mb-0">
        <thead class="table-light text-center">
            <tr>
                <th>#</th>
                <th class="text-start">Keterangan</th>
                <th class="text-start">Detail</th>
                <th class="text-end">Harga</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td class="fw-semibold">Hotel</td>
                <td>{{ $sppd['hotel'] ?? '-' }}</td>
                <td class="text-end">Rp{{ number_format($hotel_price,0,',','.') }}</td>
            </tr>
            <tr>
                <td class="text-center">2</td>
                <td class="fw-semibold">Transportasi</td>
                <td>{{ $sppd['transportasi'] ?? '-' }}</td>
                <td class="text-end">Rp{{ number_format($transportasi,0,',','.') }}</td>
            </tr>
            <tr>
                <td class="text-center">3</td>
                <td class="fw-semibold">Tiket Pergi</td>
                <td>{{ $sppd['departure_airport'] ?? '-' }} → {{ $sppd['arrival_airport'] ?? '-' }}</td>
                <td class="text-end">Rp{{ number_format($ticket_depart,0,',','.') }}</td>
            </tr>
            <tr>
                <td class="text-center">4</td>
                <td class="fw-semibold">Tiket Pulang</td>
                <td>{{ $sppd['return_departure_airport'] ?? '-' }} → {{ $sppd['return_arrival_airport'] ?? '-' }}</td>
                <td class="text-end">Rp{{ number_format($ticket_return,0,',','.') }}</td>
            </tr>
            @if(!empty($sppd['addons']))
                <tr>
                    <td class="text-center">5</td>
                    <td class="fw-semibold">Add-ons</td>
                    <td>{{ implode(', ', array_map(fn($a)=>$a['name'] ?? '', $sppd['addons'])) }}</td>
                    <td class="text-end">Rp{{ number_format($addons_price,0,',','.') }}</td>
                </tr>
            @endif
        </tbody>
        <tfoot class="table-light">
            <tr>
                <th colspan="3" class="text-end fw-bold fs-5">Total Keseluruhan</th>
                <th class="text-end fw-bold fs-5">Rp{{ number_format($total,0,',','.') }}</th>
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
    </div>
</div>
@endsection
