@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Laporan EDOM</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered" id="example1">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Dosen</center>
                            </th>
                            <th>
                                <center>MAKUL Qty</center>
                            </th>
                            <th>
                                <center>MHS Qty</center>
                            </th>
                            <th>
                                <center>EDOM Qty</center>
                            </th>
                            <th>
                                <center>Nilai Angka</center>
                            </th>
                            <th>
                                <center>Nilai Huruf</center>
                            </th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach ($data_dsn as $item)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>
                                    {{ $item->nama_dosen }}
                                </td>
                                <td>
                                    <center>
                                        @foreach ($data_mk as $item_mk)
                                            @if ($item_mk->iddosen == $item->iddosen)
                                                {{ $item_mk->jumlah_mk }}
                                            @endif
                                        @endforeach
                                    </center>
                                </td>
                                <td>
                                    <center>{{ $item->jumlah_mhs }}</center>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
