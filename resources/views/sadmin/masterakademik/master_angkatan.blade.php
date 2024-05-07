@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">Master Angkatan</h3>
                </div>
                <div class="box-body">
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addangkatan">Tambah Master Angkatan</button>
                    @include('sadmin.masterakademik.modals.add_angkatan')
                    <br><br>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Angkatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->idangkatan }}</td>
                                    <td>{{ $item->angkatan }}</td>
                                    <td>
                                        <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalUpdateAngkatan{{ $item->idangkatan }}" title="Klik untuk edit"><i class="fa fa-edit"></i></button>
                                        @include('sadmin.masterakademik.modals.update_angkatan', ['item' => $item])
                                        <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modalHapusAngkatan{{ $item->idangkatan }}" title="Klik untuk hapus"><i class="fa fa-trash"></i></button>
                                        @include('sadmin.masterakademik.modals.delete_angkatan', ['item' => $item])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
