@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ url('simpan_info') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="box box-info">
                        <div class="box-header">
                            <h3 class="box-title">Form Informasi</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" class="form-control" name="judul" placeholder="Masukan Judul disini"
                                    required>
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" cols="20" rows="5" placeholder="Masukan Deskripsi disini"
                                    required></textarea>
                            </div>
                            <div class="form-group">
                                <label>File</label>
                                <input type="file" class="form-control" name="file">
                                <p class="help-block">Maksimal ukuran file 1mb dengan format .JPG .JPEG .PNG</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Tabel Informasi</h3>
                    </div>
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <center>No</center>
                                    </th>
                                    <th>
                                        <center>Judul</center>
                                    </th>
                                    <th>
                                        <center>Deskripsi</center>
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
                                @foreach ($info as $item)
                                    <tr>
                                        <td>
                                            <center>{{ $no++ }}</center>
                                        </td>
                                        <td>{{ $item->judul }}</td>
                                        <td>{{ $item->deskripsi }}</td>
                                        <td>
                                            <center>
                                                @if ($item->file != null)
                                                    <a href="{{ asset('/data_file/' . $item->file) }}"
                                                        target="_blank">File</a>
                                                @endif
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <a class="btn btn-success btn-xs" href="/editinfo/{{ $item->id_informasi }}"
                                                    title="klik untuk edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a class="btn btn-danger btn-xs" href="/hapusinfo/{{ $item->id_informasi }}"
                                                    title="klik untuk hapus"><i class="fa fa-trash"></i></a>
                                                <center>
                                        </td>
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
