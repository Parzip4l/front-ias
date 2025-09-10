    @extends('layouts.vertical', ['title' => 'Finance Report'])

    @section('css')
        @vite(['node_modules/jsvectormap/dist/jsvectormap.min.css'])
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <style>
        .hover-shadow:hover {
            background-color: #f8f9fa;
            box-shadow: 0 4px 12px rgb(0 0 0 / 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        </style>
    @endsection

    @section('content')
        @include('layouts.partials.page-title', ['title' => 'Finance Report'])
        <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1 g-3">
            <!-- Total SPPD Bulan Ini -->
            <div class="col">
                <div class="card overflow-hidden">
                <div class="card-body">
                    <h5 class="text-muted fs-13 text-uppercase" title="Total SPPD Bulan Ini">Total Pengeluaran Perjalanan</h5>
                    <div class="d-flex align-items-center gap-2 my-2 py-1 position-relative">
                    <div class="user-img fs-42 flex-shrink-0">
                        <span class="avatar-title text-bg-primary rounded-circle fs-22">
                        <iconify-icon icon="mdi:cash-multiple"></iconify-icon>
                        </span>
                    </div>
                    <h3 class="mb-0 fw-bold">Rp {{ number_format($summary['total_pengeluaran'] ?? 0, 0, ',', '.') }}</h3>
                    <iconify-icon icon="mdi:cash-multiple" class="ms-auto display-1 position-absolute opacity-25 text-muted widget-icon"></iconify-icon>
                    </div>
                    <p class="mb-0 text-muted">
                    <span class="text-success me-2"><i class="ti ti-caret-up-filled"></i> 5.2%</span>
                    <span class="text-nowrap">Dari bulan lalu</span>
                    </p>
                </div>
                </div>
            </div>

            <!-- SPPD Sedang Diproses -->
            <div class="col">
                <div class="card overflow-hidden">
                <div class="card-body">
                    <h5 class="text-muted fs-13 text-uppercase" title="SPPD Sedang Diproses">Outstanding</h5>
                    <div class="d-flex align-items-center gap-2 my-2 py-1 position-relative">
                    <div class="user-img fs-42 flex-shrink-0">
                        <span class="avatar-title text-bg-warning rounded-circle fs-22">
                        <iconify-icon icon="mdi:progress-clock"></iconify-icon>
                        </span>
                    </div>
                    <h3 class="mb-0 fw-bold">Rp {{ number_format($summary['total_outstanding'] ?? 0, 0, ',', '.') }}</h3>
                    <iconify-icon icon="mdi:progress-clock" class="ms-auto display-1 position-absolute opacity-25 text-muted widget-icon"></iconify-icon>
                    </div>
                    <p class="mb-0 text-muted">
                    <span class="text-warning me-2"><i class="ti ti-clock"></i> Dalam Proses</span>
                    </p>
                </div>
                </div>
            </div>

            <!-- SPPD Disetujui -->
            <div class="col">
                <div class="card overflow-hidden">
                <div class="card-body">
                    <h5 class="text-muted fs-13 text-uppercase" title="SPPD Disetujui">Digital Payment</h5>
                    <div class="d-flex align-items-center gap-2 my-2 py-1 position-relative">
                    <div class="user-img fs-42 flex-shrink-0">
                        <span class="avatar-title text-bg-success rounded-circle fs-22">
                        <iconify-icon icon="mdi:credit-card-outline"></iconify-icon>
                        </span>
                    </div>
                    <h3 class="mb-0 fw-bold">Rp {{ number_format($summary['total_digital'] ?? 0, 0, ',', '.') }}</h3>
                    <iconify-icon icon="mdi:credit-card-outline" class="ms-auto display-1 position-absolute opacity-25 text-muted widget-icon"></iconify-icon>
                    </div>
                    <p class="mb-0 text-muted">
                    <span class="text-success me-2"><i class="ti ti-caret-up-filled"></i> 12.5%</span>
                    <span class="text-nowrap">Sejak bulan lalu</span>
                    </p>
                </div>
                </div>
            </div>

            <!-- SPPD Ditolak -->
            <div class="col">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <h5 class="text-muted fs-13 text-uppercase" title="SPPD Ditolak">Invoicing</h5>
                        <div class="d-flex align-items-center gap-2 my-2 py-1 position-relative">
                        <div class="user-img fs-42 flex-shrink-0">
                            <span class="avatar-title text-bg-danger rounded-circle fs-22">
                            <iconify-icon icon="mdi:invoice-text"></iconify-icon>
                            </span>
                        </div>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($summary['total_reimbursement'] ?? 0, 0, ',', '.') }}</h3>
                        <iconify-icon icon="mdi:invoice-text" class="ms-auto display-1 position-absolute opacity-25 text-muted widget-icon"></iconify-icon>
                        </div>
                        <p class="mb-0 text-muted">
                        <span class="text-danger me-2"><i class="ti ti-caret-down-filled"></i> 3.8%</span>
                        <span class="text-nowrap">Sejak bulan lalu</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Grafik Jumlah SPPD per Bulan -->
            <div class="col-xl-12">
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">Tren Pengeluaran per Bulan</h5>
                    </div>
                    <div class="card-body">
                        <div id="trendChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-12">
                <div class="card card-h-100">
                    <div class="card-header d-flex flex-wrap align-items-center gap-2">
                        <h4 class="header-title me-auto">Daftar SPPD Terbaru</h4>

                        <div class="d-flex gap-2 justify-content-end text-end">
                            <a href="javascript:void(0);" class="btn btn-sm btn-light">Import <i
                                    class="ti ti-download ms-1"></i></a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-primary">Export <i
                                    class="ti ti-file-export ms-1"></i></a>
                        </div>
                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-3">No</th>
                                        <th>Nomor SPPD</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Jenis</th>
                                        <th>Tanggal</th>
                                        <th>Invoice</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($payments as $payment)
                                    <tr>
                                        <td class="px-3">{{ $loop->iteration }}</td>
                                        <td>{{ $payment['sppd']['nomor_sppd'] ?? '-' }}</td>
                                        <td>{{ $payment['sppd']['user']['name'] ?? '-' }}</td>
                                        <td>{{ $payment['payer_email'] ?? '-' }}</td>
                                        <td>Rp {{ number_format($payment['amount'], 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $payment['status'] === 'PAID' ? 'success' : 'warning' }}">
                                                {{ $payment['status'] }}
                                            </span>
                                        </td>
                                        <td>{{ ucfirst($payment['payment_type'] ?? '-') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment['created_at'])->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ $payment['invoice_url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                Lihat Invoice
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">Tidak ada data pembayaran</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div> <!-- end table-responsive-->
                    </div> <!-- end card-body-->

                    
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>

    
        </div> <!-- end card -->
    </div> <!-- end col -->
    </div> <!-- end row -->

    @endsection

    @section('scripts')
        @vite(['resources/js/pages/dashboard.js'])
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        // Ambil data dari backend
        const months = {!! json_encode(array_column($trends, 'month')) !!};
        const digital = {!! json_encode(array_column($trends, 'digital')) !!};
        const invoicing = {!! json_encode(array_column($trends, 'invoicing')) !!};

        const options = {
            chart: {
                type: 'line',
                height: 350,
                toolbar: { show: false }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: ['#1cc88a', '#4e73df'], // digital (green), reimbursement (blue)
            series: [
                {
                    name: 'Digital Payment',
                    data: digital
                },
                {
                    name: 'Invoicing',
                    data: invoicing
                }
            ],
            xaxis: {
                categories: months,
                labels: {
                    rotate: -45
                }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val);
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val);
                    }
                }
            },
            legend: {
                position: 'top'
            }
        };

        const chart = new ApexCharts(document.querySelector("#trendChart"), options);
        chart.render();
    </script>

    @endsection
