@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Jurnal Perkuliahan
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('makul_diampu_dsn') }}"> Data Matakuliah yang diampu</a></li>
            <li><a href="/entri_bap_dsn/{{ $bap->id_kurperiode }}"> BAP</a></li>
            <li class="active">Jurnal Perkuliahan </li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <table width="100%">
                    <tr>
                        <td>Matakuliah</td>
                        <td>:</td>
                        <td>{{ $bap->makul }} - {{ $bap->akt_sks }} SKS</td>
                        <td>Tahun Akademik</td>
                        <td>:</td>
                        <td>{{ $bap->periode_tahun }} {{ $bap->periode_tipe }}</td>
                    </tr>
                    <tr>
                        <td>Waktu / Ruangan</td>
                        <td>:</td>
                        <td>{{ $bap->hari }},
                            @if ($bap->id_kelas == 1)
                                {{ $bap->jam }} -
                                {{ date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 50 + 60 * $bap->akt_sks_praktek * 120) }}
                            @elseif ($bap->id_kelas == 2)
                                {{ $bap->jam }} -
                                {{ date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 45 + 60 * $bap->akt_sks_praktek * 90) }}
                            @elseif ($bap->id_kelas == 3)
                                {{ $bap->jam }} -
                                {{ date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 45 + 60 * $bap->akt_sks_praktek * 90) }}
                            @endif
                            / {{ $bap->nama_ruangan }}
                        </td>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $bap->prodi }}</td>
                    </tr>
                    <tr>
                        <td>Dosen</td>
                        <td>:</td>
                        <td>{{ $bap->nama }}, {{ $bap->akademik }}</td>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $bap->kelas }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <a href="/print_jurnal_dsn/{{ $bap->id_kurperiode }}" class="btn btn-success" target="_blank">Print</a>
                <a href="/download_jurnal_dsn/{{ $bap->id_kurperiode }}" class="btn btn-info">Download</a>
                <br><br>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Tanggal </center>
                            </th>
                            <th>
                                <center>Jam</center>
                            </th>
                            <th>
                                <center>Materi</center>
                            </th>
                            <th>
                                <center>Paraf Dosen</center>
                            </th>
                            <th>
                                <center>Validasi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->tanggal }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->jam_mulai }} - {{ $item->jam_selsai }}</center>
                                </td>
                                <td>{{ $item->materi_kuliah }}</td>
                                <td>
                                    <center>By System</center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->tanggal_validasi == '2001-01-01')
                                            BELUM
                                        @else
                                            SUDAH
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
