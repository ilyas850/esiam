@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Kritik & Saran</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <button type="button" class="btn btn-success mr-5" data-toggle="modal" data-target="#addsertifikat">
                            Input Kritik & Saran
                        </button>
                    </div>
                </div>
                <br>
                <div class="modal fade" id="addsertifikat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('post_kritiksaran') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Input Kritik & Saran</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Pilih Kategori</label>
                                        <select name="id_kategori_kritiksaran" class="form-control" required>
                                            <option></option>
                                            @foreach ($kategori as $kate)
                                                <option value="{{ $kate->id_kategori_kritiksaran }}">
                                                    {{ $kate->kategori_kritiksaran }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Kritik</label>
                                        <textarea name="kritik" class="form-control" cols="10" rows="5" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Saran</label>
                                        <textarea name="saran" class="form-control" cols="10" rows="5" required></textarea>
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
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Tahun Akademik</center>
                            </th>
                            <th>
                                <center>Kategori</center>
                            </th>
                            <th>
                                <center>Kritik</center>
                            </th>
                            <th>
                                <center>Saran</center>
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
                                <td>{{ $item->periode_tahun }} - {{ $item->periode_tipe }}</td>
                                <td>{{ $item->kategori_kritiksaran }}</td>
                                <td>{{ $item->kritik }}</td>
                                <td>{{ $item->saran }}</td>
                                <td align="center">
                                    @if ($item->tp_status == 'ACTIVE' && $item->thn_status == 'ACTIVE')
                                        <button class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#modalUpdateKritiksaran{{ $item->id_trans_kritiksaran }}"
                                            title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                    @else
                                        <button class="btn btn-danger btn-xs" disabled><i class="fa fa-edit"></i></button>
                                    @endif

                                </td>
                            </tr>

                            <div class="modal fade" id="modalUpdateKritiksaran{{ $item->id_trans_kritiksaran }}"
                                tabindex="-1" aria-labelledby="modalUpdateKritiksaran" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Kritik & Saran</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/put_kritiksaran/{{ $item->id_trans_kritiksaran }}"
                                                method="post" enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                                <div class="form-group">
                                                    <label>Kategori</label>
                                                    <select name="id_kategori_kritiksaran" class="form-control">
                                                        <option value="{{ $item->id_kategori_kritiksaran }}">
                                                            {{ $item->kategori_kritiksaran }}</option>
                                                        @foreach ($kategori as $kate)
                                                            <option value="{{ $kate->id_kategori_kritiksaran }}">
                                                                {{ $kate->kategori_kritiksaran }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Kritik</label>
                                                    <textarea name="kritik" class="form-control" cols="10" rows="5" required>{{ $item->kritik }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Saran</label>
                                                    <textarea name="saran" class="form-control" cols="10" rows="5" required>{{ $item->saran }}</textarea>
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
