

<?php $__env->startSection('css'); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['node_modules/select2/dist/css/select2.min.css']); ?>
    <style>
        .addon-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .addon-card:hover {
            background: #f8f9fa;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }
        .addon-card.selected {
            border: 2px solid #0d6efd;
            background: #e7f1ff;
        }
        .addon-card input[type="checkbox"] {
            display: none;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.partials.page-title', ['subtitle' => 'SPPD', 'title' => 'Ajukan SPPD'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="row">
    <div class="col-lg-12">
        <form id="form-sppd">
            <?php echo csrf_field(); ?>
            <div class="card">
                <div class="card-header border-bottom border-dashed">
                    <h4 class="header-title">Data Perjalanan Dinas</h4>
                </div>
                <div class="card-body row g-3">

                    <!-- Data Pegawai -->
                    <div class="col-md-6">
                        <label class="form-label">Nama Pegawai</label>
                        <input type="text" class="form-control" name="employee_name" value="<?php echo e(session('user.name')); ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jabatan</label>
                        <input type="text" class="form-control" name="position" value="<?php echo e(session('user.position')); ?>" readonly>
                    </div>

                    <!-- Lokasi Tujuan -->
                    <div class="col-md-4">
                        <label class="form-label">Provinsi</label>
                        <select class="form-select select2" id="province" name="province">
                            <option value="">Pilih Provinsi...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kabupaten/Kota</label>
                        <select class="form-select select2" id="city" name="city">
                            <option value="">Pilih Kabupaten/Kota...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kota Tujuan</label>
                        <select class="form-select select2" id="destination" name="destination">
                            <option value="">Pilih Kota Tujuan...</option>
                        </select>
                    </div>

                    <!-- Jadwal -->
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Berangkat</label>
                        <input type="date" class="form-control" name="departure_date" id="departure_date">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Kembali</label>
                        <input type="date" class="form-control" name="return_date" id="return_date">
                    </div>

                    <!-- Hotel -->
                    <div class="col-md-6">
                        <label class="form-label">Hotel</label>
                        <select class="form-select select2" name="hotel" id="hotel">
                            <option value="">Pilih Hotel...</option>
                        </select>
                        <div id="hotel-type" class="mt-1 text-muted fst-italic"></div>
                    </div>

                    <!-- Transportasi -->
                    <div class="col-md-6">
                        <label class="form-label">Transportasi</label>
                        <select class="form-select select2" name="transport" id="transport">
                            <option value="">Pilih...</option>
                            <option value="pesawat">Pesawat</option>
                            <option value="kereta">Kereta</option>
                            <option value="mobil-dinas">Mobil Dinas</option>
                        </select>
                    </div>

                    <!-- Conditional Transport Fields -->
                    <div id="transport-extra" class="row mt-2"></div>

                    <!-- Add-ons -->
                    <div class="col-md-12">
                        <label class="form-label">Tambahan Layanan</label>
                        <div class="row" id="addon-list">
                            <?php $__currentLoopData = [
                                ['id' => 'sewa-kendaraan', 'nama' => 'Sewa Kendaraan', 'harga' => 500000, 'desc' => 'Mobil atau motor selama perjalanan'],
                                ['id' => 'concierge', 'nama' => 'Concierge', 'harga' => 300000, 'desc' => 'Bantuan administrasi & reservasi'],
                                ['id' => 'paket-makan', 'nama' => 'Paket Makan', 'harga' => 200000, 'desc' => 'Makan pagi, siang, malam'],
                                ['id' => 'asuransi', 'nama' => 'Asuransi Perjalanan', 'harga' => 150000, 'desc' => 'Proteksi perjalanan lengkap']
                            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-3">
                                <div class="card addon-card border" data-id="<?php echo e($addon['id']); ?>" data-harga="<?php echo e($addon['harga']); ?>">
                                    <div class="card-body text-center">
                                        <h6 class="fw-bold"><?php echo e($addon['nama']); ?></h6>
                                        <small class="text-muted d-block"><?php echo e($addon['desc']); ?></small>
                                        <div class="mt-2 fw-bold text-primary">Rp <?php echo e(number_format($addon['harga'], 0, ',', '.')); ?> / Hari</div>
                                        <input type="checkbox" name="addons[]" value="<?php echo e($addon['id']); ?>" class="addon-check mt-2">
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Estimasi & Saldo -->
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Estimasi Biaya</label>
                        <input type="text" class="form-control" id="estimated_cost" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Saldo Perjalanan</label>
                        <input type="text" class="form-control" value="Rp 5.000.000" readonly>
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-12 mt-3">
                        <button type="button" id="btn-preview" class="btn btn-warning">Preview</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['node_modules/select2/dist/js/select2.min.js']); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
    $('.select2').select2();

    // Lokasi Dummy Data
    const lokasiData = {
        "DKI Jakarta": {
            "Jakarta Selatan": ["Blok M", "Kebayoran Baru", "Pondok Indah"],
            "Jakarta Pusat": ["Gambir", "Menteng", "Tanah Abang"]
        },
        "Jawa Barat": {
            "Bandung": ["Dago", "Cihampelas", "Lembang"],
            "Bogor": ["Cibinong", "Sentul", "Puncak"]
        }
    };

    const bandaraData = {
        "Blok M": ["Soekarno-Hatta (CGK)", "Halim Perdanakusuma (HLP)"],
        "Kebayoran Baru": ["Halim Perdanakusuma (HLP)"],
        "Pondok Indah": ["Soekarno-Hatta (CGK)"],
        "Gambir": ["Halim Perdanakusuma (HLP)"],
        "Menteng": ["Soekarno-Hatta (CGK)"],
        "Tanah Abang": ["Soekarno-Hatta (CGK)"],
        "Dago": ["Husein Sastranegara (BDO)"],
        "Cihampelas": ["Husein Sastranegara (BDO)"],
        "Lembang": ["Husein Sastranegara (BDO)"],
        "Cibinong": ["Soekarno-Hatta (CGK)"],
        "Sentul": ["Soekarno-Hatta (CGK)"],
        "Puncak": ["Soekarno-Hatta (CGK)"]
    };

    const stasiunData = {
        "Blok M": ["Stasiun Gambir", "Stasiun Senen"],
        "Kebayoran Baru": ["Stasiun Kebayoran"],
        "Pondok Indah": ["Stasiun Kebayoran"],
        "Gambir": ["Stasiun Gambir"],
        "Menteng": ["Stasiun Cikini"],
        "Tanah Abang": ["Stasiun Tanah Abang"],
        "Dago": ["Stasiun Bandung"],
        "Cihampelas": ["Stasiun Bandung"],
        "Lembang": ["Stasiun Cimahi"],
        "Cibinong": ["Stasiun Cibinong"],
        "Sentul": ["Stasiun Bogor"],
        "Puncak": ["Stasiun Bogor"]
    };

    let selectedDestination = "";

    // Populate Provinsi
    $.each(lokasiData, (prov) => $('#province').append(`<option>${prov}</option>`));

    $('#province').change(function(){
        let prov = $(this).val();
        $('#city').html('<option value="">Pilih Kabupaten/Kota...</option>');
        $('#destination').html('<option value="">Pilih Kota Tujuan...</option>');
        if(lokasiData[prov]){
            $.each(lokasiData[prov], (city) => $('#city').append(`<option>${city}</option>`));
        }
    });

    $('#city').change(function(){
        let prov = $('#province').val();
        let city = $(this).val();
        $('#destination').html('<option value="">Pilih Kota Tujuan...</option>');
        if(lokasiData[prov]?.[city]){
            lokasiData[prov][city].forEach(dest => $('#destination').append(`<option>${dest}</option>`));
        }
    });

    $('#destination').change(function(){
        selectedDestination = $(this).val();

        // Contoh data hotel dengan harga & tipe
        let hotels = [
            { name: "Hotel Santika", harga: 700000, type: "Bintang 3" },
            { name: "Aston", harga: 1000000, type: "Bintang 4" },
            { name: "Ibis Styles", harga: 850000, type: "Bintang 3" },
            { name: "RedDoorz", harga: 400000, type: "Budget" }
        ];

        // Reset option hotel
        $('#hotel').html('<option value="">Pilih Hotel...</option>');

        // Append option hotel dengan data harga dan tipe
        hotels.forEach(h => {
            $('#hotel').append(`<option value="${h.name}" data-harga="${h.harga}" data-type="${h.type}">${h.name} (Rp ${h.harga.toLocaleString('id-ID')} / hari, ${h.type})</option>`);
        });

        // Reset hotel type display jika ada
        $('#hotel-type').text('');
    });


    $('#transport').change(function(){
        let type = $(this).val(), html = '';
        if(type === 'pesawat'){
            html = pesawatFields(selectedDestination);
        } else if(type === 'kereta'){
            html = keretaFields(selectedDestination);
        } else if(type === 'mobil-dinas'){
            html = mobilFields();
        }
        $('#transport-extra').html(html);
        $('.select2').select2();
    });

    // Validasi Tanggal
    $('#departure_date, #return_date').change(function(){
        let dep = $('#departure_date').val(), ret = $('#return_date').val();
        if(dep && ret && ret < dep){
            alert('Tanggal kembali tidak boleh sebelum tanggal berangkat.');
            $('#return_date').val('');
        }
    });

    // Estimasi Biaya (Dummy Logic)
    $('#form-sppd').on('change', 'input, select', function(){
        let base = 500000;
        let transportCost = parseInt($('input[name="ticket_price"]').val()) || 0;
        let addons = $('input[name="addons[]"]:checked').length * 200000;
        $('#estimated_cost').val(`Rp ${(base + transportCost + addons).toLocaleString()}`);
    });

    // Preview
    $('#btn-preview').click(function(){
        let formData = $('#form-sppd').serializeArray();
        let html = '<table class="table table-bordered"><tbody>';
        formData.forEach(item => {
            html += `<tr><th>${item.name}</th><td>${item.value}</td></tr>`;
        });
        html += '</tbody></table>';
        $('#previewContent').html(html);
        $('#previewModal').modal('show');
    });

    // Submit
    $('#form-sppd').submit(function(e){
        e.preventDefault();
        $.post('/api/sppd', $(this).serialize())
        .done(() => {
            alert('SPPD berhasil dibuat!');
            window.location.href = '/sppd';
        })
        .fail(() => {
            alert('Terjadi kesalahan saat membuat SPPD.');
        });
    });

    // Field Generators
    function pesawatFields(dest){
        let bandaraAsal = bandaraData["Gambir"] || []; // ganti sesuai lokasi kantor
        let bandaraTiba = bandaraData[dest] || [];

        let asalHTML = bandaraAsal.map(b => `<option>${b}</option>`).join('');
        let tibaHTML = bandaraTiba.map(b => `<option>${b}</option>`).join('');

        return `
            <!-- Tiket Berangkat -->
            <h6 class="mt-3">Keberangkatan</h6>
            <div class="col-md-4 mt-2">
                <label class="form-label">Bandara Berangkat</label>
                <select class="form-select select2" name="departure_airport">
                    ${asalHTML}
                </select>
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">Bandara Tiba</label>
                <select class="form-select select2" name="arrival_airport">
                    ${tibaHTML}
                </select>
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">Maskapai</label>
                <select class="form-select select2" name="airline">
                    <option>Garuda Indonesia</option>
                    <option>Batik Air</option>
                    <option>Citilink</option>
                    <option>Lion Air</option>
                </select>
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">No. Penerbangan</label>
                <input type="text" class="form-control" name="flight_number">
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">Kelas</label>
                <select class="form-select select2" name="flight_class">
                    <option>Ekonomi</option>
                    <option>Bisnis</option>
                    <option>First Class</option>
                </select>
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">Harga Tiket</label>
                <input type="text" class="form-control rupiah ticket-price" name="ticket_price_depart">
            </div>

            <!-- Tiket Pulang -->
            <h6 class="mt-4">Kepulangan</h6>
            <div class="col-md-4 mt-2">
                <label class="form-label">Bandara Berangkat</label>
                <select class="form-select select2" name="return_departure_airport">
                    ${tibaHTML}
                </select>
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">Bandara Tiba</label>
                <select class="form-select select2" name="return_arrival_airport">
                    ${asalHTML}
                </select>
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">Maskapai</label>
                <select class="form-select select2" name="return_airline">
                    <option>Garuda Indonesia</option>
                    <option>Batik Air</option>
                    <option>Citilink</option>
                    <option>Lion Air</option>
                </select>
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">No. Penerbangan</label>
                <input type="text" class="form-control" name="return_flight_number">
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">Kelas</label>
                <select class="form-select select2" name="return_flight_class">
                    <option>Ekonomi</option>
                    <option>Bisnis</option>
                    <option>First Class</option>
                </select>
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">Harga Tiket</label>
                <input type="text" class="form-control rupiah ticket-price" name="ticket_price_return">
            </div>
        `;
    }


    function keretaFields(dest, origin){
        let stasiunAsalOptions = stasiunData[origin] || [];
        let stasiunTibaOptions = stasiunData[dest] || [];

        let asalHTML = stasiunAsalOptions.map(s => `<option>${s}</option>`).join('');
        let tibaHTML = stasiunTibaOptions.map(s => `<option>${s}</option>`).join('');

        return `
            <!-- Berangkat -->
            <h6 class="mt-3">Keberangkatan</h6>
            <div class="col-md-4 mt-2">
                <label class="form-label">Stasiun Berangkat</label>
                <select class="form-select select2" name="departure_station">
                    ${asalHTML}
                </select>
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">Stasiun Tiba</label>
                <select class="form-select select2" name="arrival_station">
                    ${tibaHTML}
                </select>
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">Nama Kereta</label>
                <select class="form-select select2" name="train_name">
                    <option>Argo Bromo Anggrek</option>
                    <option>Taksaka</option>
                    <option>Turangga</option>
                </select>
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">Kelas</label>
                <select class="form-select select2" name="train_class">
                    <option>Eksekutif</option>
                    <option>Bisnis</option>
                    <option>Ekonomi</option>
                </select>
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">Harga Tiket</label>
                <input type="text" class="form-control rupiah ticket-price" name="ticket_price_depart">
            </div>

            <!-- Pulang -->
            <h6 class="mt-4">Kepulangan</h6>
            <div class="col-md-4 mt-2">
                <label class="form-label">Stasiun Berangkat</label>
                <select class="form-select select2" name="return_departure_station">
                    ${tibaHTML}
                </select>
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">Stasiun Tiba</label>
                <select class="form-select select2" name="return_arrival_station">
                    ${asalHTML}
                </select>
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">Nama Kereta</label>
                <select class="form-select select2" name="return_train_name">
                    <option>Argo Bromo Anggrek</option>
                    <option>Taksaka</option>
                    <option>Turangga</option>
                </select>
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">Kelas</label>
                <select class="form-select select2" name="return_train_class">
                    <option>Eksekutif</option>
                    <option>Bisnis</option>
                    <option>Ekonomi</option>
                </select>
            </div>
            <div class="col-md-4 mt-2">
                <label class="form-label">Harga Tiket</label>
                <input type="text" class="form-control rupiah ticket-price" name="ticket_price_return">
            </div>
        `;
    }

    document.addEventListener("input", function(e){
        if(e.target.classList.contains("rupiah")){
            let value = e.target.value.replace(/[^,\d]/g, "");
            let parts = value.split(",");
            let integerPart = parts[0];
            let decimalPart = parts[1];

            let sisa = integerPart.length % 3;
            let rupiah = integerPart.substr(0, sisa);
            let ribuan = integerPart.substr(sisa).match(/\d{3}/g);

            if(ribuan){
                let separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            rupiah = decimalPart !== undefined ? rupiah + "," + decimalPart : rupiah;
            e.target.value = rupiah ? "Rp " + rupiah : "";
        }
    });

    function mobilFields(){
        return `
            <div class="col-md-3 mt-2">
                <label class="form-label">Plat Nomor</label>
                <input type="text" class="form-control" name="plate_number">
            </div>
            <div class="col-md-3 mt-2">
                <label class="form-label">Jenis Mobil</label>
                <input type="text" class="form-control" name="car_type">
            </div>
            <div class="col-md-3 mt-2">
                <label class="form-label">Jenis BBM</label>
                <select class="form-control select2" name="fuel_type">
                    <option>Pertalite</option>
                    <option>Pertamax</option>
                    <option>Solar</option>
                    <option>Dexlite</option>
                </select>
            </div>
            <div class="col-md-3 mt-2">
                <label class="form-label">Nominal Bensin</label>
                <input type="text" class="form-control" name="fuel_cost">
            </div>
        `;
    }
});
</script>
<script>
$(function(){
    // Klik card untuk pilih/unpilih add-on
    $('#addon-list').on('click', '.addon-card', function(){
        let checkbox = $(this).find('.addon-check');
        checkbox.prop('checked', !checkbox.prop('checked'));
        $(this).toggleClass('selected', checkbox.prop('checked'));
        hitungTotal();
    });

    // Fungsi Hitung Total
    function hitungTotal(){
        let total = 0;

        // Ambil tiket berangkat & pulang (jika ada input .ticket-price)
        $('.ticket-price').each(function(){
            let val = $(this).val().replace(/[^0-9]/g, "");
            total += parseInt(val) || 0;
        });

        // Hitung jumlah hari perjalanan
        let depDate = $('#departure_date').val();
        let retDate = $('#return_date').val();
        let totalHari = 1;
        if(depDate && retDate){
            let dep = new Date(depDate);
            let ret = new Date(retDate);
            let diffTime = ret - dep;
            totalHari = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;
            if(totalHari < 1) totalHari = 1;
        }

        // Hitung biaya hotel per hari
        let hotelSelected = $('#hotel option:selected');
        let hotelHarga = parseInt(hotelSelected.data('harga')) || 0;
        total += hotelHarga * totalHari;

        // Hitung biaya add-on per hari
        $('.addon-check:checked').each(function(){
            let harga = $(this).closest('.addon-card').data('harga');
            total += parseInt(harga) * totalHari;
        });

        // Update estimasi biaya di input
        $('#estimated_cost').val(`Rp ${total.toLocaleString('id-ID')}`);
    }

    // Trigger setiap ada perubahan
    $(document).on('input change', '.ticket-price, .addon-check, #departure_date, #return_date, #hotel', hitungTotal);

});

$('#btn-preview').click(function() {
    let formData = $('#form-sppd').serialize();
    let url = '/preview?' + formData;
    window.open(url, '_blank'); // buka di tab baru
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical', ['title' => 'Buat SPPD'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/pages/sppd/create.blade.php ENDPATH**/ ?>