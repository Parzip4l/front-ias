@extends('layouts.vertical', ['title' => 'Dashboard'])

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
    @include('layouts.partials.page-title', ['title' => 'Dashboard'])
    <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1 g-3">
        <!-- Total SPPD Bulan Ini -->
        <div class="col">
            <div class="card overflow-hidden">
            <div class="card-body">
                <h5 class="text-muted fs-13 text-uppercase" title="Total SPPD Bulan Ini">Total SPPD Bulan Ini</h5>
                <div class="d-flex align-items-center gap-2 my-2 py-1 position-relative">
                <div class="user-img fs-42 flex-shrink-0">
                    <span class="avatar-title text-bg-primary rounded-circle fs-22">
                    <iconify-icon icon="mdi:file-document-multiple-outline"></iconify-icon>
                    </span>
                </div>
                <h3 class="mb-0 fw-bold">120</h3>
                <iconify-icon icon="mdi:file-document-outline" class="ms-auto display-1 position-absolute opacity-25 text-muted widget-icon"></iconify-icon>
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
                <h5 class="text-muted fs-13 text-uppercase" title="SPPD Sedang Diproses">SPPD Sedang Diproses</h5>
                <div class="d-flex align-items-center gap-2 my-2 py-1 position-relative">
                <div class="user-img fs-42 flex-shrink-0">
                    <span class="avatar-title text-bg-warning rounded-circle fs-22">
                    <iconify-icon icon="mdi:progress-clock"></iconify-icon>
                    </span>
                </div>
                <h3 class="mb-0 fw-bold">25</h3>
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
                <h5 class="text-muted fs-13 text-uppercase" title="SPPD Disetujui">SPPD Disetujui</h5>
                <div class="d-flex align-items-center gap-2 my-2 py-1 position-relative">
                <div class="user-img fs-42 flex-shrink-0">
                    <span class="avatar-title text-bg-success rounded-circle fs-22">
                    <iconify-icon icon="mdi:check-circle-outline"></iconify-icon>
                    </span>
                </div>
                <h3 class="mb-0 fw-bold">80</h3>
                <iconify-icon icon="mdi:check-circle" class="ms-auto display-1 position-absolute opacity-25 text-muted widget-icon"></iconify-icon>
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
                <h5 class="text-muted fs-13 text-uppercase" title="SPPD Ditolak">SPPD Ditolak</h5>
                <div class="d-flex align-items-center gap-2 my-2 py-1 position-relative">
                <div class="user-img fs-42 flex-shrink-0">
                    <span class="avatar-title text-bg-danger rounded-circle fs-22">
                    <iconify-icon icon="mdi:close-circle-outline"></iconify-icon>
                    </span>
                </div>
                <h3 class="mb-0 fw-bold">15</h3>
                <iconify-icon icon="mdi:close-circle" class="ms-auto display-1 position-absolute opacity-25 text-muted widget-icon"></iconify-icon>
                </div>
                <p class="mb-0 text-muted">
                <span class="text-danger me-2"><i class="ti ti-caret-down-filled"></i> 3.8%</span>
                <span class="text-nowrap">Sejak bulan lalu</span>
                </p>
            </div>
            </div>
        </div>
        </div>

        <!-- Row kedua untuk Total Pengeluaran Perjalanan -->
        <div class="row row-cols-xxl-1 row-cols-md-1 row-cols-1">
        <div class="col">
            <div class="card overflow-hidden">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="user-img fs-42 flex-shrink-0">
                <span class="avatar-title text-bg-info rounded-circle fs-22">
                    <iconify-icon icon="mdi:cash-multiple"></iconify-icon>
                </span>
                </div>
                <div>
                <h5 class="text-muted fs-13 text-uppercase mb-1" title="Total Pengeluaran Perjalanan">Total Pengeluaran Perjalanan</h5>
                <h3 class="mb-0 fw-bold">Rp 75.300.000</h3>
                <p class="mb-0 text-muted small">Data bulan ini</p>
                </div>
                <iconify-icon icon="mdi:currency-idr" class="ms-auto display-1 position-absolute opacity-25 text-muted widget-icon"></iconify-icon>
            </div>
            </div>
        </div>
        </div>


    <div class="row">
        <!-- Grafik Jumlah SPPD per Bulan -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="header-title mb-1">Jumlah SPPD per Bulan</h4>
                            <p class="text-muted">Jan - Desember 2025</p>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle drop-arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item">Lihat Laporan</a>
                                <a href="javascript:void(0);" class="dropdown-item">Export Data</a>
                            </div>
                        </div>
                    </div>

                    <div dir="ltr">
                        <div id="chart-sppd-per-bulan" class="apex-charts" data-colors="#188ae2,#6ac75a"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Penggunaan Anggaran / Pengeluaran -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="header-title mb-1">Penggunaan Anggaran</h4>
                            <p class="text-muted">Total Pengeluaran Perjalanan</p>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle drop-arrow-none card-drop" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="ti ti-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item">Lihat Laporan</a>
                                <a href="javascript:void(0);" class="dropdown-item">Export Data</a>
                            </div>
                        </div>
                    </div>

                    <div dir="ltr">
                        <div id="chart-pengeluaran" class="apex-charts" data-colors="#31ce77,#188ae2"></div>
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

                    <div class="d-flex gap-2 justify-content-end text-end">
                        <a href="javascript:void(0);" class="btn btn-sm btn-light">Import <i
                                class="ti ti-download ms-1"></i></a>
                        <a href="javascript:void(0);" class="btn btn-sm btn-primary">Export <i
                                class="ti ti-file-export ms-1"></i></a>
                    </div>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table table-custom table-centered table-sm table-nowrap table-hover mb-0">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="text-muted fs-12">Nomor SPPD</span>
                                        <h5 class="fs-14 mt-1 fw-normal">SPPD-2025-0014</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Nama </span>
                                        <h5 class="fs-14 mt-1 fw-normal">John Doe</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Tanggal Pengajuan</span>
                                        <h5 class="fs-14 mt-1 fw-normal">john.doe@example.com</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Tujuan Perjalanan</span> <br />
                                        <h5 class="fs-14 mt-1 fw-normal">Administrator</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Status</span>
                                        <h5 class="fs-14 mt-1 fw-normal"><i
                                                class="ti ti-circle-filled fs-12 text-success"></i> Active
                                        </h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Pengeluaran (Rp)</span>
                                        <h5 class="fs-14 mt-1 fw-normal">Rp 1.729.000
                                        </h5>
                                    </td>
                                    <td style="width: 30px;">
                                        <div class="dropdown">
                                            <a href="#"
                                                class="dropdown-toggle text-muted drop-arrow-none card-drop p-0"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="javascript:void(0);" class="dropdown-item">View Details</a>
                                                
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="text-muted fs-12">Nomor SPPD</span>
                                        <h5 class="fs-14 mt-1 fw-normal">SPPD-2025-0014</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Nama </span>
                                        <h5 class="fs-14 mt-1 fw-normal">John Doe</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Tanggal Pengajuan</span>
                                        <h5 class="fs-14 mt-1 fw-normal">john.doe@example.com</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Tujuan Perjalanan</span> <br />
                                        <h5 class="fs-14 mt-1 fw-normal">Administrator</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Status</span>
                                        <h5 class="fs-14 mt-1 fw-normal"><i
                                                class="ti ti-circle-filled fs-12 text-success"></i> Active
                                        </h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Pengeluaran (Rp)</span>
                                        <h5 class="fs-14 mt-1 fw-normal">Rp 1.729.000
                                        </h5>
                                    </td>
                                    <td style="width: 30px;">
                                        <div class="dropdown">
                                            <a href="#"
                                                class="dropdown-toggle text-muted drop-arrow-none card-drop p-0"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="javascript:void(0);" class="dropdown-item">View Details</a>
                                                
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="text-muted fs-12">Nomor SPPD</span>
                                        <h5 class="fs-14 mt-1 fw-normal">SPPD-2025-0014</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Nama </span>
                                        <h5 class="fs-14 mt-1 fw-normal">John Doe</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Tanggal Pengajuan</span>
                                        <h5 class="fs-14 mt-1 fw-normal">john.doe@example.com</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Tujuan Perjalanan</span> <br />
                                        <h5 class="fs-14 mt-1 fw-normal">Administrator</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Status</span>
                                        <h5 class="fs-14 mt-1 fw-normal"><i
                                                class="ti ti-circle-filled fs-12 text-success"></i> Active
                                        </h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Pengeluaran (Rp)</span>
                                        <h5 class="fs-14 mt-1 fw-normal">Rp 1.729.000
                                        </h5>
                                    </td>
                                    <td style="width: 30px;">
                                        <div class="dropdown">
                                            <a href="#"
                                                class="dropdown-toggle text-muted drop-arrow-none card-drop p-0"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="javascript:void(0);" class="dropdown-item">View Details</a>
                                                
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="text-muted fs-12">Nomor SPPD</span>
                                        <h5 class="fs-14 mt-1 fw-normal">SPPD-2025-0014</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Nama </span>
                                        <h5 class="fs-14 mt-1 fw-normal">John Doe</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Tanggal Pengajuan</span>
                                        <h5 class="fs-14 mt-1 fw-normal">john.doe@example.com</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Tujuan Perjalanan</span> <br />
                                        <h5 class="fs-14 mt-1 fw-normal">Administrator</h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Status</span>
                                        <h5 class="fs-14 mt-1 fw-normal"><i
                                                class="ti ti-circle-filled fs-12 text-success"></i> Active
                                        </h5>
                                    </td>
                                    <td>
                                        <span class="text-muted fs-12">Pengeluaran (Rp)</span>
                                        <h5 class="fs-14 mt-1 fw-normal">Rp 1.729.000
                                        </h5>
                                    </td>
                                    <td style="width: 30px;">
                                        <div class="dropdown">
                                            <a href="#"
                                                class="dropdown-toggle text-muted drop-arrow-none card-drop p-0"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="javascript:void(0);" class="dropdown-item">View Details</a>
                                                
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div> <!-- end table-responsive-->
                </div> <!-- end card-body-->

                <div class="card-footer">
                    <div class="align-items-center justify-content-between row text-center text-sm-start">
                        <div class="col-sm">
                            <div class="text-muted">
                                Showing <span class="fw-semibold">5</span> of <span class="fw-semibold">10</span>
                                Results
                            </div>
                        </div>
                        <div class="col-sm-auto mt-3 mt-sm-0">
                            <ul class="pagination pagination-boxed pagination-sm mb-0 justify-content-center">
                                <li class="page-item disabled">
                                    <a href="#" class="page-link"><i class="ti ti-chevron-left"></i></a>
                                </li>
                                <li class="page-item active">
                                    <a href="#" class="page-link">1</a>
                                </li>
                                <li class="page-item">
                                    <a href="#" class="page-link">2</a>
                                </li>
                                <li class="page-item">
                                    <a href="#" class="page-link"><i class="ti ti-chevron-right"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div> <!-- -->
                </div>
            </div> <!-- end card-->
        </div> <!-- end col-->

        <!-- Grafik Status SPPD -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="header-title mb-1">Status SPPD</h4>
                        <p class="text-muted">Approved, Pending, Rejected</p>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle drop-arrow-none card-drop" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="ti ti-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript:void(0);" class="dropdown-item">Lihat Detail</a>
                        <a href="javascript:void(0);" class="dropdown-item">Export Data</a>
                        </div>
                    </div>
                    </div>

                    <div dir="ltr">
                        <div id="chart-status-sppd" class="apex-charts" data-colors="#6ac75a,#fbc02d,#ef5350"></div>
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

        <div>
          <button type="button" class="btn btn-light btn-sm">All</button>
          <button type="button" class="btn btn-light active btn-sm">1M</button>
          <button type="button" class="btn btn-light btn-sm">6M</button>
          <button type="button" class="btn btn-light btn-sm">1Y</button>
        </div>
      </div>
      <div class="card-body">
  <div class="row">
    <div class="col-xl-7">
      <div id="map-provinces-line" style="height: 300px;"></div>
    </div>
    <div class="col-xl-5" dir="ltr">
      <div class="p-3">
        <!-- Province Data List -->
        <div id="province-list">
          <!-- Contoh item provinsi, nanti bisa di-generate dinamis juga -->
          <div class="d-flex justify-content-between align-items-center mb-3 province-item">
            <p class="mb-1 d-flex align-items-center">
              <img src="/images/flags/id.svg" alt="Jawa Barat" class="me-2 rounded-circle" height="24" style="width:24px; object-fit:cover;">
              <span>Jawa Barat</span>
            </p>
            <div><h5 class="fw-semibold mb-0">45.7k</h5></div>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3 province-item">
            <p class="mb-1 d-flex align-items-center">
              <img src="/images/flags/id.svg" alt="Jawa Timur" class="me-2 rounded-circle" height="24" style="width:24px; object-fit:cover;">
              <span>Jawa Timur</span>
            </p>
            <div><h5 class="fw-semibold mb-0">38.2k</h5></div>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3 province-item">
            <p class="mb-1 d-flex align-items-center">
              <img src="/images/flags/id.svg" alt="DKI Jakarta" class="me-2 rounded-circle" height="24" style="width:24px; object-fit:cover;">
              <span>DKI Jakarta</span>
            </p>
            <div><h5 class="fw-semibold mb-0">31.9k</h5></div>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3 province-item">
            <p class="mb-1 d-flex align-items-center">
              <img src="/images/flags/id.svg" alt="Jawa Tengah" class="me-2 rounded-circle" height="24" style="width:24px; object-fit:cover;">
              <span>Jawa Tengah</span>
            </p>
            <div><h5 class="fw-semibold mb-0">29.1k</h5></div>
          </div>

          <div class="d-flex justify-content-between align-items-center province-item">
            <p class="mb-1 d-flex align-items-center">
              <img src="/images/flags/id.svg" alt="Bali" class="me-2 rounded-circle" height="24" style="width:24px; object-fit:cover;">
              <span>Bali</span>
            </p>
            <div><h5 class="fw-semibold mb-0">23.6k</h5></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end card-body -->
    </div> <!-- end card -->
  </div> <!-- end col -->
      </div> <!-- end card-body-->
    </div> <!-- end card -->
  </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Fungsi helper dapatkan config tooltip berdasarkan theme
        function getTooltipOptions(isDarkTheme = false) {
        if (isDarkTheme) {
            return {
            theme: 'dark',
            style: {
                fontSize: '12px',
                background: 'rgba(33, 33, 33, 0.8)',
                color: '#fff',
            },
            shadow: {
                enabled: true,
                color: 'rgba(0,0,0,0.2)',
                top: 2,
                left: 2,
                blur: 4,
                opacity: 0.35
            },
            };
        } else {
            // Light theme tooltip style
            return {
            theme: 'light',
            style: {
                fontSize: '12px',
                background: '#fff',
                color: '#333',
            },
            shadow: {
                enabled: true,
                color: 'rgba(0,0,0,0.1)',
                top: 1,
                left: 1,
                blur: 3,
                opacity: 0.2
            },
            };
        }
        }

        document.addEventListener("DOMContentLoaded", function () {
        // Deteksi apakah dark theme (contoh: cek class 'dark-theme' di body)
        var isDarkTheme = document.body.classList.contains('dark-theme');

        // Chart Jumlah SPPD per Bulan (Bar Chart)
        var colorsSPPDPerBulan = document.querySelector('#chart-sppd-per-bulan').getAttribute('data-colors').split(',');
        var optionsSPPDPerBulan = {
            chart: {
            type: 'bar',
            height: 200,
            toolbar: { show: false },
            },
            colors: colorsSPPDPerBulan,
            series: [{
            name: 'SPPD',
            data: [12, 15, 18, 20, 22, 25, 28, 24, 20, 18, 15, 10]
            }],
            xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            },
            plotOptions: {
            bar: {
                borderRadius: 4,
                columnWidth: '50%',
            }
            },
            dataLabels: { enabled: false },
            tooltip: {
            ...getTooltipOptions(isDarkTheme)
            }
        };
        var chartSPPDPerBulan = new ApexCharts(document.querySelector("#chart-sppd-per-bulan"), optionsSPPDPerBulan);
        chartSPPDPerBulan.render();

        // Chart Status SPPD (Donut Chart)
        var colorsStatusSPPD = document.querySelector('#chart-status-sppd').getAttribute('data-colors').split(',');
        var optionsStatusSPPD = {
            chart: {
            type: 'donut',
            height: 300,
            },
            colors: colorsStatusSPPD,
            series: [80, 25, 15],
            labels: ['Approved', 'Pending', 'Rejected'],
            legend: {
            position: 'bottom'
            },
            dataLabels: {
            formatter: function (val, opts) {
                return val.toFixed(0) + "%";
            }
            },
            tooltip: {
            ...getTooltipOptions(isDarkTheme)
            }
        };
        var chartStatusSPPD = new ApexCharts(document.querySelector("#chart-status-sppd"), optionsStatusSPPD);
        chartStatusSPPD.render();

        // Chart Penggunaan Anggaran per Bulan (Area Chart)
        var colorsPengeluaran = document.querySelector('#chart-pengeluaran').getAttribute('data-colors').split(',');
        var optionsPengeluaran = {
            chart: {
            type: 'area',
            height: 200,
            toolbar: { show: false },
            },
            colors: colorsPengeluaran,
            series: [{
            name: 'Pengeluaran',
            // Data per bulan, contoh jan - jul
            data: [5000000, 4500000, 6000000, 5500000, 7000000, 6500000, 7200000]
            }],
            xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth' },
            tooltip: {
            ...getTooltipOptions(isDarkTheme),
            y: {
                formatter: function (val) {
                return "Rp " + val.toLocaleString('id-ID');
                }
            }
            }
        };
        var chartPengeluaran = new ApexCharts(document.querySelector("#chart-pengeluaran"), optionsPengeluaran);
        chartPengeluaran.render();
    });
    </script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi map di koordinat tengah Indonesia
    var map = L.map('map-provinces-line').setView([-2.5, 118], 5);

    // Tile layer OpenStreetMap (gratis dan open source)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 7,
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Data provinsi dengan koordinat dan info kunjungan
    var provinces = [
      {
        name: "Jawa Barat",
        coords: [-6.9147, 107.6098],
        visits: "45.7k",
        iconUrl: "maps/marker-icon.png"
      },
      {
        name: "Jawa Timur",
        coords: [-7.2504, 112.7688],
        visits: "38.2k",
        iconUrl: "maps/marker-icon.png"
      },
      {
        name: "DKI Jakarta",
        coords: [-6.2088, 106.8456],
        visits: "31.9k",
        iconUrl: "maps/marker-icon.png"
      },
      {
        name: "Jawa Tengah",
        coords: [-7.1500, 110.1403],
        visits: "29.1k",
        iconUrl: "maps/marker-icon.png"
      },
      {
        name: "Bali",
        coords: [-8.3405, 115.0920],
        visits: "23.6k",
        iconUrl: "maps/marker-icon.png"
      }
    ];

    provinces.forEach(function(prov) {
      // Custom icon dengan logo provinsi kecil
      var icon = L.icon({
        iconUrl: prov.iconUrl,
        iconSize: [13, 20], // ukuran icon
        iconAnchor: [15, 30], // titik anchor icon
        popupAnchor: [0, -30] // posisi popup
      });

      // Marker dengan icon custom
      var marker = L.marker(prov.coords, { icon: icon }).addTo(map);

      // Popup info
      marker.bindPopup(`<b>${prov.name}</b><br/>Visits: ${prov.visits}`);
    });
  });
</script>

@endsection
