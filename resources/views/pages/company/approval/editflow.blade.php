@extends('layouts.vertical', ['title' => 'Edit Step Approval'])

@section('content')
@include('layouts.partials.page-title', [
    'title' => 'Approval Flow',
    'subtitle' => 'Edit Step Approval'
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
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="bg-primary p-4 text-white d-flex align-items-center">
                        <h4 class="mb-0">Edit Flow Approval</h4>
                    </div>
                    
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('flow.update') }}">
                            @csrf
                            @method('PUT')
                            <!-- Urutan Step -->
                            <div class="mb-3">
                                <label class="form-label">Nama Flow</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name', $flow['name']) }}" required>
                                    <input type="hidden" name="id" value="{{$flow['id']}}">
                            </div>

                            <!-- Division -->
                            <div class="mb-3">
                                <label class="form-label">Perusahaan</label>
                                <select name="company_id" class="form-select select2">
                                    <option value="">-- Pilih Perusahaan --</option>
                                    @foreach($companies as $d)
                                        <option value="{{ $d['id'] }}" 
                                            {{ $flow['company_id'] == $d['id'] ? 'selected' : '' }}>
                                            {{ $d['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Position Pemohon -->
                            <div class="mb-3">
                                <label class="form-label">Posisi Pemohon</label>
                                <select name="requester_position_id" class="form-select select2">
                                    <option value="">-- Pilih Posisi --</option>
                                    @foreach($position as $p)
                                        <option value="{{ $p['id'] }}" 
                                            {{ $flow['requester_position_id'] == $p['id'] ? 'selected' : '' }}>
                                            {{ $p['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Approval Type</label>
                                <select class="form-select" name="approval_type">
                                    <option value="hirarki" {{ $flow['approval_type'] == 'hirarki' ? 'selected' : '' }}>Hirarki</option>
                                    <option value="nominal" {{ $flow['approval_type'] == 'nominal' ? 'selected' : '' }}>Nominal</option>
                                </select>
                            </div>

                            <!-- Final Step -->
                            <div class="mb-3">
                                <label class="form-label">Aktif Step</label>
                                <select class="form-select" name="is_active">
                                    <option value="0" {{ $flow['is_active'] == 0 ? 'selected' : '' }}>Tidak</option>
                                    <option value="1" {{ $flow['is_active'] == 1 ? 'selected' : '' }}>Ya</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('flow.single', $flow['id']) }}" class="btn btn-light">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
