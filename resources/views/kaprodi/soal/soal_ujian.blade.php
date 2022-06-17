@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Soal UTS dan UAS</h3>
            </div>
            <div class="box-body">
                <table id="example4" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4%">
                                <center>No</center>
                            </th>
                            <th width="8%">
                                <center>Kode </center>
                            </th>
                            <th width="20%">
                                <center>Matakuliah</center>
                            </th>
                            <th width="15%">
                                <center>Program Studi</center>
                            </th>
                            <th width="10%">
                                <center>Kelas</center>
                            </th>
                            <th width="10%">
                                <center>Semester</center>
                            </th>
                            <th width="10%">
                                <center>Id Absen</center>
                            </th>
                            <th width="8%">Soal UTS</th>
                            <th width="8%">Soal UAS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->kode }}</center>
                                </td>
                                <td>{{ $item->makul }}</td>
                                <td>
                                    <center>{{ $item->prodi }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->kelas }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->semester }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->id_kurperiode }} </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->soal_uts == null)
                                            <button class="btn btn-success btn-xs" data-toggle="modal"
                                                data-target="#modalUploadSoalUts{{ $item->id_kurperiode }}"
                                                title="klik untuk upload">Upload</button>
                                        @else
                                            <button class="btn btn-warning btn-xs" data-toggle="modal"
                                                data-target="#modalUploadSoalUts{{ $item->id_kurperiode }}"
                                                title="klik untuk edit"><i class="fa fa-edit"></i></button> |
                                            <a href="/Soal Ujian/UTS/{{ $item->id_kurperiode }}/{{ $item->soal_uts }}"
                                                target="_blank" style="font: white"> Soal UTS</a>
                                        @endif

                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->soal_uas == null)
                                            <button class="btn btn-success btn-xs" data-toggle="modal"
                                                data-target="#modalUploadSoalUas{{ $item->id_kurperiode }}"
                                                title="klik untuk upload">Upload</button>
                                        @else
                                            <button class="btn btn-warning btn-xs" data-toggle="modal"
                                                data-target="#modalUploadSoalUas{{ $item->id_kurperiode }}"
                                                title="klik untuk edit"><i class="fa fa-edit"></i></button> |
                                            <a href="/Soal Ujian/UAS/{{ $item->id_kurperiode }}/{{ $item->soal_uas }}"
                                                target="_blank" style="font: white"> Soal UAS</a>
                                        @endif
                                    </center>
                                </td>
                            </tr>
                            <div class="modal fade" id="modalUploadSoalUts{{ $item->id_kurperiode }}" tabindex="-1"
                                aria-labelledby="modalUploadSoalUts" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Upload Soal UTS {{ $item->makul }}</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('simpan_soal_uts_dsn_kprd') }}" method="post"
                                                enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id_kurperiode"
                                                    value="{{ $item->id_kurperiode }}">
                                                <div class="form-group">
                                                    <label>File Soal UTS</label>
                                                    <input type="file" class="form-control" name="soal_uts">
                                                    <span>Max. size 4 mb dengan format (.pdf) atau (.doc)</span>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="modalUploadSoalUas{{ $item->id_kurperiode }}" tabindex="-1"
                                aria-labelledby="modalUploadSoalUas" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Upload Soal UAS {{ $item->makul }}</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('simpan_soal_uas_dsn_kprd') }}" method="post"
                                                enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id_kurperiode"
                                                    value="{{ $item->id_kurperiode }}">
                                                <div class="form-group">
                                                    <label>File Soal UAS</label>
                                                    <input type="file" class="form-control" name="soal_uas">
                                                    <span>Max. size 4 mb dengan format (.pdf) atau (.doc)</span>
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
