@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        @if (Auth::user()->role == 1)
        @elseif (Auth::user()->role == 2)

        @elseif (Auth::user()->role == 3)
        @endif

        @if (Auth::user()->role == 1)
            @include('layouts.admin_home')
        @elseif (Auth::user()->role == 2)
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-widget widget-user">
                        <div class="widget-user-header bg-aqua-active">
                            <h3 class="widget-user-username">{{ Auth::user()->name }}</h3>
                            <h5 class="widget-user-desc">Dosen</h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle" src="/adminlte/img/default.jpg" alt="User Avatar">
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header"></h5>
                                        <span class="description-text"></span>
                                    </div>
                                </div>
                                <div class="col-sm-4 border-right">

                                </div>
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header"></h5>
                                        <span class="description-text"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th style="width:30%">Nama</th>
                                    <td style="width:5%">:</td>
                                    <td>{{ $dsn->nama }}, {{ $dsn->akademik }} </td>
                                </tr>
                                <tr>
                                    <th>NIK</th>
                                    <td>:</td>
                                    <td>{{ Auth::user()->username }}</td>
                                </tr>
                                <tr>
                                    <th>Tempat, tanggal lahir</th>
                                    <td>:</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Agama</th>
                                    <td>:</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Jenis kelamin</th>
                                    <td>:</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>No HP</th>
                                    <td>:</td>
                                    <td>{{ $dsn->hp }}</td>
                                </tr>
                                <tr>
                                    <th>E-Mail</th>
                                    <td>:</td>
                                    <td>{{ $dsn->email }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tahun Akademik</span>
                            <span class="info-box-number">
                                {{ $tahun->periode_tahun }}</span>
                            <span class="info-box-number">{{ $tipe->periode_tipe }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-calendar-check-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jadwal KRS</span>
                            <span class="info-box-number">
                                @if ($time->status == 0)
                                    Jadwal Belum ada
                                @elseif ($time->status == 1)
                                    {{ date(' d-m-Y', strtotime($time->waktu_awal)) }} s/d
                                    {{ date(' d-m-Y', strtotime($time->waktu_akhir)) }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Informasi Terbaru</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <ul class="products-list product-list-in-box">
                                @foreach ($info as $item)
                                    <li class="item">
                                        <div class="product-img">
                                            <img class="img-circle" src="/images/bell.jpg" alt="user">
                                        </div>
                                        <div class="product-info">
                                            <a href="/lihat/{{ $item->id_informasi }}"
                                                class="product-title">{{ $item->judul }}
                                                <span class="label label-info pull-right">
                                                    {{ date('l, d F Y', strtotime($item->created_at)) }}<br>
                                                    {{ $item->created_at->diffForHumans() }}
                                                </span></a>
                                            <span class="product-description">{{ $item->deskripsi }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="box-footer text-center">
                            <a href="/lihat_semua" class="uppercase">Lihat Semua Informasi</a>
                        </div>
                    </div>
                </div>
            </div>
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
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-widget widget-user">
                        <div class="widget-user-header bg-aqua-active">
                            <h3 class="widget-user-username">{{ Auth::user()->name }}</h3>
                            <h5 class="widget-user-desc">Dosen</h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle" src="/adminlte/img/default.jpg" alt="User Avatar">
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header"></h5>
                                        <span class="description-text"></span>
                                    </div>
                                </div>
                                <div class="col-sm-4 border-right">

                                </div>
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header"></h5>
                                        <span class="description-text"></span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <table class="table table-striped">

                            <tbody>
                                <tr>
                                    <th style="width:30%">Nama</th>
                                    <td style="width:5%">:</td>
                                    <td>{{ $dsn->nama }}, {{ $dsn->akademik }} </td>
                                </tr>
                                <tr>
                                    <th>NIK</th>
                                    <td>:</td>
                                    <td>{{ Auth::user()->username }}</td>

                                </tr>
                                <tr>
                                    <th>Tempat, tanggal lahir</th>
                                    <td>:</td>
                                    <td>
                                    </td>

                                </tr>
                                <tr>
                                    <th>Agama</th>
                                    <td>:</td>
                                    <td>
                                    </td>

                                </tr>
                                <tr>
                                    <th>Jenis kelamin</th>
                                    <td>:</td>
                                    <td>
                                    </td>

                                </tr>
                                <tr>
                                    <th>No HP</th>
                                    <td>:</td>
                                    <td>{{ $dsn->hp }}</td>
                                </tr>
                                <tr>
                                    <th>E-Mail</th>
                                    <td>:</td>
                                    <td>{{ $dsn->email }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Tahun Akademik</span>
                            <span class="info-box-number"> {{ $tahun->periode_tahun }}</span>
                            <span class="info-box-number">{{ $tipe->periode_tipe }} </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-calendar-check-o"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Jadwal KRS</span>
                            <span class="info-box-number">
                                @if ($time->status == 0)
                                    Jadwal Belum ada
                                @elseif ($time->status == 1)
                                    {{ date(' d-m-Y', strtotime($time->waktu_awal)) }} s/d
                                    {{ date(' d-m-Y', strtotime($time->waktu_akhir)) }}
                                @endif
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Informasi Terbaru</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <ul class="products-list product-list-in-box">
                                @foreach ($info as $item)
                                    <li class="item">
                                        <div class="product-img">
                                            @if ($item->file != null)
                                                <img class="img-circle"
                                                    src="{{ asset('/data_file/' . $item->file) }}">
                                            @else
                                            @endif

                                        </div>
                                        <div class="product-info">
                                            <a href="/lihat/{{ $item->id_informasi }}"
                                                class="product-title">{{ $item->judul }}
                                                <span class="label label-info pull-right">
                                                    {{ date('l, d F Y', strtotime($item->created_at)) }}<br>
                                                    {{ $item->created_at->diffForHumans() }}
                                                </span></a>
                                            <span class="product-description">
                                                {{ $item->deskripsi }}
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-center">
                            <a href="/lihat_semua" class="uppercase">Lihat Semua Informasi</a>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                </div>
            </div>
        @elseif (Auth::user()->role == 6)
            @include('layouts.kaprodi_home')
        @elseif (Auth::user()->role == 7)
            @include('layouts.wadir1_home')
        @elseif (Auth::user()->role == 11)
            @include('layouts.prausta_home')
        @elseif (Auth::user()->role == 9)
            @include('layouts.adminprodi_home')
        @endif
    </section>
@endsection
