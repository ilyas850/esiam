@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Tambah Kalender Akademik</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form action="{{ url('save_kalender_akademik') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="col-xs-3">
                            <input type="text" class="form-control" name="deskripsi" placeholder="Masukan Nama File"
                                required>
                        </div>
                        <div class="col-xs-3">
                            <select class="form-control" name="id_periodetahun" required>
                                <option></option>
                                @foreach ($thn as $tahun)
                                    <option value="{{ $tahun->id_periodetahun }}">{{ $tahun->periode_tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <div class="col-xs-3">
                            <button type="submit" class="btn btn-info ">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Kalender Akademik Politeknik META Industri Cikarang</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Nama File</center>
                            </th>
                            <th>
                                <center>File</center>
                            </th>
                            <th>
                                <center>Tahun Akademik</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $keypdm)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>{{ $keypdm->deskripsi }}</td>
                                <td><a href="{{ asset('/Kalender Akademik/' . $keypdm->file) }}"
                                        target="_blank">{{ $keypdm->file }}</a></td>
                                <td>
                                    <center>{{ $keypdm->periode_tahun }}</center>
                                </td>
                                <td align="center">
                                    <button class="btn btn-success btn-xs" data-toggle="modal"
                                        data-target="#modalUpdateStandar{{ $keypdm->id_kalender }}"
                                        title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                    <a href="hapus_kalender_akademik/{{ $keypdm->id_kalender }}"
                                        class="btn btn-danger btn-xs" title="klik untuk hapus"
                                        onclick="return confirm('anda yakin akan menghapus ini?')"><i
                                            class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="modalUpdateStandar{{ $keypdm->id_kalender }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Kalender Akademik</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/put_kalender_akademik/{{ $keypdm->id_kalender }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Nama File</label>
                                                            <input type="text" class="form-control" name="deskripsi"
                                                                value="{{ $keypdm->deskripsi }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Periode Tahun</label>
                                                        <select class="form-control" name="id_periodetahun">
                                                            <option value="{{ $keypdm->id_periodetahun }}">
                                                                {{ $keypdm->periode_tahun }}</option>
                                                            @foreach ($thn as $tahun)
                                                                <option value="{{ $tahun->id_periodetahun }}">
                                                                    {{ $tahun->periode_tahun }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>File</label>
                                                            <input type="file" name="file" class="form-control"
                                                                value="{{ $keypdm->file }}"> {{ $keypdm->file }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="updated_by" value="{{ Auth::user()->name }}">
                                                <button type="submit" class="btn btn-primary">Perbarui Data</button>
                                            </form>
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
