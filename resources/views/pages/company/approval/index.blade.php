@extends('layouts.vertical', ['title' => 'Data Persetujuan SPPD'])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
@endsection

@section('content')
  @include('layouts.partials.page-title', [
      'title' => 'Perusahaan',
      'subtitle' => 'Persetujuan SPPD'
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
                    <h5 class="mb-0 align-self-center">Data Persetujuan</h5>
                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modal-create-flow">Buat Data Baru</a>
                </div>
                <div class="card-body">
                    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                          <tr>
                              <th>#</th>
                              <th>Nama Alur Persetujuan</th>
                              <th>Approval Type</th>
                              <th>Perusahaan</th>
                              <th>Posisi Pemohon</th>
                              <th>Aktif Status</th>
                              <th>Aksi</th>
                          </tr>
                        </thead>

                        <tbody>
                          @foreach ($flow as $index => $item)
                          <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item['name'] ?? '-' }}</td>
                                <td>{{ $item['approval_type'] ?? '-' }}</td>
                                <td>{{ $item['company']['name'] ?? '-' }}</td>
                                <td>{{ $item['requester_position']['name'] ?? '-' }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggle-final" 
                                            type="checkbox" 
                                            data-id="{{ $item['id'] }}"
                                            {{ $item['is_active'] ? 'checked' : '' }}>
                                    </div>
                                </td>
                              <td>
                                <a href="{{ route('flow.single', $item['id']) }}" 
                                    class="btn btn-sm btn-primary">
                                    <i class="ti ti-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $item['id'] }}, '{{ addslashes($item['name']) }}')"><i class="ti ti-trash"></i></button>
                              </td>
                          </tr>
                          @endforeach
                      </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->

            <!-- Modal Create -->
            <div class="modal fade" id="modal-create-flow" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="mySmallModalLabel">Buat Alur Persetujuan Baru</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('flow.store')}}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Nama Alur Persetujuan</label>
                                    <input type="text" name="name" class="form-control" placeholder="eg; Alur 1" required>
                                </div>
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Type Approval</label>
                                    <select class="form-select" name="approval_type">
                                        <option value="hirarki">Hirarki</option>
                                        <option value="nominal">Nominal</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Posisi Pemohon</label>
                                    <select name="requester_position_id" class="form-select select2">
                                        <option value="">-- Pilih Posisi --</option>
                                        @foreach($position as $p)
                                            <option value="{{ $p['id'] }}">
                                                {{ $p['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Perusahaan</label>
                                    <select name="company_id" id="" class="form-select select2" required>
                                        @foreach($company as $company)
                                            <option value="{{ $company['id'] }}">{{ $company['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button class="btn btn-primary w-100" type="submit">Simpan</button>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

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
                    form.action = `/sppd/approval/delete`;
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
        function openEditModal(id, name, companyId) {
            // isi form
            document.getElementById("edit_id").value = id;
            document.getElementById("edit_name").value = name;
            document.getElementById("edit_company").value = companyId;

            // set action form
            let form = document.getElementById("editForm");
            form.action = "/flow/" + id; // sesuaikan dengan route update

            // tampilkan modal
            let modal = new bootstrap.Modal(document.getElementById("editModal"));
            modal.show();
        }
    </script>
    <script>
        const API_BASE_URL = "{{ env('SPPD_API_URL') }}"; 
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ambil token dari session (dikirim dari Blade ke JS)
            const token = "{{ Session::get('jwt_token') }}";
            const API_BASE_URL = "{{ env('SPPD_API_URL') }}"; 

            document.querySelectorAll(".toggle-final").forEach(function(switcher) {
                switcher.addEventListener("change", function() {
                    let stepId = this.dataset.id;
                    let isActive = this.checked ? 1 : 0;

                    fetch(`${API_BASE_URL}/approval/flow/active-flow/${stepId}`, {
                        method: "POST",
                        headers: {
                            "Authorization": `Bearer ${token}`,
                            "Content-Type": "application/json",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({ is_active: isActive })
                    })
                    .then(res => {
                        if (!res.ok) {
                            throw new Error("HTTP error " + res.status);
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            console.log("✅ Updated:", data);
                        } else {
                            alert("❌ Gagal update");
                            this.checked = !isActive; // rollback
                        }
                    })
                    .catch(err => {
                        console.error("Fetch error:", err);
                        alert("⚠️ Error koneksi ke API");
                        this.checked = !isActive;
                    });
                });
            });
        });
    </script>
@endsection

