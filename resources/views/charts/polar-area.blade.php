@extends('layouts.vertical', ['title' => 'Apex Polar Area Chart'])

@section('content')
    @include('layouts.partials.page-title', ['subtitle' => 'Apex', 'title' => 'Polar Area Charts'])
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Basic Polar Area Chart</h4>
                    <div dir="ltr">
                        <div id="basic-polar-area" class="apex-charts"
                            data-colors="#6b5eae,#35b8e0,#31ce77,#fa5c7c,#fbcc5c,#39afd1"></div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Monochrome Polar Area</h4>
                    <div dir="ltr">
                        <div id="monochrome-polar-area" class="apex-charts" data-colors="#35b8e0"></div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
    </div>
    <!-- end row-->
@endsection

@section('scripts')
    @vite(['resources/js/components/chart-apex-polar-area.js'])
@endsection
