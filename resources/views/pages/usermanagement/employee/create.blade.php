@extends('layouts.vertical', ['title' => 'Form Tambah Data Karyawan'])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- <style>
        .form-label { font-weight: 600; }
        .card { border-radius: 1rem; }
        .form-control, .select2-container .select2-selection {
            border-radius: 0.75rem;
            padding: 0.5rem 0.75rem;
        }
    </style> -->
@endsection

@section('content')
@include('layouts.partials.page-title', [
    'subtitle' => 'Karyawan',
    'title' => 'Tambah Data Karyawan'
])

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light border-bottom">
                <h5 class="mb-0">Tambah Data Karyawan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('employee.store') }}" method="POST" id="employeeForm" novalidate>
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Perusahaan</label>
                            <select name="company_id" class="form-control select2" required>
                                <option value="">-- Pilih Perusahaan --</option>
                                @foreach($company as $data)
                                    <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ID Karyawan</label>
                            <input type="text" class="form-control" name="employee_number" placeholder="EMP-001" required>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-3">
                            <label class="form-label">Divisi</label>
                            <select name="division_id" class="form-control select2" required>
                                <option value="">-- Pilih Divisi --</option>
                                @foreach($divisi as $data)
                                    <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Posisi</label>
                            <select name="position_id" class="form-control select2" required>
                                <option value="">-- Pilih Posisi --</option>
                                @foreach($posisi as $data)
                                    <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="employment_status" class="form-control select2" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="permanent">Tetap</option>
                                <option value="contract">Kontrak</option>
                                <option value="intern">Magang</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Level</label>
                            <select name="grade_level" class="form-control select2" required>
                                <option value="">-- Pilih Grade --</option>
                                @foreach(['A','B','C','D','E','F'] as $g)
                                    <option value="{{ $g }}">{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Extra Biodata --}}
                    <div class="row g-3 mt-2">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" name="join_date">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Keluar</label>
                            <input type="date" class="form-control" name="end_date">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="gender" class="form-control select2">
                                <option value="">-- Pilih Gender --</option>
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="date_of_birth">
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" name="place_of_birth" placeholder="Kota/Kabupaten">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status Perkawinan</label>
                            <select name="marital_status" class="form-control select2">
                                <option value="">-- Pilih Status --</option>
                                <option value="single">Belum Menikah</option>
                                <option value="married">Menikah</option>
                                <option value="divorced">Cerai</option>
                                <option value="widowed">Duda/Janda</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nomor KTP</label>
                            <input type="text" class="form-control" name="national_id" 
                                   placeholder="16 digit angka" 
                                   pattern="\d{16}" maxlength="16" minlength="16" 
                                   title="Nomor KTP harus 16 digit angka" required>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label class="form-label">Nomor NPWP</label>
                            <input type="text" class="form-control" name="tax_number" 
                                   placeholder="Hanya angka" pattern="\d+" 
                                   title="NPWP hanya boleh angka">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" name="phone_number" 
                                   placeholder="08xxxxxxx" pattern="\d+" 
                                   title="Nomor telepon hanya boleh angka">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control" name="address" rows="1"></textarea>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Kontak Darurat</label>
                            <input type="text" class="form-control" name="kontak_darurat" placeholder="Nama & Nomor Kontak">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('employee.index') }}" class="btn btn-light border">Kembali</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function() {
        $('.select2').select2({ width: '100%', placeholder: "Pilih Data" });

        // Validasi realtime untuk input angka (KTP, Telepon, NPWP)
        $('input[name="national_id"], input[name="phone_number"], input[name="tax_number"]').on('input', function() {
            this.value = this.value.replace(/\D/g, ''); // hapus semua huruf
        });

        // Cek panjang KTP
        $('#employeeForm').on('submit', function(e) {
            let ktp = $('input[name="national_id"]').val();
            if (ktp.length !== 16) {
                alert('Nomor KTP harus 16 digit!');
                e.preventDefault();
            }
        });
    });
</script>
@endsection
