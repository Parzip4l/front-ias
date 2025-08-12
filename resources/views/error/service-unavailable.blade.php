@extends('layouts.base', ['title' => 'Error 503'])

@section('body-attribuet')
    class="h-100"
@endsection

@section('content')
    <div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xl-4 col-lg-5 col-md-6">
                <div class="card overflow-hidden text-center h-100 p-xxl-4 p-3 mb-0">
                    <a href="{{ route('root') }}" class="auth-brand mb-4">
                        <img src="/images/logo-dark.png" alt="dark logo" height="26" class="logo-dark">
                        <img src="/images/logo.png" alt="logo light" height="26" class="logo-light">
                    </a>

                    <div class="mx-auto text-center">
                        <img src="/images/error/error-503.png" alt="error 503 img" height="230" class="mb-2">
                        <h3 class="fw-bold mt-3 text-primary lh-base">Services Unavailable !</h3>
                        <h4 class="fw-medium mt-2 text-dark lh-base">This site is temporarily down for improvements.</h4>
                        <p class="text-muted mb-3">The server is currently unable to handle the request due to temporary
                            overload or maintenance. Please try again later.</p>
                        <a href="{{ route('root') }}" class="btn btn-primary">Back to Home <i
                                class="ti ti-home ms-1"></i></a>
                    </div>

                    <p class="mt-3 mb-0">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © Greeva - By <span
                            class="fw-bold text-decoration-underline text-uppercase text-reset fs-12">Coderthemes</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
