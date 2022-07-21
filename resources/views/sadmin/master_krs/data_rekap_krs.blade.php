@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Data Rekap KRS Mahasiswa</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th rowspan="2">
                                <center> No</center>
                            </th>
                            <th rowspan="2">
                                <center>Periode Tahun</center>
                            </th>
                            <th colspan="4">
                                <center>Ganjil</center>
                            </th>
                            <th colspan="4">
                                <center>Genap</center>
                            </th>
                            <th colspan="4">
                                <center>Pendek</center>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <center>TRPL</center>
                            </th>
                            <th>
                                <center>TI</center>
                            </th>
                            <th>
                                <center>FA</center>
                            </th>
                            <th>
                                <center>JUMLAH</center>
                            </th>
                            <th>
                                <center>TRPL</center>
                            </th>
                            <th>
                                <center>TI</center>
                            </th>
                            <th>
                                <center>FA</center>
                            </th>
                            <th>
                                <center>JUMLAH</center>
                            </th>
                            <th>
                                <center>TRPL</center>
                            </th>
                            <th>
                                <center>TI</center>
                            </th>
                            <th>
                                <center>FA</center>
                            </th>
                            <th>
                                <center>JUMLAH</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td>{{ $item->periode_tahun }}</td>
                                <td align="center">{{ $item->tk_gnj }}</td>
                                <td align="center">{{ $item->ti_gnj }}</td>
                                <td align="center">{{ $item->fa_gnj }}</td>
                                <td align="center">{{ $item->jml_ganjil }}</td>
                                <td align="center">{{ $item->tk_gnp }}</td>
                                <td align="center">{{ $item->ti_gnp }}</td>
                                <td align="center">{{ $item->fa_gnp }}</td>
                                <td align="center">{{ $item->jml_genap }}</td>
                                <td align="center">{{ $item->tk_pndk }}</td>
                                <td align="center">{{ $item->ti_pndk }}</td>
                                <td align="center">{{ $item->fa_pndk }}</td>
                                <td align="center">{{ $item->jml_pendek }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
