<div class="mb-3">
    <label class="form-label">Pegawai</label>
    <?php if( session('user.role') == 'admin'): ?>
        <select name="pegawai_id" class="form-select select2" required>
            <option value="">-- Pilih Pegawai --</option>
            <?php $__currentLoopData = $pegawai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?> - <?php echo e($p->position); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    <?php else: ?>
        <input type="hidden" name="pegawai_id" value="<?php echo e(auth()->user()->id); ?>">
        <input type="text" class="form-control" value="<?php echo e(auth()->user()->name); ?>" readonly>
    <?php endif; ?>
</div>
<?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/sppd/steps/pegawai.blade.php ENDPATH**/ ?>