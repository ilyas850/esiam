@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Filter Periode Tahun Akademik - Semester</h3>
            </div>
            <form class="form" role="form" action="{{ url('filter_rekap_perkuliahan') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label for="">Periode Tahun</label>
                            <select class="form-control" name="id_periodetahun" required>
                                <option></option>
                                @foreach ($tahun as $thn)
                                    <option value="{{ $thn->id_periodetahun }}">{{ $thn->periode_tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Semester</label>
                            <select class="form-control" name="id_periodetipe" required>
                                <option></option>
                                @foreach ($tipe as $tipee)
                                    <option value="{{ $tipee->id_periodetipe }}">{{ $tipee->periode_tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">Filter</button>
                </div>
            </form>
        </div>
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Rekap Perkuliahan <b>{{ $namaperiodetahun }} - {{ $namaperiodetipe }}</b></h3>
            </div>
            <div class="box-body">
                <table id="example8" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Kode/Matakuliah</center>
                            </th>
                            <th>
                                <center>SKS (T/P)</center>
                            </th>
                            <th>
                                <center>Prodi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Dosen</center>
                            </th>
                            <th>
                                <center>Jumlah Pertemuan</center>
                            </th>
                            <th>
                                <center>BAP</center>
                            </th>
                            <th>
                                <center>Download</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>{{ $key->makul }}</td>
                                <td>
                                    <center>{{ $key->sks }}</center>
                                </td>
                                <td>{{ $key->prodi }}</td>
                                <td>{{ $key->kelas }}</td>
                                <td>{{ $key->nama }}</td>
                                <td>
                                    <center>
                                        {{ $key->jml_per }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a href="cek_rekapan/{{ $key->id_kurperiode }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Cek
                                        </a>
                                    </center>
                                </td>
                                <td align="center">
                                    <a href="/download_bap_dosen/{{ $key->id_kurperiode }}" class="btn btn-danger btn-xs" title="klik untuk download BAP"><i class="fa fa-download"></i> BAP</a>
                                    <a href="/download_absensi_mhs/{{ $key->id_kurperiode }}" class="btn btn-danger btn-xs" title="klik untuk download Absensi"><i class="fa fa-download"></i> Absen</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
