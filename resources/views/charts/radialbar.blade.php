@extends('layouts.vertical', ['title' => 'Apex RadialBar Chart'])

@section('content')
    @include('layouts.partials.page-title', ['subtitle' => 'Apex', 'title' => 'RadialBar Charts'])
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-4">Basic RadialBar Chart</h4>
                    <div dir="ltr">
                        <div id="basic-radialbar" class="apex-charts" data-colors="#39afd1"></div>
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
                    <h4 class="header-title mb-4">Multiple RadialBars</h4>
                    <div dir="ltr">
                        <div id="multiple-radialbar" class="apex-charts" data-colors="#35b8e0,#fbcc5c,#6b5eae,#31ce77">
                        </div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
    </div>
    <!-- end row-->

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-4">Circle Chart - Custom Angle</h4>
                    <div class="text-center" dir="ltr">
                        <div id="circle-angle-radial" class="apex-charts" data-colors="#31ce77,#6b5eae,#fa5c7c,#fbcc5c">
                        </div>
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
                    <h4 class="header-title mb-4">Circle Chart with Image</h4>
                    <div dir="ltr">
                        <div id="image-radial" class="apex-charts"></div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
    </div>
    <!-- end row-->

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-4">Stroked Circular Guage</h4>
                    <div dir="ltr">
                        <div id="stroked-guage-radial" class="apex-charts" data-colors="#6b5eae"></div>
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
                    <h4 class="header-title mb-4">Gradient Circular Chart</h4>
                    <div dir="ltr">
                        <div id="gradient-chart" class="apex-charts" data-colors="#8f75da,#6b5eae"></div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
    </div>
    <!-- end row-->

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-4">Semi Circle Gauge</h4>
                    <div dir="ltr">
                        <div id="semi-circle-gauge" class="apex-charts" data-colors="#6b5eae"></div>
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
    @vite(['resources/js/components/chart-apex-radialbar.js'])
@endsection
