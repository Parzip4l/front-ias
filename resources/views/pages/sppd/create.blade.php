@extends('layouts.vertical', ['title' => 'Buat SPPD'])

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .stepper {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    .stepper .step {
        flex: 1;
        text-align: center;
        padding: 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
        background: #f1f3f5;
        margin: 0 5px;
        transition: .3s;
    }
    .stepper .step.active {
        background: #0d6efd;
        color: #fff;
    }
    .form-step { display: none; }
    .form-step.active { display: block; }
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
@endsection

@section('content')
@include('layouts.partials.page-title', ['subtitle' => 'SPPD', 'title' => 'Ajukan SPPD'])

<div class="row">
    <div class="col-lg-12">
        <form id="form-sppd">
            @csrf
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
                            <div class="col-md-6">
                                <label class="form-label">Nama Pegawai</label>
                                <input type="text" class="form-control" value="{{ session('user.name') }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jabatan</label>
                                <input type="text" class="form-control" value="{{ session('user.position') }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tujuan</label>
                                <input type="text" class="form-control" name="destination" placeholder="Masukkan kota tujuan">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Berangkat</label>
                                <input type="date" class="form-control" name="departure_date" id="departure_date">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Kembali</label>
                                <input type="date" class="form-control" name="return_date" id="return_date">
                            </div>
                        </div>
                        <div class="mt-3 text-end">
                            <button type="button" class="btn btn-primary next-step">Lanjut</button>
                        </div>
                    </div>

                    <!-- Step 2: Pesawat -->
                    <div class="form-step" id="step-2">
                        <h5 class="mb-3">Pesawat</h5>
                        <div class="flight-list">
                            @foreach([
                                ['maskapai'=>'AirAsia Indonesia','from'=>'CGK','to'=>'SIN','depart'=>'08:30','arrive'=>'11:20','durasi'=>'1j 50m','harga'=>1143000,'bagasi'=>1,'wifi'=>true],
                                ['maskapai'=>'Pelita Air','from'=>'CGK','to'=>'SIN','depart'=>'07:10','arrive'=>'10:00','durasi'=>'1j 50m','harga'=>1195400,'bagasi'=>20,'wifi'=>false],
                                ['maskapai'=>'Citilink','from'=>'CGK','to'=>'SIN','depart'=>'06:20','arrive'=>'09:10','durasi'=>'1j 50m','harga'=>1202600,'bagasi'=>0,'wifi'=>false],
                            ] as $f)
                            <div class="card mb-3 shadow-sm flight-card" data-nama="{{ $f['maskapai'] }}" data-harga="{{ $f['harga'] }}">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold">{{ $f['maskapai'] }}</h6>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">{{ $f['depart'] }} - {{ $f['arrive'] }}</span>
                                            <span class="text-muted">{{ $f['durasi'] }} • {{ $f['from'] }} → {{ $f['to'] }}</span>
                                        </div>
                                        <small>
                                            @if($f['bagasi']>0) 🧳 {{ $f['bagasi'] }}kg @endif
                                            @if($f['wifi']) 📶 WiFi @endif
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-danger">Rp {{ number_format($f['harga'],0,',','.') }}</div>
                                        <button type="button" class="btn btn-sm btn-primary pilih-pesawat mt-2">Pilih</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
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
                            @foreach([
                                ['nama'=>'Hotel Mulia Senayan','lokasi'=>'Jakarta','harga'=>950000,'bintang'=>5,'fasilitas'=>'Kolam renang, Gym, Spa'],
                                ['nama'=>'Ibis Styles','lokasi'=>'Jakarta','harga'=>550000,'bintang'=>3,'fasilitas'=>'Restoran, Meeting Room'],
                                ['nama'=>'RedDoorz Plus','lokasi'=>'Jakarta','harga'=>250000,'bintang'=>2,'fasilitas'=>'AC, WiFi Gratis'],
                            ] as $h)
                            <div class="card mb-3 shadow-sm hotel-card" data-nama="{{ $h['nama'] }}" data-harga="{{ $h['harga'] }}">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold">{{ $h['nama'] }} ⭐{{ $h['bintang'] }}</h6>
                                        <div class="text-muted">{{ $h['lokasi'] }}</div>
                                        <small>{{ $h['fasilitas'] }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-danger">Rp {{ number_format($h['harga'],0,',','.') }}/mlm</div>
                                        <button type="button" class="btn btn-sm btn-primary pilih-hotel mt-2">Pilih</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
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
                            @foreach([
                                ['id' => 'sewa-kendaraan', 'nama' => 'Sewa Kendaraan', 'harga' => 500000, 'desc' => 'Mobil atau motor selama perjalanan'],
                                ['id' => 'concierge', 'nama' => 'Concierge', 'harga' => 300000, 'desc' => 'Bantuan administrasi & reservasi'],
                                ['id' => 'paket-makan', 'nama' => 'Paket Makan', 'harga' => 200000, 'desc' => 'Makan pagi, siang, malam'],
                                ['id' => 'asuransi', 'nama' => 'Asuransi Perjalanan', 'harga' => 150000, 'desc' => 'Proteksi perjalanan lengkap']
                            ] as $addon)
                            <div class="col-md-3">
                                <div class="card addon-card" data-harga="{{ $addon['harga'] }}">
                                    <div class="card-body text-center">
                                        <h6 class="fw-bold">{{ $addon['nama'] }}</h6>
                                        <small class="text-muted d-block">{{ $addon['desc'] }}</small>
                                        <div class="mt-2 fw-bold text-primary">Rp {{ number_format($addon['harga'],0,',','.') }}</div>
                                        <input type="checkbox" name="addons[]" value="{{ $addon['id'] }}" class="addon-check">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-3 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                            <button type="button" class="btn btn-primary next-step">Lanjut</button>
                        </div>
                    </div>

                    <!-- Step 5: Preview -->
                    <div class="form-step" id="step-5">
                        <h5 class="mb-3">Preview</h5>
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
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function(){
    $('.select2').select2();

    let currentStep = 1;
    const steps = $(".form-step");
    const stepper = $(".stepper .step");

    function showStep(step){
        steps.removeClass("active").eq(step-1).addClass("active");
        stepper.removeClass("active").eq(step-1).addClass("active");
    }

    $(".next-step").click(function(){
        if(currentStep < steps.length){ currentStep++; showStep(currentStep); }
    });
    $(".prev-step").click(function(){
        if(currentStep > 1){ currentStep--; showStep(currentStep); }
    });

    // Addon toggle
    $('#addon-list').on('click', '.addon-card', function(){
        let check = $(this).find('.addon-check');
        check.prop('checked', !check.prop('checked'));
        $(this).toggleClass('selected', check.prop('checked'));
    });

    // Preview
    $('#form-sppd').on('submit', function(e){
        e.preventDefault();
        Swal.fire({
            icon: 'success',
            title: 'SPPD Berhasil Dibuat!',
            showConfirmButton: false,
            timer: 1500
        }).then(()=> window.location.href='/sppd');
    });
});

// Pesawat pilih
$('.pilih-pesawat').click(function(){
    let card = $(this).closest('.flight-card');
    let nama = card.data('nama');
    let harga = card.data('harga');
    $('#pesawat-preview').removeClass('d-none').html(
        `<b>Pesawat dipilih:</b> ${nama} <br> Harga: Rp ${harga.toLocaleString()}`
    );
});

// Hotel pilih
$('.pilih-hotel').click(function(){
    let card = $(this).closest('.hotel-card');
    let nama = card.data('nama');
    let harga = card.data('harga');
    $('#hotel-preview').removeClass('d-none').html(
        `<b>Hotel dipilih:</b> ${nama} <br> Harga: Rp ${harga.toLocaleString()}/mlm`
    );
});
</script>
@endsection
