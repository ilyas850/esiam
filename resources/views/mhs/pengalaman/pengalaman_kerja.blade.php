@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Pengalaman Kerja</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success mr-5" data-toggle="modal" data-target="#addsertifikat">
                            Input Pengalaman Kerja
                        </button>
                    </div>
                </div>
                <div class="modal fade" id="addsertifikat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('post_pengalaman') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Pengalaman Kerja</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Perusahaan/Instansi</label>
                                        <input type="text" class="form-control" name="nama_pt" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Posisi/Jabatan</label>
                                        <input type="text" name="posisi" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Tahun Masuk</label>
                                        <select name="tahun_masuk" class="form-control">
                                            <option></option>
                                            <option value="Masih bekerja">Masih bekerja</option>
                                            <option value="2022">2022</option>
                                            <option value="2021">2021</option>
                                            <option value="2020">2020</option>
                                            <option value="2019">2019</option>
                                            <option value="2018">2018</option>
                                            <option value="2017">2017</option>
                                            <option value="2016">2016</option>
                                            <option value="2015">2015</option>
                                            <option value="2014">2014</option>
                                            <option value="2013">2013</option>
                                            <option value="2012">2012</option>
                                            <option value="2011">2011</option>
                                            <option value="2010">2010</option>
                                            <option value="2009">2009</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Tahun Keluar</label>
                                        <select name="tahun_keluar" class="form-control">
                                            <option></option>
                                            <option value="Masih bekerja">Masih bekerja</option>
                                            <option value="2022">2022</option>
                                            <option value="2021">2021</option>
                                            <option value="2020">2020</option>
                                            <option value="2019">2019</option>
                                            <option value="2018">2018</option>
                                            <option value="2017">2017</option>
                                            <option value="2016">2016</option>
                                            <option value="2015">2015</option>
                                            <option value="2014">2014</option>
                                            <option value="2013">2013</option>
                                            <option value="2012">2012</option>
                                            <option value="2011">2011</option>
                                            <option value="2010">2010</option>
                                            <option value="2009">2009</option>
                                        </select>
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
                <br>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Nama Perusahaan/Instansi</center>
                            </th>
                            <th>
                                <center>Posisi/Jabatan</center>
                            </th>
                            <th>
                                <center>Tahun Masuk</center>
                            </th>
                            <th>
                                <center>Tahun Keluar</center>
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
                                <td>{{ $item->nama_pt }}</td>
                                <td>{{ $item->posisi }}</td>
                                <td align="center">{{ $item->tahun_masuk }}</td>
                                <td align="center">{{ $item->tahun_keluar }}</td>
                                <td align="center">
                                    <button class="btn btn-success btn-xs" data-toggle="modal"
                                        data-target="#modalUpdateSertifikat{{ $item->id_pengalaman }}"
                                        title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                    <a class="btn btn-danger btn-xs" href="/hapus_pengalaman/{{ $item->id_pengalaman }}"
                                        onclick="return confirm('anda yakin akan menghapus ini ?')"><i
                                            class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="modalUpdateSertifikat{{ $item->id_pengalaman }}" tabindex="-1"
                                aria-labelledby="modalUpdateSertifikat" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Pengalaman</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/put_pengalaman/{{ $item->id_pengalaman }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                                <div class="form-group">
                                                    <label>Nama Perusahaan/Instansi</label>
                                                    <input type="text" class="form-control" name="nama_pt"
                                                        value="{{ $item->nama_pt }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Posisi/Jabatan</label>
                                                    <input type="text" name="posisi" class="form-control"
                                                        value="{{ $item->posisi }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tahun Masuk</label>
                                                    <select name="tahun_masuk" class="form-control">
                                                        <option value="{{ $item->tahun_masuk }}">{{ $item->tahun_masuk }}
                                                        </option>
                                                        <option value="Masih bekerja">Masih bekerja</option>
                                                        <option value="2022">2022</option>
                                                        <option value="2021">2021</option>
                                                        <option value="2020">2020</option>
                                                        <option value="2019">2019</option>
                                                        <option value="2018">2018</option>
                                                        <option value="2017">2017</option>
                                                        <option value="2016">2016</option>
                                                        <option value="2015">2015</option>
                                                        <option value="2014">2014</option>
                                                        <option value="2013">2013</option>
                                                        <option value="2012">2012</option>
                                                        <option value="2011">2011</option>
                                                        <option value="2010">2010</option>
                                                        <option value="2009">2009</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tahun Keluar</label>
                                                    <select name="tahun_keluar" class="form-control" required>
                                                        <option value="{{ $item->tahun_keluar }}">
                                                            {{ $item->tahun_keluar }}</option>
                                                        <option value="Masih bekerja">Masih bekerja</option>
                                                        <option value="2022">2022</option>
                                                        <option value="2021">2021</option>
                                                        <option value="2020">2020</option>
                                                        <option value="2019">2019</option>
                                                        <option value="2018">2018</option>
                                                        <option value="2017">2017</option>
                                                        <option value="2016">2016</option>
                                                        <option value="2015">2015</option>
                                                        <option value="2014">2014</option>
                                                        <option value="2013">2013</option>
                                                        <option value="2012">2012</option>
                                                        <option value="2011">2011</option>
                                                        <option value="2010">2010</option>
                                                        <option value="2009">2009</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
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
