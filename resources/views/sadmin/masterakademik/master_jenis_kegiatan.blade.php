@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Master Jenis Kegiatan</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addJenis">
                            Tambah Jenis Kegiatan
                        </button>
                    </div>
                </div>
                <br>
                <div class="modal fade" id="addJenis" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('simpan_jeniskegiatan') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Jenis Kegiatan</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Deskripsi Jenis Kegiatan</label>
                                        <input type="text" class="form-control" name="deskripsi"
                                            placeholder="Masukan Deskripsi">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
                                <center>Deskripsi</center>
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
                                <td align="center">{{ $no++ }}</td>
                                <td>{{ $item->deskripsi }}</td>
                                <td align="center">
                                    <button class="btn btn-success btn-xs" data-toggle="modal"
                                        data-target="#modalUpdateJenisKegiatan{{ $item->id_jeniskegiatan }}"
                                        title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                    <a href="hapus_jeniskegiatan/{{ $item->id_jeniskegiatan }}"
                                        class="btn btn-danger btn-xs" title="klik untuk hapus" onclick="return confirm('anda yakin akan menghapus ini?')"><i
                                            class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="modalUpdateJenisKegiatan{{ $item->id_jeniskegiatan }}" tabindex="-1"
                                aria-labelledby="modalUpdateJenisKegiatan" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="/put_jeniskegiatan/{{ $item->id_jeniskegiatan }}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Update Master Jenis Kegiatan</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Deskripsi Jenis Kegiatan</label>
                                                    <input type="text" class="form-control" name="deskripsi"
                                                        value="{{ $item->deskripsi }}">
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
    </section>
@endsection
