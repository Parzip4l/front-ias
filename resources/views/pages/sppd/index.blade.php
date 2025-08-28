@extends('layouts.vertical', ['title' => 'List Data SPPD'])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
@endsection

@section('content')
  @include('layouts.partials.page-title', [
      'subtitle' => 'Sppd',
      'title' => 'List SPPD'
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
                    <h5 class="mb-0 align-self-center">SPPD Data</h5>
                    <a href="{{route('sppd.create')}}" class="btn btn-sm btn-primary">Buat Pengajuan SPPD</a>
                </div>
                <div class="card-body">
                    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nomor SPPD</th>
                            <th>Tujuan</th>
                            <th>Lokasi</th>
                            <th>Tanggal Berangkat</th>
                            <th>Tanggal Pulang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sppds as $index => $sppd)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $sppd['nomor_sppd'] }}</td>
                            <td>{{ $sppd['tujuan'] ?? '-' }}</td>
                            <td>{{ $sppd['lokasi_tujuan'] ?? '-' }}</td>
                            <td>{{ $sppd['tanggal_berangkat'] ?? '-' }}</td>
                            <td>{{ $sppd['tanggal_pulang'] ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $sppd['status']=='Pending' ? 'warning' : ($sppd['status']=='Approved' ? 'success' : 'secondary') }}">
                                    {{ $sppd['status'] }}
                                </span>
                            </td>
                            <td>
                                <!-- Lihat Details -->
                                <a href="{{ route('sppd.previews', $sppd['id']) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="ti ti-eye"></i>
                                </a>

                                <!-- Edit -->
                                <a href="{{ route('sppd.create', $sppd['id']) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="ti ti-pencil"></i>
                                </a>

                                <!-- Hapus -->
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $sppd['id'] }})" title="Hapus">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->

            <!-- Modal Create -->
            

            <!-- End Modal -->



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
                    form.action = `/user-management/divisi/delete`;
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
@endsection

