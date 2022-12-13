@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                Tabel Bimbingan Perwalian
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success mr-5" data-toggle="modal" data-target="#addbimpa">
                            Input Data Bimbingan
                        </button>
                    </div>
                </div>
                <br>
                <div class="modal fade" id="addbimpa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('post_bim_pa') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="id_dosbim_pa" value="{{ $dsn_pa->id }}">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah Data Bimbingan</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Tanggal Bimbingan</label>
                                        <input type="date" class="form-control" name="tanggal_bimbingan" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Isi Bimbingan</label>
                                        <textarea name="isi_bimbingan" class="form-control" cols="30" rows="10" required></textarea>
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
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Tahun Akademik</center>
                            </th>
                            <th>
                                <center>Tanggal Bimbingan</center>
                            </th>
                            <th>
                                <center>Uraian Bimbingan</center>
                            </th>
                            <th>
                                <center>Validasi</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td>{{ $key->periode_tahun }} - {{ $key->periode_tipe }}</td>
                                <td align="center">{{ $key->tanggal_bimbingan }}</td>
                                <td>{{ $key->isi_bimbingan }}</td>
                                <td align="center">
                                    @if ($key->validasi == 'BELUM' or $key->validasi == null)
                                        <span class="badge bg-yellow">BELUM</span>
                                    @elseif($key->validasi == 'SUDAH')
                                        <span class="badge bg-blue">Sudah</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($key->validasi == 'BELUM' or $key->validasi == null)
                                        <button class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#modalUpdateBim{{ $key->id_transbim_perwalian }}"
                                            title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                        <a href="hapus_bim_perwalian/{{ $key->id_transbim_perwalian }}"
                                            class="btn btn-danger btn-xs" title="klik untuk hapus"
                                            onclick="return confirm('anda yakin akan menghapus ini?')"><i
                                                class="fa fa-trash"></i></a>
                                    @endif
                                </td>
                            </tr>

                            <div class="modal fade" id="modalUpdateBim{{ $key->id_transbim_perwalian }}" tabindex="-1"
                                aria-labelledby="modalUpdateBim" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Data Bimbingan</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/edit_bimbingan_perwalian/{{ $key->id_transbim_perwalian }}"
                                                method="post" enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                                <div class="form-group">
                                                    <label>Tanggal Bimbingan</label>
                                                    <input type="date" class="form-control" name="tanggal_bimbingan"
                                                        value="{{ $key->tanggal_bimbingan }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Isi Bimbingan</label>
                                                    <textarea name="isi_bimbingan" class="form-control" cols="30" rows="10">{{ $key->isi_bimbingan }}</textarea>

                                                </div>

                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Perbarui
                                                    Data</button>
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
