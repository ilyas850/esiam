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
                <h3 class="box-title">Data validasi KRS mahasiswa</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Nama Mahasiswa</center>
                            </th>
                            <th width="10%">
                                <center>NIM</center>
                            </th>
                            <th width="15%">
                                <center>Program Studi</center>
                            </th>
                            <th width="10%">
                                <center>Kelas</center>
                            </th>
                            <th width="10%">
                                <center>Angkatan</center>
                            </th>
                            <th>
                                <center>
                                    Jumlah KRS
                                </center>
                            </th>
                            <th>
                                <center>Status KRS</center>
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
                                    <center>{{ $key->angkatan }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @foreach ($bim as $item)
                                            @if ($key->id_student == $item->id_student)
                                                {{ $item->jml_krs }}
                                            @endif
                                        @endforeach
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @foreach ($bim as $item)
                                            @if ($key->id_student == $item->id_student)
                                                @if ($item->remark == 1)
                                                    <span class="badge bg-yellow">Sudah divalidasi</span>
                                                @elseif ($item->remark == 0)
                                                    <form action="{{ url('krs_validasi') }}" method="post">
                                                        <input type="hidden" name="id_student"
                                                            value="{{ $item->id_student }}">
                                                        <input type="hidden" name="remark" value="1">
                                                        {{ csrf_field() }}
                                                        <button type="submit" class="btn btn-success btn-xs"
                                                            data-toggle="tooltip" data-placement="right">Validasi</button>
                                                    </form>
                                                @endif
                                            @endif
                                        @endforeach
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
