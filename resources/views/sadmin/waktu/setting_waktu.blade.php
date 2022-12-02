@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Setting Waktu</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success mr-5" data-toggle="modal" data-target="#addwaktu">
                            Input Setting Waktu
                        </button>
                    </div>
                </div>
                <br>
                <div class="modal fade" id="addwaktu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('post_waktu') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Setting Waktu</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Tipe Waktu</label>
                                                <select class="form-control" name="tipe_waktu" required>
                                                    <option></option>
                                                    <option value="1">Yudisium</option>
                                                    <option value="2">Wisuda</option>
                                                    <option value="3">Penangguhan</option>
                                                    <option value="4">Beasiswa</option>
                                                    <option value="5">UTS</option>
                                                    <option value="6">UAS</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Deskripsi</label>
                                                <textarea name="deskripsi" class="form-control" cols="10" rows="3" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Waktu Awal</label>
                                                <input type="date" class="form-control" name="waktu_awal" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Waktu Akhir</label>
                                                <input type="date" class="form-control" name="waktu_akhir" required>
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
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Tipe Waktu</center>
                            </th>
                            <th>
                                <center>Deskripsi</center>
                            </th>
                            <th>
                                <center>Waktu Awal</center>
                            </th>
                            <th>
                                <center>Waktu Akhir</center>
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
                                <td align="center">
                                    @if ($item->tipe_waktu == 1)
                                        Yudisium
                                    @elseif($item->tipe_waktu == 2)
                                        Wisuda
                                    @elseif($item->tipe_waktu == 3)
                                        Penangguhan
                                    @elseif($item->tipe_waktu == 4)
                                        Beasiswa
                                    @elseif($item->tipe_waktu == 5)
                                        UTS
                                    @elseif($item->tipe_waktu == 6)
                                        UAS
                                    @endif
                                </td>
                                <td>{{ $item->deskripsi }}</td>
                                <td align="center">{{ Carbon\Carbon::parse($item->waktu_awal)->formatLocalized('%d %B %Y') }} </td>
                                <td align="center">{{ Carbon\Carbon::parse($item->waktu_akhir)->formatLocalized('%d %B %Y') }}</td>
                                <td align="center">
                                    @if ($item->status == 1)
                                        <a href="/nonaktifkan_waktu/{{ $item->id_waktu }}" class="btn btn-danger btn-xs"
                                            title="klik untuk nonaktifkan"
                                            onclick="return confirm('anda yakin akan menonaktifkan ini?')">Nonaktifkan</a>
                                    @elseif($item->status == 0)
                                        <a href="/aktifkan_waktu/{{ $item->id_waktu }}" class="btn btn-info btn-xs"
                                            title="klik untuk aktifkan"
                                            onclick="return confirm('anda yakin akan mengaktifkan ini?')">Aktifkan</a>
                                    @endif
                                </td>
                                <td align="center">
                                    <button class="btn btn-success btn-xs" data-toggle="modal"
                                        data-target="#modalUpdateWaktu{{ $item->id_waktu }}" title="klik untuk edit"><i
                                            class="fa fa-edit"></i></button>
                                    <a href="hapus_waktu/{{ $item->id_waktu }}" class="btn btn-danger btn-xs"
                                        title="klik untuk hapus"
                                        onclick="return confirm('anda yakin akan menghapus ini?')"><i
                                            class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="modalUpdateWaktu{{ $item->id_waktu }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Setting Waktu</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/put_waktu/{{ $item->id_waktu }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('put')

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Tipe Waktu</label>
                                                            <select class="form-control" name="tipe_waktu" required>
                                                                <option value="{{ $item->tipe_waktu }}">
                                                                    @if ($item->tipe_waktu == 1)
                                                                        Yudisium
                                                                    @elseif($item->tipe_waktu == 2)
                                                                        Wisuda
                                                                    @elseif($item->tipe_waktu == 3)
                                                                        Penangguhan
                                                                    @elseif($item->tipe_waktu == 4)
                                                                        Beasiswa
                                                                    @elseif($item->tipe_waktu == 5)
                                                                        UTS
                                                                    @elseif($item->tipe_waktu == 6)
                                                                        UAS
                                                                    @endif
                                                                </option>
                                                                <option value="1">Yudisium</option>
                                                                <option value="2">Wisuda</option>
                                                                <option value="3">Penangguhan</option>
                                                                <option value="4">Beasiswa</option>
                                                                <option value="5">UTS</option>
                                                                <option value="6">UAS</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Deskripsi</label>
                                                        <textarea name="deskripsi" class="form-control" cols="10" rows="3" required>{{ $item->deskripsi }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Waktu Awal</label>
                                                            <input type="date" class="form-control" name="waktu_awal"
                                                                value="{{$item->waktu_awal}}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Waktu Akhir</label>
                                                            <input type="date" class="form-control" name="waktu_akhir"
                                                                value="{{ $item->waktu_akhir }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="updated_by"
                                                    value="{{ Auth::user()->name }}">
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
