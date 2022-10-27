@extends('layouts.master_auth')

@section('content')
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-6 order-md-2">
                    <img src="{{ asset('dsg_login/images/undraw_file_sync_ot38.png') }}" alt="Image" class="img-fluid">
                </div>
                <div class="col-md-6 contents">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h3>Confirm your NIM & date of birth to reset password <strong>eSIAM</strong></h3>
                                <p class="mb-4">Empowering to Industry</p>
                            </div>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group first">
                                    <label>NIM</label>
                                    <input type="text" class="form-control" name="nim" required
                                        autofocus>

                                </div>
                                <div class="form-group last mb-4">
                                    
                                    <input type="date" class="form-control" name="tgllahir" required>

                                </div>

                                <div class="d-flex mb-5 align-items-center">
                                    <span class="ml-auto"><a href="/forgot_password" class="forgot-pass">Forgot Password
                                            ?</a></span>
                                </div>

                                <input type="submit" value="Log In" class="btn text-white btn-block btn-primary">

                                <span class="d-block text-left my-4 text-muted"> <a href="/">Back to
                                        HOME</a></span>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
