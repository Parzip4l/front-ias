<?php $__env->startSection('body-attribuet'); ?>
    class="h-100"
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xl-4 col-lg-5 col-md-6">
                <div class="card overflow-hidden text-center h-100 p-xxl-4 p-3 mb-0">
                    <a href="" class="auth-brand">
                        <img src="/images/ias.png" alt="dark logo" height="120" class="logo-dark">
                        <img src="/images/ias.png" alt="logo light" height="120" class="logo-light">
                    </a>

                    <form action="<?php echo e(route('root')); ?>" class="text-start mb-3">
                        <div class="mb-3">
                            <label class="form-label" for="example-name">Your Name</label>
                            <input type="text" id="example-name" name="example-name" class="form-control"
                                placeholder="Enter your name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="example-email">Email</label>
                            <input type="email" id="example-email" name="example-email" class="form-control"
                                placeholder="Enter your email">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="example-password">Password</label>
                            <input type="password" id="example-password" class="form-control"
                                placeholder="Enter your password">
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary fw-semibold" type="submit">Sign Up</button>
                        </div>
                    </form>

                    <p class="text-nuted fs-14 mb-4">Already have an account? <a
                            href="<?php echo e(route('second', ['auth', 'login'])); ?>" class="fw-semibold text-danger ms-1">Login
                            !</a></p>

                    <p class="mt-auto mb-0">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © IAS Travel
                    </p>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.base', ['title' => 'Sign Up'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/auth/register.blade.php ENDPATH**/ ?>