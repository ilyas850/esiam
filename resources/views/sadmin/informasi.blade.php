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
                            <h3 class="box-title">Form Informasi Terbaru</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" class="form-control" name="judul" placeholder="Masukan judul">
                            </div>
                            <div class="form-group">
                                <label >Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" cols="20" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label >File</label>
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

                <br>

                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Tabel Informasi</h3>
                    </div>
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="4px">
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
                                                @if ($item->file == null)
                                                    Tidak ada file
                                                @else
                                                    <a href="{{ asset('/data_file/' . $item->file) }}"
                                                        target="_blank">{{ $item->file }}</a>
                                                @endif
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                <a class="btn btn-success"
                                                    href="/editinfo/{{ $item->id_informasi }}">Edit</a>
                                                <a class="btn btn-danger"
                                                    href="/hapusinfo/{{ $item->id_informasi }}">Hapus</a>
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
