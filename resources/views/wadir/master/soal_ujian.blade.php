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
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2">
                                <center>No</center>
                            </th>
                            <th rowspan="2">
                                <center>Kode/Matakuliah</center>
                            </th>
                            <th rowspan="2">
                                <center>Program Studi</center>
                            </th>
                            <th rowspan="2">
                                <center>Kelas</center>
                            </th>
                            <th rowspan="2">
                                <center>Dosen</center>
                            </th>
                            <th colspan="3">
                                <center> UTS</center>
                            </th>
                            <th colspan="3">
                                <center> UAS</center>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <center>Soal</center>
                            </th>
                            <th>
                                <center>Sifat Ujian</center>
                            </th>
                            <th>
                                <center>Ket.</center>
                            </th>
                            <th>
                                <center>Soal</center>
                            </th>
                            <th>
                                <center>Sifat Ujian</center>
                            </th>
                            <th>
                                <center>Ket.</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>{{ $item->makul }}</td>
                                <td>
                                    <center>{{ $item->prodi }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->kelas }}</center>
                                </td>
                                <td>
                                    {{ $item->nama }}
                                </td>
                                <td>
                                    <center>

                                        @if ($item->soal_uts == null)
                                            Belum
                                        @else
                                            @if ($item->validasi_uts == 'BELUM' or $item->validasi_uts == null)
                                                <span class="badge bg-yellow"><i class="fa fa-close"></i></span><a
                                                    href="/Soal Ujian/UTS/{{ $item->id_kurperiode }}/{{ $item->soal_uts }}"
                                                    target="_blank" style="font: white"> File</a>
                                            @else
                                                <span class="badge bg-green"><i class="fa fa-check"></i></span>
                                                <a href="/Soal Ujian/UTS/{{ $item->id_kurperiode }}/{{ $item->soal_uts }}"
                                                    target="_blank" style="font: white"> File</a>
                                            @endif
                                        @endif
                                    </center>
                                </td>
                                <td>{{ $item->tipe_ujian_uts }}</td>
                                <td>
                                    @if ($item->komentar_uts == null)
                                    @else
                                        <a class="btn btn-danger btn-xs" data-toggle="modal"
                                            data-target="#modalTambahKomentarUts{{ $item->id_soal }}">Komentar</a>
                                    @endif
                                </td>
                                <td>
                                    <center>
                                        @if ($item->soal_uas == null)
                                            Belum
                                        @else
                                            @if ($item->validasi_uas == 'BELUM' or $item->validasi_uts == null)
                                                <span class="badge bg-yellow"><i class="fa fa-close"></i></span>
                                                <a href="/Soal Ujian/UAS/{{ $item->id_kurperiode }}/{{ $item->soal_uas }}"
                                                    target="_blank" style="font: white"> File</a>
                                            @else
                                                <span class="badge bg-green"><i class="fa fa-check"></i></span>
                                                <a href="/Soal Ujian/UAS/{{ $item->id_kurperiode }}/{{ $item->soal_uas }}"
                                                    target="_blank" style="font: white"> File</a>
                                            @endif
                                        @endif
                                    </center>
                                </td>
                                <td>{{ $item->tipe_ujian_uas }}</td>
                                <td>
                                    @if ($item->komentar_uas == null)
                                    @else
                                        <a class="btn btn-danger btn-xs" data-toggle="modal"
                                            data-target="#modalTambahKomentarUas{{ $item->id_soal }}">Komentar</a>
                                    @endif
                                </td>
                            </tr>
                            <div class="modal fade" id="modalTambahKomentarUts{{ $item->id_soal }}" tabindex="-1"
                                aria-labelledby="modalTambahKomentarUts" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Komentar</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <textarea class="form-control" name="komentar_uts" cols="20" rows="10"> {{ $item->komentar_uts }} </textarea>
                                            </div>
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="modalTambahKomentarUas{{ $item->id_soal }}" tabindex="-1"
                                aria-labelledby="modalTambahKomentarUas" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Komentar</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <textarea class="form-control" name="komentar_uas" cols="20" rows="10"> {{ $item->komentar_uas }} </textarea>
                                            </div>
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Tutup</button>
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
