

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('layouts.partials.page-title', [
      'subtitle' => 'Karyawan',
      'title' => 'Edit Data Karyawan'
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
                                    <label for="position_id" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" value="<?php echo e($employee['name']); ?>" required>
                                    <input type="hidden" id="id" name="id" value="<?php echo e($employee['id'] ?? 0); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="position_id" class="form-label">Perusahaan Asal</label>
                                    <select name="company_id" id="" class="form-control select2" required>
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
                                    <label for="position_id" class="form-label">ID Karyawan</label>
                                    <input type="text" class="form-control" name="employee_number" placeholder="eg; EMP-08210" value="<?php echo e($employee['employee_number']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="position_id" class="form-label">Divisi</label>
                                    <select name="division_id" id="" class="form-control select2" required>
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
                                    <label for="position_id" class="form-label">Posisi</label>
                                    <select name="position_id" id="" class="form-control select2" required>
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
                                        <option value="permanent" <?php echo e($employee['employment_status'] == 'permanent' ? 'selected' : ''); ?>>
                                            Karyawan Tetap
                                        </option>
                                        <option value="contract" <?php echo e($employee['employment_status'] == 'contract' ? 'selected' : ''); ?>>
                                            Karyawan Kontrak
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Level</label>
                                    <select name="grade_level" class="form-control select2" required>
                                        <option value="">-- Pilih Grade --</option>
                                        <option value="A" <?php echo e($employee['grade_level'] == 'A' ? 'selected' : ''); ?>>A</option>
                                        <option value="B" <?php echo e($employee['grade_level'] == 'B' ? 'selected' : ''); ?>>B</option>
                                        <option value="C" <?php echo e($employee['grade_level'] == 'C' ? 'selected' : ''); ?>>C</option>
                                        <option value="D" <?php echo e($employee['grade_level'] == 'D' ? 'selected' : ''); ?>>D</option>
                                        <option value="E" <?php echo e($employee['grade_level'] == 'E' ? 'selected' : ''); ?>>E</option>
                                        <option value="F" <?php echo e($employee['grade_level'] == 'F' ? 'selected' : ''); ?>>F</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('employee.index')); ?>" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Update Data </button>
                        </div>
                    </form>
                </div> <!-- end card body-->
            </div> <!-- end card -->


        </div><!-- end col-->
    </div> <!-- end row-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: "Pilih Data"
            });
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.vertical', ['title' => 'Form Tambah Data Karyawan'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/usermanagement/employee/edit.blade.php ENDPATH**/ ?>