

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('layouts.partials.page-title', [
      'subtitle' => 'Karyawan',
      'title' => 'Edit Data Karyawan'
  ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom border-dashed d-flex justify-content-between">
                    <h5 class="mb-0">Edit Data Karyawan</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('employee.update', $employee['id'])); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="name"
                                        value="<?php echo e(old('name', $employee['name'])); ?>" required>
                                        <input type="hidden" name="id" value="<?php echo e($employee['id']); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Perusahaan Asal</label>
                                    <select name="company_id" class="form-control select2" required>
                                        <option value="">-- Pilih Perusahaan --</option>
                                        <?php $__currentLoopData = $company; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($j['id']); ?>" <?php echo e($employee['company_id'] == $j['id'] ? 'selected' : ''); ?>>
                                                <?php echo e($j['name']); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">ID Karyawan</label>
                                    <input type="text" class="form-control" name="employee_number"
                                        value="<?php echo e(old('employee_number', $employee['employee_number'])); ?>" required>
                                </div>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Divisi</label>
                                    <select name="division_id" class="form-control select2" required>
                                        <option value="">-- Pilih Divisi --</option>
                                        <?php $__currentLoopData = $divisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($d['id']); ?>" <?php echo e($employee['division_id'] == $d['id'] ? 'selected' : ''); ?>>
                                                <?php echo e($d['name']); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Posisi</label>
                                    <select name="position_id" class="form-control select2" required>
                                        <option value="">-- Pilih Posisi --</option>
                                        <?php $__currentLoopData = $posisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($p['id']); ?>" <?php echo e($employee['position_id'] == $p['id'] ? 'selected' : ''); ?>>
                                                <?php echo e($p['name']); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Status Karyawan</label>
                                    <select name="employment_status" class="form-control select2" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="permanent" <?php echo e($employee['employment_status'] == 'permanent' ? 'selected' : ''); ?>>Karyawan Tetap</option>
                                        <option value="contract" <?php echo e($employee['employment_status'] == 'contract' ? 'selected' : ''); ?>>Karyawan Kontrak</option>
                                        <option value="intern" <?php echo e($employee['employment_status'] == 'intern' ? 'selected' : ''); ?>>Magang</option>
                                        <option value="probation" <?php echo e($employee['employment_status'] == 'probation' ? 'selected' : ''); ?>>Probation</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Level</label>
                                    <select name="grade_level" class="form-control select2">
                                        <option value="">-- Pilih Grade --</option>
                                        <?php $__currentLoopData = ['A','B','C','D','E','F']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($g); ?>" <?php echo e($employee['grade_level'] == $g ? 'selected' : ''); ?>><?php echo e($g); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="gender" class="form-control select2">
                                        <option value="">-- Pilih Gender --</option>
                                        <option value="male" <?php echo e($employee['gender'] == 'male' ? 'selected' : ''); ?>>Laki-laki</option>
                                        <option value="female" <?php echo e($employee['gender'] == 'female' ? 'selected' : ''); ?>>Perempuan</option>
                                        <option value="other" <?php echo e($employee['gender'] == 'other' ? 'selected' : ''); ?>>Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="date_of_birth" class="form-control"
                                           value="<?php echo e(old('date_of_birth', $employee['date_of_birth'])); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" name="place_of_birth" class="form-control"
                                           value="<?php echo e(old('place_of_birth', $employee['place_of_birth'])); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Status Pernikahan</label>
                                    <select name="marital_status" class="form-control select2">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="single" <?php echo e($employee['marital_status'] == 'single' ? 'selected' : ''); ?>>Belum Menikah</option>
                                        <option value="married" <?php echo e($employee['marital_status'] == 'married' ? 'selected' : ''); ?>>Menikah</option>
                                        <option value="divorced" <?php echo e($employee['marital_status'] == 'divorced' ? 'selected' : ''); ?>>Cerai</option>
                                        <option value="widowed" <?php echo e($employee['marital_status'] == 'widowed' ? 'selected' : ''); ?>>Duda/Janda</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Nomor KTP</label>
                                    <input type="text" name="national_id" maxlength="16" pattern="\d*"
                                           class="form-control"
                                           value="<?php echo e(old('national_id', $employee['national_id'])); ?>"
                                           placeholder="16 digit angka">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">NPWP</label>
                                    <input type="text" name="tax_number" class="form-control"
                                           value="<?php echo e(old('tax_number', $employee['tax_number'])); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Nomor HP</label>
                                    <input type="text" name="phone_number" class="form-control"
                                           value="<?php echo e(old('phone_number', $employee['phone_number'])); ?>">
                                </div>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="address" class="form-control" rows="2"><?php echo e(old('address', $employee['address'])); ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kontak Darurat</label>
                                    <input type="text" name="kontak_darurat" class="form-control"
                                           value="<?php echo e(old('kontak_darurat', $employee['kontak_darurat'])); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('employee.index')); ?>" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Update Data</button>
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
    <script>
        $(document).ready(function() {
            $('.select2').select2({ width: '100%' });

            // validasi KTP di frontend (hanya angka, max 16 digit)
            $('input[name="national_id"]').on('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0,16);
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['title' => 'Form Edit Data Karyawan'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/usermanagement/employee/edit.blade.php ENDPATH**/ ?>