

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('layouts.partials.page-title', [
      'subtitle' => 'Sppd',
      'title' => 'List SPPD Perlu Pembayaran'
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between">
                    <h5 class="mb-0 align-self-center"><?php echo e($pageTitle ?? 'Daftar SPPD'); ?></h5>
                    <?php if(empty($pageTitle)): ?>
                        <a href="<?php echo e(route('sppd.create')); ?>" class="btn btn-sm btn-primary">Buat Pengajuan SPPD</a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nomor SPPD</th>
                            <th>Pemohon</th>
                            <th>Tujuan</th>
                            <th>Tanggal Berangkat</th>
                            <th>Tanggal Pulang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $sppds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sppd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($sppd['nomor_sppd']); ?></td>
                            <td><?php echo e($sppd['user']['name']); ?></td>
                            <td><?php echo e($sppd['tujuan'] ?? '-'); ?></td>
                            <td><?php echo e($sppd['tanggal_berangkat'] ?? '-'); ?></td>
                            <td><?php echo e($sppd['tanggal_pulang'] ?? '-'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($sppd['status']=='Pending' ? 'warning' : ($sppd['status']=='Approved' ? 'success' : 'secondary')); ?>">
                                    <?php echo e($sppd['status']); ?>

                                </span>
                            </td>
                            <td>
                                <!-- Lihat Details -->
                                <a href="<?php echo e(route('sppd.previews', hid($sppd['id']))); ?>" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="ti ti-eye"></i>
                                </a>

                                <!-- Edit -->
                                <a href="<?php echo e(route('sppd.create', $sppd['id'])); ?>" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="ti ti-pencil"></i>
                                </a>

                                <!-- Hapus -->
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo e(json_encode(hid($sppd['id']))); ?>,'<?php echo e($sppd['nomor_sppd']); ?>')" title="Hapus">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->

            <!-- Modal Create -->
            

            <!-- End Modal -->



        </div><!-- end col-->
    </div> <!-- end row-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/components/table-datatable.js']); ?>
    
    <script>
        function confirmDelete(id, nomor_sppd) {
            Swal.fire({
                title: `Hapus data SPPD "${nomor_sppd}"?`,
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
                    form.action = `/sppd/delete`;
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
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.vertical', ['title' => 'List Data SPPD Perlu Pembayaran'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/sppd/payment/index.blade.php ENDPATH**/ ?>