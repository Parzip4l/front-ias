@extends('layouts.vertical', ['title' => 'Buat Klaim Reimbursement'])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Buat Klaim Reimbursement',
        'subtitle' => 'Reimbursement'
    ])

    <div class="row">
        <div class="col-lg-2">

        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header border-bottom border-dashed">
                    <h5 class="mb-0">Form Klaim Reimbursement</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reimbursement.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Pilih SPPD -->
                        <div class="mb-3">
                            <label for="sppd_id" class="form-label">Pilih SPPD</label>
                            <select name="sppd_id" id="sppd_id" class="form-control select2" required>
                                <option value="">-- Pilih SPPD --</option>
                                @foreach($sppds as $sppd)
                                    <option value="{{ $sppd['id'] }}">
                                        {{ $sppd['nomor_sppd'] }} | {{ \Illuminate\Support\Str::limit($sppd['keperluan'], 40) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kategori Klaim -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Pilih Kategori Klaim</label>
                            <select name="category_id" id="category_id" class="form-control select2" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($category as $category)
                                    <option value="{{ $category['id'] }}">
                                        {{ $category['name']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <!-- Judul -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Klaim</label>
                            <input type="text" name="title" id="title" class="form-control" 
                                   value="{{ old('title') }}" required>
                            @error('title')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi (Opsional)</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jumlah -->
                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah Klaim</label>
                            <input type="number" name="amount" id="amount" class="form-control" 
                                   value="{{ old('amount') }}" min="0" required>
                            @error('amount')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload Bukti -->
                        <div class="mb-3">
                            <label for="files" class="form-label">Upload Bukti (PDF/JPG/PNG)</label>
                            <input type="file" name="files[]" id="files" class="form-control" multiple>
                            <small class="text-muted">Maks 2MB per file</small>
                            @error('files.*')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('reimbursement.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan & Ajukan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#sppd_id').select2({
                placeholder: "Pilih SPPD",
                allowClear: true
            });
        });
    </script>
@endsection
