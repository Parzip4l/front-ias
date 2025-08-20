@extends('layouts.vertical', ['title' => 'Data Jenis Usaha'])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
@endsection

@section('content')
  @include('layouts.partials.page-title', [
      'subtitle' => 'Perusahaan',
      'title' => 'Jenis Usaha'
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
                    <h5 class="mb-0">Data Jenis Usaha</h5>
                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modal-create-type">Buat Data Baru</a>
                </div>
                <div class="card-body">
                    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                          <tr>
                              <th>#</th>
                              <th>Nama Jenis Usaha</th>
                              <th>Deskripsi</th>
                              <th>Aksi</th>
                          </tr>
                        </thead>

                        <tbody>
                          @foreach ($company as $index => $company)
                          <tr>
                              <td>{{ $index + 1 }}</td>
                              <td>{{ $company['name'] ?? '-' }}</td>
                              <td>{{ $company['description'] ?? '-' }}</td>
                              <td>
                                <a href="javascript:void(0)" 
                                    class="btn btn-sm btn-warning" 
                                    onclick="openEditModal({{ $company['id'] }}, '{{ addslashes($company['name']) }}', '{{ $company['description'] }}')">
                                    Edit
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $company['id'] }}, '{{ addslashes($company['name']) }}')">Hapus</button>
                              </td>
                          </tr>
                          @endforeach
                      </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->

            <!-- Modal Create -->
            <div class="modal fade" id="modal-create-type" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="mySmallModalLabel">Buat Jenis Usaha Baru</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('companytype.store')}}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Jenis Usaha</label>
                                    <input type="text" name="name" class="form-control" placeholder="eg; Logistik" required>
                                </div>
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Deskripsi</label>
                                    <textarea name="description" class="form-control" id=""></textarea>
                                </div>
                                <button class="btn btn-primary w-100" type="submit">Simpan</button>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Modal Edit -->
             <div class="modal fade" id="modal-edit-type" role="dialog" aria-labelledby="modalEditRoleLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <form id="form-edit-type" method="POST" action="">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="id" id="edit-role-id" />

                            <div class="modal-header">
                                <h4 class="modal-title" id="modalEditRoleLabel">Edit Divisi</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Jenis Usaha</label>
                                    <input type="text" name="name" id="edit-role-name" class="form-control" placeholder="eg; Logistik" required>
                                </div>
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Deskripsi</label>
                                    <textarea name="description" class="form-control" id="edit-role-deskription"></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div><!-- end col-->
    </div> <!-- end row-->
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @vite(['resources/js/components/table-datatable.js'])

    {{-- fungsi confirmDelete dan openEditModal tetap di bawah --}}
    <script>
        function confirmDelete(id, name) {
            Swal.fire({
                title: `Hapus Data "${name}"?`,
                text: "Data yang sudah dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/company/type/delete`;
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);
                    const idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = 'id';
                    idInput.value = id;
                    form.appendChild(idInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>

    <script>
        function openEditModal(id, name, description) {
            const form = document.getElementById('form-edit-type');
            form.action = `{{ route('companytype.update') }}`;
            document.getElementById('edit-role-id').value = id;
            document.getElementById('edit-role-name').value = name;
            document.getElementById('edit-role-deskription').value = description;

            var editModal = new bootstrap.Modal(document.getElementById('modal-edit-type'));
            editModal.show();
        }
    </script>
@endsection

