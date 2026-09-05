<style>
    .onboarding-card {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border-top: 4px solid #3c8dbc;
        margin-top: 15px;
        margin-bottom: 30px;
        overflow: hidden;
    }

    .onboarding-header {
        padding: 22px 24px 15px;
        border-bottom: 1px solid #f0f0f0;
        background: #fafbfc;
    }

    .onboarding-header h3 {
        margin: 0 0 6px 0;
        font-size: 19px;
        font-weight: 700;
        color: #2c3e50;
    }

    .onboarding-header p {
        margin: 0;
        color: #7f8c8d;
        font-size: 13px;
    }

    .onboarding-body {
        padding: 24px;
    }

    .student-info-badge {
        background: #f4f8fb;
        border: 1px solid #d9edf7;
        border-left: 4px solid #3c8dbc;
        border-radius: 6px;
        padding: 14px 18px;
        margin-bottom: 22px;
    }

    .student-info-badge .info-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 4px;
        font-size: 13px;
    }

    .student-info-badge .info-row:last-child {
        margin-bottom: 0;
    }

    .student-info-badge .info-label {
        font-weight: 600;
        color: #555;
        min-width: 110px;
    }

    .student-info-badge .info-value {
        color: #222;
    }

    .form-group-modern {
        margin-bottom: 18px;
    }

    .form-group-modern label {
        font-weight: 600;
        color: #444;
        font-size: 13px;
        margin-bottom: 7px;
    }

    .input-group-modern .form-control {
        height: 42px;
        font-size: 14px;
        border-color: #d2d6de;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .input-group-modern .form-control:focus {
        border-color: #3c8dbc;
        box-shadow: 0 0 6px rgba(60, 141, 188, 0.25);
    }

    .input-group-modern .input-group-addon {
        background-color: #f9fafc;
        border-color: #d2d6de;
        color: #666;
        min-width: 44px;
    }

    .btn-toggle-pwd {
        cursor: pointer;
        user-select: none;
        -webkit-user-select: none;
        transition: background-color 0.2s, color 0.2s;
    }

    .btn-toggle-pwd:hover {
        background-color: #eef2f7 !important;
        color: #3c8dbc !important;
    }

    .rule-box {
        background: #fbfcfd;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 12px 16px;
        margin-top: 18px;
        margin-bottom: 22px;
    }

    .rule-item {
        font-size: 12.5px;
        color: #8a96a3;
        margin-top: 5px;
        transition: color 0.25s ease;
    }

    .rule-item.valid {
        color: #00a65a;
        font-weight: 600;
    }

    .rule-item.invalid {
        color: #dd4b39;
    }

    .rule-item i {
        width: 18px;
        text-align: center;
        margin-right: 4px;
    }

    .onboarding-footer {
        padding: 16px 24px;
        background: #fafbfc;
        border-top: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    @media (max-width: 767px) {
        .onboarding-body {
            padding: 18px 16px;
        }

        .onboarding-header {
            padding: 18px 16px;
        }

        .onboarding-footer {
            padding: 16px;
            flex-direction: column-reverse;
            gap: 12px;
        }

        .onboarding-footer .btn {
            width: 100%;
            margin: 4px 0 !important;
        }

        .student-info-badge .info-label {
            min-width: 85px;
        }
    }
</style>

<div class="row">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div class="onboarding-card">
            <div class="onboarding-header">
                <div class="pull-right hidden-xs">
                    <span class="label label-primary" style="font-size: 11px; padding: 4px 8px; border-radius: 3px;">
                        <i class="fa fa-shield"></i> Aktivasi Akun
                    </span>
                </div>
                <h3><i class="fa fa-graduation-cap text-primary" style="margin-right: 6px;"></i> Selamat Datang di ESIAM</h3>
                <p>Politeknik META Industri Cikarang &bull; Sistem Informasi Akademik</p>
            </div>

            <div class="onboarding-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible" style="border-radius: 6px;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4 style="font-size: 14px; margin-bottom: 5px;"><i class="icon fa fa-ban"></i> Terjadi Kesalahan!</h4>
                        <ul style="padding-left: 20px; margin-bottom: 0; font-size: 13px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Kartu Identitas Mahasiswa -->
                <div class="student-info-badge">
                    <div class="info-row">
                        <span class="info-label"><i class="fa fa-user" style="width: 16px;"></i> Nama</span>
                        <span class="info-value">: <strong>{{ $mhs->nama ?? Auth::user()->name }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fa fa-id-card-o" style="width: 16px;"></i> NIM</span>
                        <span class="info-value">: <strong>{{ $mhs->nim ?? Auth::user()->username }}</strong></span>
                    </div>
                    @if (!empty($mhs->prodi))
                        <div class="info-row">
                            <span class="info-label"><i class="fa fa-book" style="width: 16px;"></i> Program Studi</span>
                            <span class="info-value">: {{ $mhs->prodi }}</span>
                        </div>
                    @endif
                    <div style="margin-top: 10px; font-size: 12px; color: #31708f; border-top: 1px dashed #d9edf7; padding-top: 8px;">
                        <i class="fa fa-info-circle"></i> Ini adalah login pertama Anda. Demi keamanan akun, silakan ubah password bawaan (NIM) dengan password baru yang aman sebelum melanjutkan ke sistem.
                    </div>
                </div>

                <!-- Form Ganti Password -->
                <form id="form-onboarding-pwd" role="form" method="POST" action="{{ url('pwd/' . Auth::user()->id . '/store') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">

                    <!-- Password Lama -->
                    <div class="form-group form-group-modern {{ $errors->has('oldpassword') ? 'has-error' : '' }}">
                        <label for="oldpassword">
                            Password Lama (Default NIM) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                            <input type="password" 
                                   id="oldpassword" 
                                   name="oldpassword" 
                                   class="form-control" 
                                   placeholder="Masukkan NIM Anda" 
                                   value="{{ old('oldpassword') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="current-password">
                            <span class="input-group-addon btn-toggle-pwd" data-target="oldpassword" title="Lihat/Sembunyikan Password">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                        <small class="text-muted" style="font-size: 11.5px; display: block; margin-top: 4px;">
                            <i class="fa fa-lightbulb-o text-warning"></i> Password bawaan saat pertama dibuat adalah Nomor Induk Mahasiswa (NIM) Anda.
                        </small>
                    </div>

                    <!-- Password Baru -->
                    <div class="form-group form-group-modern {{ $errors->has('password') ? 'has-error' : '' }}">
                        <label for="password">
                            Password Baru <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-control" 
                                   placeholder="Minimal 7 karakter" 
                                   required 
                                   autocomplete="new-password">
                            <span class="input-group-addon btn-toggle-pwd" data-target="password" title="Lihat/Sembunyikan Password">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="form-group form-group-modern">
                        <label for="password_confirmation">
                            Ulangi Password Baru <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-modern">
                            <span class="input-group-addon"><i class="fa fa-check-square-o"></i></span>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="form-control" 
                                   placeholder="Ketik ulang password baru Anda" 
                                   required 
                                   autocomplete="new-password">
                            <span class="input-group-addon btn-toggle-pwd" data-target="password_confirmation" title="Lihat/Sembunyikan Password">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Kotak Syarat Validasi Real-time -->
                    <div class="rule-box">
                        <div style="font-weight: 600; font-size: 12.5px; color: #495057; margin-bottom: 6px;">
                            <i class="fa fa-shield text-primary"></i> Ketentuan Password:
                        </div>
                        <div id="rule-length" class="rule-item">
                            <i class="fa fa-circle-o" id="icon-length"></i> Minimal 7 karakter
                        </div>
                        <div id="rule-match" class="rule-item">
                            <i class="fa fa-circle-o" id="icon-match"></i> Konfirmasi password sesuai
                        </div>
                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="onboarding-footer" style="margin-left: -24px; margin-right: -24px; margin-bottom: -24px; margin-top: 15px;">
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-onboard-form').submit();"
                           class="btn btn-default btn-flat" 
                           style="border-radius: 4px; padding: 9px 18px; font-weight: 500;">
                            <i class="fa fa-sign-out" style="margin-right: 4px;"></i> Keluar
                        </a>

                        <button type="submit" 
                                id="btn-submit-pwd" 
                                class="btn btn-primary btn-flat" 
                                style="border-radius: 4px; padding: 9px 24px; font-weight: 600; background-color: #3c8dbc; border-color: #367fa9;">
                            <i class="fa fa-check-circle" style="margin-right: 4px;"></i> Simpan & Aktifkan Akun
                        </button>
                    </div>
                </form>

                <form id="logout-onboard-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        function initOnboarding() {
            if (typeof jQuery === 'undefined') return;
            jQuery(function ($) {
                // Auto collapse sidebar AdminLTE agar tampilan fokus
                $('body').addClass('sidebar-collapse');

                // Toggle Password Visibility
                $('.btn-toggle-pwd').on('click', function (e) {
                    e.preventDefault();
                    var targetId = $(this).data('target');
                    var input = $('#' + targetId);
                    var icon = $(this).find('i');
                    if (input.attr('type') === 'password') {
                        input.attr('type', 'text');
                        icon.removeClass('fa-eye').addClass('fa-eye-slash text-primary');
                    } else {
                        input.attr('type', 'password');
                        icon.removeClass('fa-eye-slash text-primary').addClass('fa-eye');
                    }
                });

                // Validasi Password Real-time
                function validatePwd() {
                    var pwd = $('#password').val() || '';
                    var confirm = $('#password_confirmation').val() || '';

                    // Rule 1: Minimal 7 Karakter
                    if (pwd.length >= 7) {
                        $('#rule-length').addClass('valid').removeClass('invalid');
                        $('#icon-length').removeClass('fa-circle-o fa-times-circle').addClass('fa-check-circle');
                    } else if (pwd.length > 0) {
                        $('#rule-length').addClass('invalid').removeClass('valid');
                        $('#icon-length').removeClass('fa-circle-o fa-check-circle').addClass('fa-times-circle');
                    } else {
                        $('#rule-length').removeClass('valid invalid');
                        $('#icon-length').removeClass('fa-check-circle fa-times-circle').addClass('fa-circle-o');
                    }

                    // Rule 2: Konfirmasi Cocok
                    if (confirm.length > 0 && pwd === confirm) {
                        $('#rule-match').addClass('valid').removeClass('invalid');
                        $('#icon-match').removeClass('fa-circle-o fa-times-circle').addClass('fa-check-circle');
                    } else if (confirm.length > 0) {
                        $('#rule-match').addClass('invalid').removeClass('valid');
                        $('#icon-match').removeClass('fa-circle-o fa-check-circle').addClass('fa-times-circle');
                    } else {
                        $('#rule-match').removeClass('valid invalid');
                        $('#icon-match').removeClass('fa-check-circle fa-times-circle').addClass('fa-circle-o');
                    }
                }

                $('#password, #password_confirmation').on('input keyup change', validatePwd);

                // Prevent double submit and validate before submit
                $('#form-onboarding-pwd').on('submit', function () {
                    var pwd = $('#password').val();
                    var confirm = $('#password_confirmation').val();

                    if (pwd.length < 7) {
                        alert('Password baru minimal 7 karakter!');
                        $('#password').focus();
                        return false;
                    }

                    if (pwd !== confirm) {
                        alert('Konfirmasi password tidak cocok!');
                        $('#password_confirmation').focus();
                        return false;
                    }

                    $('#btn-submit-pwd').prop('disabled', true).html('<i class="fa fa-spinner fa-spin" style="margin-right: 4px;"></i> Menyimpan...');
                });
            });
        }

        if (document.readyState === 'complete') {
            initOnboarding();
        } else {
            window.addEventListener('load', initOnboarding);
        }
    })();
</script>
