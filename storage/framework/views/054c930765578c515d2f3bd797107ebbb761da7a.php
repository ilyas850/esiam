<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    <div class="login-container">
                        <div class="row g-0">
                            <div class="col-md-6 order-md-2">
                                <div class="image-section">
                                    <div class="floating-shapes">
                                        <div class="shape"></div>
                                        <div class="shape"></div>
                                        <div class="shape"></div>
                                    </div>
                                    <div class="logo-section">
                                        <div class="logo-container">
                                            <img src="<?php echo e(asset('dsg_login/images/Logo Meta.png')); ?>" 
                                                 alt="Logo META" 
                                                 class="logo-img"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <i class="fas fa-graduation-cap logo-fallback" style="display: none; font-size: 4rem; margin-bottom: 20px;"></i>
                                        </div>
                                        <h4>Politeknik META Industri</h4>
                                        <p>Excellence in Education</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-section">
                                    <div class="w-100">
                                        <div class="login-header">
                                            <h3>Welcome to <strong>eSIAM</strong></h3>
                                            <p>Empowering to Industry</p>
                                        </div>
                                        
                                        <?php if(session('error')): ?>
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <?php echo e(session('error')); ?>

                                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <form method="POST" action="<?php echo e(route('login')); ?>">
                                            <?php echo csrf_field(); ?>
                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input type="text" 
                                                       class="form-control <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       id="username" 
                                                       name="username"
                                                       value="<?php echo e(old('username')); ?>" 
                                                       required 
                                                       autocomplete="username" 
                                                       autofocus 
                                                       placeholder="Enter your username">
                                                <i class="fas fa-user input-icon"></i>
                                                <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong><?php echo e($message); ?></strong>
                                                    </span>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" 
                                                       class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       id="password" 
                                                       name="password" 
                                                       required 
                                                       autocomplete="current-password" 
                                                       placeholder="Enter your password">
                                                <i class="fas fa-lock input-icon"></i>
                                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong><?php echo e($message); ?></strong>
                                                    </span>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>

                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-login text-white">
                                                    <i class="fas fa-sign-in-alt me-2"></i>
                                                    Log In
                                                </button>
                                            </div>

                                            <div class="back-home">
                                                <a href="/">
                                                    <i class="fas fa-arrow-left me-1"></i>
                                                    Back to HOME
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master_auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/auth/masuk.blade.php ENDPATH**/ ?>