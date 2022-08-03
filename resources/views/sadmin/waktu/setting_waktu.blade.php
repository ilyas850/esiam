@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Setting Waktu</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Tipe Waktu</center>
                            </th>
                            <th>
                                <center>Deskripsi</center>
                            </th>
                            <th>
                                <center>Waktu Awal</center>
                            </th>
                            <th>
                                <center>Waktu Akhir</center>
                            </th>
                            <th>
                                <center>Status</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </section>
@endsection
