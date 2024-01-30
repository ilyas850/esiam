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
                <a href="/input_bap_dsn/{{ $bap->id_kurperiode }}" class="btn btn-success">Input BAP</a>
                <a href="/sum_absen_dsn/{{ $bap->id_kurperiode }}" class="btn btn-info">Absensi Perkuliahan</a>
                <a href="/jurnal_bap_dsn/{{ $bap->id_kurperiode }}" class="btn btn-warning">Jurnal Perkuliahan</a>
                <br><br>
                <table id="example6" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>Pertemuan</center>
                            </th>
                            <th>
                                <center>Tanggal</center>
                            </th>
                            <th>
                                <center>Jam</center>
                            </th>
                            <th>
                                <center>Kurang Jam</center>
                            </th>
                            <th>
                                <center>Aktual Materi Pembelajaran</center>
                            </th>
                            <th>
                                <center>Alasan Pembaharuan Materi</center>
                            </th>
                            <th>
                                <center>Aktual Materi Praktikum</center>
                            </th>
                            <th>
                                <center>Kesesuaian RPS</center>
                            </th>
                            <th>
                                <center>Tipe Kuliah</center>
                            </th>
                            <th>
                                <center>Absensi <br> (Hadir/Tidak)</center>
                            </th>
                            <th>
                                <center>Absen</center>
                            </th>
                            <th>
                                <center>Action</center>
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
                                    <center>{{ Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</center>
                                </td>
                                <td>
                                    <center>
                                        {{ Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}-{{ Carbon\Carbon::parse($item->jam_selsai)->format('H:i') }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ Carbon\Carbon::parse($item->kurang_jam)->format('H:i') }}
                                    </center>
                                </td>
                                <td>{{ $item->materi_kuliah }}</td>
                                <td>{{ $item->alasan_pembaharuan_materi }}</td>
                                <td>{{ $item->praktikum }}</td>
                                <td align="center"><b>
                                        @if ($item->kesesuaian_rps == 'SESUAI')
                                            <span>&#10003;</span>
                                        @elseif($item->kesesuaian_rps == 'TIDAK SESUAI')
                                            <span>&#10007;</span>
                                        @endif
                                    </b>
                                </td>
                                <td>
                                    <center>{{ $item->tipe_kuliah }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->hadir }} / {{ $item->tidak_hadir }}</center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->hadir != null && $item->tidak_hadir != null)
                                            <a href="/edit_absen_dsn/{{ $item->id_bap }}" class="btn btn-success btn-xs">
                                                Edit</a>
                                        @elseif ($item->hadir == null && $item->tidak_hadir == null)
                                            <a href="/entri_absen_dsn/{{ $item->id_bap }}" class="btn btn-warning btn-xs">
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
