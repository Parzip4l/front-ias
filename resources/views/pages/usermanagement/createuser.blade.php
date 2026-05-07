@extends('layouts.vertical', ['title' => 'Create User'])

@section('css')
  @vite(['node_modules/choices.js/public/assets/styles/choices.min.css', 'node_modules/select2/dist/css/select2.min.css'])
@endsection

@section('content')
  @include('layouts.partials.page-title', [
      'subtitle' => 'User Management',
      'title' => ($pageMode ?? 'create') === 'edit' ? 'Edit User' : 'Create User'
  ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between">
                    <h5 class="mb-0">{{ ($pageMode ?? 'create') === 'edit' ? 'Form Edit User' : 'Form Tambah User' }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ $formAction ?? route('users.store') }}" method="post">
                        @csrf
                        <div class="mb-2">
                            <label for="simpleinput" class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" value="{{ old('name', $userData['name'] ?? '') }}" required>
                        </div>
                        <div class="mb-2">
                            <label for="simpleinput" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="user@email.com" value="{{ old('email', $userData['email'] ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Divisi</label>
                            <select name="divisi_id" id="divisi_id" class="form-control select2" data-toggle="select2" required>
                                @foreach($divisi as $div)
                                    <option value="{{ $div['id'] }}" @selected((string) old('divisi_id', $userData['divisi_id'] ?? '') === (string) $div['id'])>{{ $div['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Role</label>
                            <select name="role" id="role_id" class="form-control select2" data-toggle="select2" required>
                                @foreach($role as $roleItem)
                                    <option value="{{ $roleItem['name'] }}" @selected((string) old('role', $userData['role'] ?? '') === (string) $roleItem['name'])>{{ $roleItem['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">{{ ($pageMode ?? 'create') === 'edit' ? 'Update User' : 'Buat User' }}</button>
                    </form>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div> <!-- end row-->
@endsection
@section('scripts')
@endsection
