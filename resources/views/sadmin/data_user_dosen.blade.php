@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Data User Dosen
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>

            <li class="active">Data User Dosen</li>
        </ol>
    </section>
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data User Dosen Politeknik META Industri</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">
                                <center>No</center>
                            </th>
                            <th width="35%">
                                <center>Nama Dosen</center>
                            </th>
                            <th>
                                <center>NIK</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($user_dsn as $keydsn)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>{{ $keydsn->nama }}, {{ $keydsn->akademik }}</td>
                                <td>
                                    <center>{{ $keydsn->nik }}</center>
                                </td>
                                <td>
                                    <center>

                                        @if ($keydsn->username == null)
                                            <form action="{{ url('saveuser_dsn') }}" method="post">
                                                <input type="hidden" name="role" value="2">
                                                <input type="hidden" name="id_user" value="{{ $keydsn->iddosen }}">
                                                <input type="hidden" name="username" value="{{ $keydsn->nik }}">
                                                <input type="hidden" name="name" value="{{ $keydsn->nama }}">
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-success btn-xs" data-toggle="tooltip"
                                                    data-placement="right">Generate</button>
                                            </form>
                                        @elseif($keydsn->username != null)
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-warning btn-xs">Pilih</button>
                                                <button type="button" class="btn btn-warning btn-xs dropdown-toggle"
                                                    data-toggle="dropdown">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <form method="POST" action="{{ url('resetuserdsn') }}">
                                                            <input type="hidden" name="password"
                                                                value="{{ $keydsn->username }}">
                                                            <input type="hidden" name="id" value="{{ $keydsn->id }}">
                                                            {{ csrf_field() }}
                                                            <button type="submit" class="btn btn-success btn-block btn-xs"
                                                                data-toggle="tooltip" data-placement="right">Reset</button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="/hapususerdsn/{{ $keydsn->id_user }}"
                                                            method="post">
                                                            <button class="btn btn-danger btn-block btn-xs"
                                                                title="klik untuk hapus" type="submit" name="submit"
                                                                onclick="return confirm('apakah anda yakin akan menghapus user ini?')">Hapus</button>
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="_method" value="DELETE">
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    </center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
