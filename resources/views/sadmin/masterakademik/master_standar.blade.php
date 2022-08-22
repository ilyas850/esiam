@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Tambah Standar Pendidikan Nasional</h3>
            </div>
            <div class="box-body">

                <form action="{{ url('save_standar_pendidikan_nasional') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-3">
                            <label for="">Nama Standar Pendidikan Nasional</label>
                            <select class="form-control" name="nama_standar" required>
                                <option></option>
                                <option value="Standar Kompetensi Lulusan">Standar Kompetensi Lulusan</option>
                                <option value="Standar Isi Pembelajaran">Standar Isi Pembelajaran</option>
                                <option value="Standar Proses Pembelajaran">Standar Proses Pembelajaran</option>
                                <option value="Standar Penilaian Pembelajaran">Standar Penilaian Pembelajaran</option>
                                <option value="Standar Dosen dan Ketenagapendidikan">Standar Dosen dan
                                    Ketenagapendidikan
                                </option>
                                <option value="Standar Sarana dan Prasarana Pembelajaran">Standar Sarana dan Prasarana
                                    Pembelajaran</option>
                                <option value="Standar Pengelolaan Pembelajaran">Standar Pengelolaan Pembelajaran
                                </option>
                                <option value="Standar Pembiayaan Pembelajaran">Standar Pembiayaan Pembelajaran</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Nama SOP</label>
                            <input type="text" class="form-control" name="nama_sop" placeholder="Masukan Nama SOP"
                                required>
                        </div>
                        <div class="col-md-3">
                            <label for="">File SOP</label>
                            <input type="file" name="file_sop" class="form-control" required>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-info ">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Standar Pendidikan Nasional</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Nama Standar</center>
                            </th>
                            <th>
                                <center>Nama SOP</center>
                            </th>
                            <th>
                                <center>File</center>
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
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>{{ $item->nama_standar }}</td>
                                <td>{{ $item->nama_sop }}</td>
                                <td><a href="{{ asset('/Standar/' . $item->nama_standar . '/' . $item->file_sop) }}"
                                        target="_blank">File SOP</a></td>
                                <td>
                                    <center>
                                        <button class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#modalUpdateStandar{{ $item->id_standar }}"
                                            title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                        <a href="hapus_standar_pendidikan_nasional/{{ $item->id_standar }}" class="btn btn-danger btn-xs"
                                            title="klik untuk hapus" onclick="return confirm('anda yakin akan menghapus ini?')"><i class="fa fa-trash"></i></a>
                                    </center>
                                </td>
                            </tr>
                            <div class="modal fade" id="modalUpdateStandar{{ $item->id_standar }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Wadir</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/put_standar_pendidikan_nasional/{{ $item->id_standar }}"
                                                method="post" enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Nama Standar Pendidikan Nasional</label>
                                                            <select class="form-control" name="nama_standar" required>
                                                                <option value="{{ $item->nama_standar }}">
                                                                    {{ $item->nama_standar }}</option>
                                                                <option value="Standar Kompetensi Lulusan">Standar
                                                                    Kompetensi
                                                                    Lulusan</option>
                                                                <option value="Standar Isi Pembelajaran">Standar Isi
                                                                    Pembelajaran
                                                                </option>
                                                                <option value="Standar Proses Pembelajaran">Standar Proses
                                                                    Pembelajaran</option>
                                                                <option value="Standar Penilaian Pembelajaran">Standar
                                                                    Penilaian
                                                                    Pembelajaran</option>
                                                                <option value="Standar Dosen dan Ketenagapendidikan">Standar
                                                                    Dosen
                                                                    dan
                                                                    Ketenagapendidikan
                                                                </option>
                                                                <option value="Standar Sarana dan Prasarana Pembelajaran">
                                                                    Standar
                                                                    Sarana dan Prasarana
                                                                    Pembelajaran</option>
                                                                <option value="Standar Pengelolaan Pembelajaran">Standar
                                                                    Pengelolaan
                                                                    Pembelajaran
                                                                </option>
                                                                <option value="Standar Pembiayaan Pembelajaran">Standar
                                                                    Pembiayaan
                                                                    Pembelajaran</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="">Nama SOP</label>
                                                            <input type="text" class="form-control" name="nama_sop"
                                                                value="{{ $item->nama_sop }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="">File SOP</label>
                                                            <input type="file" name="file_sop" class="form-control"
                                                                value="{{ $item->file_sop }}"> {{ $item->file_sop }}
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
