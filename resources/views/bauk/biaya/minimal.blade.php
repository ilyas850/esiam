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
                        <h3 class="box-title">Minimal Pembayaran</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-2">
                                <button type="button" class="btn btn-success mr-5" data-toggle="modal"
                                    data-target="#addminimal">
                                    Input Minimal
                                </button>
                            </div>
                        </div>
                        <br>
                        <div class="modal fade" id="addminimal" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form method="post" action="{{ url('post_min_biaya') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Tambah Minimal Pembayarab</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Kategori</label>
                                                        <select class="form-control" name="kategori" required>
                                                            <option></option>
                                                            <option value="UTS">UTS</option>
                                                            <option value="UAS">UAS</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Persentase (%)</label>
                                                        <input type="number" name="persentase" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
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
                        <table id="example8" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <center> No</center>
                                    </th>
                                    <th>
                                        <center>Kategori</center>
                                    </th>
                                    <th>
                                        <center>Persentase Minimal</center>
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
                                        <td>{{ $item->kategori }}</td>
                                        <td align="center">{{ $item->persentase }}%</td>
                                        <td></td>
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
