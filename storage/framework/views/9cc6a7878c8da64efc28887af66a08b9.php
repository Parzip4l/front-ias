

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', [
    'title' => 'Approval Flow',
    'subtitle' => 'Edit Step Approval'
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
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="bg-primary p-4 text-white d-flex align-items-center">
                        <h4 class="mb-0">Edit Flow Approval</h4>
                    </div>
                    
                    <div class="card-body p-4">
                        <form method="POST" action="<?php echo e(route('flow.update')); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <!-- Urutan Step -->
                            <div class="mb-3">
                                <label class="form-label">Nama Flow</label>
                                <input type="text" class="form-control" name="name"
                                    value="<?php echo e(old('name', $flow['name'])); ?>" required>
                                    <input type="hidden" name="id" value="<?php echo e($flow['id']); ?>">
                            </div>

                            <!-- Division -->
                            <div class="mb-3">
                                <label class="form-label">Perusahaan</label>
                                <select name="company_id" class="form-select select2">
                                    <option value="">-- Pilih Perusahaan --</option>
                                    <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($d['id']); ?>" 
                                            <?php echo e($flow['company_id'] == $d['id'] ? 'selected' : ''); ?>>
                                            <?php echo e($d['name']); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Position Pemohon -->
                            <div class="mb-3">
                                <label class="form-label">Posisi Pemohon</label>
                                <select name="requester_position_id" class="form-select select2">
                                    <option value="">-- Pilih Posisi --</option>
                                    <?php $__currentLoopData = $position; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($p['id']); ?>" 
                                            <?php echo e($flow['requester_position_id'] == $p['id'] ? 'selected' : ''); ?>>
                                            <?php echo e($p['name']); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Approval Type</label>
                                <select class="form-select" name="approval_type">
                                    <option value="hirarki" <?php echo e($flow['approval_type'] == 'hirarki' ? 'selected' : ''); ?>>Hirarki</option>
                                    <option value="nominal" <?php echo e($flow['approval_type'] == 'nominal' ? 'selected' : ''); ?>>Nominal</option>
                                </select>
                            </div>

                            <!-- Final Step -->
                            <div class="mb-3">
                                <label class="form-label">Aktif Step</label>
                                <select class="form-select" name="is_active">
                                    <option value="0" <?php echo e($flow['is_active'] == 0 ? 'selected' : ''); ?>>Tidak</option>
                                    <option value="1" <?php echo e($flow['is_active'] == 1 ? 'selected' : ''); ?>>Ya</option>
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

<?php echo $__env->make('layouts.vertical', ['title' => 'Edit Step Approval'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/company/approval/editflow.blade.php ENDPATH**/ ?>