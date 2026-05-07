<?php $__env->startSection('css'); ?>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('layouts.partials.page-title', [
      'subtitle' => 'User Management',
      'title' => 'User List Management'
  ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between">
                    <h5 class="mb-0">List Data User</h5>
                    <a href="<?php echo e(route('users.create')); ?>" class="btn btn-sm btn-primary">Tambah User</a>
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
                          <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                              <td><?php echo e($index + 1); ?></td>
                              <td><?php echo e($user['name'] ?? '-'); ?></td>
                              <td><?php echo e($user['email'] ?? '-'); ?></td>
                              <td><?php echo e($user['role'] ?? '-'); ?></td>
                              <td>
                                  <?php if(!empty($user['email_verified_at'])): ?>
                                      <span class="badge bg-success">Terverifikasi</span>
                                  <?php else: ?>
                                      <span class="badge bg-danger">Belum Terverifikasi</span>
                                  <?php endif; ?>
                              </td>
                              <td>
                                <a href="<?php echo e(route('users.edit', $user['id'])); ?>" class='btn btn-sm btn-warning'>Edit</a>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-info"
                                    onclick="confirmResendResetPassword(<?php echo e((int) $user['id']); ?>, <?php echo e(json_encode($user['email'] ?? '')); ?>, <?php echo e(json_encode($user['name'] ?? 'user')); ?>)"
                                >
                                    Reset Password
                                </button>
                                <a href="" class='btn btn-sm btn-danger'>Hapus</a>
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
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/components/table-datatable.js']); ?>
    <script>
        function confirmResendResetPassword(id, email, name) {
            Swal.fire({
                title: `Kirim ulang reset password untuk ${name}?`,
                text: `Link reset password akan dikirim ke ${email}.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0ea5e9',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, kirim',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `<?php echo e(url('/user-management/users')); ?>/${id}/resend-reset-password`;

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '<?php echo e(csrf_token()); ?>';
                    form.appendChild(csrfInput);

                    const emailInput = document.createElement('input');
                    emailInput.type = 'hidden';
                    emailInput.name = 'email';
                    emailInput.value = email;
                    form.appendChild(emailInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['title' => 'User List Management'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/muhamadsobirin/Documents/front-ias/resources/views/pages/usermanagement/userlist.blade.php ENDPATH**/ ?>