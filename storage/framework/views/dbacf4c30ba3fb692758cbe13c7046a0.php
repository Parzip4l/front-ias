

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', [
        'title' => 'Buat Klaim Reimbursement',
        'subtitle' => 'Reimbursement'
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row">
        <div class="col-lg-2">

        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header border-bottom border-dashed">
                    <h5 class="mb-0">Form Klaim Reimbursement</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('reimbursement.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <!-- Pilih SPPD -->
                        <div class="mb-3">
                            <label for="sppd_id" class="form-label">Pilih SPPD</label>
                            <select name="sppd_id" id="sppd_id" class="form-control select2" required>
                                <option value="">-- Pilih SPPD --</option>
                                <?php $__currentLoopData = $sppds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sppd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sppd['id']); ?>">
                                        <?php echo e($sppd['nomor_sppd']); ?> | <?php echo e(\Illuminate\Support\Str::limit($sppd['keperluan'], 40)); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Kategori Klaim -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Pilih Kategori Klaim</label>
                            <select name="category_id" id="category_id" class="form-control select2" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category['id']); ?>">
                                        <?php echo e($category['name']); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>


                        <!-- Judul -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Klaim</label>
                            <input type="text" name="title" id="title" class="form-control" 
                                   value="<?php echo e(old('title')); ?>" required>
                            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi (Opsional)</label>
                            <textarea name="description" id="description" class="form-control" rows="3"><?php echo e(old('description')); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Jumlah -->
                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah Klaim</label>
                            <input type="number" name="amount" id="amount" class="form-control" 
                                   value="<?php echo e(old('amount')); ?>" min="0" required>
                            <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Upload Bukti -->
                        <div class="mb-3">
                            <label for="files" class="form-label">Upload Bukti (PDF/JPG/PNG)</label>
                            <input type="file" name="files[]" id="files" class="form-control" multiple>
                            <small class="text-muted">Maks 2MB per file</small>
                            <?php $__errorArgs = ['files.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('reimbursement.index')); ?>" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan & Ajukan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#sppd_id').select2({
                placeholder: "Pilih SPPD",
                allowClear: true
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['title' => 'Buat Klaim Reimbursement'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/reimbursement/create.blade.php ENDPATH**/ ?>