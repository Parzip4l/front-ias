@extends('layouts.vertical', ['title' => 'Apex Bar Chart'])

@section('content')
    @include('layouts.partials.page-title', ['subtitle' => 'Apex', 'title' => 'Bar Charts'])
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Basic Bar Chart</h4>
                    <div dir="ltr">
                        <div id="basic-bar" class="apex-charts" data-colors="#39afd1"></div>
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
                    <h4 class="header-title">Grouped Bar Chart</h4>
                    <div dir="ltr">
                        <div id="grouped-bar" class="apex-charts" data-colors="#fa5c7c,#35b8e0"></div>
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
                    <h4 class="header-title">Stacked Bar Chart</h4>
                    <div dir="ltr">
                        <div id="stacked-bar" class="apex-charts" data-colors="#6b5eae,#31ce77,#fa5c7c,#35b8e0,#39afd1">
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
                    <h4 class="header-title">100% Stacked Bar Chart</h4>
                    <div dir="ltr">
                        <div id="full-stacked-bar" class="apex-charts"
                            data-colors="#fbcc5c,#39afd1,#35b8e0,#e3eaef,#6b5eae"></div>
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
                    <h4 class="header-title">Bar with Negative Values</h4>
                    <div dir="ltr">
                        <div id="negative-bar" class="apex-charts" data-colors="#fa5c7c,#31ce77"></div>
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
                    <h4 class="header-title">Reversed Bar Chart</h4>
                    <div dir="ltr">
                        <div id="reversed-bar" class="apex-charts" data-colors="#6b5eae,#31ce77"></div>
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
                    <h4 class="header-title">Bar with Image Fill</h4>
                    <div dir="ltr">
                        <div id="image-fill-bar" class="apex-charts" data-colors="#6b5eae,#31ce77,#e3eaef"></div>
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
                    <h4 class="header-title">Custom DataLabels Bar</h4>
                    <div dir="ltr">
                        <div id="datalables-bar" class="apex-charts"
                            data-colors="#6b5eae,#31ce77,#fa5c7c,#35b8e0,#39afd1,#2b908f,#fbcc5c,#90ee7e,#f48024,#212730">
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
                    <h4 class="header-title">Patterned Bar Chart</h4>
                    <div dir="ltr">
                        <div id="pattern-bar" class="apex-charts" data-colors="#6b5eae,#31ce77,#fa5c7c,#39afd1"></div>
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
                    <h4 class="header-title">Bar with Markers</h4>
                    <div dir="ltr">
                        <div id="bar-markers" class="apex-charts" data-colors="#31ce77,#fa5c7c"></div>
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
    <script src="https://apexcharts.com/samples/assets/irregular-data-series.js"></script>

    @vite(['resources/js/components/chart-apex-bar.js'])
@endsection
