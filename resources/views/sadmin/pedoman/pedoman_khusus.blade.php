@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Tambah Pedoman Khusus Dosen</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form action="{{ url('save_pedoman_khusus') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="col-xs-3">
                            <input type="text" class="form-control" name="nama_pedoman" placeholder="Masukan Nama File" required>
                        </div>
                        <div class="col-xs-3">
                            <select class="form-control" name="id_periodetahun" required>
                                <option></option>
                                @foreach ($tahun as $thn)
                                    <option value="{{ $thn->id_periodetahun }}">{{ $thn->periode_tahun }}</option>
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
                <h3 class="box-title">Pedoman Khusus Dosen Politeknik META Industri Cikarang</h3>
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
                                <td>{{ $keypdm->nama_pedoman }}</td>
                                <td><a href="{{ asset('/Pedoman Khusus/' . $keypdm->file) }}"
                                        target="_blank">{{ $keypdm->file }}</a></td>
                                <td>
                                    <center>
                                        @foreach ($tahun as $thn)
                                            @if ($keypdm->id_periodetahun == $thn->id_periodetahun)
                                                {{ $thn->periode_tahun }}
                                            @endif
                                        @endforeach
                                    </center>
                                </td>
                                <td align="center">
                                    <button class="btn btn-success btn-xs" data-toggle="modal"
                                        data-target="#modalUpdateStandar{{ $keypdm->id_pedomankhusus }}"
                                        title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                    <a href="hapus_pedoman_khusus/{{ $keypdm->id_pedomankhusus }}"
                                        class="btn btn-danger btn-xs" title="klik untuk hapus"
                                        onclick="return confirm('anda yakin akan menghapus ini?')"><i
                                            class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="modalUpdateStandar{{ $keypdm->id_pedomankhusus }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Pedoman</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/put_pedoman_khusus/{{ $keypdm->id_pedomankhusus }}"
                                                method="post" enctype="multipart/form-data">
                                                @csrf
                                                @method('put')

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Nama File</label>
                                                            <input type="text" class="form-control" name="nama_pedoman"
                                                                value="{{ $keypdm->nama_pedoman }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Periode Tahun</label>
                                                        <select class="form-control" name="id_periodetahun">
                                                            <option value="{{ $keypdm->id_periodetahun }}">
                                                                {{ $keypdm->periode_tahun }}</option>
                                                            @foreach ($tahun as $thn)
                                                                <option value="{{ $thn->id_periodetahun }}">
                                                                    {{ $thn->periode_tahun }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>File Pedoman</label>
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
