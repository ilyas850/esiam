@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Total Mahasiswa Per Matakuliah <b> {{ $thn_aktif->periode_tahun }} -
                        {{ $tp_aktif->periode_tipe }}</b></h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Matakuliah</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Prodi</center>
                            </th>
                            <th>
                                <center>Aktual SKS</center>
                            </th>
                            <th>
                                <center>Non Aktual SKS</center>
                            </th>
                            <th>
                                <center>Jml Mhs</center>
                            </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td>{{ $item->makul }}</td>
                                <td align="center">{{ $item->kelas }}</td>
                                <td align="center">{{ $item->prodi }}</td>
                                <td align="center">{{ $item->sks_aktual }}</td>
                                <td align="center">{{ $item->sks_non }}</td>
                                <td align="center">{{ $item->jml_mhs }}</td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
