@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            History Matakuliah yang diampu
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li class="active">History Matakuliah yang diampu</li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">History Matakuliah</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>
                                <center>Matakuliah</center>
                            </th>
                            <th>
                                <center>SKS</center>
                            </th>
                            <th>
                                <center>Program Studi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Semester</center>
                            </th>
                            <th>
                                <center>Tahun Akademik</center>
                            </th>
                            <th align="center">EDOM</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($makul as $item)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>{{ $item->makul }}</td>
                                <td>
                                    <center>{{ $item->sks }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->prodi }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->kelas }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->semester }}</center>
                                </td>
                                <td>{{ $item->periode_tahun }} {{ $item->periode_tipe }}</td>
                                <td align="center">
                                    @if ($item->id_periodetahun == 6 && $item->id_periodetipe == 3)
                                        {{ $item->nilai_edom }}
                                    @elseif($item->id_periodetahun > 6)
                                        {{ $item->nilai_edom }}
                                    @elseif($item->id_periodetahun < 6)
                                        {{ $item->jml }}
                                    @elseif($item->id_periodetahun == 6 && $item->id_periodetipe == 1)
                                        {{ $item->jml }}
                                    @elseif($item->id_periodetahun == 6 && $item->id_periodetipe == 2)
                                        {{ $item->jml }}
                                    @endif
                                </td>
                                <td>
                                    <center>
                                        <a href="cekmhs_dsn_hislr/{{ $item->id_kurperiode }}" class="btn btn-info btn-xs"
                                            title="klik untuk cek mahasiswa"><i class="fa fa-users"></i></a>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <form action="{{ url('export_xlsnilai_dsn') }}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id_kurperiode" value="{{ $item->id_kurperiode }}">
                                            <button type="submit" class="btn btn-success btn-xs"
                                                title="klik untuk export excel"><i class="fa fa-file-excel-o"></i></button>
                                        </form>

                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <form class="" action="{{ url('unduh_pdf_nilai_dsn') }}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id_kurperiode" value="{{ $item->id_kurperiode }}">
                                            <button type="submit" class="btn btn-danger btn-xs"
                                                title="klik untuk unduh ke pdf"><i class="fa fa-file-pdf-o"></i></button>
                                        </form>

                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a href="view_bap_his_dsn/{{ $item->id_kurperiode }}"
                                            class="btn btn-warning btn-xs" title="klik untuk cek BAP"> <i
                                                class="fa fa-file-text"></i></a>

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
