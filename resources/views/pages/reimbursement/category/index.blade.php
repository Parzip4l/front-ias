@extends('layouts.vertical', ['title' => 'Data Kategori Reimbursement'])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
@endsection

@section('content')
  @include('layouts.partials.page-title', [
      'title' => 'Reimbursement',
      'subtitle' => 'Kategori'
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
                <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data Kategori Reimbursement</h5>
                    <div class="d-flex gap-2">
                        <!-- Tombol Create -->
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-create-type">
                            <i class="ti ti-plus me-1"></i> Buat Data Baru
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                          <tr>
                              <th>#</th>
                              <th>Nama Kategori</th>
                              <th>Perusahaan</th>
                              <th>Aksi</th>
                          </tr>
                        </thead>

                        <tbody>
                          @foreach ($category as $index => $category)
                          <tr>
                              <td>{{ $index + 1 }}</td>
                              <td>{{ $category['name'] ?? '-' }}</td>
                              <td>{{ $category['companies']['name'] ?? '-' }}</td>
                              <td>
                                <a href="javascript:void(0)" 
                                    class="btn btn-sm btn-warning btn-edit-category"
                                    data-id="{{ $category['id'] }}"
                                    data-name="{{ $category['name'] }}"
                                    data-code="{{ $category['code'] }}">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $category['id'] }}, '{{ addslashes($category['name']) }}')"><i class="ti ti-trash"></i></button>
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
                            <h4 class="modal-title" id="mySmallModalLabel">Buat Kategori Baru</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('reimbursement.category.store')}}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Nama Kategori</label>
                                    <input type="text" name="name" class="form-control" placeholder="Nama Kategori" required>
                                </div>
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Kode Kategori</label>
                                    <input type="text" name="code" class="form-control" placeholder="Kode Kategori" required>
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

            <!-- Modal -->
            <div class="modal fade" id="modal-edit-category" tabindex="-1" aria-labelledby="editCategoryLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                    <form id="editCategoryForm" action="{{ route('reimbursement.category.update') }}" method="POST">
                        @csrf
                        {{-- karena pakai post ke API, tidak perlu @method('PUT') --}}
                        <input type="hidden" name="id" id="editCategoryId">

                        <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryLabel">Edit Kategori Reimbursement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" id="editCategoryName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kode</label>
                            <input type="text" name="code" id="editCategoryCode" class="form-control" required>
                        </div>
                        </div>

                        <div class="modal-footer">
                        <button type="submit" class="btn btn-success w-100">Update</button>
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
                    form.action = `/reimbursement/category/delete`;
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
        document.addEventListener("DOMContentLoaded", function () {
            const editModal = new bootstrap.Modal(document.getElementById('modal-edit-category'));

            document.querySelectorAll('.btn-edit-category').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.getElementById('editCategoryId').value = this.dataset.id;
                    document.getElementById('editCategoryName').value = this.dataset.name;
                    document.getElementById('editCategoryCode').value = this.dataset.code;

                    editModal.show();
                });
            });
        });
    </script>
@endsection

