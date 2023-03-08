@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Nilai Mahasiswa</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table">
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
                        @foreach ($data as $item)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nim }}</center>
                                </td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td>
                                    <center>{{ $item->kelas }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->angkatan }} </center>
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
