@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Pilih Tahun Akademik dan Semester</h3>
            </div>
            <div class="box-body">
                <form class="form" role="form" action="{{ url('filter_rekap_nilai_mhs_wadir') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-xs-3">
                            <label>Periode Tahun</label>
                            <select class="form-control" name="id_periodetahun" required>
                                <option></option>
                                @foreach ($periode_tahun as $tahun)
                                    <option value="{{ $tahun->id_periodetahun }}">
                                        {{ $tahun->periode_tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label>Semester</label>
                            <select class="form-control" name="id_periodetipe" required>
                                <option></option>
                                @foreach ($periode_tipe as $tipe)
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
        </div>
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Rekap Nilai Mahasiswa <b> {{$nama_tahun}} - {{$nama_tipe}} </b></h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th> <center> No</center></th>
                            <th><center>Kode/Matakuliah</center></th>
                            <th><center>SKS</center></th>
                            <th><center>Prodi</center></th>
                            <th><center>Kelas</center></th>
                            <th><center>Jumlah Mahasiswa</center></th>
                            <th><center>Dosen</center></th>
                            <th><center>Nilai</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td>{{ $key->kode }}/{{ $key->makul }}</td>
                                <td align="center">{{ $key->akt_sks_teori + $key->akt_sks_praktek }}</td>
                                <td align="center">{{ $key->prodi }}</td>
                                <td align="center">{{ $key->kelas }}</td>
                                <td align="center">{{ $key->jml_mhs }}</td>  
                                <td>{{ $key->nama }}</td>
                                <td align="center">
                                    <a href="cek_rekap_nilai_mhs_wadir/{{ $key->id_kurperiode }}" class="btn btn-info btn-xs">Cek</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
