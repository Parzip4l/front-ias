@extends('layouts.vertical', ['title' => 'Edit Step Approval'])

@section('content')
@include('layouts.partials.page-title', [
    'title' => 'Approval Flow',
    'subtitle' => 'Edit Step Approval'
])

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="bg-primary p-4 text-white d-flex align-items-center">
                <h4 class="mb-0">Edit Step Approval</h4>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('steps.update', [$flow['id'], $step['id']]) }}">
                    @csrf
                    @method('PUT')
                    <!-- Urutan Step -->
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" class="form-control" name="step_order"
                            value="{{ old('step_order', $step['step_order']) }}" required>
                            <input type="hidden" name="id" value="{{$step['id']}}">
                    </div>

                    <!-- Division -->
                    <div class="mb-3">
                        <label class="form-label">Divisi</label>
                        <select name="division_id" class="form-select select2">
                            <option value="">-- Pilih Divisi --</option>
                            @foreach($divisions as $d)
                                <option value="{{ $d['id'] }}" 
                                    {{ $step['division_id'] == $d['id'] ? 'selected' : '' }}>
                                    {{ $d['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Position -->
                    <div class="mb-3">
                        <label class="form-label">Posisi</label>
                        <select name="position_id" class="form-select select2">
                            <option value="">-- Pilih Posisi --</option>
                            @foreach($positions as $p)
                                <option value="{{ $p['id'] }}" 
                                    {{ $step['position_id'] == $p['id'] ? 'selected' : '' }}>
                                    {{ $p['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- User -->
                    <div class="mb-3">
                        <label class="form-label">User</label>
                        <select name="user_id" class="form-select select2">
                            <option value="">-- Pilih User --</option>
                            @foreach($users as $u)
                                <option value="{{ $u['id'] }}" 
                                    {{ $step['user_id'] == $u['id'] ? 'selected' : '' }}>
                                    {{ $u['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Final Step -->
                    <div class="mb-3">
                        <label class="form-label">Final Step?</label>
                        <select class="form-select" name="is_final">
                            <option value="0" {{ $step['is_final'] == 0 ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ $step['is_final'] == 1 ? 'selected' : '' }}>Ya</option>
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
