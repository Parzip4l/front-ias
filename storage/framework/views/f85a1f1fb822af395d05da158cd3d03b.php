

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.partials.page-title', [
        'title' => 'Detail Reimbursement',
        'subtitle' => 'Reimbursement'
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row">
        <!-- Kolom Detail Klaim -->
        <div class="col-lg-8">
            <!-- Card Klaim -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0">💳 Detail Klaim</h5>
                    <span class="badge px-3 py-2 rounded-pill 
                        <?php if($reimbursement['status'] == 'SUBMITTED'): ?> bg-warning text-dark
                        <?php elseif($reimbursement['status'] == 'APPROVED'): ?> bg-success
                        <?php elseif($reimbursement['status'] == 'REJECTED'): ?> bg-danger
                        <?php else: ?> bg-secondary <?php endif; ?>">
                        <?php echo e(ucfirst(strtolower($reimbursement['status']))); ?>

                    </span>
                </div>
                <div class="card-body">
                    <h5 class="fw-bold mb-1"><?php echo e($reimbursement['title']); ?></h5>
                    <p class="text-muted fst-italic"><?php echo e($reimbursement['description'] ?? 'Tidak ada deskripsi'); ?></p>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <p class="mb-1 text-muted small">Nominal</p>
                                <h6 class="fw-bold text-dark">Rp <?php echo e(number_format($reimbursement['amount'], 0, ',', '.')); ?></h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <p class="mb-1 text-muted small">Diajukan Oleh</p>
                                <h6 class="fw-bold text-dark"><?php echo e($user['name'] ?? '-'); ?></h6>
                                <small class="text-muted"><?php echo e($user['email'] ?? ''); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Detail SPPD -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">🚌 Detail SPPD</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">No. SPPD</span>
                            <span><?php echo e($sppd['nomor_sppd']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Tujuan</span>
                            <span><?php echo e($sppd['tujuan']); ?> (<?php echo e($sppd['lokasi_tujuan']); ?>)</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Tanggal</span>
                            <span><?php echo e($sppd['tanggal_berangkat']); ?> → <?php echo e($sppd['tanggal_pulang']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Transportasi</span>
                            <span><?php echo e($sppd['transportasi']); ?></span>
                        </li>
                    </ul>
                    <div class="mt-3">
                        <p class="fw-semibold mb-1">Keperluan</p>
                        <p class="text-muted"><?php echo e($sppd['keperluan']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Card Lampiran -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">📂 Lampiran</h5>
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex align-items-center p-2 bg-light rounded mb-2 shadow-sm">
                            <i class="bi bi-file-earmark-pdf text-danger fs-4 me-2"></i>
                            <a href="<?php echo e(asset($file['file_path'])); ?>" target="_blank" class="fw-semibold text-decoration-none">
                                Dokumen #<?php echo e($file['id']); ?> (<?php echo e(strtoupper($file['file_type'])); ?>)
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted fst-italic">Tidak ada file lampiran.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Kolom Histori -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">📝 Histori Persetujuan</h5>
                </div>
                <div class="card-body">
                    <?php if(!empty($approvals)): ?>
                        <div class="timeline">
                            <?php $__currentLoopData = $approvals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $approval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="mb-3 p-2 rounded border-start border-3 
                                    <?php if($approval['status'] == 'APPROVED'): ?> border-success
                                    <?php elseif($approval['status'] == 'REJECTED'): ?> border-danger
                                    <?php else: ?> border-warning <?php endif; ?>">
                                    <strong><?php echo e($approval['user']['name'] ?? '-'); ?></strong>
                                    <span class="badge rounded-pill ms-2
                                        <?php if($approval['status'] == 'APPROVED'): ?> bg-success
                                        <?php elseif($approval['status'] == 'REJECTED'): ?> bg-danger
                                        <?php else: ?> bg-warning text-dark <?php endif; ?>">
                                        <?php echo e($approval['status']); ?>

                                    </span>
                                    <br>
                                    <small class="text-muted"><?php echo e($approval['created_at']); ?></small>
                                    <p class="mb-0"><?php echo e($approval['notes'] ?? ''); ?></p>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted fst-italic">Belum ada histori persetujuan.</p>
                    <?php endif; ?>
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
            $('#sppd_id').select2({
                placeholder: "Pilih SPPD",
                allowClear: true
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['title' => 'Buat Klaim Reimbursement'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/reimbursement/single.blade.php ENDPATH**/ ?>