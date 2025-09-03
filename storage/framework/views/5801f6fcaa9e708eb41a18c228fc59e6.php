<?php $__env->startSection('css'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .stepper { display:flex; justify-content:space-between; margin-bottom:1.5rem; }
    .stepper .step { flex:1; text-align:center; padding:10px; border-radius:20px; font-weight:600; font-size:14px; background:#f1f3f5; margin:0 5px; transition:.3s; }
    .stepper .step.active { background:#0d6efd; color:#fff; }
    .form-step { display:none; }
    .form-step.active { display:block; }
    .addon-card { border:1px solid #e0e0e0; border-radius:10px; cursor:pointer; transition:all 0.2s; }
    .addon-card:hover { background:#f8f9fa; box-shadow:0 3px 6px rgba(0,0,0,0.1); }
    .addon-card.selected { border:2px solid #0d6efd; background:#e7f1ff; }
    .addon-card input[type="checkbox"] { display:none; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['subtitle' => 'SPPD', 'title' => 'Ajukan SPPD'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
    <div class="col-lg-12">
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
        <form id="form-sppd" method="POST" action="<?php echo e(route('sppd.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="card">
                <div class="card-body">

                    <!-- Progress -->
                    <div class="stepper mb-4">
                        <div class="step active">1. Informasi Umum</div>
                        <div class="step">2. Pesawat</div>
                        <div class="step">3. Hotel</div>
                        <div class="step">4. Addons</div>
                        <div class="step">5. Preview</div>
                    </div>

                    <!-- Step 1: Informasi Umum -->
                    <div class="form-step active" id="step-1">
                        <h5 class="mb-3">Informasi Umum</h5>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Nama Pegawai</label>
                                <input type="text" class="form-control" value="<?php echo e(session('user.name')); ?>" readonly>
                                <input type="hidden" name="userid" value="<?php echo e(session('user.id')); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tujuan</label>
                                <input type="text" class="form-control" name="tujuan" placeholder="Masukkan kota tujuan">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Lokasi Tujuan</label>
                                <input type="text" class="form-control" name="lokasi_tujuan" placeholder="Masukkan lokasi tujuan detail1">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Berangkat</label>
                                <input type="date" class="form-control" name="tanggal_berangkat" id="tanggal_berangkat">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Pulang</label>
                                <input type="date" class="form-control" name="tanggal_pulang" id="tanggal_pulang">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Keperluan</label>
                                <textarea name="keperluan" class="form-control" id="" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="mt-3 text-end">
                            <button type="button" class="btn btn-primary next-step">Lanjut</button>
                        </div>
                    </div>

                    <!-- Step 2: Pesawat -->
                    <div class="form-step" id="step-2">
                        <h5 class="mb-3">Pesawat</h5>
                        <input type="hidden" name="transportasi" id="transportasi">
                        <input type="hidden" name="biaya_estimasi" id="biaya_estimasi">
                        <div class="flight-list">
                            <?php $__currentLoopData = [
                                ['maskapai'=>'AirAsia Indonesia','from'=>'CGK','to'=>'SIN','depart'=>'08:30','arrive'=>'11:20','durasi'=>'1j 50m','harga'=>1143000,'bagasi'=>1,'wifi'=>true],
                                ['maskapai'=>'Pelita Air','from'=>'CGK','to'=>'SIN','depart'=>'07:10','arrive'=>'10:00','durasi'=>'1j 50m','harga'=>1195400,'bagasi'=>20,'wifi'=>false],
                                ['maskapai'=>'Citilink','from'=>'CGK','to'=>'SIN','depart'=>'06:20','arrive'=>'09:10','durasi'=>'1j 50m','harga'=>1202600,'bagasi'=>0,'wifi'=>false],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card mb-3 shadow-sm flight-card" data-nama="<?php echo e($f['maskapai']); ?>" data-harga="<?php echo e($f['harga']); ?>">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold"><?php echo e($f['maskapai']); ?></h6>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2"><?php echo e($f['depart']); ?> - <?php echo e($f['arrive']); ?></span>
                                            <span class="text-muted"><?php echo e($f['durasi']); ?> • <?php echo e($f['from']); ?> → <?php echo e($f['to']); ?></span>
                                        </div>
                                        <small>
                                            <?php if($f['bagasi']>0): ?> 🧳 <?php echo e($f['bagasi']); ?>kg <?php endif; ?>
                                            <?php if($f['wifi']): ?> 📶 WiFi <?php endif; ?>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-danger">Rp <?php echo e(number_format($f['harga'],0,',','.')); ?></div>
                                        <button type="button" class="btn btn-sm btn-primary pilih-pesawat mt-2">Pilih</button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div id="pesawat-preview" class="alert alert-info mt-3 d-none"></div>
                        <div class="mt-3 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                            <button type="button" class="btn btn-primary next-step">Lanjut</button>
                        </div>
                    </div>

                    <!-- Step 3: Hotel -->
                    <div class="form-step" id="step-3">
                        <h5 class="mb-3">Hotel</h5>
                        <div class="hotel-list">
                            <?php $__currentLoopData = [
                                ['nama'=>'Hotel Mulia Senayan','lokasi'=>'Jakarta','harga'=>950000,'bintang'=>5,'fasilitas'=>'Kolam renang, Gym, Spa'],
                                ['nama'=>'Ibis Styles','lokasi'=>'Jakarta','harga'=>550000,'bintang'=>3,'fasilitas'=>'Restoran, Meeting Room'],
                                ['nama'=>'RedDoorz Plus','lokasi'=>'Jakarta','harga'=>250000,'bintang'=>2,'fasilitas'=>'AC, WiFi Gratis'],
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card mb-3 shadow-sm hotel-card" data-nama="<?php echo e($h['nama']); ?>" data-harga="<?php echo e($h['harga']); ?>">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold"><?php echo e($h['nama']); ?> ⭐<?php echo e($h['bintang']); ?></h6>
                                        <div class="text-muted"><?php echo e($h['lokasi']); ?></div>
                                        <small><?php echo e($h['fasilitas']); ?></small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-danger">Rp <?php echo e(number_format($h['harga'],0,',','.')); ?>/mlm</div>
                                        <button type="button" class="btn btn-sm btn-primary pilih-hotel mt-2">Pilih</button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- Hidden untuk expenses hotel -->
                        <input type="hidden" name="expenses[0][kategori]" value="Hotel">
                        <input type="hidden" name="expenses[0][deskripsi]" id="hotel_nama">
                        <input type="hidden" name="expenses[0][jumlah]" id="hotel_harga">

                        <div id="hotel-preview" class="alert alert-info mt-3 d-none"></div>
                        <div class="mt-3 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                            <button type="button" class="btn btn-primary next-step">Lanjut</button>
                        </div>
                    </div>

                    <!-- Step 4: Addons -->
                    <div class="form-step" id="step-4">
                        <h5 class="mb-3">Tambahan Layanan</h5>
                        <div class="row" id="addon-list">
                            <?php $__currentLoopData = [
                                ['id' => 'Sewa Kendaraan', 'nama' => 'Sewa Kendaraan', 'harga' => 500000, 'desc' => 'Mobil atau motor selama perjalanan'],
                                ['id' => 'Concierge', 'nama' => 'Concierge', 'harga' => 300000, 'desc' => 'Bantuan administrasi & reservasi'],
                                ['id' => 'Paket Makan', 'nama' => 'Paket Makan', 'harga' => 200000, 'desc' => 'Makan pagi, siang, malam'],
                                ['id' => 'Asuransi', 'nama' => 'Asuransi Perjalanan', 'harga' => 150000, 'desc' => 'Proteksi perjalanan lengkap']
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-3">
                                <div class="card addon-card" data-harga="<?php echo e($addon['harga']); ?>">
                                    <div class="card-body text-center">
                                        <h6 class="fw-bold"><?php echo e($addon['nama']); ?></h6>
                                        <small class="text-muted d-block"><?php echo e($addon['desc']); ?></small>
                                        <div class="mt-2 fw-bold text-primary">Rp <?php echo e(number_format($addon['harga'],0,',','.')); ?></div>
                                        
                                        <!-- Checkbox untuk toggle -->
                                        <input type="checkbox" name="expenses[<?php echo e($i+1); ?>][kategori]" 
                                            value="<?php echo e($addon['nama']); ?>" 
                                            class="addon-check d-none">

                                        <!-- Hidden input, default disabled -->
                                        <input type="hidden" name="expenses[<?php echo e($i+1); ?>][deskripsi]" value="<?php echo e($addon['nama']); ?>" disabled>
                                        <input type="hidden" name="expenses[<?php echo e($i+1); ?>][jumlah]" value="<?php echo e($addon['harga']); ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="mt-3 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                            <button type="button" class="btn btn-primary next-step">Lanjut</button>
                        </div>
                    </div>

                    <!-- Step 5: Preview -->
                    <div class="form-step" id="step-5">
                        <div class="form-group mb-2">
                            <label for="" class="form-label">Pilih Metode Pembayaran</label>
                            <select name="digital_payment" id="" class="form-control">
                                <option value="">- - Pilih Metode Pembayaran - -</option>
                                <option value="digital_payment">Digital Payment</option>
                                <option value="reimbrusment">Reimbursement</option>
                            </select>
                        </div>
                        <h5 class="mb-3">Preview SPPD</h5>
                        <div id="previewContent" class="border rounded p-3 bg-light"></div>
                        
                        <div class="mt-3 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function(){
    let currentStep = 1;
    const steps = $(".form-step");
    const stepper = $(".stepper .step");

    function showStep(step){
        steps.removeClass("active").eq(step-1).addClass("active");
        stepper.removeClass("active").eq(step-1).addClass("active");

        console.log("Current step:", step); // debug tampil step aktif

        // jika sudah step 5, tampilkan preview
        if (step === 5) {
            let tujuan = $("input[name='tujuan']").val();
            let lokasi = $("input[name='lokasi_tujuan']").val();
            let tglB = $("input[name='tanggal_berangkat']").val();
            let tglP = $("input[name='tanggal_pulang']").val();
            let trans = $("#transportasi").val();
            let biaya = parseInt($("#biaya_estimasi").val() || 0); // harga sekali jalan
            let hotel = $("#hotel_nama").val();
            let hotelHarga = parseInt($("#hotel_harga").val() || 0);

            // hitung jumlah hari perjalanan
            let startDate = new Date(tglB);
            let endDate = new Date(tglP);
            let timeDiff = endDate - startDate;
            let totalHari = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)) || 1;

            // hotel total
            let totalHotel = hotelHarga * totalHari;

            // transport PP
            let totalTransport = biaya * 2;

            // addons
            let addons = [];
            let totalAddon = 0;
            $(".addon-check:checked").each(function () {
                let addonName = $(this).val();
                let addonPrice = parseInt($(this).closest(".addon-card").data("harga") || 0);
                let addonTotal = addonPrice * totalHari;

                addons.push({
                    name: addonName,
                    price: addonPrice,
                    total: addonTotal
                });

                totalAddon += addonTotal;
            });

            // total keseluruhan
            let totalKeseluruhan = totalTransport + totalHotel + totalAddon;

            // tampilkan dengan gaya modern
            $("#previewContent").html(`
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white">
                <h6 class="mb-0">📋 Preview SPPD</h6>
                </div>
                <div class="card-body p-4">
                <div class="mb-3">
                    <h6 class="fw-bold text-secondary">Tujuan</h6>
                    <p class="mb-1 fs-5">${tujuan} (${lokasi})</p>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold text-secondary">Tanggal</h6>
                    <p class="mb-1">${tglB} s/d ${tglP} 
                    <span class="badge bg-light text-dark border ms-2">${totalHari} hari</span>
                    </p>
                </div>

                <h6 class="fw-bold text-secondary mb-2">Rincian Biaya</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                    <span>✈️ Transportasi (PP) - ${trans || '-'}</span>
                    <span class="fw-semibold">Rp ${totalTransport.toLocaleString()}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                    <span>🏨 Hotel - ${hotel || '-'}</span>
                    <span class="fw-semibold">Rp ${hotelHarga.toLocaleString()} × ${totalHari} hari = Rp ${totalHotel.toLocaleString()}</span>
                    </li>
                    <li class="list-group-item">
                        <div class="fw-bold mb-1">➕ Addons</div>
                            <ul class="list-unstyled mb-0">
                                ${
                                addons.length > 0 
                                    ? addons.map(a => `
                                    <li class="d-flex justify-content-between">
                                        <span>${a.name}</span>
                                        <span class="fw-semibold">Rp ${a.price.toLocaleString()} × ${totalHari} hari = Rp ${a.total.toLocaleString()}</span>
                                    </li>
                                    `).join("")
                                    : "<li class='text-muted'>Tidak ada addons</li>"
                                }
                            </ul>
                        </li>
                </ul>

                <div class="mt-4 p-3 bg-light rounded text-end">
                    <h6 class="fw-bold text-primary">Total Keseluruhan</h6>
                    <h4 class="fw-bolder text-success">Rp ${totalKeseluruhan.toLocaleString()}</h4>
                </div>
                </div>
            </div>
            `);
        }


    }

    // tombol next
    $(".next-step").click(function(){
        if(currentStep < steps.length){
            currentStep++;
            showStep(currentStep);
        }
    });

    // tombol prev
    $(".prev-step").click(function(){
        if(currentStep > 1){
            currentStep--;
            showStep(currentStep);
        }
    });

    // Pesawat pilih
    $('.pilih-pesawat').click(function(){
        let card = $(this).closest('.flight-card');
        let nama = card.data('nama');
        let harga = card.data('harga');
        $('#transportasi').val(nama);
        $('#biaya_estimasi').val(harga);
        $('#pesawat-preview').removeClass('d-none').html(
            `<b>Pesawat dipilih:</b> ${nama} <br> Harga: Rp ${harga.toLocaleString()}`
        );
    });

    // Hotel pilih
    $('.pilih-hotel').click(function(){
        let card = $(this).closest('.hotel-card');
        let nama = card.data('nama');
        let harga = card.data('harga');
        $('#hotel_nama').val(nama);
        $('#hotel_harga').val(harga);
        $('#hotel-preview').removeClass('d-none').html(
            `<b>Hotel dipilih:</b> ${nama} <br> Harga: Rp ${harga.toLocaleString()}/mlm`
        );
    });

    // Addon toggle
    $(document).on("click", ".addon-card", function(){
        let checkbox = $(this).find(".addon-check");
        let hiddenInputs = $(this).find("input[type=hidden]");

        // toggle checkbox
        checkbox.prop("checked", !checkbox.prop("checked"));
        $(this).toggleClass("selected", checkbox.prop("checked"));

        // enable/disable hidden inputs
        hiddenInputs.prop("disabled", !checkbox.prop("checked"));
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['title' => 'Buat SPPD'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/sppd/create.blade.php ENDPATH**/ ?>