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
            <form class="form" role="form" action="{{ url('filter_jadwal_perkuliahan') }}" method="POST">
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
                                <center>Hari</center>
                            </th>
                            <th>
                                <center>Jam</center>
                            </th>
                            <th>
                                <center>Ruangan</center>
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
                                        {{ $key->hari }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $key->jam }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $key->nama_ruangan }}
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
