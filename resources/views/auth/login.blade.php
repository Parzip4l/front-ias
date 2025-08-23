@extends('layouts.base', ['title' => 'Log In'])

@section('body-attribuet')
class="h-100"
@endsection

@section('content')
<div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
    <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
        <div class="col-xl-4 col-lg-5 col-md-6">
            <div class="card overflow-hidden text-center h-100 p-xxl-4 p-3 mb-0">
                <a href="" class="auth-brand">
                    <img src="/images/ias.png" alt="dark logo" height="120" class="logo-dark">
                    <img src="/images/ias.png" alt="logo light" height="120" class="logo-light">
                </a>

                <h4 class="fw-semibold mb-2 fs-18">Log in to your account</h4>

                <p class="text-muted mb-4">Enter your email address and password to access admin panel.</p>

                <form method="POST" action="{{ route('login.post') }}" class="text-start mb-3">
                    @csrf
                    @if (session('success'))
                        <p class="text-success mb-3">{{ session('success') }}</p>
                    @endif
                    @if (sizeof($errors) > 0)
                    @foreach ($errors->all() as $error)
                    <p class="text-danger mb-3">{{ $error }}</p>
                    @endforeach
                    @endif

                    <div class="mb-3">
                        <label class="form-label" for="example-email">Email</label>
                        <input type="email" id="example-email" name="email" value="" class="form-control"
                            placeholder="Enter your email">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="example-password">Password</label>
                        <input type="password" id="example-password" name="password" value="" class="form-control"
                            placeholder="Enter your password">
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="show-password" onclick="togglePassword()">
                            <label class="form-check-label" for="show-password">Show Password</label>
                        </div>

                        <a href="{{route('sppd.forgotpass')}}"
                            class="text-muted border-bottom border-dashed">Forget Password</a>
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary fw-semibold" type="submit">Login</button>
                        <div>
                            <span class="text-muted">Belum Punya Akun ?<a href="{{route('register')}}"
                            class="text-muted border-bottom border-dashed"> <b>Register disini</b></a></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePassword() {
        const passwordField = document.getElementById("example-password");
        if (document.getElementById("show-password").checked) {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
</script>
@endsection