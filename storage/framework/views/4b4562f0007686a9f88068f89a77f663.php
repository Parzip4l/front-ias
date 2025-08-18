

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', [
    'subtitle' => 'User Management',
    'title' => 'Edit Budget'
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

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header text-dark border-bottom border-dashed">
                <h5 class="mb-0">Form Edit Budget</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('budget.update', $budget['id'])); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <!-- Jabatan / Position -->
                    <div class="mb-3">
                        <label for="position_id" class="form-label">Posisi / Jabatan</label>
                        <select name="position_id" id="position_id" class="form-select select2" required>
                            <option value="">-- Pilih Jabatan --</option>
                            <?php $__currentLoopData = $jabatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($j['id']); ?>" <?php echo e($budget['position']['id'] == $j['id'] ? 'selected' : ''); ?>>
                                    <?php echo e($j['name']); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <input type="hidden" id="id" name="id" value="<?php echo e($budget['id'] ?? 0); ?>">
                    </div>

                    <!-- Kategori -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select name="category_id" id="category_id" class="form-select select2" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c['id']); ?>" <?php echo e($budget['category']['id'] == $c['id'] ? 'selected' : ''); ?>>
                                    <?php echo e($c['name']); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Type -->
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe</label>
                        <input type="text" name="type" id="type" class="form-control" value="<?php echo e($budget['type']); ?>" required>
                    </div>

                    <!-- Max Budget -->
                    <div class="mb-3">
                        <label for="max_budget_display" class="form-label">Max Budget</label>
                        <input type="text" id="max_budget_display" class="form-control rupiah-input"
                            data-target="max_budget" 
                            value="<?php echo e(number_format($budget['max_budget'] ?? 0, 0, ',', '.')); ?>">
                        <input type="hidden" id="max_budget" name="max_budget" value="<?php echo e($budget['max_budget'] ?? 0); ?>">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('budget.index')); ?>" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-warning">Update Budget</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?php echo e(asset('js/rupiah.js')); ?>"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: "Pilih Data"
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['title' => 'Edit Budget'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/usermanagement/budget/edit.blade.php ENDPATH**/ ?>