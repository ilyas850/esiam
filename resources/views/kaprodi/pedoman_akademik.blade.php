@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Silahkan Download Pedoman di Tabel ini</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Nama File</center>
                            </th>
                            <th>
                                <center>Tahun Akademik</center>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($pedoman as $keypdm)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>
                                    <center>{{ $keypdm->nama_pedoman }}</center>
                                </td>
                                <td>
                                    <center>
                                        {{ $keypdm->periode_tahun }}
                                    </center>
                                </td>
                                <td>
                                    <center><a href="/download_pedoman_dsn_kprd/{{ $keypdm->id_pedomanakademik }}"
                                            class="btn btn-warning btn-xs">Download</a></center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </section>
@endsection
