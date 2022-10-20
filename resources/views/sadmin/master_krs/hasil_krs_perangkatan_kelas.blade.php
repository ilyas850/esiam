@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Rekap KRS Mahasiswa Per Angkatan <b>{{ $nama_tahun }} - {{ $nama_tipe }} -
                        {{ $nama_kelas }}</b></h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th rowspan="2">
                                <center> No</center>
                            </th>
                            <th rowspan="2">
                                <center>Angkatan</center>
                            </th>
                            <th colspan="3">
                                <center>Jumlah Mahasiswa</center>
                            </th>
                            <th rowspan="2">
                                <center>Total</center>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <center>TRPL</center>
                            </th>
                            <th>
                                <center>Teknik Industri</center>
                            </th>
                            <th>
                                <center>Farmasi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td align="center">{{ $item->angkatan }}</td>
                                <td align="center">{{ $item->jml_mhs_tk }}</td>
                                <td align="center">{{ $item->jml_mhs_ti }}</td>
                                <td align="center">{{ $item->jml_mhs_fa }}</td>
                                <td align="center">{{ $item->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
