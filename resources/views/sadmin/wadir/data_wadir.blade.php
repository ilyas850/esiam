@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data WADIR Politeknik META Industri</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success mr-5" data-toggle="modal" data-target="#addpsi">
                            <i class="fa fa-plus"></i> Input Data WADIR
                        </button>
                    </div>
                </div>
                <br>
                <div class="modal fade" id="addpsi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('post_wadir') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Wadir</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Dosen</label>
                                        <select class="form-control" name="id_dosen">
                                            <option>-pilih-</option>
                                            @foreach ($dosen as $keydsn)
                                                <option value="{{ $keydsn->iddosen }},{{ $keydsn->nama }}">
                                                    {{ $keydsn->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Wadir</label>
                                        <select class="form-control" name="wadir">
                                            <option>-pilih-</option>
                                            <option value="wadir1,7">Wadir 1</option>
                                            <option value="wadir2,8">Wadir 2</option>
                                            <option value="wadir3,9">Wadir 3</option>
                                            <option value="wadir4,10">Wadir 4</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                                <center>NIK</center>
                            </th>
                            <th>
                                <center>Nama Dosen</center>
                            </th>
                            <th>
                                <center>Wadir</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($wadir as $key)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nik }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nama }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->wadir }}</center>
                                </td>
                                <td>
                                    <center>
                                        <button class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#modalUpdateKaprodi{{ $key->id_kaprodi }}"
                                            title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-danger btn-xs" data-toggle="modal"
                                            data-target="#modalHapusKaprodi{{ $key->id_kaprodi }}"
                                            title="klik untuk hapus"><i class="fa fa-trash"></i></button>
                                    </center>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalUpdateKaprodi{{ $key->id_kaprodi }}" tabindex="-1"
                                aria-labelledby="modalUpdateKaprodi" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Kaprodi</h5>
                                        </div>
                                        <div class="modal-body">
                                            <!--FORM UPDATE Tingkat-->
                                            <form action="/put_kaprodi/{{ $key->id_kaprodi }}" method="post">
                                                @csrf
                                                @method('put')
                                                <div class="form-group">
                                                    <label>Nama Dosen</label>
                                                    <select class="form-control" name="id_dosen">
                                                        <option value="{{ $key->id_dosen }}">{{ $key->nama }}
                                                        </option>
                                                        @foreach ($dosen as $keydsn)
                                                            <option value="{{ $keydsn->iddosen }}">{{ $keydsn->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Wadir</label>
                                                    <select class="form-control" name="wadir">
                                                        <option value="{{ $key->wadir }}">{{ $key->wadir }}</option>
                                                        <option value="wadir1,7">Wadir 1</option>
                                                        <option value="wadir2,8">Wadir 2</option>
                                                        <option value="wadir3,9">Wadir 3</option>
                                                        <option value="wadir4,10">Wadir 4</option>
                                                    </select>


                                                </div>
                                                <input type="hidden" name="updated_by" value="{{ Auth::user()->name }}">
                                                <button type="submit" class="btn btn-primary">Perbarui Data</button>
                                            </form>
                                            <!--END FORM Tingkat-->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="modalHapusKaprodi{{ $key->id_kaprodi }}" tabindex="-1"
                                aria-labelledby="modalHapusKaprodi" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <h4 class="text-center">Apakah anda yakin menghapus data kaprodi ini ?</h4>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ url('hapuskaprodi') }}" method="post">
                                                {{ csrf_field() }}
                                                {{-- @csrf
                              @method('delete') --}}
                                                <input type="hidden" name="id_kaprodi" value="{{ $key->id_kaprodi }}" />
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
    </section>
@endsection
