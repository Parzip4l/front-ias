<?php $__env->startSection('body-attribuet'); ?>
    class="h-100"
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xl-4 col-lg-5 col-md-6">
                <div class="card overflow-hidden text-center h-100 p-xxl-4 p-3 mb-0">
                    <a href="" class="auth-brand">
                        <img src="/images/ias.png" alt="dark logo" height="100" class="logo-dark">
                        <img src="/images/ias.png" alt="logo light" height="100" class="logo-light">
                    </a>

                    <h4 class="fw-semibold mb-2 fs-18">Reset Your Password</h4>

                    <p class="text-muted mb-4">Please enter your email address to request a password reset.</p>

                    <form id="forgot-password-form" class="text-start mb-3">
                        <div class="mb-3">
                            <label class="form-label" for="example-email">Email</label>
                             <input type="email" id="email" name="email" class="form-control"
            placeholder="Enter your email" required>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary fw-semibold" type="submit">Send Reset Link</button>
                        </div>
                    </form>
                    <div id="message" class="mt-3"></div>
                    <p class="text-muted fs-14 mb-4">Back To <a href="<?php echo e(route('second', ['auth', 'login'])); ?>"
                            class="fw-semibold text-danger ms-1">Login !</a></p>
                </div>
            </div>
        </div>
    </div>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
        document.getElementById('forgot-password-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            let email = document.getElementById('email').value;
            let messageDiv = document.getElementById('message');

            messageDiv.innerHTML = ''; // clear message

            try {
                let response = await fetch("<?php echo e(env('SPPD_API_URL')); ?>/auth/forgot-password", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ email })
                });

                let data = await response.json();

                if (response.ok) {
                    messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                } else {
                    let errorMsg = Object.values(data).join('<br>');
                    messageDiv.innerHTML = `<div class="alert alert-danger">${errorMsg}</div>`;
                }
            } catch (err) {
                messageDiv.innerHTML = `<div class="alert alert-danger">Server error: ${err.message}</div>`;
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', ['title' => 'Reset Password'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/auth/recoverpw.blade.php ENDPATH**/ ?>