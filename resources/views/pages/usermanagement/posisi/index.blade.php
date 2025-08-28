@extends('layouts.vertical', ['title' => 'Jabatan List Management'])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
@endsection

@section('content')
  @include('layouts.partials.page-title', [
      'subtitle' => 'User Management',
      'title' => 'Posisi / Jabatan List'
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
                    <h5 class="mb-0">Data jabatan</h5>
                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modal-create-role">Buat Jabatan Baru</a>
                </div>
                <div class="card-body">
                    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                          <tr>
                              <th>#</th>
                              <th>Jabatan</th>
                              <th>Perusahaan</th>
                              <th>Aksi</th>
                          </tr>
                         </thead>


                        <tbody>
                          @foreach ($posisi as $index => $p)
                          <tr>
                              <td>{{ $index + 1 }}</td>
                              <td>{{ $p['name'] ?? '-' }}</td>
                              <td>{{ $p['company']['name'] ?? '-' }}</td>
                              <td>
                                <a href="javascript:void(0)" 
                                    class="btn btn-sm btn-warning" 
                                    onclick="openEditModal({{ $p['id'] }}, '{{ addslashes($p['name']) }}')">
                                    Edit
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $p['id'] }}, '{{ addslashes($p['name']) }}')">Hapus</button>
                              </td>
                          </tr>
                          @endforeach
                      </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->

            <!-- Modal Create -->
            <div class="modal fade" id="modal-create-role" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="mySmallModalLabel">Buat Jabatan Baru</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('posisi.store')}}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Jabatan</label>
                                    <input type="text" name="name" class="form-control" placeholder="Roles Name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Perusahaan</label>
                                    <select name="company_id" class="form-control" id="">
                                        <option value="">--Pilih Perusahaan--</option>
                                        @foreach($companies as $index => $c)
                                        <option value="{{$c['id']}}">{{$c['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <button class="btn btn-primary w-100" type="submit">Simpan</button>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Modal -->

            <!-- Modal Edit Divisi -->
            <div class="modal fade" id="modal-edit-role" role="dialog" aria-labelledby="modalEditRoleLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <form id="form-edit-role" method="POST" action="">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="id" id="edit-role-id" />

                            <div class="modal-header">
                                <h4 class="modal-title" id="modalEditRoleLabel">Edit Jabatan</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="edit-role-name" class="form-label">Jabatan</label>
                                    <input type="text" name="name" id="edit-role-name" class="form-control" placeholder="Nama Jabatan" required />
                                </div>
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Perusahaan</label>
                                    <select name="company_id" class="form-control" id="">
                                        <option value="">--Pilih Perusahaan--</option>
                                        @foreach($companies as $index => $c)
                                            <option value="{{ $c['id'] }}" 
                                                {{ isset($data) && $data->company_id == $c['id'] ? 'selected' : '' }}>
                                                {{ $c['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- End Modal -->



        </div><!-- end col-->
    </div> <!-- end row-->
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @vite(['resources/js/components/table-datatable.js'])

    <script>
       $(document).ready(function() {
        // Modal Create
        $('#modal-create-role .select2').select2({
            width: '100%',
            placeholder: "Pilih Kepala Divisi",
            dropdownParent: $('#modal-create-role')
        });

        // Modal Edit
        $('#modal-edit-role .select2').select2({
            width: '100%',
            placeholder: "Pilih Kepala Divisi",
            dropdownParent: $('#modal-edit-role')
        });
    });
    </script>

    {{-- fungsi confirmDelete dan openEditModal tetap di bawah --}}
    <script>
        function confirmDelete(id, name) {
            Swal.fire({
                title: `Hapus role "${name}"?`,
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
                    form.action = `/user-management/posisi/delete`;
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
        function openEditModal(id, name, headId) {
            const form = document.getElementById('form-edit-role');
            form.action = `{{ route('posisi.update') }}`;
            document.getElementById('edit-role-id').value = id;
            document.getElementById('edit-role-name').value = name;

            var editModal = new bootstrap.Modal(document.getElementById('modal-edit-role'));
            editModal.show();
        }
    </script>
@endsection

