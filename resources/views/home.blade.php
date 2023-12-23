@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        @if (Auth::user()->role == 1)
            @include('layouts.admin_home')
        @elseif (Auth::user()->role == 2)
            @include('layouts.dosen_home')
        @elseif (Auth::user()->role == 3)
            @include('layouts.mhs_home')
        @elseif (Auth::user()->role == 4)
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <span class="fa fa-graduation-cap"></span>
                            <h3 class="box-title">Selamat Datang Mahasiswa Politeknik META Industri Cikarang</h3>
                        </div>
                        <form class="form-horizontal" role="form" method="POST"
                            action="/new_pwd_user/{{ Auth::user()->username }}">
                            {{ csrf_field() }}
                            <input id="role" type="hidden" class="form-control" name="role" value="3">
                            <div class="box-body">
                                <center>
                                    <a class="btn btn-warning" href="pwd/{{ Auth::user()->id }}"
                                        class="btn btn-default btn-flat">Klik disini untuk ganti password !!!</a>
                                </center>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @elseif (Auth::user()->role == 5)
            @include('layouts.dosenluar_home')
        @elseif (Auth::user()->role == 6)
            @include('layouts.kaprodi_home')
        @elseif (Auth::user()->role == 7)
            @include('layouts.wadir1_home')
        @elseif (Auth::user()->role == 8)
            @include('layouts.bauk_home')
        @elseif (Auth::user()->role == 9)
            @include('layouts.adminprodi_home')
        @elseif (Auth::user()->role == 10)
            @include('layouts.wadir3_home')
        @elseif (Auth::user()->role == 11)
            @include('layouts.prausta_home')
        @elseif (Auth::user()->role == 12)
            @include('layouts.gugusmutu_home')
        @endif
    </section>
@endsection
