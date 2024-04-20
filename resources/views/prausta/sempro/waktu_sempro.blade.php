@extends('layouts.master')

@section('side')
@include('layouts.side')
@endsection

@section('content')
<section class="content">
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">Waktu Pelaksanaan SEMPRO</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-xs-2">
                    <button type="button" class="btn btn-success mr-5" data-toggle="modal" data-target="#addwaktu">
                        Input Waktu Pelaksanaan
                    </button>
                </div>
            </div>
            <br>
            <div class="modal fade" id="addwaktu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="post" action="{{ url('post_waktu_prausta') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="tipe_prausta" value="SEMPRO">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah Waktu Pelaksanaan SEMPRO</h5>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Periode tahun</label>
                                            <select class="form-control" name="id_periodetahun">
                                                <option></option>
                                                @foreach ($periodetahun as $thn)
                                                <option value="{{ $thn->id_periodetahun }}">
                                                    {{ $thn->periode_tahun }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Periode tipe</label>
                                            <select class="form-control" name="id_periodetipe">
                                                <option></option>
                                                @foreach ($periodetipe as $tp)
                                                <option value="{{ $tp->id_periodetipe }}">
                                                    {{ $tp->periode_tipe }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Prodi</label>
                                            <select class="form-control" name="id_prodi">
                                                <option></option>
                                                @foreach ($prodi as $prd)
                                                <option value="{{ $prd->id_prodi }}">
                                                    {{ $prd->prodi }} - {{ $prd->konsentrasi }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Waktu Awal</label>
                                            <input type="date" class="form-control" name="set_waktu_awal" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Waktu Akhir</label>
                                            <input type="date" class="form-control" name="set_waktu_akhir" required>
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
            <table id="example1" class="table table-bordered">
                <thead>
                    <tr>
                        <th>
                            <center>No</center>
                        </th>
                        <th>
                            <center>Periode tahun</center>
                        </th>
                        <th>
                            <center>Periode tipe</center>
                        </th>
                        <th>
                            <center>Prodi</center>
                        </th>
                        <th>
                            <center>Waktu Awal</center>
                        </th>
                        <th>
                            <center>Waktu Akhir</center>
                        </th>
                        <th>
                            <center>Tipe PraUSTA</center>
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
                        <td>{{ $item->periode_tahun }}</td>
                        <td>{{ $item->periode_tipe }}</td>
                        <td>{{ $item->prodi }} - {{ $item->konsentrasi }}</td>
                        <td>{{ $item->set_waktu_awal }}</td>
                        <td>{{ $item->set_waktu_akhir }}</td>
                        <td>{{ $item->tipe_prausta }}</td>
                        <td>{{ $item->status }}</td>
                        <td>
                            <center>
                                <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#modalUpdateWaktu{{ $item->id_masterwaktu_prausta }}" title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                <a href="hapus_waktu_prausta/{{ $item->id_masterwaktu_prausta }}" class="btn btn-danger btn-xs" title="klik untuk hapus"><i class="fa fa-trash" onclick="return confirm('apakah anda yakin akan menghapus waktu ini ?')"></i></a>
                            </center>
                        </td>
                    </tr>
                    <div class="modal fade" id="modalUpdateWaktu{{ $item->id_masterwaktu_prausta }}" tabindex="-1" aria-labelledby="modalUpdateWadir" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update Waktu SEMPRO</h5>
                                </div>
                                <div class="modal-body">
                                    <form action="/put_waktu_prausta/{{ $item->id_masterwaktu_prausta }}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Periode tahun</label>
                                                    <select class="form-control" name="id_periodetahun">
                                                        <option value="{{ $item->id_periodetahun }}">
                                                            {{ $item->periode_tahun }}
                                                        </option>
                                                        @foreach ($periodetahun as $thn)
                                                        <option value="{{ $thn->id_periodetahun }}">
                                                            {{ $thn->periode_tahun }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Periode tipe</label>
                                                    <select class="form-control" name="id_periodetipe">
                                                        <option value="{{ $item->id_periodetipe }}">
                                                            {{ $item->periode_tipe }}
                                                        </option>
                                                        @foreach ($periodetipe as $tp)
                                                        <option value="{{ $tp->id_periodetipe }}">
                                                            {{ $tp->periode_tipe }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Prodi</label>
                                                    <select class="form-control" name="id_prodi">
                                                        <option value="{{ $item->id_prodi }}">{{ $item->prodi }}
                                                        </option>
                                                        @foreach ($prodi as $prd)
                                                        <option value="{{ $prd->id_prodi }}">
                                                            {{ $prd->prodi }} - {{ $prd->konsentrasi }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Waktu Awal</label>
                                                    <input type="date" class="form-control" name="set_waktu_awal" value="{{ $item->set_waktu_awal }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Waktu Akhir</label>
                                                    <input type="date" class="form-control" name="set_waktu_akhir" value="{{ $item->set_waktu_akhir }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="tipe_prausta" value="SEMPRO">
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