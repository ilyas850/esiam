@extends('layouts.master')

@section('side')
    @include('layouts.side')
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
                <a href="/cek_sum_absen/{{ $bap->id_kurperiode }}" class="btn btn-info">Absensi Perkuliahan</a>
                <a href="/cek_jurnal_bap/{{ $bap->id_kurperiode }}" class="btn btn-warning">Jurnal Perkuliahan</a>
                <br><br>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>

                            <th rowspan="2">
                                <center>Pertemuan</center>
                            </th>
                            <th colspan="2">
                                <center>Tanggal</center>
                            </th>
                            <th rowspan="2">
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
                                <center>Aksi</center>
                            </th>

                        </tr>
                        <tr>
                            <th>
                                <center>Kuliah</center>
                            </th>
                            <th>
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
                                <td>
                                    <center>Ke-{{ $item->pertemuan }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->tanggal->isoFormat('D-M-Y') }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->created_at->isoFormat('D-M-Y') }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->jam_mulai }} - {{ $item->jam_selsai }}</center>
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
                                        <a href="/cek_view_bap/{{ $item->id_bap }}" class="btn btn-info btn-xs"
                                            title="klik untuk lihat BAP"> <i class="fa fa-eye"></i></a>
                                        <a href="/cek_absen_bap/{{ $item->id_bap }}" class="btn btn-warning btn-xs"
                                            title="klik untuk lihat absensi"><i class="fa fa-edit"></i></a>
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
