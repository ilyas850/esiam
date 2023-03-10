@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Konversi Matakuliah</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success mr-5" data-toggle="modal" data-target="#addpsi">
                            <i class="fa fa-plus"></i> Filter Data Matakuliah
                        </button>
                    </div>
                </div>
                <br>
                <br>
                <div class="modal fade" id="addpsi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('filter_matakuliah') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Filter Data Matakuliah</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Kurikulum</label>
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
                                            <option value="wadir2,12">Wadir 2</option>
                                            <option value="wadir3,10">Wadir 3</option>
                                            <option value="wadir4,13">Wadir 4</option>
                                        </select>
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
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Matakuliah Awal</center>
                            </th>
                            <th>
                                <center>Matakuliah Baru</center>
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
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
