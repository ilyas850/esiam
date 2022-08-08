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
                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode/Matakuliah</th>
                            <th>SKS</th>
                            <th>Prodi</th>
                            <th>Kelas</th>
                            <th>Dosen</th>
                            <th>Jumlah Pertemuan</th>
                            <th>Perkuliahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td><span style="font-size:95%">
                                        <center>{{ $no++ }}</center>
                                    </span></td>
                                <td><span style="font-size:95%">{{ $key->kode }}/{{ $key->makul }}</span></td>
                                <td><span style="font-size:95%">
                                        <center>{{ $key->akt_sks_teori + $key->akt_sks_praktek }}</center>
                                    </span></td>
                                <td><span style="font-size:95%">{{ $key->prodi }}</span></td>
                                <td><span style="font-size:95%">{{ $key->kelas }}</span< /td>
                                <td><span style="font-size:95%">{{ $key->nama }}</span></td>
                                <td>
                                    <center><span style="font-size:95%">
                                            @foreach ($jml as $keyjml)
                                                @if ($key->id_kurperiode == $keyjml->id_kurperiode)
                                                    {{ $keyjml->jml_per }}
                                                @endif
                                            @endforeach
                                        </span></center>
                                </td>
                                <td>
                                    <center>
                                        <a href="cek_rekapan/{{ $key->id_kurperiode }}" class="btn btn-info btn-xs">Cek
                                            BAP</a>
                                    </center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
