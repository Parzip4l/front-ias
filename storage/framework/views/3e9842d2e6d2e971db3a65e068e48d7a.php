<?php $__env->startSection('css'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', [
    'title' => 'Approval Flow',
    'subtitle' => 'Detail Approval Flow'
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="row justify-content-center">
    <div class="col-lg-10">
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

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <!-- Header -->
            <div class="bg-primary p-4 text-white d-flex align-items-center">
                <div class="rounded-circle bg-white text-primary fw-bold d-flex align-items-center justify-content-center me-3" style="width:60px;height:60px;font-size:24px;">
                    <?php echo e(strtoupper(substr($flow['name'],0,1))); ?>

                </div>
                <div>
                    <h4 class="mb-0"><?php echo e($flow['name'] ?? '-'); ?></h4>
                    <b><small class="opacity-75">Flow ID: <?php echo e($flow['id'] ?? '-'); ?></small></b>
                </div>
                <div class="ms-auto d-flex">
                    <a href="<?php echo e(route('flow.index')); ?>" class="btn btn-light btn-sm me-2">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <a href="<?php echo e(route('flow.edit', $flow['id'])); ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit Flow
                    </a>
                </div>
            </div>

            <!-- Body -->
            <div class="card-body p-4">
                <!-- Info Flow -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted fw-bold mb-3">Informasi Flow</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <small class="text-muted d-block">Nama Flow</small>
                                <i class="bi bi-diagram-3 text-primary me-2"></i>
                                <span class="fw-semibold"><?php echo e($flow['name'] ?? '-'); ?></span>
                            </li>
                            <li class="mb-3">
                                <small class="text-muted d-block">Approval Type</small>
                                <i class="bi bi-building text-primary me-2"></i>
                                <span class="fw-semibold"><?php echo e($flow['approval_type'] ?? '-'); ?></span>
                            </li>
                            <li class="mb-3">
                                <small class="text-muted d-block">Posisi Pemohon </small>
                                <i class="bi bi-diagram-3 text-primary me-2"></i>
                                <span class="fw-semibold"><?php echo e($flow['requester_position']['name'] ?? '-'); ?></span>
                            </li>
                            <li class="mb-3">
                                <small class="text-muted d-block">Perusahaan</small>
                                <i class="bi bi-building text-primary me-2"></i>
                                <span class="fw-semibold"><?php echo e($flow['company']['name'] ?? '-'); ?></span>
                            </li>
                            <li>
                                <small class="text-muted d-block">Status</small>
                                <i class="bi bi-toggle-on text-primary me-2"></i>
                                <?php if($flow['is_active']): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Non Aktif</span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Steps / Amount -->
                <div class="mt-5">
                    <?php if($flow['approval_type'] === 'hirarki'): ?>
                        <!-- Hirarki Flow Steps -->
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="text-uppercase text-muted fw-bold mb-0 flex-grow-1">Approval Steps</h6>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStepModal">
                                <i class="bi bi-plus-lg"></i> Tambah Step
                            </button>
                        </div>
                        <?php if(!empty($steps)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>Urutan Approval</th>
                                        <th>User</th>
                                        <th>Divisi</th>
                                        <th>Posisi</th>
                                        <th>Final Approval</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><span class="badge bg-info"><?php echo e($step['step_order'] ?? '-'); ?></span></td>
                                        <td><?php echo e($step['user']['name'] ?? '-'); ?></td>
                                        <td><?php echo e($step['division']['name'] ?? '-'); ?></td>
                                        <td><?php echo e($step['position']['name'] ?? '-'); ?></td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-final" 
                                                    type="checkbox" 
                                                    data-id="<?php echo e($step['id']); ?>"
                                                    <?php echo e($step['is_final'] ? 'checked' : ''); ?>>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="openEditStepModal('<?php echo e($step['id']); ?>','<?php echo e($step['position']['name'] ?? ''); ?>','<?php echo e($step['step_order']); ?>')">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo e($step['id']); ?>)">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                            <p class="text-muted fst-italic">Belum ada step approval.</p>
                        <?php endif; ?>
                    <?php elseif($flow['approval_type'] === 'nominal'): ?>
                    <!-- Nominal Flow -->
                    <div class="d-flex align-items-center mb-3">
                        <h6 class="text-uppercase text-muted fw-bold mb-0 flex-grow-1">Approval Amount Flows</h6>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAmountFlowModal">
                            <i class="bi bi-plus-lg"></i> Tambah Amount Flow
                        </button>
                    </div>

                    <?php if(!empty($amountFlows)): ?>
                    <div class="accordion" id="amountFlowAccordion">
                        <?php $__currentLoopData = $amountFlows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $flowAmount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="accordion-item mb-3 shadow-sm rounded">
                                <h2 class="accordion-header" id="heading<?php echo e($index); ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo e($index); ?>">
                                        Rp <?php echo e(number_format($flowAmount['min_amount'],0,',','.')); ?> - 
                                        <?php echo e($flowAmount['max_amount'] ? number_format($flowAmount['max_amount'],0,',','.') : 'Unlimited'); ?>

                                    </button>
                                </h2>
                                <div id="collapse<?php echo e($index); ?>" class="accordion-collapse collapse" data-bs-parent="#amountFlowAccordion">
                                    <div class="accordion-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">Steps</h6>
                                            <div>
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addStepAmountModal" data-flow-id="<?php echo e($flowAmount['id']); ?>">
                                                    <i class="bi bi-plus-lg"></i> Tambah Step
                                                </button>
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editAmountFlowModal" data-flow-id="<?php echo e($flowAmount['id']); ?>">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-danger btn-delete-flow" data-id="<?php echo e($flowAmount['id']); ?>">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Urutan Approval</th>
                                                        <th>User</th>
                                                        <th>Divisi</th>
                                                        <th>Posisi</th>
                                                        <th>Final Approval</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__empty_1 = true; $__currentLoopData = $flowAmount['steps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <tr>
                                                        <td><span class="badge bg-info"><?php echo e($step['step_order'] ?? '-'); ?></span></td>
                                                        <td><?php echo e($step['user']['name'] ?? '-'); ?></td>
                                                        <td><?php echo e($step['division']['name'] ?? '-'); ?></td>
                                                        <td><?php echo e($step['position']['name'] ?? '-'); ?></td>
                                                        <td>
                                                            <?php if($step['is_final']): ?>
                                                                <span class="badge bg-success">Ya</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">Tidak</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-warning" onclick="openEditStepModal({
                                                                id: '<?php echo e($step['id']); ?>',
                                                                step_order: '<?php echo e($step['step_order']); ?>',
                                                                division_id: '<?php echo e($step['division_id'] ?? ''); ?>',
                                                                position_id: '<?php echo e($step['position_id'] ?? ''); ?>',
                                                                user_id: '<?php echo e($step['user_id'] ?? ''); ?>',
                                                                is_final: '<?php echo e($step['is_final']); ?>'
                                                            })">
                                                                <i class="bi bi-pencil"></i> Edit
                                                            </button>
                                                            <button class="btn btn-sm btn-danger btn-delete-step" data-id="<?php echo e($step['id']); ?>">
                                                                <i class="bi bi-trash"></i> Hapus
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted fst-italic">Belum ada step untuk amount flow ini.</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted fst-italic">Belum ada approval amount flow.</p>
                    <?php endif; ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Amount Flow -->
<div class="modal fade" id="addAmountFlowModal">
    <div class="modal-dialog">
        <form method="POST" action="<?php echo e(route('amountflow.store')); ?>" class="modal-content">
            <?php echo csrf_field(); ?>
            <div class="modal-header">
                <h5 class="modal-title">Tambah Amount Flow</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="approval_flow_id" value="<?php echo e($flow['id']); ?>">
                <div class="mb-3">
                    <label class="form-label">Minimal Amount</label>
                    <input type="number" name="min_amount" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Maksimal Amount (kosongkan untuk Unlimited)</label>
                    <input type="number" name="max_amount" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="addStepModal">
    <div class="modal-dialog">
        <form method="POST" action="<?php echo e(route('steps.store')); ?>" class="modal-content">
            <?php echo csrf_field(); ?>
            <div class="modal-header">
                <h5 class="modal-title">Tambah Tahapan Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="approval_flow_id" id="approval_flow_id" value="<?php echo e($flow['id']); ?>">
                <div class="mb-3">
                    <label class="form-label">Urutan Step</label>
                    <input type="number" name="step_order" class="form-control" min="1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Divisi</label>
                    <select name="division_id" class="form-select select2">
                        <option value="">-- Pilih Divisi --</option>
                        <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($d['id']); ?>"><?php echo e($d['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Posisi</label>
                    <select name="position_id" class="form-select select2">
                        <option value="">-- Pilih Posisi --</option>
                        <?php $__currentLoopData = $position; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p['id']); ?>"><?php echo e($p['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-select select2">
                        <option value="">-- Pilih User --</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($u['id']); ?>"><?php echo e($u['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Final Step?</label>
                    <select class="form-select" name="is_final">
                        <option value="0">Tidak</option>
                        <option value="1">Ya</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Step untuk Amount Flow -->
<div class="modal fade" id="addStepAmountModal">
    <div class="modal-dialog">
        <form method="POST" action="<?php echo e(route('amountstep.store')); ?>" class="modal-content">
            <?php echo csrf_field(); ?>
            <div class="modal-header">
                <h5 class="modal-title">Tambah Step Amount Flow</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="approval_amount_flow_id" id="amountFlowIdStep">
                <div class="mb-3">
                    <label class="form-label">Urutan Step</label>
                    <input type="number" name="step_order" class="form-control" min="1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Divisi</label>
                    <select name="division_id" class="form-select select2">
                        <option value="">-- Pilih Divisi --</option>
                        <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($d['id']); ?>"><?php echo e($d['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Posisi</label>
                    <select name="position_id" class="form-select select2">
                        <option value="">-- Pilih Posisi --</option>
                        <?php $__currentLoopData = $position; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p['id']); ?>"><?php echo e($p['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-select select2">
                        <option value="">-- Pilih User --</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($u['id']); ?>"><?php echo e($u['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Final Step?</label>
                    <select class="form-select" name="is_final">
                        <option value="0">Tidak</option>
                        <option value="1">Ya</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

 <!-- Modal Edit Amount Flow -->
<div class="modal fade" id="editAmountFlowModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editAmountFlowForm" class="modal-content">
            <?php echo csrf_field(); ?>
            <div class="modal-header">
                <h5 class="modal-title">Edit Amount Flow</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="approval_flow_id" value="<?php echo e($flow['id']); ?>">
                <input type="hidden" name="flow_id" id="editFlowId">
                <div class="mb-3">
                    <label class="form-label">Minimal Amount</label>
                    <input type="number" name="min_amount" class="form-control" id="editMinAmount" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Maksimal Amount (kosongkan untuk Unlimited)</label>
                    <input type="number" name="max_amount" class="form-control" id="editMaxAmount">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Step -->
<div class="modal fade" id="editStepAmountModal">
    <div class="modal-dialog">
        <form id="editStepAmountForm" class="modal-content">
            <?php echo csrf_field(); ?>
            <div class="modal-header">
                <h5 class="modal-title">Edit Step</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="step_id" id="editStepId">
                <div class="mb-3">
                    <label class="form-label">Urutan Step</label>
                    <input type="number" name="step_order" id="editStepOrder" class="form-control" min="1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Divisi</label>
                    <select name="division_id" id="editStepDivision" class="form-select select2">
                        <option value="">-- Pilih Divisi --</option>
                        <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($d['id']); ?>"><?php echo e($d['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Posisi</label>
                    <select name="position_id" id="editStepPosition" class="form-select select2">
                        <option value="">-- Pilih Posisi --</option>
                        <?php $__currentLoopData = $position; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p['id']); ?>"><?php echo e($p['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">User</label>
                    <select name="user_id" id="editStepUser" class="form-select select2">
                        <option value="">-- Pilih User --</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($u['id']); ?>"><?php echo e($u['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Final Step?</label>
                    <select name="is_final" id="editStepFinal" class="form-select">
                        <option value="0">Tidak</option>
                        <option value="1">Ya</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function () {
    $(document).on('shown.bs.modal', '.modal', function () {
        $(this).find('select.select2').each(function () {
            const $el = $(this);
            if ($el.data('select2')) $el.select2('destroy');
            $el.select2({ dropdownParent: $(this).closest('.modal'), width:'100%', allowClear:true });
        });
    });
    $(document).on('hidden.bs.modal', '.modal', function () {
        $(this).find('select.select2').each(function () {
            if ($(this).data('select2')) $(this).select2('destroy');
        });
    });

    // Set amountFlowId when opening Add Step modal for Amount Flow
    $('#addStepAmountModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget)
        let flowId = button.data('flow-id')
        $(this).find('#amountFlowIdStep').val(flowId)
    });
});
</script>
<script>
$(function () {
    $(document).on('shown.bs.modal', '.modal', function () {
        const $modal = $(this);
        $modal.find('select.select2').each(function () {
            const $el = $(this);
            if ($el.data('select2')) $el.select2('destroy');
            $el.select2({ dropdownParent: $modal, width:'100%', allowClear:true });
        });
    });
    $(document).on('hidden.bs.modal', '.modal', function () {
        $(this).find('select.select2').each(function () {
            if ($(this).data('select2')) $(this).select2('destroy');
        });
    });
});
</script>
<script>
const API_BASE_URL = "<?php echo e(env('SPPD_API_URL')); ?>";
const TOKEN = "<?php echo e(Session::get('jwt_token')); ?>";

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".toggle-final").forEach(function(switcher) {
        switcher.addEventListener("change", function() {
            let stepId = this.dataset.id;
            let isFinal = this.checked ? 1 : 0;
            fetch(`${API_BASE_URL}/approval/steps/final-step/${stepId}`, {
                method: "POST",
                headers: {
                    "Authorization": `Bearer ${TOKEN}`,
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ is_final: isFinal })
            })
            .then(res => res.json())
            .then(data => {
                if(!data.success) { alert("❌ Gagal update"); this.checked = !isFinal; }
            })
            .catch(err => { console.error(err); alert("⚠️ Error koneksi ke API"); this.checked = !isFinal; });
        });
    });
});

function confirmDelete(id) {
    Swal.fire({
        title: `Hapus Data Ini ?`,
        text: "Data yang sudah dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if(result.isConfirmed){
            const form = document.createElement('form');
            form.method='POST'; form.action='/sppd/approval/delete-steps';
            const csrf = document.createElement('input'); csrf.type='hidden'; csrf.name='_token'; csrf.value='<?php echo e(csrf_token()); ?>';
            const idInput = document.createElement('input'); idInput.type='hidden'; idInput.name='id'; idInput.value=id;
            form.appendChild(csrf); form.appendChild(idInput); document.body.appendChild(form); form.submit();
        }
    });
}

function openEditStepModal(id, name, order){
    let form = document.getElementById('editStepForm');
    form.action = `/approval/flow/<?php echo e($flow['id']); ?>/steps/${id}`;
    document.getElementById('editStepName').value = name;
    document.getElementById('editStepOrder').value = order;
    new bootstrap.Modal(document.getElementById('editStepModal')).show();
}
</script>

<script>

document.addEventListener("DOMContentLoaded", function() {

    // Helper untuk delete via AJAX dengan POST JSON
    function deleteResource(url, successMessage) {
        fetch(url, {
            method: "POST",
            headers: {
                "Authorization": `Bearer ${TOKEN}`,
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({}) // body kosong karena API cuma butuh URL
        })
        .then(res => res.json())
        .then(data => {
            if (data.success || data.message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: successMessage,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', data.message || 'Gagal menghapus data.', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error', 'Terjadi kesalahan koneksi ke API.', 'error');
        });
    }

    // Delete Amount Flow
    document.querySelectorAll(".btn-delete-flow").forEach(button => {
        button.addEventListener("click", function() {
            const flowId = this.dataset.id;
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data Amount Flow ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            }).then(result => {
                if (result.isConfirmed) {
                    deleteResource(`${API_BASE_URL}/approval/amount-flow/delete/${flowId}`, "Amount Flow berhasil dihapus.");
                }
            });
        });
    });

    // Delete Amount Step
    document.querySelectorAll(".btn-delete-step").forEach(button => {
        button.addEventListener("click", function() {
            const stepId = this.dataset.id;
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Step approval ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            }).then(result => {
                if (result.isConfirmed) {
                    deleteResource(`${API_BASE_URL}/approval/amount-step/delete/${stepId}`, "Step berhasil dihapus.");
                }
            });
        });
    });

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // Buka modal edit dan set data
    document.querySelectorAll("[data-bs-target='#editAmountFlowModal']").forEach(button => {
        button.addEventListener("click", function() {
            const flowId = this.dataset.flowId;
            // Ambil data dari accordion atau array JS jika sudah ada
            const flowData = <?php echo json_encode($amountFlows); ?>.find(f => f.id == flowId);

            if(flowData){
                document.getElementById("editFlowId").value = flowData.id;
                document.getElementById("editMinAmount").value = flowData.min_amount;
                document.getElementById("editMaxAmount").value = flowData.max_amount ?? '';
            }
        });
    });

    // Submit edit via fetch AJAX
    const editForm = document.getElementById("editAmountFlowForm");
    editForm.addEventListener("submit", function(e){
        e.preventDefault();

        const flowId = document.getElementById("editFlowId").value;
        const minAmount = document.getElementById("editMinAmount").value;
        const maxAmount = document.getElementById("editMaxAmount").value;

        fetch(`${API_BASE_URL}/approval/amount-flow/update/${flowId}`, {
            method: "POST", // atau PUT sesuai API
            headers: {
                "Authorization": `Bearer ${TOKEN}`,
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                min_amount: minAmount,
                max_amount: maxAmount
            })
        })
        .then(res => res.json())
        .then(data => {
            // jika data.message ada dan mengandung kata "success", anggap berhasil
            if(data.success || (data.message && data.message.toLowerCase().includes('success'))){
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message || 'Amount Flow berhasil diupdate',
                    timer: 1500,
                    showConfirmButton: false
                }).then(()=> location.reload());
            } else {
                Swal.fire('Error', data.message || 'Gagal update data', 'error');
            }
        })
        .catch(err=>{
            console.error(err);
            Swal.fire('Error','Terjadi kesalahan server','error');
        });
    });

});
</script>
<script>
    function openEditStepModal(step) {
        $('#editStepId').val(step.id);
        $('#editStepOrder').val(step.step_order);
        $('#editStepDivision').val(step.division_id).trigger('change');
        $('#editStepPosition').val(step.position_id).trigger('change');
        $('#editStepUser').val(step.user_id).trigger('change');
        $('#editStepFinal').val(step.is_final ? '1' : '0');
        new bootstrap.Modal(document.getElementById('editStepAmountModal')).show();
    }

    document.addEventListener('DOMContentLoaded', function () {

        $('#editStepAmountForm').submit(function (e) {
            e.preventDefault();

            const stepId = $('#editStepId').val();
            const payload = {
                step_order: $('#editStepOrder').val(),
                division_id: $('#editStepDivision').val(),
                position_id: $('#editStepPosition').val(),
                user_id: $('#editStepUser').val(),
                is_final: $('#editStepFinal').val()
            };

            fetch(`${API_BASE_URL}/approval/amount-step/update/${stepId}`, {
                method: 'POST',
                headers: {
                    "Authorization": `Bearer ${TOKEN}`,
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.success || data.message){
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Step berhasil diupdate.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); // reload supaya tabel update
                    });
                } else {
                    Swal.fire('Error', data.message || 'Gagal update step.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Terjadi kesalahan koneksi ke API.', 'error');
            });
        });
    });

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['title' => 'Detail Approval Flow'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/company/approval/single.blade.php ENDPATH**/ ?>