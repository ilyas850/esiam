@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Dosen Pembimbing Seminar Proposal, Tugas Akhir & Skripsi</h3>
            </div>
            <div class="box-body">
                <form class="form" role="form" action="{{ url('save_dsn_bim_sempro_ta') }}" method="POST">
                    {{ csrf_field() }}
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>
                                    <center>No</center>
                                </th>
                                <th>
                                    <center>NIM</center>
                                </th>
                                <th>
                                    <center>Nama Mahasiswa</center>
                                </th>
                                <th>
                                    <center>Kelas</center>
                                </th>
                                <th>
                                    <center>Konsentrasi</center>
                                </th>
                                <th>
                                    <center>Dosen Pembimbing</center>
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
                                    <td align="center">{{ $keydsn->kelas }}</td>
                                    <td>{{ $keydsn->konsentrasi }}</td>
                                    <td>
                                        <center>
                                            <select name="iddosen[]">
                                                <option></option>
                                                @foreach ($dosen as $keyangk)
                                                    <option
                                                        value="{{ $keydsn->idstudent }},{{ $keyangk->iddosen }},{{ $keyangk->nama }},{{ $keydsn->id_prodi }}">
                                                        {{ $keyangk->nama }}</option>
                                                @endforeach
                                            </select>
                                        </center>
                                    </td>
                                </tr>
                                {{-- <input type="hidden" name="id_masterkode_prausta1" value="{{ $kode1 }}">
                                <input type="hidden" name="id_masterkode_prausta2" value="{{ $kode2 }}"> --}}
                            @endforeach
                        </tbody>
                    </table>

                    <button class="btn btn-info" type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </section>
@endsection
