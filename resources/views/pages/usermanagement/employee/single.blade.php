@extends('layouts.vertical', ['title' => 'Profil Karyawan'])

@section('content')
    @include('layouts.partials.page-title', [
        'subtitle' => 'Karyawan',
        'title' => 'Profil Karyawan'
    ])

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    {{-- Header --}}
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($employee['name']) }}&size=100&background=0D8ABC&color=fff"
                                class="rounded-circle shadow-sm" alt="avatar">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1">{{ $employee['name'] }}</h4>
                            <p class="text-muted mb-0">{{ $employee['employee_number'] }} • {{ ucfirst($employee['employment_status']) }}</p>
                        </div>
                        <div>
                            <a href="{{ route('employee.edit', $employee['id']) }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-pencil me-1"></i>Edit Data
                            </a>
                        </div>
                    </div>

                    <hr>

                    {{-- Informasi Utama --}}
                    <h5 class="mb-3">Informasi Utama</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Perusahaan</p>
                            <h6>{{ $employee['company']['name'] ?? '-' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Divisi</p>
                            <h6>{{ $employee['division']['name'] ?? '-' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Posisi</p>
                            <h6>{{ $employee['position']['name'] ?? '-' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Grade</p>
                            <h6>{{ $employee['grade_level'] ?? '-' }}</h6>
                        </div>
                    </div>

                    <hr>

                    {{-- Data Pribadi --}}
                    <h5 class="mb-3">Data Pribadi</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Jenis Kelamin</p>
                            <h6>{{ ucfirst($employee['gender']) ?? '-' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Tanggal Lahir</p>
                            <h6>{{ $employee['date_of_birth'] ? \Carbon\Carbon::parse($employee['date_of_birth'])->format('d M Y') : '-' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Tempat Lahir</p>
                            <h6>{{ $employee['place_of_birth'] ?? '-' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Status Pernikahan</p>
                            <h6>{{ ucfirst($employee['marital_status']) ?? '-' }}</h6>
                        </div>
                    </div>

                    <hr>

                    {{-- Identitas & Kontak --}}
                    <h5 class="mb-3">Identitas & Kontak</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Nomor KTP</p>
                            <h6>{{ $employee['national_id'] ?? '-' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">NPWP</p>
                            <h6>{{ $employee['tax_number'] ?? '-' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Nomor HP</p>
                            <h6>{{ $employee['phone_number'] ?? '-' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Kontak Darurat</p>
                            <h6>{{ $employee['kontak_darurat'] ?? '-' }}</h6>
                        </div>
                        <div class="col-12">
                            <p class="mb-1 text-muted">Alamat</p>
                            <h6>{{ $employee['address'] ?? '-' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
