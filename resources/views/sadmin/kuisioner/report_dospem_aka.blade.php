@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Report Kuisioner Pembimbing Akademik</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="1%">
                                <center>No</center>
                            </th>
                            <th width="30%">
                                <center>Dosen</center>
                            </th>
                            <th width="10%">
                                <center>Mhs Qty</center>
                            </th>
                            <th width="10%">
                                <center>Kuisioner Qty</center>
                            </th>
                            <th width="10%">
                                <center>Nilai Angka</center>
                            </th>
                            <th width="10%">
                                <center>Nilai Huruf</center>
                            </th>
                            <th width="5%">
                                <center>Aksi</center>
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
                                    {{ $item->nama }}
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <center>
                                        <a href="/report_kuisioner_kategori_akademik/{{ $item->iddosen }}"
                                            class="btn btn-info btn-xs">Report</a>
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
