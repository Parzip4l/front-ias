

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
                                <a href="" class='btn btn-sm btn-warning'>Edit</a>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['title' => 'User List Management'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/usermanagement/userlist.blade.php ENDPATH**/ ?>