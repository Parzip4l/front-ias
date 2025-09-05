<?php $__env->startSection('css'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<meta name="sppd-api-url" content="<?php echo e(env('SPPD_API_URL')); ?>">
<style>
    .stepper { display:flex; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; }
    .stepper .step { flex:1; text-align:center; padding:8px; border-radius:20px; font-weight:600; font-size:13px; background:#f1f3f5; margin:3px; transition:.3s; }
    .stepper .step.active { background:#0d6efd; color:#fff; }
    .form-step { display:none; }
    .form-step.active { display:block; }
    .addon-card { border:1px solid #e0e0e0; border-radius:10px; cursor:pointer; transition:all 0.2s; }
    .addon-card:hover { background:#f8f9fa; box-shadow:0 3px 6px rgba(0,0,0,0.1); }
    .addon-card.selected { border:2px solid #0d6efd; background:#e7f1ff; }
    .addon-card input[type="checkbox"] { display:none; }

    /* Flight card responsive */
    .flight-card { border-radius:12px; }
    .flight-card .card-body { flex-wrap:wrap; gap:10px; }
    .flight-card .col-time { min-width:70px; }
    .flight-card .col-duration { min-width:100px; }
    @media(max-width:768px){
        .flight-card .card-body { flex-direction:column; align-items:flex-start; }
        .flight-card .text-end { align-self:flex-end; }
    }
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
                        <input type="hidden" name="transportasi_pergi" id="transportasi_pergi">
                        <input type="hidden" name="biaya_pergi" id="biaya_pergi">
                        <input type="hidden" name="transportasi_pulang" id="transportasi_pulang">
                        <input type="hidden" name="biaya_pulang" id="biaya_pulang">

                        
                        <div class="card mb-3 p-3 shadow-sm">
                            <div class="row g-2">
                                <div class="col-md-5">
                                    <label for="origin" class="form-label">Bandara Asal</label>
                                    <select class="form-control select2" id="origin">
                                        <option value="">Pilih Bandara</option>
                                        <option value="CGK">Jakarta (Soekarno-Hatta)</option>
                                        <option value="HLP">Jakarta (Halim Perdanakusuma)</option>
                                        <option value="SUB">Surabaya (Juanda)</option>
                                        <option value="DPS">Denpasar (Ngurah Rai)</option>
                                        <option value="UPG">Makassar (Sultan Hasanuddin)</option>
                                        <option value="YIA">Yogyakarta (YIA)</option>
                                        <option value="KNO">Medan (Kualanamu)</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label for="destination" class="form-label">Bandara Tujuan</label>
                                    <select class="form-control select2" id="destination">
                                        <option value="">Pilih Bandara</option>
                                        <option value="CGK">Jakarta (Soekarno-Hatta)</option>
                                        <option value="HLP">Jakarta (Halim Perdanakusuma)</option>
                                        <option value="SUB">Surabaya (Juanda)</option>
                                        <option value="DPS">Denpasar (Ngurah Rai)</option>
                                        <option value="UPG">Makassar (Sultan Hasanuddin)</option>
                                        <option value="YIA">Yogyakarta (YIA)</option>
                                        <option value="KNO">Medan (Kualanamu)</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="adults" class="form-label">Penumpang</label>
                                    <input type="number" class="form-control" id="adults" value="1" min="1">
                                </div>
                            </div>
                            <div class="row g-2 mt-2">
                                <div class="col-md-12 d-flex align-items-end">
                                    <button type="button" class="btn btn-primary w-100" id="btnCariPesawat">Cari Penerbangan</button>
                                </div>
                            </div>
                        </div>

                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold">Penerbangan Pergi</h6>
                                <div class="flight-list" id="flightListPergi"></div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Penerbangan Pulang</h6>
                                <div class="flight-list" id="flightListPulang"></div>
                            </div>
                        </div>

                        <div id="pesawat-preview" class="alert alert-info mt-3 d-none"></div>

                        <div class="mt-3 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                            <button type="button" class="btn btn-primary next-step">Lanjut</button>
                        </div>
                    </div>


                    <!-- Step 3: Hotel -->
                    <div class="form-step" id="step-3">
                        <h5 class="mb-3">Pilih Hotel (By Map)</h5>
                        <div id="map" style="height:400px;"></div>

                        <div class="hotel-list mt-3"></div>

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
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="<?php echo e(asset('js/flight.js')); ?>"></script>
<script>
    $(document).ready(function(){
        const BASE_URL = document.querySelector('meta[name="sppd-api-url"]').content;

        var map = L.map('map').setView([-6.2, 106.816666], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var marker = L.marker([-6.2, 106.816666], {draggable:true}).addTo(map);

        function loadHotels(lat, lng) {
            $(".hotel-list").html(`<div class="text-center p-3">Loading hotel...</div>`);

            $.ajax({
                url: BASE_URL + "/hotels/geo",
                method: "GET",
                data: {
                    latitude: lat,
                    longitude: lng,
                    radius: 5,
                    adults: 1,
                    checkIn: "2025-09-10",
                    checkOut: "2025-09-12"
                },
                success: function(res){
                    $(".hotel-list").empty();

                    if(res.data && res.data.length > 0){
                        res.data.forEach(function(item){
                            let hotel = item.hotel;
                            let offer = item.offers[0]; 
                            let harga = offer?.price?.total ?? "0";

                            let card = `
                                <div class="card mb-3 shadow-sm hotel-card" 
                                    data-nama="${hotel.name}" 
                                    data-harga="${harga}">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-bold">${hotel.name}</h6>
                                            <div class="text-muted">${hotel.cityCode}</div>
                                            <small>${hotel.latitude}, ${hotel.longitude}</small>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-danger">Rp ${parseInt(harga).toLocaleString("id-ID")}</div>
                                            <button type="button" class="btn btn-sm btn-primary pilih-hotel mt-2">Pilih</button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $(".hotel-list").append(card);
                        });
                    } else {
                        $(".hotel-list").html(`<div class="alert alert-warning">Hotel tidak ditemukan</div>`);
                    }
                },
                error: function(err){
                    console.error(err);
                    $(".hotel-list").html(`<div class="alert alert-danger">Gagal mengambil data hotel</div>`);
                }
            });
        }

        loadHotels(-6.2, 106.816666);

        marker.on("dragend", function(e){
            var pos = marker.getLatLng();
            loadHotels(pos.lat, pos.lng);
        });

        $(document).on("click", ".pilih-hotel", function(){
            let nama = $(this).closest(".hotel-card").data("nama");
            let harga = $(this).closest(".hotel-card").data("harga");

            $("#hotel_nama").val(nama);
            $("#hotel_harga").val(harga);

            $("#hotel-preview").removeClass("d-none").html(`
                <b>${nama}</b><br>
                Harga: Rp ${parseInt(harga).toLocaleString("id-ID")}
            `);
        });
    });
    </script>



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