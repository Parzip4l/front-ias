@extends('layouts.vertical', ['title' => 'Edit Data Perusahaan'])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
@endsection

@section('content')
  @include('layouts.partials.page-title', [
      'title' => 'Perusahaan',
      'subtitle' => 'Edit Perusahaan'
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
            <div class="card shadow-sm rounded-3">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Data Perusahaan</h5>
                    <a href="{{ route('company.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('company.update', $company['id']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <!-- Nama Perusahaan -->
                            <div class="col-md-6">
                                <label class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ $company['name'] ?? '' }}" maxlength="50" required>
                                    <input type="hidden" value="{{$company['id']}}" name="id">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Customer ID -->
                            <div class="col-md-6">
                                <label class="form-label">Customer ID <span class="text-danger">*</span></label>
                                <input type="text" name="customer_id" class="form-control @error('customer_id') is-invalid @enderror"
                                    value="{{ $company['customer_id'] ?? '' }}" required>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ $company['email'] ?? '' }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Telepon -->
                            <div class="col-md-6">
                                <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ $company['phone'] ?? '' }}" maxlength="20" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="col-md-12">
                                <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror" required>{{ $company['address'] ?? '' }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Zipcode -->
                            <div class="col-md-4">
                                <label class="form-label">Kode Pos</label>
                                <input type="text" name="zipcode" class="form-control @error('zipcode') is-invalid @enderror"
                                    value="{{ $company['zipcode'] ?? '' }}" maxlength="10">
                                @error('zipcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Jenis Perusahaan -->
                            <div class="col-md-8">
                                <label class="form-label">Jenis Perusahaan <span class="text-danger">*</span></label>
                                <select name="company_type_id" class="form-select select2 @error('company_type_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type['id'] }}" {{ ($company['company_type_id'] ?? '') == $type['id'] ? 'selected' : '' }}>
                                            {{ $type['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status PKP -->
                            <div class="col-md-4">
                                <label class="form-label">Status PKP <span class="text-danger">*</span></label>
                                <select name="is_pkp" class="form-select @error('is_pkp') is-invalid @enderror" required>
                                    <option value="1" {{ ($company['is_pkp'] ?? '') == '1' ? 'selected' : '' }}>Ya</option>
                                    <option value="0" {{ ($company['is_pkp'] ?? '') == '0' ? 'selected' : '' }}>Tidak</option>
                                </select>
                                @error('is_pkp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NPWP -->
                            <div class="col-md-8">
                                <label class="form-label">Nomor NPWP</label>
                                <input type="text" name="npwp_number" class="form-control @error('npwp_number') is-invalid @enderror"
                                    value="{{ $company['npwp_number'] ?? '' }}" maxlength="25">
                                @error('npwp_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- File Upload -->
                            <div class="col-md-6">
                                <label class="form-label">File NPWP</label>
                                <input type="file" name="npwp_file" class="form-control">
                                @if(!empty($company['npwp_file']))
                                    <small class="text-muted">File lama: <a href="{{ config('app.backend_url') . '/storage/' . $company['npwp_file'] }}" target="_blank">Lihat</a></small>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">File SPPKP</label>
                                <input type="file" name="sppkp_file" class="form-control">
                                @if(!empty($company['sppkp_file']))
                                    <small class="text-muted">File lama: <a href="{{ config('app.backend_url') . '/storage/' . $company['sppkp_file'] }}" target="_blank">Lihat</a></small>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">File SKT</label>
                                <input type="file" name="skt_file" class="form-control">
                                @if(!empty($company['skt_file']))
                                    <small class="text-muted">File lama: <a href="{{ config('app.backend_url') . '/storage/' . $company['skt_file'] }}" target="_blank">Lihat</a></small>
                                @endif
                            </div>

                            <!-- Aktif / Tidak -->
                            <div class="col-md-6">
                                <label class="form-label">Status Aktif</label>
                                <select name="is_active" class="form-select">
                                    <option value="1" {{ ($company['is_active'] ?? '') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ ($company['is_active'] ?? '') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>


                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('company.index') }}" class="btn btn-light">Batal</a>
                            <button type="submit" class="btn btn-primary">Update</button>
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
                placeholder: "Pilih...",
                width: '100%'
            });
        });
    </script>
@endsection
