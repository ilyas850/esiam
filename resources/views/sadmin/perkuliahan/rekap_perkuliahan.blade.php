@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Rekap Perkuliahan</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode/Matakuliah</th>
                            <th>SKS</th>
                            <th>Prodi</th>
                            <th>Kelas</th>
                            <th>Dosen</th>
                            <th>Jumlah Pertemuan</th>
                            <th>Perkuliahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td><span style="font-size:95%">
                                        <center>{{ $no++ }}</center>
                                    </span></td>
                                <td><span style="font-size:95%">{{ $key->kode }}/{{ $key->makul }}</span></td>
                                <td><span style="font-size:95%">
                                        <center>{{ $key->akt_sks_teori + $key->akt_sks_praktek }}</center>
                                    </span></td>
                                <td><span style="font-size:95%">{{ $key->prodi }}</span></td>
                                <td><span style="font-size:95%">{{ $key->kelas }}</span< /td>
                                <td><span style="font-size:95%">{{ $key->nama }}</span></td>
                                <td>
                                    <center><span style="font-size:95%">
                                            @foreach ($jml as $keyjml)
                                                @if ($key->id_kurperiode == $keyjml->id_kurperiode)
                                                    {{ $keyjml->jml_per }}
                                                @endif
                                            @endforeach
                                        </span></center>
                                </td>
                                <td>
                                    <center>
                                        <a href="cek_rekapan/{{ $key->id_kurperiode }}" class="btn btn-info btn-xs">Cek
                                            BAP</a>
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
