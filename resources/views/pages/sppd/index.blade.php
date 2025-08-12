@extends('layouts.vertical', ['title' => 'List SPPD'])

@section('css')
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
@endsection

@section('content')
    @include('layouts.partials.page-title', ['subtitle' => 'SPPD', 'title' => 'List SPPD'])

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="d-flex card-header justify-content-between align-items-center border-bottom border-dashed">
                    <h4 class="header-title">List SPPD</h4>
                    @if(session('user.role') == 'admin')
                        <a href="{{ route('sppd.create') }}" class="btn btn-sm btn-primary">Buat SPPD <i class="ti ti-plus ms-1"></i></a>
                    @endif
                </div>

                <div class="card-body">
                    <div id="table-gridjs"></div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
@endsection

@section('scripts')
    @vite(['resources/js/components/table-gridjs.js'])
@endsection
