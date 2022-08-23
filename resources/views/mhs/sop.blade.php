@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Standar Operasional Prosedur</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Nama Standar</center>
                            </th>
                            <th>
                                <center>Nama SOP</center>
                            </th>
                            <th>
                                <center>File</center>
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
                                <td>{{ $item->nama_standar }}</td>
                                <td>{{ $item->nama_sop }}</td>
                                <td><a href="{{ asset('/Standar/' . $item->nama_standar . '/' . $item->file_sop) }}"
                                        target="_blank">File SOP</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
