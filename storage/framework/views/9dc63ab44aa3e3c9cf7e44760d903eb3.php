

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('layouts.partials.page-title', [
      'title' => 'Perusahaan',
      'subtitle' => 'Persetujuan SPPD'
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
                    <h5 class="mb-0 align-self-center">Data Persetujuan</h5>
                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modal-create-flow">Buat Data Baru</a>
                </div>
                <div class="card-body">
                    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                          <tr>
                              <th>#</th>
                              <th>Nama Alur Persetujuan</th>
                              <th>Approval Type</th>
                              <th>Perusahaan</th>
                              <th>Posisi Pemohon</th>
                              <th>Aktif Status</th>
                              <th>Aksi</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php $__currentLoopData = $flow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($item['name'] ?? '-'); ?></td>
                                <td><?php echo e($item['approval_type'] ?? '-'); ?></td>
                                <td><?php echo e($item['company']['name'] ?? '-'); ?></td>
                                <td><?php echo e($item['requester_position']['name'] ?? '-'); ?></td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggle-final" 
                                            type="checkbox" 
                                            data-id="<?php echo e($item['id']); ?>"
                                            <?php echo e($item['is_active'] ? 'checked' : ''); ?>>
                                    </div>
                                </td>
                              <td>
                                <a href="<?php echo e(route('flow.single', $item['id'])); ?>" 
                                    class="btn btn-sm btn-primary">
                                    <i class="ti ti-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo e($item['id']); ?>, '<?php echo e(addslashes($item['name'])); ?>')"><i class="ti ti-trash"></i></button>
                              </td>
                          </tr>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->

            <!-- Modal Create -->
            <div class="modal fade" id="modal-create-flow" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="mySmallModalLabel">Buat Alur Persetujuan Baru</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="<?php echo e(route('flow.store')); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Nama Alur Persetujuan</label>
                                    <input type="text" name="name" class="form-control" placeholder="eg; Alur 1" required>
                                </div>
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Perusahaan</label>
                                    <select name="company_id" id="" class="form-select select2" required>
                                        <?php $__currentLoopData = $company; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($company['id']); ?>"><?php echo e($company['name']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <button class="btn btn-primary w-100" type="submit">Simpan</button>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

        </div><!-- end col-->
    </div> <!-- end row-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/components/table-datatable.js']); ?>

    
    <script>
        function confirmDelete(id, name) {
            Swal.fire({
                title: `Hapus Data "${name}"?`,
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
                    form.action = `/sppd/approval/delete`;
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
        function openEditModal(id, name, companyId) {
            // isi form
            document.getElementById("edit_id").value = id;
            document.getElementById("edit_name").value = name;
            document.getElementById("edit_company").value = companyId;

            // set action form
            let form = document.getElementById("editForm");
            form.action = "/flow/" + id; // sesuaikan dengan route update

            // tampilkan modal
            let modal = new bootstrap.Modal(document.getElementById("editModal"));
            modal.show();
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
                    let isActive = this.checked ? 1 : 0;

                    fetch(`${API_BASE_URL}/approval/flow/active-flow/${stepId}`, {
                        method: "POST",
                        headers: {
                            "Authorization": `Bearer ${token}`,
                            "Content-Type": "application/json",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({ is_active: isActive })
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
                            this.checked = !isActive; // rollback
                        }
                    })
                    .catch(err => {
                        console.error("Fetch error:", err);
                        alert("⚠️ Error koneksi ke API");
                        this.checked = !isActive;
                    });
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.vertical', ['title' => 'Data Persetujuan SPPD'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/company/approval/index.blade.php ENDPATH**/ ?>