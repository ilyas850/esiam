@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <b> Kartu Hasil Studi Mahasiswa (MID-TERM)</b>
                <table width="100%">
                    <tr>
                        <td>TA Semester</td>
                        <td>:</td>
                        <td>
                            {{ $nama_periodetahun }}
                            {{ $nama_periodetipe }}

                        </td>
                        <td align=right>Jumlah SKS Maksimal&ensp; </td>
                        <td> : </td>
                        <td>24</td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $mhs->nama }}</td>
                        <td align=right>SKS Tempuh&ensp; </td>
                        <td> : </td>
                        <td>{{ $sks }}</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td>{{ $mhs->nim }}
                        </td>
                    </tr>
                    <tr>
                        <td>Program Studi</td>
                        <td> : </td>
                        <td>{{ $mhs->prodi }}
                        </td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $mhs->kelas }}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="box-body">
                <form class="" action="{{ url('unduh_khs_mid_term') }}" method="post">
                    {{ csrf_field() }}

                    <input type="hidden" name="id_student" value="{{ $id }}">
                    <input type="hidden" name="id_periodetahun" value="{{ $idthn }}">
                    <input type="hidden" name="id_periodetipe" value="{{ $idtp }}">
                    <button type="submit" class="btn btn-danger ">Unduh KHS</button>
                </form>
                <br>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Kode</center>
                            </th>
                            <th>
                                <center>Matakuliah</center>
                            </th>
                            <th>
                                <center>SKS Teori/Praktek</center>
                            </th>
                            <th>
                                <center>Nilai Angka</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($record as $item)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>
                                    <center>
                                        {{ $item->kode }}
                                    </center>
                                </td>
                                <td>
                                    {{ $item->makul }}
                                </td>
                                <td>
                                    <center>{{ $item->akt_sks_teori }}/{{ $item->akt_sks_praktek }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_UTS }}</center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
