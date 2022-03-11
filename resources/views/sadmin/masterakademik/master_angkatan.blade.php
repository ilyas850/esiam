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
                        <div class="row">
                            <div class="col-xs-2">
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                    data-target="#addangkatan">
                                    Tambah Master Angkatan
                                </button>
                            </div>
                        </div>
                        <br>
                        <div class="modal fade" id="addangkatan" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form method="post" action="{{ url('simpan_angkatan') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Tambah Master Angkatan</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>ID Angkatan</label>
                                                <input type="text" class="form-control" name="idangkatan"
                                                    placeholder="Masukan ID">
                                            </div>
                                            <div class="form-group">
                                                <label>Angkatan</label>
                                                <input type="text" class="form-control" name="angkatan"
                                                    placeholder="Masukan Tahun">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="4px">
                                        <center>No</center>
                                    </th>
                                    <th>
                                        <center>ID</center>
                                    </th>
                                    <th>
                                        <center>Angkatan</center>
                                    </th>
                                    <th>
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
                                            <center> {{ $item->idangkatan }} </center>
                                        </td>
                                        <td>
                                            <center>{{ $item->angkatan }} </center>
                                        </td>
                                        <td>
                                            <center>
                                                <button class="btn btn-success btn-xs" data-toggle="modal"
                                                    data-target="#modalUpdateAngkatan{{ $item->idangkatan }}"
                                                    title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger btn-xs" data-toggle="modal"
                                                    data-target="#modalHapusAngkatan{{ $item->idangkatan }}"
                                                    title="klik untuk hapus"><i class="fa fa-trash"></i></button>
                                                <center>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="modalUpdateAngkatan{{ $item->idangkatan }}"
                                        tabindex="-1" aria-labelledby="modalUpdateAngkatan" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="/put_angkatan/{{ $item->idangkatan }}" method="post">
                                                @csrf
                                                @method('put')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Update Master Angkatan</h5>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="form-group">
                                                            <label>ID Angkatan</label>
                                                            <input type="text" class="form-control" name="idangkatan"
                                                                value="{{ $item->idangkatan }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Angkatan</label>
                                                            <input type="text" class="form-control" name="angkatan"
                                                                value="{{ $item->angkatan }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="modalHapusAngkatan{{ $item->idangkatan }}"
                                        tabindex="-1" aria-labelledby="modalHapusAngkatan" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <h4 class="text-center">Apakah anda yakin menghapus data master
                                                        angkatan ini ?</h4>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ url('hapusangkatan') }}" method="post">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="idangkatan"
                                                            value="{{ $item->idangkatan }}" />
                                                        <button type="submit" class="btn btn-primary">Hapus data!</button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Batal</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
