

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('layouts.partials.page-title', [
      'title' => 'Reimbursement'
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
                <div class="card-header border-bottom border-dashed d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data Klaim Reimbursement</h5>
                    <div class="d-flex gap-2">

                        <!-- Tombol Create -->
                        <a href="<?php echo e(route('reimbursement.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus me-1"></i> Buat Klaim Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                          <tr>
                              <th>#</th>
                              <th>Title</th>
                              <th>Amount</th>
                              <th>Status</th>
                              <th>Aksi</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php $__currentLoopData = $reimbursement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $reimbursement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($reimbursement['title'] ?? '-'); ?></td>
                                <td>Rp <?php echo e(number_format($reimbursement['amount'], 0, ',', '.')); ?></td>
                                <td>
                                    <?php
                                        $status = $reimbursement['status'] ?? '-';
                                        $classMap = [
                                            'DRAFT'     => 'bg-secondary',
                                            'SUBMITTED' => 'bg-info',
                                            'APPROVED'  => 'bg-success',
                                            'REJECTED'  => 'bg-danger',
                                            'PAID'      => 'bg-primary',
                                        ];
                                        $badgeClass = $classMap[$status] ?? 'bg-dark';
                                    ?>

                                    <span class="badge rounded-pill <?php echo e($badgeClass); ?>">
                                        <?php echo e($status); ?>

                                    </span>
                                </td>
                                <td>
                                <a href="<?php echo e(route('reimbursement.single', $reimbursement['id'])); ?>" 
                                    class="btn btn-sm btn-primary">
                                    <i class="ti ti-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo e($reimbursement['id']); ?>, '<?php echo e(addslashes($reimbursement['title'])); ?>')"><i class="ti ti-trash"></i></button>
                              </td>
                          </tr>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div> <!-- end row-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/components/table-datatable.js']); ?>

    
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
                    form.action = `/reimbursement/delete`;
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


<?php echo $__env->make('layouts.vertical', ['title' => 'Data Reimbursement'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/reimbursement/index.blade.php ENDPATH**/ ?>