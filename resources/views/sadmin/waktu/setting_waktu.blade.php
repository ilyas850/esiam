@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Setting Waktu</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success mr-5" data-toggle="modal" data-target="#addwaktu">
                            Input Setting Waktu
                        </button>
                    </div>
                </div>
                <br>
                <div class="modal fade" id="addwaktu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('post_waktu') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Setting Waktu</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Tipe Waktu</label>
                                                <select class="form-control" name="tipe_waktu" required>
                                                    <option></option>
                                                    <option value="1">Yudisium</option>
                                                    <option value="2">Wisuda</option>
                                                    <option value="3">Penangguhan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Deskripsi</label>
                                                <textarea name="deskripsi" class="form-control" cols="10" rows="3" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Waktu Awal</label>
                                                <input type="date" class="form-control" name="waktu_awal"
                                                    value="{{ $date }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Waktu Akhir</label>
                                                <input type="date" class="form-control" name="waktu_akhir" required>
                                            </div>
                                        </div>
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
                                <center>Tipe Waktu</center>
                            </th>
                            <th>
                                <center>Deskripsi</center>
                            </th>
                            <th>
                                <center>Waktu Awal</center>
                            </th>
                            <th>
                                <center>Waktu Akhir</center>
                            </th>
                            <th>
                                <center>Status</center>
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
                                <td align="center">
                                    @if ($item->tipe_waktu == 1)
                                        Yudisium
                                    @elseif($item->tipe_waktu == 2)
                                        Wisuda
                                    @elseif($item->tipe_waktu == 3)
                                        Penangguhan
                                    @endif
                                </td>
                                <td>{{ $item->deskripsi }}</td>
                                <td align="center">{{ $item->waktu_awal }}</td>
                                <td align="center">{{ $item->waktu_akhir }}</td>
                                <td align="center">{{ $item->status }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
@endsection
