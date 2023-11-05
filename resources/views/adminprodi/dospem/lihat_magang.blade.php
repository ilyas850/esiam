@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Dosen Pembimbing Magang</h3>
            </div>
            <div class="box-body">
                <form class="form" role="form" action="{{ url('save_dsn_bim_magang') }}" method="POST">
                    {{ csrf_field() }}
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>
                                    <center>No</center>
                                </th>
                                <th>
                                    <center>NIM </center>
                                </th>
                                <th>
                                    Nama Mahasiswa
                                </th>
                                <th>
                                    Prodi
                                </th>
                                <th>
                                    Kelas
                                </th>
                                <th>
                                    <center>Pilih Pembimbing</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($data as $keydsn)
                                <tr>
                                    <td>
                                        <center>{{ $no++ }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $keydsn->nim }}</center>
                                    </td>
                                    <td>{{ $keydsn->nama }}</td>
                                    <td>{{ $keydsn->prodi }}</td>
                                    <td>{{ $keydsn->kelas }}</td>
                                    <td>
                                        <center>
                                            <select name="iddosen[]">
                                                <option></option>
                                                @foreach ($dosen as $keyangk)
                                                    <option
                                                        value="{{ $keydsn->idstudent }},{{ $keyangk->iddosen }},{{ $keyangk->nama }}, {{ $keydsn->id_masterkode_prausta }}">
                                                        {{ $keyangk->nama }}</option>
                                                @endforeach
                                            </select>
                                        </center>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button class="btn btn-info" type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </section>
@endsection
