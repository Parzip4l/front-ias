<?php $__env->startSection('body-attribuet'); ?>
    class="h-100"
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="auth-bg d-flex min-vh-100 justify-content-center align-items-center">
        <div class="row g-0 justify-content-center w-100 m-xxl-5 px-xxl-4 m-3">
            <div class="col-xl-4 col-lg-5 col-md-6">
                <div class="card overflow-hidden text-center h-100 p-xxl-4 p-3 mb-0">
                    <a href="#" class="auth-brand">
                        <img src="/images/ias.png" alt="dark logo" height="100" class="logo-dark">
                        <img src="/images/ias.png" alt="logo light" height="100" class="logo-light">
                    </a>

                    <h4 class="fw-semibold mb-2 fs-20">Create New Password</h4>

                    <p class="text-muted mb-2">Please create your new password.</p>
                    <form id="resetForm" class="text-start mb-3">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="token" value="<?php echo e($token); ?>">
                        <input type="hidden" name="email" value="<?php echo e($email); ?>">

                        <div class="mb-3">
                            <label class="form-label" for="new-password">Create New Password</label>
                            <div class="input-group">
                                <input type="password" id="new-password" name="password" class="form-control" placeholder="New Password">
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">👁</button>
                            </div>
                            <small id="passwordHelp" class="text-muted"></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="re-password">Reenter New Password</label>
                            <input type="password" id="re-password" name="password_confirmation" class="form-control" placeholder="Reenter Password">
                            <small id="matchHelp" class="text-muted"></small>
                        </div>

                        <div class="mb-2 d-grid">
                            <button class="btn btn-primary fw-semibold" type="submit">Create New Password</button>
                        </div>
                    </form>

                    <p class="text-muted fs-14 mb-4">Back To <a href="<?php echo e(route('second', ['auth', 'login'])); ?>"
                            class="fw-semibold text-danger ms-1">Login !</a></p>

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
<?php $__env->startSection('scripts'); ?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('resetForm');
    const passwordInput = document.getElementById('new-password');
    const confirmInput = document.getElementById('re-password');
    const passwordHelp = document.getElementById('passwordHelp');
    const matchHelp = document.getElementById('matchHelp');
    const toggleBtn = document.getElementById('togglePassword');

    // Show/Hide Password
    toggleBtn.addEventListener('click', () => {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        toggleBtn.textContent = type === 'password' ? '👁' : '🙈';
    });

    // Real-time password validation
    passwordInput.addEventListener('input', () => {
        const value = passwordInput.value;
        let messages = [];

        if (!/[A-Z]/.test(value)) messages.push("Uppercase letter");
        if (!/[a-z]/.test(value)) messages.push("Lowercase letter");
        if (!/[0-9]/.test(value)) messages.push("Number");
        if (!/[^A-Za-z0-9]/.test(value)) messages.push("Special character");
        if (value.length < 8) messages.push("Min 8 characters");

        if (messages.length === 0) {
            passwordHelp.textContent = "✅ Strong password";
            passwordHelp.className = "text-success";
        } else {
            passwordHelp.textContent = "❌ Missing: " + messages.join(", ");
            passwordHelp.className = "text-danger";
        }
    });

    // Confirm password match
    confirmInput.addEventListener('input', () => {
        if (confirmInput.value === passwordInput.value) {
            matchHelp.textContent = "✅ Password match";
            matchHelp.className = "text-success";
        } else {
            matchHelp.textContent = "❌ Passwords do not match";
            matchHelp.className = "text-danger";
        }
    });

    // Submit form inline ke API
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const data = {
            token: form.querySelector('input[name="token"]').value,
            email: form.querySelector('input[name="email"]').value,
            password: passwordInput.value,
            password_confirmation: confirmInput.value
        };

        try {
            const res = await fetch("<?php echo e(env('SPPD_API_URL')); ?>/auth/reset-password", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            });

            const result = await res.json();

            if (res.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Password reset successful. Redirecting to login...',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "<?php echo e(route('second', ['auth', 'login'])); ?>";
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message || 'Failed to reset password'
                });
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Request Failed',
                text: err.message
            });
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.base', ['title' => 'Create Password'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Muhamad Sobirin\project\front-ias2\resources\views/auth/createpw.blade.php ENDPATH**/ ?>