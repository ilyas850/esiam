@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Data Approve Dosen Pembimbing
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>

            <li class="active">Data KRS Mahasiswa pembimbing</li>
        </ol>
    </section>
@endsection
@section('content')
    <section class="content">

        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Approve KRS Dosen Pembimbing</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="form" role="form" action="{{ url('view_krs') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="col-xs-4">
                            <select class="form-control" name="remark">
                                <option>-pilih status-</option>
                                <option value="1">Sudah divalidasi</option>
                                <option value="0">Belum divalidasi</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success ">Tampilkan</button>
                    </form>
                </div>
                <br>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>

                            <th>
                                <center>NIM</center>
                            </th>
                            <th>
                                <center>Nama Mahasiswa</center>
                            </th>
                            <th>
                                <center>Program Studi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Angkatan</center>
                            </th>
                            <th>
                                <center>Dosen Pembimbing</center>
                            </th>
                            <th>
                                <center>Status</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($appr as $app)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>

                                <td>
                                    <center>{{ $app->nim }}</center>
                                </td>
                                <td>{{ $app->nama }}</td>
                                <td>
                                    {{ $app->prodi }}
                                </td>
                                <td>
                                    <center>
                                        {{ $app->kelas }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $app->angkatan }}
                                    </center>
                                <td>
                                    {{ $app->nama_dsn }}
                                </td>
                                <td>
                                    <center>
                                        @if ($app->remark == 1)
                                            <span class="badge bg-green">Valid</span>
                                        @elseif ($app->remark == 0 or $app->remark == null)
                                            <span class="badge bg-yellow">Belum</span>
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($app->remark == 1)
                                            <a class="btn btn-danger btn-xs" href="/batal_krs_admin/{{ $app->id_student }}"
                                                title="Klik untuk batal validasi"><i class="fa fa-close"></i>
                                            </a>
                                        @elseif($app->remark == 0)
                                            <a class="btn btn-success btn-xs"
                                                href="/batal_krs_admin/{{ $app->id_student }}"
                                                title="Klik untuk validasi"><i class="fa fa-check"></i>
                                            </a>
                                        @endif
                                        <a class="btn btn-info btn-xs" href="/cek_krs_admin/{{ $app->id_student }}"
                                            title="Klik untuk cek KRS"><i class="fa fa-eye"></i></a>
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
