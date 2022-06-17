@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Soal UTS dan UAS</h3>
            </div>
            <div class="box-body">
                <table id="example4" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4%">
                                <center>No</center>
                            </th>
                            <th width="8%">
                                <center>Kode </center>
                            </th>
                            <th width="20%">
                                <center>Matakuliah</center>
                            </th>
                            <th width="15%">
                                <center>Program Studi</center>
                            </th>
                            <th width="10%">
                                <center>Kelas</center>
                            </th>
                            <th width="10%">
                                <center>Semester</center>
                            </th>
                            <th width="10%">
                                <center>Id Absen</center>
                            </th>
                            <th width="8%">Soal UTS</th>
                            <th width="8%">Soal UAS</th>
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
                                    <center>{{ $item->kode }}</center>
                                </td>
                                <td>{{ $item->makul }}</td>
                                <td>
                                    <center>{{ $item->prodi }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->kelas }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->semester }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->id_kurperiode }} </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->soal_uts == null)
                                            Belum ada
                                        @else
                                            <a href="/Soal Ujian/UTS/{{ $item->id_kurperiode }}/{{ $item->soal_uts }}"
                                                target="_blank" style="font: white"> Soal UTS</a>
                                        @endif

                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->soal_uas == null)
                                            Belum ada
                                        @else
                                            <a href="/Soal Ujian/UAS/{{ $item->id_kurperiode }}/{{ $item->soal_uas }}"
                                                target="_blank" style="font: white"> Soal UAS</a>
                                        @endif

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
