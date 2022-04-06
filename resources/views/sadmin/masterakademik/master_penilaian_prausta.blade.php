@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Master Penilaian PraUSTA</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addpenilaian">
                            Tambah Master Penilaian
                        </button>
                    </div>
                </div>
                <br>
                <div class="modal fade" id="addpenilaian" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('simpan_penilaian_prausta') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Master Penilaian</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Komponen Penilaian</label>
                                        <input type="text" class="form-control" name="komponen"
                                            placeholder="Masukan Komponen Penilaian" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Acuan Penilaian</label>
                                        <textarea name="acuan" cols="10" rows="5" class="form-control"
                                            placeholder="Masukan Acuan Penilaian"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Bobot Penilaian</label>
                                        <input type="number" class="form-control" name="bobot"
                                            placeholder="Masukan Bobot Penilaian Contoh 1 - 100" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Kategori</label>
                                        <select name="kategori" class="form-control" required>
                                            <option value="1">Prakerin</option>
                                            <option value="2">Sempro</option>
                                            <option value="3">Tugas Akhir</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Form</label>
                                        <select name="jenis_form" class="form-control" required>
                                            <option></option>
                                            <option value="Form Pembimbing">Form Pembimbing</option>
                                            <option value="Form Seminar">Form Seminar</option>
                                            <option value="Form Penguji I">Form Penguji I</option>
                                            <option value="Form Penguji II">Form Penguji II</option>
                                        </select>
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
                            <th width="30%">
                                <center>Komponen Penilaian</center>
                            </th>
                            <th width="30%">
                                <center>Acuan Penilaian</center>
                            </th>
                            <th width="5%">
                                <center>Bobot</center>
                            </th>
                            <th width="5%">
                                <center>Kategori</center>
                            </th>
                            <th width="10%">
                                <center>Jenis Form</center>
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
                                    {{ $item->komponen }}
                                </td>
                                <td>
                                    {{ $item->acuan }}
                                </td>
                                <td>
                                    <center>{{ $item->bobot }} </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->kategori == 1)
                                            Prakerin
                                        @elseif ($item->kategori == 2)
                                            Sempro
                                        @elseif($item->kategori == 3)
                                            Tugas Akhir
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    {{ $item->jenis_form }}
                                </td>
                                <td>
                                    <center>
                                        <button class="btn btn-warning btn-xs" data-toggle="modal"
                                            data-target="#modalUpdatePenilaian{{ $item->id_penilaian_prausta }}"
                                            title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-danger btn-xs" data-toggle="modal"
                                            data-target="#modalHapusPenilaian{{ $item->id_penilaian_prausta }}"
                                            title="klik untuk hapus"><i class="fa fa-trash"></i></button>
                                    </center>
                                </td>
                                <div class="modal fade" id="modalUpdatePenilaian{{ $item->id_penilaian_prausta }}"
                                    tabindex="-1" aria-labelledby="modalUpdatePenilaian" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="/put_penilaian_prausta/{{ $item->id_penilaian_prausta }}"
                                            method="post">
                                            @csrf
                                            @method('put')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Master Penilaian PraUSTA</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Komponen Penilaian</label>
                                                        <input type="text" class="form-control" name="komponen"
                                                            placeholder="Masukan Komponen Penilaian"
                                                            value="{{ $item->komponen }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Acuan Penilaian</label>
                                                        <textarea name="acuan" cols="10" rows="5" class="form-control"
                                                            placeholder="Masukan Acuan Penilaian">{{ $item->acuan }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Bobot Penilaian</label>
                                                        <input type="number" class="form-control" name="bobot"
                                                            placeholder="Masukan Bobot Penilaian Contoh 1 - 100"
                                                            value="{{ $item->bobot }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Kategori</label>
                                                        <select name="kategori" class="form-control" required>
                                                            <option value="{{ $item->kategori }}">
                                                                @if ($item->kategori == 1)
                                                                    Prakerin
                                                                @elseif ($item->kategori == 2)
                                                                    Sempro
                                                                @elseif($item->kategori == 3)
                                                                    Tugas Akhir
                                                                @endif
                                                            </option>
                                                            <option value="1">Prakerin</option>
                                                            <option value="2">Sempro</option>
                                                            <option value="3">Tugas Akhir</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Jenis Form</label>
                                                        <select name="jenis_form" class="form-control" required>
                                                            <option value="{{ $item->jenis_form }}">
                                                                {{ $item->jenis_form }}</option>
                                                            <option value="Form Pembimbing">Form Pembimbing</option>
                                                            <option value="Form Seminar">Form Seminar</option>
                                                            <option value="Form Penguji I">Form Penguji I</option>
                                                            <option value="Form Penguji II">Form Penguji II</option>
                                                        </select>
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
                                <div class="modal fade" id="modalHapusPenilaian{{ $item->id_penilaian_prausta }}"
                                    tabindex="-1" aria-labelledby="modalHapusPenilaian" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <h4 class="text-center">Apakah anda yakin menghapus data master
                                                    angkatan ini ?</h4>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ url('hapus_penilaian_prausta') }}" method="post">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="id_penilaian_prausta"
                                                        value="{{ $item->id_penilaian_prausta }}" />
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
