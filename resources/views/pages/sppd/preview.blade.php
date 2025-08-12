@extends('layouts.vertical', ['title' => 'Preview SPPD'])

@section('content')
    @include('layouts.partials.page-title', ['subtitle' => 'SPPD', 'title' => 'Preview SPPD'])
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Header -->
                    <div class="d-flex align-items-start justify-content-between mb-4">
                        <div>
                            <img src="/images/ias.png" alt="logo" height="65">
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary-subtle text-primary px-2 fs-12 mb-3">DRAFT</span>
                            <h3 class="m-0 fw-bolder fs-20 nomor-sppd">SPPD: SPPD/XI/00/JKT/000</h3>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Data Pegawai -->
                        <div class="col-12 col-md-4 mb-3 mb-md-0 text-start">
                            <h5 class="fw-bold pb-1 mb-2 fs-14">Diberikan Kepada:</h5>
                            <h6 class="fs-14 mb-2">{{ $employee_name ?? '-' }}</h6>
                            <h6 class="fs-14 text-muted mb-1">-</h6>
                            <h6 class="fs-14 text-muted mb-0">{{ $province ?? '-' }}, {{ $city ?? '-' }}</h6>
                        </div>

                        <!-- Data Perjalanan -->
                        <div class="col-12 col-md-4 mb-3 mb-md-0 text-start">
                            <h5 class="fw-bold pb-1 mb-2 fs-14">Tujuan:</h5>
                            <h6 class="fs-14 mb-1">{{ $destination ?? '-' }}</h6>
                            <h6 class="fs-14 text-muted">
                                Berangkat:
                                {{ !empty($departure_date) ? \Carbon\Carbon::parse($departure_date)->translatedFormat('d F Y') : '-' }}
                            </h6>
                            <h6 class="fs-14 text-muted">
                                Kembali:
                                {{ !empty($return_date) ? \Carbon\Carbon::parse($return_date)->translatedFormat('d F Y') : '-' }}
                            </h6>
                        </div>

                        <!-- QR Code (center di mobile, default di desktop) -->
                        <div class="col-12 col-md-4 text-center text-md-end">
                            <img src="/images/png/qr-code.png" alt="QR Code" height="100" class="">
                        </div>
                    </div>
                </div>


                <!-- Detail Perjalanan -->
                @php
                    function rupiahToInt($value) {
                        if (empty($value)) return 0;
                        $clean = str_replace(['Rp', ' ', '.'], '', $value);
                        return (int) $clean;
                    }

                    $hotel_price = rupiahToInt($hotel_price ?? '0');
                    $ticket_price_depart_int = rupiahToInt($ticket_price_depart ?? '0');
                    $ticket_price_return_int = rupiahToInt($ticket_price_return ?? '0');

                    $addons_price = 0;
                    if(!empty($addons) && is_array($addons)) {
                        foreach($addons as $addon) {
                            if (is_string($addon)) continue;
                            if (isset($addon['price'])) {
                                $addons_price += rupiahToInt($addon['price']);
                            }
                        }
                    }

                    $total = $hotel_price + $ticket_price_depart_int + $ticket_price_return_int + $addons_price;
                @endphp

                <div class="p-3 border-top border-dashed" style="border-top-width:3px!important;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead class="table-light text-center">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th class="text-start">Keterangan</th>
                                    <th class="text-start">Detail</th>
                                    <th style="width: 15%;" class="text-end">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="text-center">1</th>
                                    <td class="fw-semibold">Hotel</td>
                                    <td>{{ $hotel ?? '-' }}</td>
                                    <td class="text-end">
                                        Rp{{ number_format($hotel_price, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">2</th>
                                    <td class="fw-semibold">Transportasi</td>
                                    <td>{{ !empty($transport) ? ucfirst($transport) : '-' }}</td>
                                    <td class="text-end">-</td>
                                </tr>
                                <tr>
                                    <th class="text-center">3</th>
                                    <td class="fw-semibold">Tiket Pergi</td>
                                    <td>
                                        <table class="table table-borderless mb-0" style="font-size: 0.9rem;">
                                            <tr>
                                                <td style="width: 40%;"><strong>Rute:</strong></td>
                                                <td>{{ $departure_airport ?? '-' }} → {{ $arrival_airport ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Maskapai:</strong></td>
                                                <td>{{ $airline ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Nomor & Kelas:</strong></td>
                                                <td>{{ $flight_number ?? '-' }} - {{ $flight_class ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="text-end">
                                        Rp{{ number_format($ticket_price_depart_int, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">4</th>
                                    <td class="fw-semibold">Tiket Pulang</td>
                                    <td>
                                        <table class="table table-borderless mb-0" style="font-size: 0.9rem;">
                                            <tr>
                                                <td style="width: 40%;"><strong>Rute:</strong></td>
                                                <td>{{ $return_departure_airport ?? '-' }} → {{ $return_arrival_airport ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Maskapai:</strong></td>
                                                <td>{{ $return_airline ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Nomor & Kelas:</strong></td>
                                                <td>{{ $return_flight_number ?? '-' }} - {{ $return_flight_class ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="text-end">
                                        Rp{{ number_format($ticket_price_return_int, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @if(!empty($addons) && is_array($addons))
                                    <tr>
                                        <th class="text-center">5</th>
                                        <td class="fw-semibold">Add-ons</td>
                                        <td>
                                            {{ implode(', ', array_map('ucwords', $addons)) }}
                                        </td>
                                        <td class="text-end">
                                            Rp{{ number_format($addons_price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <th colspan="3" class="text-end fw-bold fs-5">Total Keseluruhan</th>
                                    <th class="text-end fw-bold fs-5">
                                        Rp{{ number_format($total, 0, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>


                <!-- Footer -->
                <div class="card-body">
                    <div class="bg-body p-2 rounded-2 mt-4">
                        <p class="mb-0"><strong>Catatan:</strong> SPPD ini digunakan sebagai bukti perjalanan dinas resmi.
                            Harap simpan dokumen ini untuk keperluan administrasi.</p>
                    </div>

                    <div class="mt-4">
                        <div class="d-inline-block">
                            <img src="/images/png/signature.png" alt="signature" height="32">
                            <h5 class="mb-0 mt-2">Pejabat Berwenang</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol -->
            <div class="d-print-none mb-5">
                <div class="d-flex justify-content-center gap-2">
                    <a href="javascript:window.print()" class="btn btn-primary"><i class="ti ti-printer me-1"></i> Print</a>
                    <a href="javascript:void(0);" class="btn btn-info"><i class="ti ti-download me-1"></i> Download</a>
                </div>
            </div>
        </div>
    </div>
@endsection
