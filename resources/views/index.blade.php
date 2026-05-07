@extends('layouts.vertical', ['title' => 'Dashboard'])

@php
    $summary = $dashboardSummary ?? [];
    $charts = $dashboardCharts ?? [];
    $latestSppds = $latestSppds ?? [];
    $topProvinces = $topProvinces ?? [];
    $dashboardMeta = $dashboardMeta ?? [];
    $dashboardFilters = $dashboardFilters ?? ['start_date' => '', 'end_date' => ''];

    $monthlySppd = $charts['monthly_sppd'] ?? ['labels' => [], 'values' => []];
    $monthlySpending = $charts['monthly_spending'] ?? ['labels' => [], 'values' => []];
    $statusBreakdown = $charts['status_breakdown'] ?? ['Approved' => 0, 'Pending' => 0, 'Rejected' => 0];
    $monthOverMonth = $summary['month_over_month'] ?? [];

    $summaryCards = [
        [
            'label' => 'Total SPPD Bulan Ini',
            'value' => $summary['total_sppd_this_month'] ?? 0,
            'icon' => 'mdi:file-document-multiple-outline',
            'bg' => 'primary',
            'change' => $monthOverMonth['total_sppd_this_month'] ?? 0,
        ],
        [
            'label' => 'SPPD Sedang Diproses',
            'value' => $summary['in_progress'] ?? 0,
            'icon' => 'mdi:progress-clock',
            'bg' => 'warning',
            'change' => null,
        ],
        [
            'label' => 'SPPD Disetujui',
            'value' => $summary['approved'] ?? 0,
            'icon' => 'mdi:check-circle-outline',
            'bg' => 'success',
            'change' => $monthOverMonth['approved'] ?? 0,
        ],
        [
            'label' => 'SPPD Ditolak',
            'value' => $summary['rejected'] ?? 0,
            'icon' => 'mdi:close-circle-outline',
            'bg' => 'danger',
            'change' => $monthOverMonth['rejected'] ?? 0,
        ],
    ];

    $statusMeta = [
        'Approved' => ['class' => 'success', 'icon' => 'ti ti-circle-filled'],
        'Pending' => ['class' => 'warning', 'icon' => 'ti ti-loader'],
        'Rejected' => ['class' => 'danger', 'icon' => 'ti ti-circle-filled'],
        'Completed' => ['class' => 'info', 'icon' => 'ti ti-circle-filled'],
    ];

    $currentRangeLabel = '';
    if (!empty($dashboardFilters['start_date']) && !empty($dashboardFilters['end_date'])) {
        $currentRangeLabel = $dashboardFilters['start_date'] . ' - ' . $dashboardFilters['end_date'];
    } elseif (!empty($dashboardFilters['start_date'])) {
        $currentRangeLabel = $dashboardFilters['start_date'];
    } elseif (!empty($dashboardFilters['end_date'])) {
        $currentRangeLabel = $dashboardFilters['end_date'];
    }
@endphp

@section('css')
    @vite(['node_modules/flatpickr/dist/flatpickr.min.css'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        .dashboard-card .widget-icon {
            right: -10px;
            top: -5px;
        }

        .province-item:last-child {
            margin-bottom: 0 !important;
        }

        .dashboard-toolbar {
            margin-bottom: 1rem;
        }

        .filter-toggle-btn {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        .dashboard-page-title {
            margin-bottom: 0;
        }

        .dashboard-page-title .breadcrumb {
            justify-content: flex-end;
        }

        .dashboard-range-badge {
            border-radius: 999px;
            font-weight: 600;
            padding: 0.65rem 0.9rem;
        }

        .dashboard-filter-modal .modal-content {
            border: 0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.18);
        }

        .dashboard-filter-modal .modal-header {
            background: linear-gradient(135deg, #f8fbff 0%, #eef5ff 100%);
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
            padding: 1.25rem 1.5rem;
        }

        .dashboard-filter-modal .modal-body {
            padding: 1.5rem;
        }

        .dashboard-filter-modal .modal-footer {
            border-top: 1px solid rgba(148, 163, 184, 0.18);
            padding: 1rem 1.5rem 1.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="page-title-head d-flex align-items-start justify-content-between gap-3 dashboard-toolbar">
        <div class="dashboard-page-title flex-grow-1">
            @include('layouts.partials.page-title', ['title' => 'Dashboard'])
        </div>
        <div class="d-flex align-items-center gap-2 ms-auto">
            @if($currentRangeLabel !== '')
                <span class="badge bg-primary-subtle text-primary dashboard-range-badge">{{ $currentRangeLabel }}</span>
            @endif
            <button
                class="btn btn-light border filter-toggle-btn"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#dashboardFilterModal"
                title="Filter dashboard"
            >
                <i class="ti ti-adjustments-horizontal fs-18"></i>
            </button>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1 g-3">
        @foreach ($summaryCards as $card)
            <div class="col">
                <div class="card overflow-hidden dashboard-card">
                    <div class="card-body">
                        <h5 class="text-muted fs-13 text-uppercase">{{ $card['label'] }}</h5>
                        <div class="d-flex align-items-center gap-2 my-2 py-1 position-relative">
                            <div class="user-img fs-42 flex-shrink-0">
                                <span class="avatar-title text-bg-{{ $card['bg'] }} rounded-circle fs-22">
                                    <iconify-icon icon="{{ $card['icon'] }}"></iconify-icon>
                                </span>
                            </div>
                            <h3 class="mb-0 fw-bold">{{ number_format((float) $card['value']) }}</h3>
                            <iconify-icon icon="{{ $card['icon'] }}" class="ms-auto display-1 position-absolute opacity-25 text-muted widget-icon"></iconify-icon>
                        </div>

                        @if (!is_null($card['change']))
                            <p class="mb-0 text-muted">
                                <span class="text-{{ $card['change'] >= 0 ? 'success' : 'danger' }} me-2">
                                    <i class="ti {{ $card['change'] >= 0 ? 'ti-caret-up-filled' : 'ti-caret-down-filled' }}"></i>
                                    {{ number_format(abs((float) $card['change']), 1) }}%
                                </span>
                                <span class="text-nowrap">Dibanding bulan lalu</span>
                            </p>
                        @else
                            <p class="mb-0 text-muted">Update real-time dari status pengajuan terbaru.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="header-title mb-1">Jumlah SPPD per Bulan</h4>
                            <p class="text-muted">12 bulan terakhir</p>
                        </div>
                    </div>
                    <div dir="ltr">
                        <div id="chart-sppd-per-bulan" class="apex-charts" data-colors="#188ae2"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="header-title mb-1">Penggunaan Anggaran</h4>
                            <p class="text-muted">Total pengeluaran SPPD berstatus PAID</p>
                        </div>
                    </div>

                    <div dir="ltr">
                        <div id="chart-pengeluaran" class="apex-charts" data-colors="#31ce77"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-8">
            <div class="card card-h-100">
                <div class="card-header d-flex flex-wrap align-items-center gap-2">
                    <h4 class="header-title me-auto">Daftar SPPD Terbaru</h4>
                    <span class="badge bg-light text-dark">{{ count($latestSppds) }} data</span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom table-centered table-sm table-nowrap table-hover mb-0">
                            <tbody>
                                @forelse ($latestSppds as $item)
                                    @php $meta = $statusMeta[$item['status']] ?? ['class' => 'secondary', 'icon' => 'ti ti-circle-filled']; @endphp
                                    <tr>
                                        <td>
                                            <span class="text-muted fs-12">Nomor SPPD</span>
                                            <h5 class="fs-14 mt-1 fw-normal mb-0">{{ $item['nomor_sppd'] }}</h5>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">Nama</span>
                                            <h5 class="fs-14 mt-1 fw-normal mb-0">{{ $item['employee_name'] ?? '-' }}</h5>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">Tanggal Pengajuan</span>
                                            <h5 class="fs-14 mt-1 fw-normal mb-0">{{ $item['submission_date'] ?? '-' }}</h5>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">Tujuan Perjalanan</span>
                                            <h5 class="fs-14 mt-1 fw-normal mb-0">{{ $item['purpose'] ?? '-' }}</h5>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">Status</span>
                                            <h5 class="fs-14 mt-1 fw-normal mb-0 text-{{ $meta['class'] }}">
                                                <i class="{{ $meta['icon'] }} fs-12"></i> {{ $item['status'] }}
                                            </h5>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">Pengeluaran (Rp)</span>
                                            <h5 class="fs-14 mt-1 fw-normal mb-0">Rp {{ number_format((float) ($item['amount'] ?? 0), 0, ',', '.') }}</h5>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data SPPD untuk ditampilkan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="header-title mb-1">Status SPPD</h4>
                            <p class="text-muted">Approved, Pending, Rejected</p>
                        </div>
                    </div>

                    <div dir="ltr">
                        <div id="chart-status-sppd" class="apex-charts" data-colors="#6ac75a,#fbc02d,#ef5350"></div>
                    </div>

                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Completed</span>
                            <strong>{{ number_format((float) ($summary['completed'] ?? 0)) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total User Terlibat</span>
                            <strong>{{ number_format((float) ($summary['total_users'] ?? 0)) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center gap-2 border-bottom border-dashed">
                    <h4 class="header-title me-auto">Top 5 Provinces Visited</h4>
                    <span class="badge bg-light text-dark">Berdasarkan data SPPD</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-7">
                            <div id="map-provinces-line" style="height: 320px;"></div>
                        </div>
                        <div class="col-xl-5" dir="ltr">
                            <div class="p-3">
                                <div id="province-list">
                                    @forelse ($topProvinces as $province)
                                        <div class="d-flex justify-content-between align-items-center mb-3 province-item">
                                            <p class="mb-1 d-flex align-items-center">
                                                <img src="/images/flags/id.svg" alt="{{ $province['name'] }}" class="me-2 rounded-circle" height="24" style="width:24px; object-fit:cover;">
                                                <span>{{ $province['name'] }}</span>
                                            </p>
                                            <div>
                                                <h5 class="fw-semibold mb-0">{{ number_format((float) $province['total']) }} trip</h5>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted mb-0">Belum ada data provinsi yang bisa ditampilkan.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade dashboard-filter-modal" id="dashboardFilterModal" tabindex="-1" aria-labelledby="dashboardFilterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-1" id="dashboardFilterModalLabel">Filter Dashboard</h5>
                        <p class="text-muted mb-0 small">Gunakan rentang tanggal untuk menyaring seluruh metrik dashboard.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="GET" action="{{ url()->current() }}" id="dashboard-filter-form">
                        <input type="hidden" id="dashboard-start-date" name="start_date" value="{{ $dashboardFilters['start_date'] ?? '' }}">
                        <input type="hidden" id="dashboard-end-date" name="end_date" value="{{ $dashboardFilters['end_date'] ?? '' }}">
                        <div class="mb-3">
                            <label for="dashboard-date-range" class="form-label">Date Range</label>
                            <input
                                type="text"
                                id="dashboard-date-range"
                                class="form-control"
                                placeholder="Pilih rentang tanggal"
                                value="{{ $currentRangeLabel }}"
                            >
                        </div>
                        <div class="d-flex flex-wrap gap-3 text-muted small">
                            <span>Scope: <strong>{{ $dashboardMeta['scope'] ?? '-' }}</strong></span>
                            <span>Dibuat: <strong>{{ $dashboardMeta['generated_at'] ?? '-' }}</strong></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <a href="{{ url()->current() }}" class="btn btn-light border">Reset</a>
                    <button type="submit" form="dashboard-filter-form" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const rangeInput = document.getElementById('dashboard-date-range');
            const startDateInput = document.getElementById('dashboard-start-date');
            const endDateInput = document.getElementById('dashboard-end-date');

            if (rangeInput && typeof flatpickr !== 'undefined') {
                flatpickr(rangeInput, {
                    mode: 'range',
                    dateFormat: 'Y-m-d',
                    defaultDate: [
                        startDateInput && startDateInput.value ? startDateInput.value : null,
                        endDateInput && endDateInput.value ? endDateInput.value : null
                    ].filter(Boolean),
                    onClose: function(selectedDates) {
                        startDateInput.value = selectedDates[0]
                            ? flatpickr.formatDate(selectedDates[0], 'Y-m-d')
                            : '';
                        endDateInput.value = selectedDates[1]
                            ? flatpickr.formatDate(selectedDates[1], 'Y-m-d')
                            : '';
                    }
                });
            }

            const monthlySppd = @json($monthlySppd);
            const monthlySpending = @json($monthlySpending);
            const statusBreakdown = @json($statusBreakdown);
            const topProvinces = @json($topProvinces);

            const sppdChartColors = document.querySelector('#chart-sppd-per-bulan').getAttribute('data-colors').split(',');
            const spendingChartColors = document.querySelector('#chart-pengeluaran').getAttribute('data-colors').split(',');
            const statusChartColors = document.querySelector('#chart-status-sppd').getAttribute('data-colors').split(',');

            new ApexCharts(document.querySelector("#chart-sppd-per-bulan"), {
                chart: { type: 'bar', height: 220, toolbar: { show: false } },
                colors: sppdChartColors,
                series: [{ name: 'SPPD', data: monthlySppd.values || [] }],
                xaxis: { categories: monthlySppd.labels || [] },
                plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } },
                dataLabels: { enabled: false }
            }).render();

            new ApexCharts(document.querySelector("#chart-pengeluaran"), {
                chart: { type: 'area', height: 220, toolbar: { show: false } },
                colors: spendingChartColors,
                series: [{ name: 'Pengeluaran', data: monthlySpending.values || [] }],
                xaxis: { categories: monthlySpending.labels || [] },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return 'Rp ' + Number(val || 0).toLocaleString('id-ID');
                        }
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth' },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return 'Rp ' + Number(val || 0).toLocaleString('id-ID');
                        }
                    }
                }
            }).render();

            new ApexCharts(document.querySelector("#chart-status-sppd"), {
                chart: { type: 'donut', height: 300 },
                colors: statusChartColors,
                series: [
                    Number(statusBreakdown.Approved || 0),
                    Number(statusBreakdown.Pending || 0),
                    Number(statusBreakdown.Rejected || 0)
                ],
                labels: ['Approved', 'Pending', 'Rejected'],
                legend: { position: 'bottom' },
                dataLabels: {
                    formatter: function (_, opts) {
                        return opts.w.config.series[opts.seriesIndex];
                    }
                }
            }).render();

            const provinceCoordinates = {
                'DKI JAKARTA': [-6.2088, 106.8456],
                'JAWA BARAT': [-6.9039, 107.6186],
                'JAWA TENGAH': [-7.1500, 110.1403],
                'JAWA TIMUR': [-7.2504, 112.7688],
                'BALI': [-8.4095, 115.1889],
                'BANTEN': [-6.1201, 106.1503],
                'DI YOGYAKARTA': [-7.7972, 110.3688],
            };

            const map = L.map('map-provinces-line').setView([-2.5, 118], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 7,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            topProvinces.forEach(function (province) {
                const key = String(province.name || '').toUpperCase();
                const coords = provinceCoordinates[key];
                if (!coords) return;

                L.marker(coords).addTo(map).bindPopup(
                    `<b>${province.name}</b><br/>${province.total} perjalanan`
                );
            });
        });
    </script>
@endsection
