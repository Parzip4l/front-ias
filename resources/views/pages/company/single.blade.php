@extends('layouts.vertical', ['title' => 'Detail Perusahaan'])

@section('content')
  @include('layouts.partials.page-title', [
      'title' => 'Perusahaan',
      'subtitle' => 'Detail Perusahaan'
  ])

  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        
        <!-- Header dengan background -->
        <div class="bg-primary p-4 text-white d-flex align-items-center">
          <div class="rounded-circle bg-white text-primary fw-bold d-flex align-items-center justify-content-center me-3" style="width:60px;height:60px;font-size:24px;">
            {{ strtoupper(substr($company['name'],0,1)) }}
          </div>
          <div>
            <h4 class="mb-0">{{ $company['name'] ?? '-' }}</h4>
            <b><small class="opacity-75">Customer ID: {{ $company['customer_id'] ?? '-' }}</small></b>
          </div>
          <div class="ms-auto">
            <a href="{{ route('company.edit', $company['id']) }}" class="btn btn-warning btn-sm me-2">
              <i class="bi bi-pencil-square"></i> Edit
            </a>
            <a href="{{ route('company.index') }}" class="btn btn-light btn-sm">
              <i class="bi bi-arrow-left"></i> Kembali
            </a>
          </div>
        </div>

        <!-- Body -->
        <div class="card-body p-4">
          <div class="row g-4">
            <!-- Informasi Kontak -->
            <div class="col-md-6">
                <h6 class="text-uppercase text-muted fw-bold mb-3">Informasi Kontak</h6>
            <ul class="list-unstyled mb-0">
                <li class="mb-3">
                <small class="text-muted d-block">Email</small>
                <i class="bi bi-envelope text-primary me-2"></i>
                <span class="fw-semibold">{{ $company['email'] ?? '-' }}</span>
                </li>
                <li class="mb-3">
                <small class="text-muted d-block">No. Telepon</small>
                <i class="bi bi-telephone text-primary me-2"></i>
                <span class="fw-semibold">{{ $company['phone'] ?? '-' }}</span>
                </li>
                <li class="mb-3">
                <small class="text-muted d-block">Alamat</small>
                <i class="bi bi-geo-alt text-primary me-2"></i>
                <span class="fw-semibold">{{ $company['address'] ?? '-' }}</span>
                </li>
                <li>
                <small class="text-muted d-block">Kode Pos</small>
                <i class="bi bi-mailbox text-primary me-2"></i>
                <span class="fw-semibold">{{ $company['zipcode'] ?? '-' }}</span>
                </li>
            </ul>
            </div>

            <!-- Informasi Perusahaan -->
            <div class="col-md-6">
            <h6 class="text-uppercase text-muted fw-bold mb-3">Detail Perusahaan</h6>
            <ul class="list-unstyled mb-0">
                <li class="mb-3">
                <small class="text-muted d-block">Jenis Perusahaan</small>
                <i class="bi bi-building text-primary me-2"></i>
                <span class="fw-semibold">
                    @php
                    $type = array_values(array_filter($types, function($t) use ($company) {
                        return $t['id'] == ($company['company_type_id'] ?? null);
                    }));
                    @endphp
                    {{ $type[0]['name'] ?? '-' }}
                </span>
                </li>
                <li class="mb-3">
                <small class="text-muted d-block">Nomor NPWP</small>
                <i class="bi bi-receipt text-primary me-2"></i>
                <span class="fw-semibold">{{ $company['npwp_number'] ?? '-' }}</span>
                </li>
                <li class="mb-3">
                <small class="text-muted d-block">Status PKP</small>
                <i class="bi bi-clipboard-check text-primary me-2"></i>
                @if($company['is_pkp'] == 1)
                    <span class="badge bg-success">PKP</span>
                @else
                    <span class="badge bg-secondary">Non PKP</span>
                @endif
                </li>
                <li>
                <small class="text-muted d-block">Status Aktif</small>
                <i class="bi bi-toggle-on text-primary me-2"></i>
                @if($company['is_active'] == 1)
                    <span class="badge bg-primary">Aktif</span>
                @else
                    <span class="badge bg-danger">Tidak Aktif</span>
                @endif
                </li>
            </ul>
            </div>

          </div>

          <!-- Dokumen -->
          <div class="mt-5">
            <h6 class="text-uppercase text-muted fw-bold mb-3">Dokumen Perusahaan</h6>
            <div class="row g-3">
              <div class="col-md-4">
                <div class="p-3 border rounded-3 text-center h-100">
                  <i class="bi bi-file-earmark-text display-6 text-primary mb-2"></i>
                  <p class="fw-semibold mb-2">NPWP</p>
                  @if(!empty($company['npwp_file']))
                    <a href="{{ config('app.backend_url').'/storage/'.$company['npwp_file'] }}" target="_blank" class="btn btn-outline-primary btn-sm">
                      <i class="bi bi-box-arrow-up-right"></i> Lihat
                    </a>
                  @else
                    <span class="text-muted small">Tidak ada</span>
                  @endif
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-3 border rounded-3 text-center h-100">
                  <i class="bi bi-file-earmark-text display-6 text-primary mb-2"></i>
                  <p class="fw-semibold mb-2">SPPKP</p>
                  @if(!empty($company['sppkp_file']))
                    <a href="{{ config('app.backend_url').'/storage/'.$company['sppkp_file'] }}" target="_blank" class="btn btn-outline-primary btn-sm">
                      <i class="bi bi-box-arrow-up-right"></i> Lihat
                    </a>
                  @else
                    <span class="text-muted small">Tidak ada</span>
                  @endif
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-3 border rounded-3 text-center h-100">
                  <i class="bi bi-file-earmark-text display-6 text-primary mb-2"></i>
                  <p class="fw-semibold mb-2">SKT</p>
                  @if(!empty($company['skt_file']))
                    <a href="{{ config('app.backend_url').'/storage/'.$company['skt_file'] }}" target="_blank" class="btn btn-outline-primary btn-sm">
                      <i class="bi bi-box-arrow-up-right"></i> Lihat
                    </a>
                  @else
                    <span class="text-muted small">Tidak ada</span>
                  @endif
                </div>
              </div>
            </div>
          </div>

        </div> <!-- end body -->
      </div>
    </div>
  </div>
@endsection
