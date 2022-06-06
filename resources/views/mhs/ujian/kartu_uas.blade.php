@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center>
                    <h3>Kartu Ujian Akhir Semester (UAS)</h3>
                </center>
                <table width="100%">
                    <tr>
                        <td width="15%">Nama</td>
                        <td>:</td>
                        <td>{{ $datamhs->nama }}</td>

                        <td width="15%">Tahun Ajaran</td>
                        <td>:</td>
                        <td>{{ $periodetahun }}</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td>{{ $datamhs->nim }}</td>

                        <td>Semester</td>
                        <td>:</td>
                        <td>{{ $periodetipe }}</td>
                    </tr>
                    <tr>
                        <td>Program Studi </td>
                        <td>:</td>
                        <td>{{ $datamhs->prodi }}</td>

                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $datamhs->kelas }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <a class="btn btn-warning" href="{{ url('unduh_kartu_uas') }}">Unduh Kartu UAS</a>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Jadwal Ujian</th>
                            <th>Waktu Ujian</th>
                            <th>Kode</th>
                            <th>Matakuliah</th>
                            <th>Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_uts as $item)
                            <tr>
                                <td>
                                    {{ Carbon\Carbon::parse($item->tanggal_ujian)->formatLocalized('%A, %d %B %Y') }}
                                </td>
                                <td>{{ $item->jam }} - {{ date('H:i', strtotime($item->jam) + 60 * 100) }}</td>
                                <td>{{ $item->kode }}</td>
                                <td>{{ $item->makul }}</td>
                                <td>{{ $item->nama_ruangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
