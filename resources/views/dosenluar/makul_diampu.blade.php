@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Data Matakuliah yang diampu
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li class="active">Data Matakuliah yang diampu</li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        {{-- <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Pilih Tahun Akademik dan Periode</h3>
            </div>
            <div class="box-body">
                <form class="form" role="form" action="{{ url('filter_makul_diampu_dsn_luar') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-xs-3">
                            <label>Periode Tahun</label>
                            <select class="form-control" name="id_periodetahun" required>
                                <option></option>
                                @foreach ($thn as $tahun)
                                    <option value="{{ $tahun->id_periodetahun }}">
                                        {{ $tahun->periode_tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label>Semester</label>
                            <select class="form-control" name="id_periodetipe" required>
                                <option></option>
                                @foreach ($tp as $tipe)
                                    <option value="{{ $tipe->id_periodetipe }}">
                                        {{ $tipe->periode_tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-3">
                            <button type="submit" class="btn btn-success">Lihat</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Matakuliah <b> {{ $nama_periodetahun }} - {{ $nama_periodetipe }} </b></h3>
            </div>
            <div class="box-body">
                <table id="example8" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Kode </center>
                            </th>
                            <th>
                                <center>Matakuliah</center>
                            </th>
                            <th>
                                <center>Program Studi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Semester</center>
                            </th>
                            <th>
                                <center>Ruangan</center>
                            </th>
                            <th>
                                <center>Jadwal</center>
                            </th>
                            <th>
                                <center>Id Absen</center>
                            </th>
                            <th>
                                <center>Soal</center>
                            </th>
                            <th>
                                <center>Ket. UTS</center>
                            </th>
                            <th>
                                <center>Ket. UAS</center>
                            </th>
                            <th>
                                <center>Entri (Nilai/BAP)</center>
                            </th>
                            <th>
                                <center>Export</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($makul as $item)
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
                                <td>{{ $item->nama_ruangan }}</td>
                                <td>{{ $item->hari }}, {{ $item->jam }}</td>
                                <td>
                                    <center>{{ $item->id_kurperiode }}</center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->soal_uts == null)
                                            <button class="btn btn-success btn-xs" data-toggle="modal"
                                                data-target="#modalUploadSoalUts{{ $item->id_kurperiode }}">
                                                <i class="fa fa-cloud-upload" title="Klik untuk upload soal uts"></i>
                                                UTS</button>
                                        @else
                                            <button class="btn btn-warning btn-xs" data-toggle="modal"
                                                data-target="#modalUploadSoalUts{{ $item->id_kurperiode }}"
                                                title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                            <a href="/Soal Ujian/UTS/{{ $item->id_kurperiode }}/{{ $item->soal_uts }}"
                                                target="_blank" style="font: white"> UTS</a>
                                        @endif

                                        @if ($item->soal_uas == null)
                                            <button class="btn btn-success btn-xs" data-toggle="modal"
                                                data-target="#modalUploadSoalUas{{ $item->id_kurperiode }}"><i
                                                    class="fa fa-cloud-upload" title="Klik untuk upload soal uas"></i>
                                                UAS</button>
                                        @else
                                            <button class="btn btn-warning btn-xs" data-toggle="modal"
                                                data-target="#modalUploadSoalUas{{ $item->id_kurperiode }}"
                                                title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                            <a href="/Soal Ujian/UAS/{{ $item->id_kurperiode }}/{{ $item->soal_uas }}"
                                                target="_blank" style="font: white"> UAS</a>
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->validasi_uts == 'BELUM' or $item->validasi_uts == null)
                                            @if ($item->komentar_uts == null)
                                            @else
                                                <a class="btn btn-danger btn-xs" data-toggle="modal"
                                                    data-target="#modalTambahKomentarUts{{ $item->id_soal }}">Komentar</a>
                                            @endif
                                        @elseif ($item->validasi_uts == 'SUDAH')
                                            <span class="badge bg-blue">Valid</span>
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->validasi_uas == 'BELUM' or $item->validasi_uas == null)
                                            @if ($item->komentar_uas == null)
                                            @else
                                                <a class="btn btn-danger btn-xs" data-toggle="modal"
                                                    data-target="#modalTambahKomentarUas{{ $item->id_soal }}">Komentar</a>
                                            @endif
                                        @elseif ($item->validasi_uas == 'SUDAH')
                                            <span class="badge bg-blue">Valid</span>
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a href="cekmhs/{{ $item->id_kurperiode }}" class="btn btn-info btn-xs"
                                            title="Klik untuk entri nilai">Nilai</a>
                                        </a>
                                        <a href="entri_bap_dsn/{{ $item->id_kurperiode }}" class="btn btn-warning btn-xs"
                                            title="Klik untuk entri nilai">
                                            BAP</a>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a href="/export_xlsnilai_dsn/{{ $item->id_kurperiode }}"
                                            class="btn btn-success btn-xs"><i class="fa fa-file-excel-o"
                                                title="Klik untuk export nilai .xls">
                                            </i></a>
                                        <a href="/unduh_pdf_nilai_dsn/{{ $item->id_kurperiode }}"
                                            class="btn btn-danger btn-xs"><i class="fa fa-file-pdf-o"
                                                title="Klik untuk export nilai .pdf">
                                            </i></a>
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
                                            <form action="{{ url('simpan_soal_uts_dsn_luar') }}" method="post"
                                                enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id_kurperiode"
                                                    value="{{ $item->id_kurperiode }}">
                                                <div class="form-group">
                                                    <label>Tipe Ujian</label>
                                                    <select name="tipe_ujian_uts" class="form-control" required>
                                                        <option value="{{ $item->tipe_ujian_uts }}">
                                                            {{ $item->tipe_ujian_uts }}</option>
                                                        <option value="TATAP MUKA">TATAP MUKA</option>
                                                        <option value="TAKE HOME">TAKE HOME</option>
                                                        <option value="PROJECT">PROJECT</option>
                                                        <option value="PRAKTIKUM">PRAKTIKUM</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>File Soal UTS</label>
                                                    <input type="file" class="form-control" name="soal_uts" required>
                                                    <span>Max. size 4 mb dengan format (.pdf) atau (.doc)</span>
                                                </div>
                                                <div class="form-group">
                                                    <label>Cetak Soal</label>
                                                    <select name="cetak_soal_uts" class="form-control" required>
                                                        <option value="{{ $item->cetak_soal_uts }}">
                                                            {{ $item->cetak_soal_uts }}</option>
                                                        <option value="YA">YA</option>
                                                        <option value="TIDAK">TIDAK</option>
                                                    </select>
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
                                            <form action="{{ url('simpan_soal_uas_dsn_luar') }}" method="post"
                                                enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id_kurperiode"
                                                    value="{{ $item->id_kurperiode }}">
                                                <div class="form-group">
                                                    <label>Tipe Ujian</label>
                                                    <select name="tipe_ujian_uas" class="form-control" required>
                                                        <option value="{{ $item->tipe_ujian_uas }}">
                                                            {{ $item->tipe_ujian_uas }}</option>
                                                        <option value="TATAP MUKA">TATAP MUKA</option>
                                                        <option value="TAKE HOME">TAKE HOME</option>
                                                        <option value="PROJECT">PROJECT</option>
                                                        <option value="PRAKTIKUM">PRAKTIKUM</option>
                                                    </select>
                                                </div>
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
