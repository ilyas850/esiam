@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Master Kategori Kritik & Saran</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                            data-target="#addkategorikuisioner">
                            Tambah Kategori
                        </button>
                    </div>
                </div>
                <br>
                <div class="modal fade" id="addkategorikuisioner" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('simpan_kategori_kritiksaran') }}"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori Kritik & Saran</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Kategori Kritik & Saran</label>
                                        <input name="kategori_kritiksaran" placeholder="Masukan Kategori Kritik & Saran"
                                            class="form-control">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="1%">
                                <center>No</center>
                            </th>
                            <th width="50%">
                                <center>Kategori</center>
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
                                    {{ $item->kategori_kritiksaran }}
                                </td>

                                <td>
                                    <center>
                                        <button class="btn btn-warning btn-xs" data-toggle="modal"
                                            data-target="#modalUpdateKategoriKuisioner{{ $item->id_kategori_kritiksaran }}"
                                            title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                        <a href="/hapus_kategori_kritiksaran/{{ $item->id_kategori_kritiksaran }}"
                                            class="btn btn-danger btn-xs" onclick="return confirm('anda yakin akan menghapus ini ?')"><i class="fa fa-trash"></i></a>
                                    </center>
                                </td>
                                <div class="modal fade"
                                    id="modalUpdateKategoriKuisioner{{ $item->id_kategori_kritiksaran }}" tabindex="-1"
                                    aria-labelledby="modalUpdateKategoriKuisioner" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="/put_kategori_kritiksaran/{{ $item->id_kategori_kritiksaran }}"
                                            method="post">
                                            @csrf
                                            @method('put')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Kategori Kritik & Saran</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Kategori Kritik & Saran</label>
                                                        <input name="kategori_kritiksaran"
                                                            placeholder="Masukan Kategori Kritik & Saran"
                                                            class="form-control" value="{{ $item->kategori_kritiksaran }}">
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

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
