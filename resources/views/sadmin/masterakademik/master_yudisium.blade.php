@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Yudisium Mahasiswa Politeknik META Industri</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Program Studi</th>
                            <th>Tempat Lahir</th>
                            <th>Tanggal Lahir</th>
                            <th>NIK</th>
                            <th>Ijazah</th>
                            <th>KTP</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->nim }}</td>
                                <td>{{ $item->nama_lengkap }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td>{{ $item->tmpt_lahir }}</td>
                                <td>{{ Carbon\Carbon::parse($item->tgl_lahir)->formatLocalized('%d %B %Y') }} {{$item->tgl_lahir->isoFormat('D MMMM Y')}}
                                </td>
                                <td>{{ $item->nik }}</td>
                                <td><a href="/File Yudisium/{{ $item->id_student }}/{{ $item->file_ijazah }}" target="_blank">
                                        File </a></td>
                                <td><a href="/File Yudisium/{{ $item->id_student }}/{{ $item->file_ktp }}" target="_blank">
                                        File </a></td>
                                <td><a href="/File Yudisium/{{ $item->id_student }}/{{ $item->file_foto }}"
                                        target="_blank"> File </a></td>
                                <td>
                                    <button class="btn btn-success btn-xs" data-toggle="modal"
                                        data-target="#modalUpdateYudisium{{ $item->id_yudisium }}"
                                        title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                    @if ($item->validasi == 'BELUM')
                                        <a href="/validate_yudisium/{{ $item->id_yudisium }}" class="btn btn-info btn-xs"
                                            title="klik untuk validasi"><i class="fa fa-check"></i></a>
                                    @elseif($item->validasi == 'SUDAH')
                                        <a href="/unvalidate_yudisium/{{ $item->id_yudisium }}"
                                            class="btn btn-danger btn-xs" title="klik untuk batal validasi"><i
                                                class="fa fa-close"></i></a>
                                        <a href="/unduh_ijazah/{{ $item->id_yudisium }}" class="btn btn-warning btn-xs"
                                            title="klik untuk unduh ijazah"><i class="fa fa-download"></i></a>
                                    @endif

                                </td>
                            </tr>
                            <div class="modal fade" id="modalUpdateYudisium{{ $item->id_yudisium }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Yudisium</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/saveedit_yudisium/{{ $item->id_yudisium }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <div class="form-group">
                                                            <label>Nama Lengkap</label>
                                                            <input type="text" class="form-control" name="nama_lengkap"
                                                                value="{{ $item->nama_lengkap }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="form-group">
                                                            <label>Nomor Induk Kependudukan</label>
                                                            <input type="number" class="form-control" name="nik"
                                                                value="{{ $item->nik }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <div class="form-group">
                                                            <label>Tempat Lahir</label>
                                                            <input type="text" class="form-control" name="tmpt_lahir"
                                                                value="{{ $item->tmpt_lahir }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="form-group">
                                                            <label>Tanggal Lahir</label>
                                                            <input type="date" class="form-control" name="tgl_lahir"
                                                                value="{{ $item->tgl_lahir }}" required>
                                                        </div>
                                                    </div>
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
