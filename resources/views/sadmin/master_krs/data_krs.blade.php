@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Export KRS Mahasiswa</h3>
            </div>
            <form class="form" role="form" action="{{ url('export_krs_mhs') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label for="">Periode Tahun</label>
                            <select class="form-control" name="id_periodetahun">
                                <option></option>
                                @foreach ($thn as $thn)
                                    <option value="{{ $thn->id_periodetahun }}">{{ $thn->periode_tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Periode Tipe</label>
                            <select class="form-control" name="id_periodetipe" required>
                                <option></option>
                                @foreach ($tp as $tipee)
                                    <option value="{{ $tipee->id_periodetipe }}">{{ $tipee->periode_tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Program Studi</label>
                            <select class="form-control" name="id_prodi" required>
                                <option></option>
                                @foreach ($prd as $prd)
                                    <option value="{{ $prd->id_prodi }}">{{ $prd->prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">Export Excel</button>
                </div>
            </form>
        </div>

        <br>

        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Rekap Nilai Mahasiswa</h3>
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
                            <th>Jumlah Mahasiswa</th>
                            <th>Dosen</th>
                            <th>Cek KRS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($krs as $key)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $key->kode }}/{{ $key->makul }}</td>
                                <td>{{ $key->akt_sks_teori + $key->akt_sks_praktek }}</td>
                                <td>{{ $key->prodi }}</td>
                                <td>{{ $key->kelas }}</td>
                                <td>{{ $key->jml_mhs }}</td>
                                <td>{{ $key->nama }}</td>
                                <td>
                                    <a href="cek_krs_mhs/{{ $key->id_kurperiode }}" class="btn btn-info btn-xs">Cek</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
