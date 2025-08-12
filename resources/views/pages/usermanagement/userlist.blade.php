@extends('layouts.vertical', ['title' => 'User List Management'])

@section('css')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
@endsection

@section('content')
  @include('layouts.partials.page-title', [
      'subtitle' => 'User Management',
      'title' => 'User List Management'
  ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom border-dashed">
                    <h5 class="mb-0">List Data User</h5>
                </div>
                <div class="card-body">
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                          <tr>
                              <th>#</th>
                              <th>Nama</th>
                              <th>Email</th>
                              <th>Role</th>
                              <th>Status Verifikasi</th>
                              <th>Aksi</th>
                          </tr>
                         </thead>


                        <tbody>
                          @foreach ($users as $index => $user)
                          <tr>
                              <td>{{ $index + 1 }}</td>
                              <td>{{ $user['name'] ?? '-' }}</td>
                              <td>{{ $user['email'] ?? '-' }}</td>
                              <td>{{ $user['role'] ?? '-' }}</td>
                              <td>
                                  @if(!empty($user['email_verified_at']))
                                      <span class="badge bg-success">Terverifikasi</span>
                                  @else
                                      <span class="badge bg-danger">Belum Terverifikasi</span>
                                  @endif
                              </td>
                              <td>
                                <a href="" class='btn btn-sm btn-warning'>Edit</a>
                                <a href="" class='btn btn-sm btn-danger'>Hapus</a>
                              </td>
                          </tr>
                          @endforeach
                      </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div> <!-- end row-->
@endsection
@section('scripts')
    @vite(['resources/js/components/table-datatable.js'])
@endsection
