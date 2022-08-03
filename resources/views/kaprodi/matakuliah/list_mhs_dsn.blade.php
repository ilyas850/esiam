@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Data List Mahasiswa
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('makul_diampu_kprd') }}"> Data Matakuliah yang diampu</a></li>
            <li class="active">Data List Mahasiswa </li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data List Mahasiswa</h3>
            </div>
            <div class="box-body">
                @if ($nilai == null)
                    <button type="button" class="btn btn-primary mr-5" data-toggle="modal" data-target="#addsettingnilai">
                        Setting Persentase (%) Nilai
                    </button>
                @else
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                        data-target="#editsettingnilai{{ $nilai->id_settingnilai }}">
                        Edit Setting Persentase (%) Nilai
                    </button>
                    <a href="/input_kat_kprd/{{ $ids }}" class="btn btn-success btn-sm">Input Nilai KAT
                        ({{ $nilai->kat }}%)</a>
                    <a href="/input_uts_kprd/{{ $ids }}" class="btn btn-info btn-sm">Input Nilai UTS
                        ({{ $nilai->uts }}%)</a>
                    <a href="/input_uas_kprd/{{ $ids }}" class="btn btn-warning btn-sm">Input Nilai UAS
                        ({{ $nilai->uas }}%)</a>
                    {{-- <a href="/input_akhir_kprd/{{ $ids }}" class="btn btn-danger btn-sm">Input Nilai AKHIR</a> --}}
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-danger">
                        Generate Nilai Akhir
                    </button>

                    <div class="modal fade" id="editsettingnilai{{ $nilai->id_settingnilai }}" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="post" action="/put_settingnilai_dsn_kprd/{{ $nilai->id_settingnilai }}">
                                @csrf
                                @method('put')
                                <input type="hidden" value="{{ $nilai->id_kurperiode }}" name="id_kurperiode">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Setting Nilai Matakuliah</h5>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Nilai KAT (%)</label>
                                                    <input type="number" name="kat" class="form-control"
                                                        value="{{ $nilai->kat }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Nilai UTS (%)</label>
                                                    <input type="number" name="uts" class="form-control"
                                                        value="{{ $nilai->uts }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Nilai UAS (%)</label>
                                                    <input type="number" name="uas" class="form-control"
                                                        value="{{ $nilai->uas }}" required>
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
                @endif

                <br><br>
                <div class="modal modal-danger fade" id="modal-danger">
                    <div class="modal-dialog">
                        <form action="{{ url('generate_nilai_akhir_dsn_kprd') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_kurperiode" value="{{ $ids }}">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Generate Nilai Akhir</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Anda yakin akan menyimpan nilai matakuliah ini ? &hellip;</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline pull-left"
                                        data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-outline">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal fade" id="addsettingnilai" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('post_settingnilai_dsn_kprd') }}"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="id_kurperiode" value="{{ $ids }}">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Setting Nilai Matakuliah</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Nilai KAT (%)</label>
                                                <input type="number" name="kat" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Nilai UTS (%)</label>
                                                <input type="number" name="uts" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Nilai UAS (%)</label>
                                                <input type="number" name="uas" class="form-control" required>
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
                            <th width="4%">
                                <center>No</center>
                            </th>
                            <th width="8%">
                                <center>NIM </center>
                            </th>
                            <th width="20%">
                                <center>Nama</center>
                            </th>
                            <th width="15%">
                                <center>Program Studi</center>
                            </th>
                            <th width="8%">
                                <center>Kelas</center>
                            </th>
                            <th width="8%">
                                <center>Angkatan</center>
                            </th>
                            <th>
                                <center>Nilai KAT</center>
                            </th>
                            <th>
                                <center>Nilai UTS</center>
                            </th>
                            <th>
                                <center>Nilai UAS</center>
                            </th>
                            <th>
                                <center>Nilai AKHIR</center>
                            </th>
                            <th>
                                <center>Nilai HURUF</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($ck as $item)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nim }}</center>
                                </td>
                                <td>{{ $item->nama }}</td>
                                <td> {{ $item->prodi }}</td>
                                <td>
                                    <center> {{ $item->kelas }} </center>
                                </td>
                                <td>
                                    <center> {{ $item->angkatan }} </center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_KAT }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_UTS }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_UAS }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_AKHIR_angka }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_AKHIR }}</center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
