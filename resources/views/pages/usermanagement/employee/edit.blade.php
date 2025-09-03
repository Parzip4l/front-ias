@extends('layouts.vertical', ['title' => 'Form Edit Data Karyawan'])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
  @include('layouts.partials.page-title', [
      'subtitle' => 'Karyawan',
      'title' => 'Edit Data Karyawan'
  ])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between">
                    <h5 class="mb-0">Edit Data Karyawan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('employee.update', hid($employee['id'])) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Row 1 --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name', $employee['name']) }}" required>
                                        <input type="hidden" name="id" value="{{ hid($employee['id']) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Perusahaan Asal</label>
                                    <select name="company_id" class="form-control select2" required>
                                        <option value="">-- Pilih Perusahaan --</option>
                                        @foreach($company as $j)
                                            <option value="{{ $j['id'] }}" {{ $employee['company_id'] == $j['id'] ? 'selected' : '' }}>
                                                {{ $j['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">ID Karyawan</label>
                                    <input type="text" class="form-control" name="employee_number"
                                        value="{{ old('employee_number', $employee['employee_number']) }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Row 2 --}}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Divisi</label>
                                    <select name="division_id" class="form-control select2" required>
                                        <option value="">-- Pilih Divisi --</option>
                                        @foreach($divisi as $d)
                                            <option value="{{ $d['id'] }}" {{ $employee['division_id'] == $d['id'] ? 'selected' : '' }}>
                                                {{ $d['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Posisi</label>
                                    <select name="position_id" class="form-control select2" required>
                                        <option value="">-- Pilih Posisi --</option>
                                        @foreach($posisi as $p)
                                            <option value="{{ $p['id'] }}" {{ $employee['position_id'] == $p['id'] ? 'selected' : '' }}>
                                                {{ $p['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Status Karyawan</label>
                                    <select name="employment_status" class="form-control select2" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="permanent" {{ $employee['employment_status'] == 'permanent' ? 'selected' : '' }}>Karyawan Tetap</option>
                                        <option value="contract" {{ $employee['employment_status'] == 'contract' ? 'selected' : '' }}>Karyawan Kontrak</option>
                                        <option value="intern" {{ $employee['employment_status'] == 'intern' ? 'selected' : '' }}>Magang</option>
                                        <option value="probation" {{ $employee['employment_status'] == 'probation' ? 'selected' : '' }}>Probation</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Level</label>
                                    <select name="grade_level" class="form-control select2">
                                        <option value="">-- Pilih Grade --</option>
                                        @foreach(['A','B','C','D','E','F'] as $g)
                                            <option value="{{ $g }}" {{ $employee['grade_level'] == $g ? 'selected' : '' }}>{{ $g }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Row 3 - Data Tambahan --}}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="gender" class="form-control select2">
                                        <option value="">-- Pilih Gender --</option>
                                        <option value="male" {{ $employee['gender'] == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="female" {{ $employee['gender'] == 'female' ? 'selected' : '' }}>Perempuan</option>
                                        <option value="other" {{ $employee['gender'] == 'other' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="date_of_birth" class="form-control"
                                           value="{{ old('date_of_birth', $employee['date_of_birth']) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" name="place_of_birth" class="form-control"
                                           value="{{ old('place_of_birth', $employee['place_of_birth']) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Status Pernikahan</label>
                                    <select name="marital_status" class="form-control select2">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="single" {{ $employee['marital_status'] == 'single' ? 'selected' : '' }}>Belum Menikah</option>
                                        <option value="married" {{ $employee['marital_status'] == 'married' ? 'selected' : '' }}>Menikah</option>
                                        <option value="divorced" {{ $employee['marital_status'] == 'divorced' ? 'selected' : '' }}>Cerai</option>
                                        <option value="widowed" {{ $employee['marital_status'] == 'widowed' ? 'selected' : '' }}>Duda/Janda</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Row 4 - ID, Kontak --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Nomor KTP</label>
                                    <input type="text" name="national_id" maxlength="16" pattern="\d*"
                                           class="form-control"
                                           value="{{ old('national_id', $employee['national_id']) }}"
                                           placeholder="16 digit angka">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">NPWP</label>
                                    <input type="text" name="tax_number" class="form-control"
                                           value="{{ old('tax_number', $employee['tax_number']) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Nomor HP</label>
                                    <input type="text" name="phone_number" class="form-control"
                                           value="{{ old('phone_number', $employee['phone_number']) }}">
                                </div>
                            </div>
                        </div>

                        {{-- Row 5 - Alamat & Kontak Darurat --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="address" class="form-control" rows="2">{{ old('address', $employee['address']) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kontak Darurat</label>
                                    <input type="text" name="kontak_darurat" class="form-control"
                                           value="{{ old('kontak_darurat', $employee['kontak_darurat']) }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('employee.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Update Data</button>
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
        $(document).ready(function() {
            $('.select2').select2({ width: '100%' });

            // validasi KTP di frontend (hanya angka, max 16 digit)
            $('input[name="national_id"]').on('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0,16);
            });
        });
    </script>
@endsection
