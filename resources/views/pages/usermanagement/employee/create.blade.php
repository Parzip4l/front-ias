@extends('layouts.vertical', ['title' => 'Form Tambah Data Karyawan'])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
@endsection

@section('content')
  @include('layouts.partials.page-title', [
      'subtitle' => 'Karyawan',
      'title' => 'Tambah Data Karyawan'
  ])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between">
                    <h5 class="mb-0">Tambah Data Karyawan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('employee.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="position_id" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="position_id" class="form-label">Perusahaan Asal</label>
                                    <select name="company_id" id="" class="form-control select2" required>
                                        <option value="">-- Pilih Perusahaan --</option>
                                        @foreach($company as $data)
                                            <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="position_id" class="form-label">ID Karyawan</label>
                                    <input type="text" class="form-control" name="employee_number" placeholder="eg; EMP-08210" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="position_id" class="form-label">Divisi</label>
                                    <select name="position_id" id="" class="form-control select2" required>
                                        <option value="">-- Pilih Divisi --</option>
                                        @foreach($divisi as $data)
                                            <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="position_id" class="form-label">Posisi</label>
                                    <select name="division_id" id="" class="form-control select2" required>
                                        <option value="">-- Pilih Posisi --</option>
                                        @foreach($posisi as $data)
                                            <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="position_id" class="form-label">Status Karyawan</label>
                                    <select name="employment_status" id="" class="form-control select2" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="permanent">Karyawan Tetap</option>
                                        <option value="contract">Karyawan Kontrak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="position_id" class="form-label">Level</label>
                                    <select name="grade_level" id="" class="form-control select2" required>
                                        <option value="">-- Pilih Grade --</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                        <option value="E">E</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('employee.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Tambah Data </button>
                        </div>
                    </form>
                </div> <!-- end card body-->
            </div> <!-- end card -->


        </div><!-- end col-->
    </div> <!-- end row-->
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: "Pilih Data"
            });
        });
    </script>
@endsection

