@extends('layouts.vertical', ['title' => 'Saldo Mitra'])

@section('css')
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
@endsection

@section('content')
    @include('layouts.partials.page-title', [
        'subtitle' => 'Finance',
        'title' => 'Saldo Mitra'
    ])

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="d-flex card-header justify-content-between align-items-center border-bottom border-dashed">
                    <h4 class="header-title">Monitoring Saldo Mitra</h4>
                    @if(session('user.role') == 'admin' || session('user.role') == 'finance')
                        <button id="btn-topup" class="btn btn-sm btn-primary">
                            Top-Up Mitra <i class="ti ti-plus ms-1"></i>
                        </button>
                    @endif
                </div>

                <div class="card-body">
                    <div id="table-saldo-mitra"></div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div>
    </div>

    <!-- Modal Top-Up -->
    <div class="modal fade" id="modalTopUp" tabindex="-1" aria-labelledby="modalTopUpLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-topup" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTopUpLabel">Top-Up Saldo Mitra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Mitra</label>
                        <select name="mitra_id" class="form-control" required></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Top-Up</label>
                        <input type="number" name="amount" class="form-control" min="10000" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/components/saldo-mitra.js'])
@endsection
