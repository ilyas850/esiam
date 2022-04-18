@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Master Kuisioner</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addkuisioner">
                            Tambah Master Kuisioner
                        </button>
                    </div>
                </div>
                <br>
                <div class="modal fade" id="addkuisioner" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('simpan_master_kuisioner') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Master Kuisioner</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Kategori Kuisioner</label>
                                        <select name="id_kategori_kuisioner" class="form-control" required>
                                            <option></option>
                                            @foreach ($kategori as $kt)
                                                <option value="{{ $kt->id_kategori_kuisioner }}">
                                                    {{ $kt->kategori_kuisioner }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Aspek Kuisioner</label>
                                        <select name="id_aspek_kuisioner" class="form-control" required>
                                            <option></option>
                                            @foreach ($aspek as $asp)
                                                <option value="{{ $asp->id_aspek_kuisioner }}">
                                                    {{ $asp->aspek_kuisioner }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Komponen Penilaian</label>
                                        <textarea name="komponen_kuisioner" id="" cols="30" rows="2" class="form-control"
                                            placeholder="Masukan Komponen Penilaian" required></textarea>

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
                            <th width="3%">
                                <center>No</center>
                            </th>
                            <th width="35%">
                                <center>Kategori Kuisioner</center>
                            </th>
                            <th width="20%">
                                <center>Aspek Kuisioner</center>
                            </th>
                            <th width="35%">
                                <center>Komponen Kuisioner</center>
                            </th>
                            <th width="7%">
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
                                    {{ $item->aspek_kuisioner }}
                                </td>
                                <td>
                                    {{ $item->komponen_kuisioner }}
                                </td>


                                <td>
                                    <center>
                                        <button class="btn btn-warning btn-xs" data-toggle="modal"
                                            data-target="#modalUpdateKuisioner{{ $item->id_kuisioner }}"
                                            title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-danger btn-xs" data-toggle="modal"
                                            data-target="#modalHapusKuisioner{{ $item->id_kuisioner }}"
                                            title="klik untuk hapus"><i class="fa fa-trash"></i></button>
                                    </center>
                                </td>
                                <div class="modal fade" id="modalUpdateKuisioner{{ $item->id_kuisioner }}"
                                    tabindex="-1" aria-labelledby="modalUpdateKuisioner" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="/put_kuisioner_master/{{ $item->id_kuisioner }}" method="post">
                                            @csrf
                                            @method('put')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Master Penilaian PraUSTA</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Kategori Kuisioner</label>
                                                        <select name="id_kategori_kuisioner" class="form-control"
                                                            required>
                                                            <option value="{{ $item->id_kategori_kuisioner }}">
                                                                {{ $item->kategori_kuisioner }} </option>
                                                            @foreach ($kategori as $kt)
                                                                <option value="{{ $kt->id_kategori_kuisioner }}">
                                                                    {{ $kt->kategori_kuisioner }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Aspek Kuisioner</label>
                                                        <select name="id_aspek_kuisioner" class="form-control" required>
                                                            <option value="{{ $item->id_aspek_kuisioner }}">
                                                                {{ $item->aspek_kuisioner }}</option>
                                                            @foreach ($aspek as $asp)
                                                                <option value="{{ $asp->id_aspek_kuisioner }}">
                                                                    {{ $asp->aspek_kuisioner }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Komponen Penilaian</label>
                                                        <textarea name="komponen_kuisioner" id="" cols="30" rows="2" class="form-control"
                                                            placeholder="Masukan Komponen Penilaian"
                                                            required> {{ $item->komponen_kuisioner }}</textarea>

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
                                <div class="modal fade" id="modalHapusKuisioner{{ $item->id_kuisioner }}"
                                    tabindex="-1" aria-labelledby="modalHapusKuisioner" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <h4 class="text-center">Apakah anda yakin menghapus data master
                                                    kuisioner ini ?</h4>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ url('hapus_kuisioner_master') }}" method="post">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="id_kuisioner"
                                                        value="{{ $item->id_kuisioner }}" />
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
