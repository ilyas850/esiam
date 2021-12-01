@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Setting Jadwal Prakerin Mahasiswa
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('data_prakerin') }}">Data Prakerin</a></li>
            <li class="active">Setting prakerin</li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <form class="" action="simpan_atur_prakerin/{{ $id }}" method="post"
                enctype="multipart/form-data">
                <div class="box-header">
                    <table width="100%">
                        <tr>
                            <td>NIM</td>
                            <td>:</td>
                            <td>{{ $data->nim }}</td>
                            <td>Program Studi</td>
                            <td>:</td>
                            <td>{{ $data->prodi }}</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>{{ $data->nama }}</td>
                            <td>Kelas</td>
                            <td>:</td>
                            <td>{{ $data->kelas }}</td>
                        </tr>
                    </table>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Dosen Penguji<font color="red-text">*</font></label>
                                <input type="text" class="form-control" name="dosen_penguji_1" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Tanggal Mulai<font color="red-text">*</font></label>
                                <input type="date" class="form-control" name="tanggal_mulai" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Tanggal Selesai<font color="red-text">*</font></label>
                                <input type="date" class="form-control" name="tanggal_selesai" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Jam Mulai<font color="red-text">*</font></label>
                                <input type="text" class="form-control" name="jam_mulai_sidang" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Jam Selesai<font color="red-text">*</font></label>
                                <input type="text" class="form-control" name="jam_selesai_sidang" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ruangan<font color="red-text">*</font></label>
                                <input type="text" class="form-control" name="dosen_penguji_1" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ruangan<font color="red-text">*</font></label>
                                <input type="text" class="form-control" name="dosen_penguji_1" required>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </section>
@endsection
