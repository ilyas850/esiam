@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title"> <b> Form Isian SKPI Mahasiswa Politeknik META Industri</b></h3>
                <table width="100%">
                    <tr>
                        <td width="10%">Program Studi</td>
                        <td width="1%">:</td>
                        <td>{{ $prodi->prodi }}</td>
                    </tr>
                    <tr>
                        <td>Angkatan</td>
                        <td>:</td>
                        <td>{{ $angkatan->angkatan }}
                        </td>
                    </tr>
                </table>
            </div>
            <form action="{{ url('save_skpi_prodi') }}" method="POST">
                {{ csrf_field() }}

                <div class="box-body">
                    <table class="table table-bordered ">
                        <thead>
                            <tr>
                                <th style="width: 10px">
                                    <center>No</center>
                                </th>
                                <th>
                                    <center>Nama Lengkap</center>
                                </th>
                                <th>
                                    <center>Tempat dan Tanggal lahir</center>
                                </th>
                                <th>
                                    <center>NIM</center>
                                </th>
                                <th>
                                    <center>Nomor SKPI</center>
                                </th>
                                <th>
                                    <center>Nomor Ijazah</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($data as $item)
                                <tr>

                                    <td align="center">{{ $no++ }}</td>
                                    <td>{{ $item->nama_lengkap }}</td>
                                    <td>{{ $item->tmpt_lahir }},
                                        {{ Carbon\Carbon::parse($item->tgl_lahir)->formatLocalized('%d %B %Y') }}</td>
                                    <td align="center">{{ $item->nim }}</td>
                                    <input type="hidden" name="id_student[]" value="{{ $item->id_student }}">
                                    <td><input type="text" class="form-control" name="no_skpi[]"
                                            value="{{ $item->no_skpi }}" required></td>
                                    <td><input type="text" class="form-control" name="no_ijazah[]"
                                            value="{{ $item->no_ijazah }}" required></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    <font color="red-text">*</font>Masukan Tanggal Masuk
                                </label>
                                <input type="date" class="form-control" name="date_masuk"
                                    placeholder="Masukan Tanggal Lulus" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    <font color="red-text">*</font>Masukan Tanggal Lulus
                                </label>
                                <input type="date" class="form-control" name="date_lulus"
                                    placeholder="Masukan Tanggal Lulus" required>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <button type="submit" class="btn btn-info btn-block">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
