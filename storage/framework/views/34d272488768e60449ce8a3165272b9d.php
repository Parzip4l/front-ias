<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('layouts.partials.page-title', [
      'title' => 'Data Karyawan'
  ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
<?php
    $companies = array_unique(array_map(fn($e) => $e['company']['name'] ?? '-', $employee));
    $divisions = array_unique(array_map(fn($e) => $e['division']['name'] ?? '-', $employee));
    $positions = array_unique(array_map(fn($e) => $e['position']['name'] ?? '-', $employee));
?>

<div class="card">
    <div class="card-header border-bottom border-dashed">
        <h5>Filter Data Karyawan</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <select id="filter-company" class="form-select select2" data-placeholder="Filter Perusahaan">
                    <option value="">Semua Perusahaan</option>
                    <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($company); ?>"><?php echo e($company); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filter-division" class="form-select select2" data-placeholder="Filter Divisi">
                    <option value="">Semua Divisi</option>
                    <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($division); ?>"><?php echo e($division); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filter-position" class="form-select select2" data-placeholder="Filter Jabatan">
                    <option value="">Semua Jabatan</option>
                    <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $position): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($position); ?>"><?php echo e($position); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </div>
</div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data Karyawan</h5>
                    <div class="d-flex gap-2">
                        <!-- Tamplate -->
                        <a href="<?php echo e(route('employee.export-template')); ?>" class="btn btn-primary btn-sm">
                            <i class="ti ti-file me-1"></i> Download Template Import
                        </a>

                        <!-- Tombol Export -->
                        <a href="<?php echo e(route('company.export')); ?>" class="btn btn-success btn-sm">
                            <i class="ti ti-download me-1"></i> Export
                        </a>

                        <!-- Tombol Import -->
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modal-import-company">
                            <i class="ti ti-upload me-1"></i> Import
                        </button>

                        <!-- Tombol Create -->
                        <a href="<?php echo e(route('employee.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus me-1"></i> Buat Data Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tableKaryawan" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                          <tr>
                              <th>No Karyawan</th>
                              <th>Nama Lengkap</th>
                              <th>Asal Perusahaan</th>
                              <th>Divisi</th>
                              <th>Jabatan</th>
                              <th>Aksi</th>
                          </tr>
                         </thead>


                        <tbody>
                          <?php $__currentLoopData = $employee; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                              <td><?php echo e($employee['employee_number'] ?? '-'); ?></td>
                              <td><?php echo e($employee['name'] ?? '-'); ?></td>
                              <td><?php echo e($employee['company']['name'] ?? '-'); ?></td>
                              <td><?php echo e($employee['division']['name'] ?? '-'); ?></td>
                              <td><?php echo e($employee['position']['name'] ?? '-'); ?></td>
                              <td>
                                <a href="<?php echo e(route('employee.show', hid($employee['id']))); ?>" 
                                    class="btn btn-sm btn-primary">
                                    <i class="ti ti-eye"></i>
                                </a>
                                <a href="<?php echo e(route('employee.edit', hid($employee['id']))); ?>" 
                                    class="btn btn-sm btn-warning">
                                    <i class="ti ti-edit"></i>
                                </a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo e(json_encode(hid($employee['id']))); ?>, '<?php echo e(addslashes($employee['name'])); ?>')"><i class="ti ti-trash"></i></button>
                              </td>
                          </tr>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->

            <div class="modal fade" id="modal-import-company" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <form action="<?php echo e(route('employee.import')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="modal-header">
                                <h5 class="modal-title" id="importModalLabel">Import Data Karyawan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted">Upload file Excel (.xlsx / .csv) sesuai template yang tersedia.</p>
                                <div class="mb-3">
                                    <label for="importFile" class="form-label">Pilih File</label>
                                    <input type="file" name="file" id="importFile" class="form-control" accept=".xlsx,.csv" required>
                                </div>
                                <a href="<?php echo e(route('employee.export-template')); ?>" class="text-primary small">
                                    <i class="ti ti-file"></i> Download Template Import
                                </a>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info w-100">Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div><!-- end col-->
    </div> <!-- end row-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/components/table-datatable.js']); ?>

    
    <script>
        function confirmDelete(id, name) {
            Swal.fire({
                title: `Hapus data "${name}"?`,
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
                    form.action = `/user-management/karyawan/delete`;
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '<?php echo e(csrf_token()); ?>';
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
        $(document).ready(function () {
            // Inisialisasi DataTable
            if ($.fn.DataTable.isDataTable('#tableKaryawan')) {
                table = $('#tableKaryawan').DataTable();
            } else {
                table = $('#tableKaryawan').DataTable({
                    responsive: true
                });
            }

            // Inisialisasi select2
            $('.select2').select2({ width: '100%' });

            // Filter berdasarkan kolom
            $('#filter-company').on('change', function () {
                table.column(2).search(this.value).draw();
            });

            $('#filter-division').on('change', function () {
                table.column(3).search(this.value).draw();
            });

            $('#filter-position').on('change', function () {
                table.column(4).search(this.value).draw();
            });
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.vertical', ['title' => 'Data Karyawan'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/usermanagement/employee/index.blade.php ENDPATH**/ ?>