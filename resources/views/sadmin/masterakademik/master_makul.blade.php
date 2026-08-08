@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Master Matakuliah</h3>
            </div>
            <div class="box-body">
                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalTambahMakul">
                    Tambah Matakuliah
                </button>

                <br><br>

                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Kode</center>
                            </th>
                            <th>
                                <center>Matakuliah</center>
                            </th>
                            <th>
                                <center>SKS Teori</center>
                            </th>
                            <th>
                                <center>SKS Praktek</center>
                            </th>
                            <th>
                                <center>Total SKS</center>
                            </th>
                            <th>
                                <center>Status</center>
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
                                <td>{{ $item->kode }}</td>
                                <td>{{ $item->makul }}</td>
                                <td align="center">{{ $item->akt_sks_teori }}</td>
                                <td align="center">{{ $item->akt_sks_praktek }}</td>
                                <td align="center">{{ $item->akt_sks_teori + $item->akt_sks_praktek }}</td>
                                <td align="center">
                                    @if ($item->active == 1)
                                        <span class="label label-success">Aktif</span>
                                    @else
                                        <span class="label label-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td align="center">
                                    <button class="btn btn-success btn-xs" data-toggle="modal"
                                        data-target="#modalUpdateMakul{{ $item->idmakul }}" title="klik untuk edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-xs" data-toggle="modal"
                                        data-target="#modalHapusMakul{{ $item->idmakul }}" title="klik untuk hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalUpdateMakul{{ $item->idmakul }}" tabindex="-1" role="dialog"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ url('update_makul/' . $item->idmakul) }}" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('put') }}
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title">Update Matakuliah</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Kode Matakuliah</label>
                                                    <input type="text" class="form-control" name="kode"
                                                        value="{{ $item->kode }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Nama Matakuliah</label>
                                                    <input type="text" class="form-control" name="makul"
                                                        value="{{ $item->makul }}" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>SKS Teori</label>
                                                            <input type="number" min="0" step="0.5"
                                                                class="form-control" name="akt_sks_teori"
                                                                value="{{ $item->akt_sks_teori }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>SKS Praktek</label>
                                                            <input type="number" min="0" step="0.5"
                                                                class="form-control" name="akt_sks_praktek"
                                                                value="{{ $item->akt_sks_praktek }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select class="form-control" name="active" required>
                                                        <option value="1" {{ $item->active == 1 ? 'selected' : '' }}>
                                                            Aktif
                                                        </option>
                                                        <option value="0" {{ $item->active == 0 ? 'selected' : '' }}>
                                                            Tidak Aktif
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="modal fade" id="modalHapusMakul{{ $item->idmakul }}" tabindex="-1" role="dialog"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ url('delete_makul') }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="idmakul" value="{{ $item->idmakul }}">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title">Nonaktifkan Matakuliah</h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>Anda yakin akan menonaktifkan matakuliah <b>{{ $item->makul }}</b>?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-danger">Nonaktifkan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalTambahMakul" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ url('simpan_makul') }}" method="post">
                {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Tambah Matakuliah</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Kode Matakuliah</label>
                            <input type="text" class="form-control" name="kode" placeholder="Masukan kode matakuliah"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Nama Matakuliah</label>
                            <input type="text" class="form-control" name="makul"
                                placeholder="Masukan nama matakuliah" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>SKS Teori</label>
                                    <input type="number" min="0" step="0.5" class="form-control"
                                        name="akt_sks_teori" value="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>SKS Praktek</label>
                                    <input type="number" min="0" step="0.5" class="form-control"
                                        name="akt_sks_praktek" value="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="active" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
