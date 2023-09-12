@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Data Validasi KRS
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>

            <li class="active">Data validasi krs</li>
        </ol>
    </section>
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data validasi KRS mahasiswa <b>{{ $tahun->periode_tahun }} -
                        {{ $tipe->periode_tipe }}</b></h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Nama Mahasiswa</center>
                            </th>
                            <th>
                                <center>NIM</center>
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
                                <center>No. HP</center>
                            </th>
                            <th>
                                <center>
                                    Jumlah KRS
                                </center>
                            </th>
                            <th>
                                <center>Validasi</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($mhs as $key)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>{{ $key->nama }}</td>
                                <td>
                                    <center>{{ $key->nim }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->prodi }}
                                    </center>
                                </td>
                                <td>
                                    <center>{{ $key->kelas }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $key->angkatan }}
                                    </center>
                                </td>
                                <td align="center">
                                    {{ $key->hp }}
                                </td>
                                <td>
                                    <center>
                                        {{ $key->jml_krs }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($key->jml_krs == 0)
                                        @elseif($key->jml_krs > 0)
                                            @if ($key->remark == 1)
                                                {{-- <span class="badge bg-yellow">Sudah divalidasi</span> --}}
                                                <form action="{{ url('batal_krs_validasi') }}" method="post">
                                                    <input type="hidden" name="id_student" value="{{ $key->id_student }}">
                                                    <input type="hidden" name="remark" value="0">
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-danger btn-xs"
                                                        data-toggle="tooltip" data-placement="right">Batal</button>
                                                </form>
                                            @elseif ($key->remark == 0)
                                                <form action="{{ url('krs_validasi') }}" method="post">
                                                    <input type="hidden" name="id_student" value="{{ $key->id_student }}">
                                                    <input type="hidden" name="remark" value="1">
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-success btn-xs"
                                                        data-toggle="tooltip" data-placement="right">Validasi</button>
                                                </form>
                                            @endif
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a class="btn btn-info btn-xs" href="/cek_krs/{{ $key->id_student }}">Cek KRS</a>
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
