

<?php $__env->startSection('css'); ?>
<style>
/* Timeline Style */
.timeline {
    border-left: 2px solid #e9ecef;
    margin-left: 15px;
    padding-left: 10px;
}
.timeline-item {
    position: relative;
}
.timeline-marker {
    left: -5px;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['subtitle' => 'SPPD', 'title' => 'Preview SPPD'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <img src="/images/ias.png" alt="logo" height="65">
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary-subtle text-primary px-2 fs-12 mb-2"><?php echo e($sppd['status'] ?? 'DRAFT'); ?></span>
                        <h3 class="fw-bold fs-20 m-0">SPPD: <?php echo e($sppd['nomor_sppd'] ?? '-'); ?></h3>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <h5 class="fw-semibold fs-14">Diberikan Kepada:</h5>
                        <p class="mb-1"><?php echo e($sppd['user']['name'] ?? '-'); ?></p>
                        <h5 class="fw-semibold fs-14">NIK:</h5>
                        <p class="mb-1"><?php echo e($sppd['user']['employee']['employee_number'] ?? '-'); ?></p>
                        <h5 class="fw-semibold fs-14">Divisi & Jabatan :</h5>
                        <p class="mb-1"><?php echo e($sppd['user']['employee']['division']['name'] ?? '-'); ?> - <?php echo e($sppd['user']['employee']['position']['name'] ?? '-'); ?></p>
                    </div>

                    <div class="col-md-5 mb-2">
                        <h5 class="fw-semibold fs-14">Tujuan Perjalanan:</h5>
                        <p class="mb-1"><?php echo e($sppd['tujuan'] ?? '-'); ?> - <?php echo e($sppd['lokasi_tujuan'] ?? '-'); ?></p>
                        <h5 class="fw-semibold fs-14">Keperluan Perjalanan:</h5>
                        <p class="text-muted">
                            <?php echo e($sppd['keperluan'] ?? '-'); ?>

                        </p>
                        <?php 
                            $start = !empty($sppd['tanggal_berangkat']) ? \Carbon\Carbon::parse($sppd['tanggal_berangkat']) : null;
                            $end   = !empty($sppd['tanggal_pulang']) ? \Carbon\Carbon::parse($sppd['tanggal_pulang']) : null;
                            $days  = ($start && $end) ? $start->diffInDays($end) + 1 : null;
                        ?>
                        <h5 class="fw-semibold fs-14">Periode Perjalanan:</h5>
                        <p class="text-muted mb-0">
                            <?php echo e($start ? $start->translatedFormat('d F Y') : '-'); ?>

                            →
                            <?php echo e($end ? $end->translatedFormat('d F Y') : '-'); ?>

                            <?php if($days): ?>
                                (<?php echo e($days); ?> hari)
                            <?php endif; ?>
                        </p>
                    </div>

                    <div class="col-md-3 text-center text-md-end">
                        <img src="/images/png/qr-code.png" alt="QR Code" height="100">
                    </div>
                </div>

                
                <?php
                    function rupiahToInt($val) {
                        if(empty($val)) return 0;
                        return (int) str_replace(['Rp','.', ' '], '', $val);
                    }

                    // Ambil semua komponen harga
                    $hotel_price   = rupiahToInt($sppd['hotel_price'] ?? 0);
                    $ticket_depart = rupiahToInt($sppd['ticket_price_depart'] ?? 0);
                    $ticket_return = rupiahToInt($sppd['ticket_price_return'] ?? 0);
                    $transportasi  = rupiahToInt($sppd['transport_price'] ?? 0);

                    $addons_price = 0;
                    if(!empty($sppd['addons']) && is_array($sppd['addons'])){
                        foreach($sppd['addons'] as $addon){
                            if(isset($addon['price'])) $addons_price += rupiahToInt($addon['price']);
                        }
                    }

                    // Total semua biaya
                    $total = $hotel_price + $ticket_depart + $ticket_return + $addons_price + $transportasi;
                ?>

                <div class="table-responsive border-top border-dashed mt-3 pt-3">
                    <!-- <table class="table table-bordered table-striped mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>#</th>
                                <th class="text-start">Keterangan</th>
                                <th class="text-start">Detail</th>
                                <th class="text-end">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td class="fw-semibold">Hotel</td>
                                <td><?php echo e($sppd['hotel'] ?? '-'); ?></td>
                                <td class="text-end">Rp<?php echo e(number_format($hotel_price,0,',','.')); ?></td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td class="fw-semibold">Transportasi</td>
                                <td><?php echo e($sppd['transportasi'] ?? '-'); ?></td>
                                <td class="text-end">Rp<?php echo e(number_format($transportasi,0,',','.')); ?></td>
                            </tr>
                            <tr>
                                <td class="text-center">3</td>
                                <td class="fw-semibold">Tiket Pergi</td>
                                <td><?php echo e($sppd['departure_airport'] ?? '-'); ?> → <?php echo e($sppd['arrival_airport'] ?? '-'); ?></td>
                                <td class="text-end">Rp<?php echo e(number_format($ticket_depart,0,',','.')); ?></td>
                            </tr>
                            <tr>
                                <td class="text-center">4</td>
                                <td class="fw-semibold">Tiket Pulang</td>
                                <td><?php echo e($sppd['return_departure_airport'] ?? '-'); ?> → <?php echo e($sppd['return_arrival_airport'] ?? '-'); ?></td>
                                <td class="text-end">Rp<?php echo e(number_format($ticket_return,0,',','.')); ?></td>
                            </tr>
                            <?php if(!empty($sppd['addons'])): ?>
                                <tr>
                                    <td class="text-center">5</td>
                                    <td class="fw-semibold">Add-ons</td>
                                    <td><?php echo e(implode(', ', array_map(fn($a)=>$a['name'] ?? '', $sppd['addons']))); ?></td>
                                    <td class="text-end">Rp<?php echo e(number_format($addons_price,0,',','.')); ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end fw-bold fs-5">Total Keseluruhan</th>
                                <th class="text-end fw-bold fs-5">Rp<?php echo e(number_format($total,0,',','.')); ?></th>
                            </tr>
                        </tfoot>
                    </table> -->
                    
                </div>

                <div class="mt-2 mb-2">
                    <h5>Rincian Pengeluaran</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-start">Pengeluaran</th>
                                <th class="text-start">Kategori</th>
                                <th class="text-end">Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $no = 1;
                                $total = collect($expense)->sum('jumlah');
                            ?>
                            <?php $__currentLoopData = $expense; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($no++); ?></td>
                                <td><?php echo e($ex['kategori']); ?></td>
                                <td><?php echo e($ex['deskripsi']); ?></td>
                                <td class="text-end">Rp<?php echo e(number_format($ex['jumlah'], 0, ',', '.')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end fw-bold fs-5">Total Keseluruhan</th>
                                <th class="text-end fw-bold fs-5">Rp<?php echo e(number_format($total,0,',','.' ?? 0)); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                

                
                <div class="bg-light p-3 mt-4 rounded-2">
                    <p class="mb-0"><strong>Catatan:</strong> SPPD ini digunakan sebagai bukti perjalanan dinas resmi. Simpan dokumen untuk administrasi.</p>
                </div>

                
                <div class="d-print-none mt-4 text-center">
                    <a href="javascript:window.print()" class="btn btn-primary me-2"><i class="ti ti-printer me-1"></i> Print</a>
                    <a href="#" class="btn btn-info"><i class="ti ti-download me-1"></i> Download</a>
                </div>
            </div>
        </div>
    </div>

    <!-- History Card -->
    <div class="col-md-4">
        <div class="card mb-2">
            <div class="card-header border-bottom border-dashed">
                <h5 class="mb-0">Approval</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php $__currentLoopData = $approval; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?php echo e($a['approver']['name']); ?></strong>
                                <span class="text-muted">
                                    (<?php echo e($a['approver']['employee']['division']['name'] ?? '-'); ?> - 
                                    <?php echo e($a['approver']['employee']['position']['name'] ?? '-'); ?>)
                                </span><br>

                                <small>Status: 
                                    <span class="<?php if($a['status']=='Approved'): ?> text-success 
                                                <?php elseif($a['status']=='Rejected'): ?> text-danger 
                                                <?php else: ?> text-warning <?php endif; ?>">
                                        <?php echo e($a['status']); ?>

                                    </span>
                                </small>
                                <?php if($a['catatan']): ?>
                                    <p class="mb-0 small text-muted"><?php echo e($a['catatan']); ?></p>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>

                <?php
                    // cari approval step yang sesuai user sekarang
                    $userApproval = collect($approval)->firstWhere('approver_id', $currentUserId);

                    // cek indexnya
                    $userIndex = $userApproval ? collect($approval)->search(fn($ap) => $ap['id'] == $userApproval['id']) : null;

                    // validasi apakah boleh approve (semua step sebelum dia sudah Approved)
                    $canApprove = false;
                    if ($userApproval && $userApproval['status'] == 'Pending') {
                        $canApprove = true;
                        for ($i = 0; $i < $userIndex; $i++) {
                            if ($approval[$i]['status'] != 'Approved') {
                                $canApprove = false;
                                break;
                            }
                        }
                    }
                ?>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <form class="approval-form approve-form" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="approval_id" value="<?php echo e($userApproval['id'] ?? ''); ?>">
                        <button type="submit" class="btn btn-sm btn-success" <?php if(!$canApprove): ?> disabled <?php endif; ?>>
                            Approve
                        </button>
                    </form>
                    <form class="approval-form reject-form" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="approval_id" value="<?php echo e($userApproval['id'] ?? ''); ?>">
                        <button type="submit" class="btn btn-sm btn-danger" <?php if(!$canApprove): ?> disabled <?php endif; ?>>
                            Reject
                        </button>
                    </form>
                </div>

                <input type="hidden" id="sppd-id" value="<?php echo e($sppd['id']); ?>">
            </div>

        </div>
        <div class="card mb-2">
            <div class="card-header border-bottom border-dashed">
                <h5 class="mb-0">Payment</h5>
            </div>
            <div class="card-body text-center">
                <?php if(!empty($payment)): ?>
                    <p>Status Pembayaran:
                        <span class="badge 
                            <?php if($payment['status'] === 'PENDING'): ?> bg-warning 
                            <?php elseif($payment['status'] === 'PAID'): ?> bg-success 
                            <?php else: ?> bg-secondary <?php endif; ?>">
                            <?php echo e(strtoupper($payment['status'])); ?>

                        </span>
                    </p>

                    <?php if($payment['status'] === 'PENDING'): ?>
                        <a href="<?php echo e($payment['invoice_url']); ?>" target="_blank" class="btn btn-primary">
                            <i class="ti ti-credit-card me-1"></i> Bayar Sekarang
                        </a>
                    <?php elseif($payment['status'] === 'PAID'): ?>
                        <p class="text-success fw-bold">Pembayaran berhasil</p>
                    <?php endif; ?>
                <?php else: ?>
                    <form action="<?php echo e(route('sppd.pay', $sppd['id'])); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="amount" value="<?php echo e($total); ?>">
                        <button type="submit" class="btn btn-success">
                            Bayar via Xendit
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>



        <div class="card">
            <div class="card-header border-bottom border-dashed">
                <h5 class="mb-0">History</h5>
            </div>
            <div class="card-body">
                 <?php if(!empty($history) && count($history) > 0): ?>
                    <ul class="timeline list-unstyled">
                        <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="timeline-item mb-4 position-relative ps-4">
                                <div class="timeline-marker position-absolute top-0 start-0 translate-middle bg-primary rounded-circle" style="width:12px; height:12px;"></div>
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1 text-dark">
                                        <?php echo e($h['status_awal']); ?> → <span class="text-primary"><?php echo e($h['status_akhir']); ?></span>
                                    </h6>
                                    <small class="text-muted">
                                        <?php echo e(\Carbon\Carbon::parse($h['created_at'])->format('d M Y H:i')); ?>

                                    </small>
                                </div>
                                <p class="mb-0 text-secondary small">
                                    <?php echo e($h['user']['name'] ?? 'Unknown User'); ?>

                                </p>
                                <p class="mb-0 text-secondary small">
                                    <?php echo e($h['catatan'] ?? '-'); ?>

                                </p>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted mb-0">Belum ada riwayat</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script>
    // Fungsi untuk mengupdate status approval
    const API_BASE_URL = "<?php echo e(env('SPPD_API_URL')); ?>";
    const TOKEN = "<?php echo e(Session::get('jwt_token')); ?>";
    function updateApprovalStatus(approvalId, status, catatan = '') {
        // Tampilkan loading indicator jika diperlukan
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
        button.disabled = true;
        
        // Kirim request Ajax
        fetch(`${API_BASE_URL}/sppd/update-status`, {
            method: 'POST',
            headers: {
                "Authorization": `Bearer ${TOKEN}`,
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                approval_id: approvalId,
                status: status,
                catatan: catatan
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh daftar approval atau update UI
                showSuccessAlert('Status berhasil diupdate');
                setTimeout(() => {
                    location.reload(); // reload otomatis
                }, 1500);; // Fungsi untuk refresh daftar approval
            } else {
                showErrorAlert(data.message || 'Terjadi kesalahan');
                button.innerHTML = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorAlert('Terjadi kesalahan jaringan');
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }

    // Fungsi untuk refresh daftar approval
    function refreshApprovalList() {
        // Dapatkan sppd_id dari elemen tersembunyi atau data attribute
        const sppdId = document.getElementById('sppd-id').value;
        
        fetch(`${API_BASE_URL}/approval/status/${sppdId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI dengan data approval terbaru
                    updateApprovalUI(data.data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Event listener untuk form approval
    document.addEventListener('DOMContentLoaded', function() {
        const approvalForms = document.querySelectorAll('.approval-form');
        
        approvalForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const approvalId = this.querySelector('input[name="approval_id"]').value;
                const isApprove = this.classList.contains('approve-form');
                const status = isApprove ? 'Approved' : 'Rejected';
                
                // Jika reject, minta catatan
                if (status === 'Rejected') {
                    Swal.fire({
                        title: 'Alasan Penolakan',
                        input: 'textarea',
                        inputLabel: 'Masukkan alasan penolakan',
                        inputPlaceholder: 'Ketik alasan penolakan di sini...',
                        inputAttributes: {
                            'aria-label': 'Ketik alasan penolakan di sini'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        cancelButtonText: 'Batal',
                        preConfirm: (catatan) => {
                            if (!catatan) {
                                Swal.showValidationMessage('Harap masukkan alasan penolakan');
                            }
                            return catatan;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            updateApprovalStatus(approvalId, status, result.value);
                        }
                    });
                } else {
                    updateApprovalStatus(approvalId, status);
                }
            });
        });
    });
    </script>
    <script>
        function showSuccessAlert(message) {
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: message,
            timer: 2000,
            showConfirmButton: false
        });
    }

    // Fungsi untuk menampilkan alert error
    function showErrorAlert(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['title' => 'Preview SPPD'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/sppd/preview.blade.php ENDPATH**/ ?>