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
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Kode/Matakuliah</center>
                            </th>
                            <th>
                                <center>SKS (T/P)</center>
                            </th>
                            <th>
                                <center>Prodi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Dosen</center>
                            </th>
                            <th>
                                <center>Jumlah Pertemuan</center>
                            </th>
                            <th>
                                <center>BAP</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>{{ $key->makul }}</td>
                                <td>
                                    <center>{{ $key->sks }}</center>
                                </td>
                                <td>{{ $key->prodi }}</td>
                                <td>{{ $key->kelas }}</span< /td>
                                <td>{{ $key->nama }}</td>
                                <td>
                                    <center>
                                        {{ $key->jml_per }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a href="cek_rekapan_kprd/{{ $key->id_kurperiode }}" class="btn btn-info btn-xs">Cek
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
