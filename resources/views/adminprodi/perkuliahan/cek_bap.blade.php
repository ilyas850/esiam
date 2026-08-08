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
            <li><a href="{{ url('rekap_perkuliahan_prodi') }}"> Rekap perkuliahan</a></li>
            <li class="active">Cek BAP</li>
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
                        <td>{{ $key->makul }}</td>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $key->prodi }}</td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $key->kelas }}</td>
                        <td>Semester</td>
                        <td>:</td>
                        <td>{{ $key->semester }}</td>
                    </tr>
                </table>
            </div>

            <div class="box-body">
                <a href="/rekap_perkuliahan_prodi" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                <br><br>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2" style="white-space: nowrap;">
                                <center>Pertemuan</center>
                            </th>
                            <th colspan="2" style="white-space: nowrap;">
                                <center>Tanggal</center>
                            </th>
                            <th rowspan="2" style="white-space: nowrap;">
                                <center>Jam</center>
                            </th>
                            <th rowspan="2">
                                <center>Kurang Jam</center>
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
                                <center>Validasi</center>
                            </th>
                        </tr>
                        <tr>
                            <th style="white-space: nowrap;">
                                <center>Kuliah</center>
                            </th>
                            <th style="white-space: nowrap;">
                                <center>Aktual</center>
                            </th>
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
                                <td style="white-space: nowrap;">
                                    <center>Ke-{{ $item->pertemuan }}</center>
                                </td>
                                <td style="white-space: nowrap;">
                                    <center>{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') : '-' }}</center>
                                </td>
                                <td style="white-space: nowrap;">
                                    <center>{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') : '-' }}</center>
                                </td>
                                <td style="white-space: nowrap;">
                                    <center>{{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selsai, 0, 5) }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->kurang_jam }}</center>
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
                                        @if ($item->tanggal_validasi != null)
                                            <span class="badge bg-yellow">Valid</span>
                                        @elseif($item->tanggal_validasi == null)
                                            <span class="badge bg-red">Belum</span>
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
