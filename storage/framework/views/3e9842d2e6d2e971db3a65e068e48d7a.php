

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

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                <div class="ms-auto">
                    <a href="<?php echo e(route('flow.index')); ?>" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
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
                                    <?php if($flow['is_active'] == 1): ?>
                                    <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                    <span class="badge bg-danger">Non Aktif</span>
                                    <?php endif; ?>
                                </li>
                        </ul>
                    </div>
                </div>

                <!-- Steps -->
                <div class="mt-5">
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
                                <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                        <button class="btn btn-sm btn-warning" 
                                        onclick="openEditStepModal(<?php echo e($step['id']); ?>, <?php echo e($step['step_order']); ?>, <?php echo e($step['is_final']); ?>)">
                                        <i class="ti ti-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo e($step['id']); ?>)"><i class="ti ti-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                        <p class="text-muted fst-italic">Belum ada step approval.</p>
                    <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Step -->
<div class="modal fade" id="addStepModal">
    <div class="modal-dialog">
        <form method="POST" action="<?php echo e(route('steps.store')); ?>" class="modal-content">
            <?php echo csrf_field(); ?>
            <div class="modal-header">
                <h5 class="modal-title">Tambah Step Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Urutan Step -->
                <div class="mb-3">
                    <label class="form-label">Urutan</label>
                    <input type="number" class="form-control" name="step_order" id="editStepOrder" min="1" required>
                    <input type="hidden" name="approval_flow_id" value="<?php echo e($flow['id']); ?>">
                </div>

                <!-- Division -->
                <div class="mb-3">
                    <label class="form-label">Divisi</label>
                    <select name="division_id" class="form-select select2" id="">
                        <option value="">-- Pilih Posisi --</option>
                        <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($d['id']); ?>"><?php echo e($d['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Position -->
                <div class="mb-3">
                    <label class="form-label">Posisi</label>
                    <select name="position_id" class="form-select select2" id="">
                        <option value="">-- Pilih Posisi --</option>
                        <?php $__currentLoopData = $position; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p['id']); ?>"><?php echo e($p['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- User -->
                <div class="mb-3">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-select select2" id="">
                        <option value="">-- Pilih Posisi --</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($u['id']); ?>"><?php echo e($u['name']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Final Step -->
                <div class="mb-3">
                    <label class="form-label">Final Step?</label>
                    <select class="form-control" name="is_final" id="editStepFinal">
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

<div class="modal fade" id="editStepModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editStepForm" class="modal-content">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="modal-header">
                <h5 class="modal-title">Edit Step Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Step</label>
                    <input type="text" class="form-control" name="position" id="editStepName" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Urutan</label>
                    <input type="number" class="form-control" name="order" id="editStepOrder" min="1" required>
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
    // Jalankan setiap kali modal ditampilkan
    $(document).on('shown.bs.modal', '.modal', function () {
      const $modal = $(this);

      // Init/re-init semua select2 di dalam modal ini
      $modal.find('select.select2').each(function () {
        const $el = $(this);
        if ($el.data('select2')) $el.select2('destroy'); // hindari double init

        $el.select2({
          dropdownParent: $modal,
          width: '100%',
          placeholder: $el.data('placeholder') || 'Pilih Data',
          allowClear: true
        });
      });
    });

    // Optional: bersihkan saat modal ditutup
    $(document).on('hidden.bs.modal', '.modal', function () {
      $(this).find('select.select2').each(function () {
        if ($(this).data('select2')) $(this).select2('destroy');
      });
    });
  });
</script>

<script>
    function openEditStepModal(id, name, order) {
        let form = document.getElementById('editStepForm');
        form.action = `/approval/flow/<?php echo e($flow['id']); ?>/steps/${id}`;
        document.getElementById('editStepName').value = name;
        document.getElementById('editStepOrder').value = order;
        new bootstrap.Modal(document.getElementById('editStepModal')).show();
    }
</script>

<script>
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
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/sppd/approval/delete-steps`;
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '<?php echo e(csrf_token()); ?>';
                form.appendChild(csrfInput);
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                idInput.value = id;
                form.appendChild(idInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
<script>
    const API_BASE_URL = "<?php echo e(env('SPPD_API_URL')); ?>"; 
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // ambil token dari session (dikirim dari Blade ke JS)
        const token = "<?php echo e(Session::get('jwt_token')); ?>";
        const API_BASE_URL = "<?php echo e(env('SPPD_API_URL')); ?>"; 

        document.querySelectorAll(".toggle-final").forEach(function(switcher) {
            switcher.addEventListener("change", function() {
                let stepId = this.dataset.id;
                let isFinal = this.checked ? 1 : 0;

                fetch(`${API_BASE_URL}/approval/steps/final-step/${stepId}`, {
                    method: "POST",
                    headers: {
                        "Authorization": `Bearer ${token}`,
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ is_final: isFinal })
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error("HTTP error " + res.status);
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        console.log("✅ Updated:", data);
                    } else {
                        alert("❌ Gagal update");
                        this.checked = !isFinal; // rollback
                    }
                })
                .catch(err => {
                    console.error("Fetch error:", err);
                    alert("⚠️ Error koneksi ke API");
                    this.checked = !isFinal;
                });
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vertical', ['title' => 'Detail Approval Flow'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/company/approval/single.blade.php ENDPATH**/ ?>