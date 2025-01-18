@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Form Pengajuan Mengundurkan Diri</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success mr-5" data-toggle="modal" data-target="#addresignmhs">
                            Input Data Pengunduran Diri
                        </button>
                    </div>
                </div>
                <br>
                <div class="modal fade" id="addresignmhs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('post_pengunduran_diri') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Input Data Pengunduran Diri</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Mengundurkan Diri Pada Tahun Akademik</label>
                                                <select name="id_periodetahun" class="form-control" required>
                                                    <option></option>
                                                    @foreach ($tahun as $thn)
                                                        <option value="{{ $thn->id_periodetahun }}">
                                                            {{ $thn->periode_tahun }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Mengundurkan Diri Pada Semester</label>
                                                <select name="id_periodetipe" class="form-control" required>
                                                    <option></option>
                                                    @foreach ($tipe as $tp)
                                                        <option value="{{ $tp->id_periodetipe }}">
                                                            {{ $tp->periode_tipe }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Alasan</label>
                                                <textarea name="alasan" class="form-control" cols="10" rows="5" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>No. HP</label>
                                                <input type="text" name="no_hp" class="form-control" required>
                                            </div>
                                        </div>
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
                <table id="example8" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2">
                                <center>No</center>
                            </th>
                            <th rowspan="2">
                                <center>Tahun Akademik</center>
                            </th>
                            <th rowspan="2">
                                <center>Alasan</center>
                            </th>
                            <th rowspan="2">
                                <center>No. HP</center>
                            </th>
                            <th colspan="4">
                                <center>Validasi</center>
                            </th>
                            <th rowspan="2">
                                <center>Aksi</center>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <center>BAUK</center>
                            </th>
                            <th>
                                <center>Dosen PA</center>
                            </th>
                            <th>
                                <center>Kaprodi</center>
                            </th>
                            <th>
                                <center>BAAK</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td align="center">{{ $item->periode_tahun }} - {{ $item->periode_tipe }}</td>
                                <td>{{ $item->alasan }}</td>
                                <td align="center">{{ $item->no_hp }}</td>
                                <td align="center">
                                    @if ($item->val_bauk == 'BELUM')
                                        <span class="badge bg-yellow">{{ $item->val_bauk }}</span>
                                    @else
                                        <span class="badge bg-green">{{ $item->val_bauk }}</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->val_dsn_pa == 'BELUM')
                                        <span class="badge bg-yellow">{{ $item->val_dsn_pa }}</span>
                                    @else
                                        <span class="badge bg-green">{{ $item->val_dsn_pa }}</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->val_kaprodi == 'BELUM')
                                        <span class="badge bg-yellow">{{ $item->val_kaprodi }}</span>
                                    @else
                                        <span class="badge bg-green">{{ $item->val_kaprodi }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->val_baak == 'BELUM')
                                        <span class="badge bg-yellow">{{ $item->val_baak }}</span>
                                    @else
                                        <span class="badge bg-green">{{ $item->val_baak }}</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->val_bauk == 'BELUM')
                                        <button class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#modalUpdateResign{{ $item->id_trans_pengajuan }}"
                                            title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                        <a class="btn btn-danger btn-xs"
                                            href="/batal_pengajuan_resign/{{ $item->id_trans_pengajuan }}"
                                            onclick="return confirm('anda yakin akan mebatalkan ini ?')"><i
                                                class="fa fa-trash"></i></a>
                                    @endif
                                </td>
                            </tr>
                            <div class="modal fade" id="modalUpdateResign{{ $item->id_trans_pengajuan }}" tabindex="-1"
                                aria-labelledby="modalUpdateResign" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Pengajuan Pengunduran Diri</h5>
                                        </div>
                                        <form action="/put_pengajuan_resign/{{ $item->id_trans_pengajuan }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('put')
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Mengajuan Cuti Pada Tahun Akademik</label>
                                                            <select name="id_periodetahun" class="form-control" required>
                                                                <option value="{{ $item->id_periodetahun }}">
                                                                    {{ $item->periode_tahun }}</option>
                                                                @foreach ($tahun as $thn)
                                                                    <option value="{{ $thn->id_periodetahun }}">
                                                                        {{ $thn->periode_tahun }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Mengajuan Cuti Pada Semester</label>
                                                            <select name="id_periodetipe" class="form-control" required>
                                                                <option value="{{ $item->id_periodetipe }}">
                                                                    {{ $item->periode_tipe }}</option>
                                                                @foreach ($tipe as $tp)
                                                                    <option value="{{ $tp->id_periodetipe }}">
                                                                        {{ $tp->periode_tipe }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Alasan</label>
                                                            <textarea name="alasan" class="form-control" cols="10" rows="5" required> {{ $item->alasan }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>No. HP</label>
                                                            <input type="text" name="no_hp"
                                                                value="{{ $item->no_hp }}" class="form-control"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
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
