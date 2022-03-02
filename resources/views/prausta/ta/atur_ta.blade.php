@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Setting Jadwal Sidang Tugas Akhir Mahasiswa
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('data_ta') }}">Data TA</a></li>
            <li class="active">Setting Siang TA</li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <form class="" action="{{ url('simpan_atur_ta') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id_settingrelasi_prausta" value="{{ $id }}">
                <div class="box-header">
                    <h3 class="box-title"><b>Data Mahasiswa</b> </h3>
                </div>
                <div class="box-body">
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
        </div>
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title"><b>Setting Sidang Tugas Akhir</b> </h3>

            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Tanggal Selesai Bimbingan<font color="red-text">*</font></label>
                            <input type="date" class="form-control" name="tanggal_selesai"
                                value="{{ $data->tanggal_selesai }}" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Jam Mulai<font color="red-text">*</font></label>
                            <select name="jam_mulai_sidang" class="form-control" required>
                                <option value="{{ $data->jam_mulai_sidang }}">{{ $data->jam_mulai_sidang }}</option>
                                @foreach ($jam as $item)
                                    <option value="{{ $item->jam }}">{{ $item->jam }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Jam Selesai<font color="red-text">*</font></label>
                            <select name="jam_selesai_sidang" class="form-control" required>
                                <option value="{{ $data->jam_selesai_sidang }}">{{ $data->jam_selesai_sidang }}
                                </option>
                                @foreach ($jam as $item)
                                    <option value="{{ $item->jam }}">{{ $item->jam }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Dosen Penguji 1<font color="red-text">*</font></label>
                            <select name="id_dosen_penguji_1" class="form-control" required>
                                <option value="{{ $data->id_dosen_penguji_1 }},{{ $data->dosen_penguji_1 }}">
                                    {{ $data->dosen_penguji_1 }}</option>
                                @foreach ($dosen as $item)
                                    <option value="{{ $item->iddosen }},{{ $item->nama }}">{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Dosen Penguji 2<font color="red-text">*</font></label>
                            <select name="id_dosen_penguji_2" class="form-control" required>
                                <option value="{{ $data->id_dosen_penguji_2 }},{{ $data->dosen_penguji_2 }}">
                                    {{ $data->dosen_penguji_2 }}</option>
                                @foreach ($dosen as $item)
                                    <option value="{{ $item->iddosen }},{{ $item->nama }}">{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Ruangan<font color="red-text">*</font></label>
                            <select name="ruangan" class="form-control" required>
                                <option value="{{ $data->ruangan }}">{{ $data->ruangan }}</option>
                                @foreach ($ruangan as $item)
                                    <option value="{{ $item->nama_ruangan }}">{{ $item->nama_ruangan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="submit" class="btn btn-info">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </section>
@endsection
