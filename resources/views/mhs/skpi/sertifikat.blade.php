@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Sertifikat</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success mr-5" data-toggle="modal" data-target="#addsertifikat">
                            Input Data Sertifikat
                        </button>
                    </div>
                </div>
                <br>
                <div class="modal fade" id="addsertifikat" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('post_sertifikat') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Upload Sertifikat</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Kegiatan</label>
                                        <input type="text" class="form-control" name="nama_kegiatan" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Pretasi (Juara I/Panitia/Peserta/Beasiswa)</label>
                                        <input type="text" name="prestasi" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Tingkat</label>
                                        <select name="tingkat" class="form-control">
                                            <option></option>
                                            <option value="Nasional">Nasional</option>
                                            <option value="Internasional">Internasional</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Pelaksanaan</label>
                                        <input type="date" name="tgl_pelaksanaan" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>File Sertifikat</label>
                                        <input type="file" name="file_sertifikat" class="form-control" required>
                                        <span>Max. size 4mb, format file JPG, PNG, JPEG</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
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
                                <center>Nama Kegiatan</center>
                            </th>
                            <th>
                                <center>Prestasi</center>
                            </th>
                            <th>
                                <center>Tingkat</center>
                            </th>
                            <th>
                                <center>Tanggal Pelaksanaan</center>
                            </th>
                            <th>
                                <center>File Sertifikat</center>
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
                                <td>{{ $item->nama_kegiatan }}</td>
                                <td>
                                    <center>{{ $item->prestasi }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->tingkat }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->tgl_pelaksanaan }}</center>
                                </td>
                                <td>
                                    <center>
                                        <a href="/Sertifikat/{{ Auth::user()->id_user }}/{{ $item->file_sertifikat }}"
                                            target="_blank"> File Sertifikat</a>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <button class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#modalUpdateSertifikat{{ $item->id_sertifikat }}"
                                            title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                        {{-- <button class="btn btn-danger btn-xs" data-toggle="modal"
                                            data-target="#modalHapusSertifikat{{ $item->id_sertifikat }}"
                                            title="klik untuk hapus"><i class="fa fa-trash"></i></button> --}}

                                            <a class="btn btn-danger btn-xs"
                                            href="/hapus_sertifikat/{{ $item->id_sertifikat }}"
                                            onclick="return confirm('anda yakin akan menghapus ini ?')"><i
                                                class="fa fa-trash"></i></a>
                                    </center>
                                </td>
                            </tr>
                            <div class="modal fade" id="modalUpdateSertifikat{{ $item->id_sertifikat }}"
                                tabindex="-1" aria-labelledby="modalUpdateSertifikat" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Sertifikat</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/put_sertifikat/{{ $item->id_sertifikat }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                                <div class="form-group">
                                                    <label>Nama Kegiatan</label>
                                                    <input type="text" class="form-control" name="nama_kegiatan"
                                                        value="{{ $item->nama_kegiatan }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Pretasi (Juara I/Panitia/Peserta/Beasiswa)</label>
                                                    <input type="text" name="prestasi" class="form-control"
                                                        value="{{ $item->prestasi }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tingkat</label>
                                                    <select name="tingkat" class="form-control">
                                                        <option>{{ $item->tingkat }}
                                                        </option>
                                                        <option value="Nasional">Nasional</option>
                                                        <option value="Internasional">Internasional</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tanggal Pelaksanaan</label>
                                                    <input type="date" name="tgl_pelaksanaan" class="form-control"
                                                        value="{{ $item->tgl_pelaksanaan }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>File Sertifikat</label>
                                                    <input type="file" name="file_sertifikat"
                                                        class="form-control">{{ $item->file_sertifikat }}
                                                    <br>
                                                    <span>Max. size 4mb, format file JPG, PNG, JPEG</span>
                                                </div>
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
