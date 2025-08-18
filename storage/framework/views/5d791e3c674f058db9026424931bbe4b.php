

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <?php echo $__env->make('layouts.partials.page-title', [
      'subtitle' => 'User Management',
      'title' => 'Budget jabatan'
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
                    <h5 class="mb-0">Data Budget Berdasarkan Jabatan</h5>
                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modal-create-role">Buat Budget Baru</a>
                </div>
                <div class="card-body">
                    <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                          <tr>
                              <th>#</th>
                              <th>Jabatan</th>
                              <th>Budget Type</th>
                              <th>Type</th>
                              <th>Max Budget</th>
                              <th>Aksi</th>
                          </tr>
                        </thead>        
                        <tbody>
                            <?php $__currentLoopData = $budget; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e(is_array($item['position']) ? ($item['position']['name'] ?? '-') : $item['position']); ?></td>
                                    <td><?php echo e(is_array($item['category']) ? ($item['category']['name'] ?? '-') : $item['category']); ?></td>
                                    <td><?php echo e($item['type'] ?? '-'); ?></td>
                                    <td><?php echo e(number_format($item['max_budget'], 0, ',', '.')); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('budget.edit', $item['id'])); ?>" 
                                        class="btn btn-sm btn-warning">
                                            Edit
                                        </a>
                                        <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo e($item['id']); ?>, '<?php echo e(addslashes($item['position']['name'])); ?>')">Hapus</button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->

            <!-- Modal Create -->
            <div class="modal fade" id="modal-create-role" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="mySmallModalLabel">Buat Budget Baru</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="<?php echo e(route('budget.store')); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Posisi / Jabatan</label>
                                    <select name="position_id" id="" class="form-select select2" required>
                                        <?php $__currentLoopData = $jabatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jabatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($jabatan['id']); ?>"><?php echo e($jabatan['name']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Budget Kategori</label>
                                    <select name="category_id" id="" class="form-select select2" required>
                                        <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category['id']); ?>"><?php echo e($category['name']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Type</label>
                                    <input type="text" name="type" class="form-control" placeholder="eg; Bintang 3, Ekonomi" required>
                                </div>
                                <div class="mb-3">
                                    <label for="max_budget_display" class="form-label">Max Budget</label>
                                    <input type="text" id="max_budget_display" class="form-control rupiah-input" data-target="max_budget_hidden" placeholder="Rp 0" required>
                                    <input type="hidden" id="max_budget_hidden" name="max_budget">
                                </div>
                                <button class="btn btn-primary w-100" type="submit">Simpan</button>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Modal -->

        </div><!-- end col-->
    </div> <!-- end row-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?php echo e(asset('js/rupiah.js')); ?>"></script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/components/table-datatable.js']); ?>

    <script>
       $(document).ready(function() {
        // Modal Create
        $('#modal-create-role .select2').select2({
            width: '100%',
            placeholder: "Pilih Kepala Divisi",
            dropdownParent: $('#modal-create-role')
        });

        // Modal Edit
        $('#editBudgetModal .select2').select2({
            width: '100%',
            placeholder: "Pilih Data",
            dropdownParent: $('#editBudgetModal')
        });
    });
    </script>

    
    <script>
        function confirmDelete(id, name) {
            Swal.fire({
                title: `Hapus Budget "${name}"?`,
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
                    form.action = `/user-management/budget/delete`;
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
    function openEditModal(id, positionId, categoryId, type, maxBudget) {
        // isi form modal
        document.getElementById("edit_id").value = id;
        document.getElementById("edit_position_id").value = positionId;
        document.getElementById("edit_category_id").value = categoryId;
        document.getElementById("edit_type").value = type;
        document.getElementById("edit_max_budget").value = maxBudget;

        // buka modal
        var modal = new bootstrap.Modal(document.getElementById('editBudgetModal'));
        modal.show();
    }
</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.vertical', ['title' => 'Budget List Management'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/usermanagement/budget/index.blade.php ENDPATH**/ ?>