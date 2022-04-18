@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Master Kategori Kuisioner</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                            data-target="#addkategorikuisioner">
                            Tambah Master Kategori Kuisioner
                        </button>
                    </div>
                </div>
                <br>
                <div class="modal fade" id="addkategorikuisioner" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('simpan_kategori_kuisioner') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Master Kategori Kuisioner</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Kategori Kuisioner</label>
                                        <textarea name="kategori_kuisioner" id="" cols="30" rows="2" placeholder="Masukan Kategori Kuisioner"
                                            class="form-control"></textarea>
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
                                <center>Kategori Kuisioner</center>
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
                                    {{ $item->kategori_kuisioner }}
                                </td>

                                <td>
                                    <center>
                                        <button class="btn btn-warning btn-xs" data-toggle="modal"
                                            data-target="#modalUpdateKategoriKuisioner{{ $item->id_kategori_kuisioner }}"
                                            title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-danger btn-xs" data-toggle="modal"
                                            data-target="#modalHapusKategoriKuisioner{{ $item->id_kategori_kuisioner }}"
                                            title="klik untuk hapus"><i class="fa fa-trash"></i></button>
                                    </center>
                                </td>
                                <div class="modal fade"
                                    id="modalUpdateKategoriKuisioner{{ $item->id_kategori_kuisioner }}" tabindex="-1"
                                    aria-labelledby="modalUpdateKategoriKuisioner" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="/put_kategori_kuisioner/{{ $item->id_kategori_kuisioner }}"
                                            method="post">
                                            @csrf
                                            @method('put')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Master Kategori Penilaian</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Kategori Kuisioner</label>
                                                        <textarea name="kategori_kuisioner" id="" cols="30" rows="2" placeholder="Masukan Kategori Kuisioner"
                                                            class="form-control">{{ $item->kategori_kuisioner }}</textarea>
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
                                <div class="modal fade"
                                    id="modalHapusKategoriKuisioner{{ $item->id_kategori_kuisioner }}" tabindex="-1"
                                    aria-labelledby="modalHapusKategoriKuisioner" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <h4 class="text-center">Apakah anda yakin menghapus data master
                                                    kategori kuisioner ini ?</h4>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ url('hapus_kategori_kuisioner') }}" method="post">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="id_kategori_kuisioner"
                                                        value="{{ $item->id_kategori_kuisioner }}" />
                                                    <button type="submit" class="btn btn-primary">Hapus data!</button>
                                                </form>
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                            </div>
                                        </div>
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
