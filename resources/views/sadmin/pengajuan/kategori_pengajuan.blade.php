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
                    <h3 class="box-title">Master Kategori Pengajuan</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-2">
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                data-target="#addpengajuan">
                                Tambah Kategori Pengajuan
                            </button>
                        </div>
                    </div>
                    <br>
                    <div class="modal fade" id="addpengajuan" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="post" action="{{ url('simpan_kategori_pengajuan') }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Tambah Master Kategori
                                            Pengajuan</h5>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Kategori</label>
                                            <input type="text" class="form-control" name="kategori"
                                                placeholder="Masukan Kategori">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
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
                                    <center>Kategori</center>
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
                                        {{ $item->kategori }}
                                    </td>
                                    <td>
                                        <center>
                                            <button class="btn btn-success btn-xs" data-toggle="modal"
                                                data-target="#modalUpdateAngkatan{{ $item->id_kategori_pengajuan }}"
                                                title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                            <a class="btn btn-danger btn-xs"
                                                href="/hapus_kategori_pengajuan/{{ $item->id_kategori_pengajuan }}"
                                                onclick="return confirm('anda yakin akan menghapus ini ?')"><i
                                                    class="fa fa-trash"></i></a>
                                            <center>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modalUpdateAngkatan{{ $item->id_kategori_pengajuan }}"
                                    tabindex="-1" aria-labelledby="modalUpdateAngkatan" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="/put_kategori_pengajuan/{{ $item->id_kategori_pengajuan }}"
                                            method="post">
                                            @csrf
                                            @method('put')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Master Kategori Pengajuan</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Kategori</label>
                                                        <input type="text" class="form-control" name="kategori"
                                                            value="{{ $item->kategori }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </div>
                                        </form>
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