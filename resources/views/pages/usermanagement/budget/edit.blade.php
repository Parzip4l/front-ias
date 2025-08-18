@extends('layouts.vertical', ['title' => 'Edit Budget'])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
@include('layouts.partials.page-title', [
    'subtitle' => 'User Management',
    'title' => 'Edit Budget'
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
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header text-dark border-bottom border-dashed">
                <h5 class="mb-0">Form Edit Budget</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('budget.update', $budget['id']) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Jabatan / Position -->
                    <div class="mb-3">
                        <label for="position_id" class="form-label">Posisi / Jabatan</label>
                        <select name="position_id" id="position_id" class="form-select select2" required>
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($jabatan as $j)
                                <option value="{{ $j['id'] }}" {{ $budget['position']['id'] == $j['id'] ? 'selected' : '' }}>
                                    {{ $j['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" id="id" name="id" value="{{ $budget['id'] ?? 0 }}">
                    </div>

                    <!-- Kategori -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select name="category_id" id="category_id" class="form-select select2" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($category as $c)
                                <option value="{{ $c['id'] }}" {{ $budget['category']['id'] == $c['id'] ? 'selected' : '' }}>
                                    {{ $c['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type -->
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe</label>
                        <input type="text" name="type" id="type" class="form-control" value="{{ $budget['type'] }}" required>
                    </div>

                    <!-- Max Budget -->
                    <div class="mb-3">
                        <label for="max_budget_display" class="form-label">Max Budget</label>
                        <input type="text" id="max_budget_display" class="form-control rupiah-input"
                            data-target="max_budget" 
                            value="{{ number_format($budget['max_budget'] ?? 0, 0, ',', '.') }}">
                        <input type="hidden" id="max_budget" name="max_budget" value="{{ $budget['max_budget'] ?? 0 }}">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('budget.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-warning">Update Budget</button>
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
<script src="{{ asset('js/rupiah.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: "Pilih Data"
        });
    });
</script>
@endsection
