

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', [
    'title' => 'Approval Flow',
    'subtitle' => 'Edit Step Approval'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="bg-primary p-4 text-white d-flex align-items-center">
                <h4 class="mb-0">Edit Step Approval</h4>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="<?php echo e(route('steps.update', [$flow['id'], $step['id']])); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <!-- Urutan Step -->
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" class="form-control" name="step_order"
                            value="<?php echo e(old('step_order', $step['step_order'])); ?>" required>
                            <input type="hidden" name="id" value="<?php echo e($step['id']); ?>">
                    </div>

                    <!-- Division -->
                    <div class="mb-3">
                        <label class="form-label">Divisi</label>
                        <select name="division_id" class="form-select select2">
                            <option value="">-- Pilih Divisi --</option>
                            <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($d['id']); ?>" 
                                    <?php echo e($step['division_id'] == $d['id'] ? 'selected' : ''); ?>>
                                    <?php echo e($d['name']); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Position -->
                    <div class="mb-3">
                        <label class="form-label">Posisi</label>
                        <select name="position_id" class="form-select select2">
                            <option value="">-- Pilih Posisi --</option>
                            <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p['id']); ?>" 
                                    <?php echo e($step['position_id'] == $p['id'] ? 'selected' : ''); ?>>
                                    <?php echo e($p['name']); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- User -->
                    <div class="mb-3">
                        <label class="form-label">User</label>
                        <select name="user_id" class="form-select select2">
                            <option value="">-- Pilih User --</option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($u['id']); ?>" 
                                    <?php echo e($step['user_id'] == $u['id'] ? 'selected' : ''); ?>>
                                    <?php echo e($u['name']); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Final Step -->
                    <div class="mb-3">
                        <label class="form-label">Final Step?</label>
                        <select class="form-select" name="is_final">
                            <option value="0" <?php echo e($step['is_final'] == 0 ? 'selected' : ''); ?>>Tidak</option>
                            <option value="1" <?php echo e($step['is_final'] == 1 ? 'selected' : ''); ?>>Ya</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('flow.single', $flow['id'])); ?>" class="btn btn-light">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['title' => 'Edit Step Approval'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/company/approval/edit.blade.php ENDPATH**/ ?>