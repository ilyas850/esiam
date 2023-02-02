@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Berita Acara Perkuliahan
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('makul_diampu') }}"> Data Matakuliah yang diampu</a></li>
            <li class="active">BAP</li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        <div class="box box-info">
            <div class="box-header with-border">
                <table width="100%">
                    <tr>
                        <td>Matakuliah</td>
                        <td>:</td>
                        <td>{{ $bap->makul }}</td>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $bap->prodi }}</td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $bap->kelas }}</td>
                        <td>Semester</td>
                        <td>:</td>
                        <td>{{ $bap->semester }}</td>
                    </tr>
                </table>
            </div>

            <div class="box-body">
                <a href="/input_bap_dsn/{{ $bap->id_kurperiode }}" class="btn btn-success">Input BAP</a>
                <a href="/sum_absen_dsn/{{ $bap->id_kurperiode }}" class="btn btn-info">Absensi Perkuliahan</a>
                <a href="/jurnal_bap_dsn/{{ $bap->id_kurperiode }}" class="btn btn-warning">Jurnal Perkuliahan</a>
                <br><br>
                <table id="example6" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2">
                                <center>Pertemuan</center>
                            </th>
                            <th rowspan="2">
                                <center>Tanggal</center>
                            </th>
                            <th rowspan="2">
                                <center>Jam</center>
                            </th>
                            <th rowspan="2">
                                <center>Materi Kuliah</center>
                            </th>
                            <th colspan="3">
                                <center>Kuliah</center>
                            </th>
                            <th colspan="2">
                                <center>Absen Mahasiswa</center>
                            </th>
                            <th rowspan="2">
                                <center>Absen</center>
                            </th>
                            <th rowspan="2">
                                <center>Action</center>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <center>Tipe</center>
                            </th>
                            <th>
                                <center>Jenis</center>
                            </th>
                            <th>
                                <center>Metode</center>
                            </th>
                            <th>
                                <center>Hadir</center>
                            </th>
                            <th>
                                <center>Tidak</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>
                                    <center>Ke-{{ $item->pertemuan }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->tanggal }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->jam_mulai }} - {{ $item->jam_selsai }}</center>
                                </td>
                                <td>{{ $item->materi_kuliah }}</td>
                                <td>
                                    <center>{{ $item->tipe_kuliah }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->jenis_kuliah }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->metode_kuliah }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->hadir }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->tidak_hadir }}</center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->hadir != null && $item->tidak_hadir != null)
                                            <a href="/edit_absen_dsn/{{ $item->id_bap }}" class="btn btn-success btn-xs">
                                                Edit</a>
                                        @elseif ($item->hadir == null && $item->tidak_hadir == null)
                                            <a href="/entri_absen_dsn/{{ $item->id_bap }}"
                                                class="btn btn-warning btn-xs">
                                                Entri</a>
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a href="/view_bap_dsn/{{ $item->id_bap }}" class="btn btn-info btn-xs"
                                            title="klik untuk lihat"> <i class="fa fa-eye"></i></a>
                                        @if ($item->payroll_check == '2001-01-01' or $item->tanggal_validasi == null)
                                            <a href="/edit_bap_dsn/{{ $item->id_bap }}" class="btn btn-success btn-xs"
                                                title="klik untuk edit"> <i class="fa fa-edit"></i></a>
                                            <a href="/delete_bap_dsn/{{ $item->id_bap }}" class="btn btn-danger btn-xs"
                                                title="klik untuk hapus"> <i class="fa fa-trash"></i></a>
                                        @else
                                            <span class="badge bg-yellow">Valid</span>
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
