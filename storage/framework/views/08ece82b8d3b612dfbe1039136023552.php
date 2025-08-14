

<?php $__env->startSection('css'); ?>
  <?php echo app('Illuminate\Foundation\Vite')(['node_modules/choices.js/public/assets/styles/choices.min.css', 'node_modules/select2/dist/css/select2.min.css']); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('layouts.partials.page-title', [
      'subtitle' => 'User Management',
      'title' => 'Create User'
  ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between">
                    <h5 class="mb-0">Form Tambah User</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('users.store')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="mb-2">
                            <label for="simpleinput" class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="mb-2">
                            <label for="simpleinput" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="user@email.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Divisi</label>
                            <select name="divisi_id" id="divisi_id" class="form-control select2" data-toggle="select2" required>
                                <?php $__currentLoopData = $divisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $divisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($divisi['id']); ?>"><?php echo e($divisi['name']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Role</label>
                            <select name="role" id="role_id" class="form-control select2" data-toggle="select2" required>
                                <?php $__currentLoopData = $role; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($role['name']); ?>"><?php echo e($role['name']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Buat User</button>
                    </form>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div> <!-- end row-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['title' => 'Create User'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/usermanagement/createuser.blade.php ENDPATH**/ ?>