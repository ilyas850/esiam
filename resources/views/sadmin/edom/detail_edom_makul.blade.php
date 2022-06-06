@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"> <b> Detail Rekapitulasi EDOM (Per Matakuliah)</b></h3>
                <table width="100%">
                    <tr>
                        <td width="20%">Tahun Akademik / Semester</td>
                        <td>:</td>
                        <td>{{ $data_mk->periode_tahun }} / {{ $data_mk->periode_tipe }}</td>
                    </tr>
                    <tr>
                        <td width="20%">Dosen</td>
                        <td>:</td>
                        <td>{{ $data_mk->nama }} </td>
                    </tr>
                    <tr>
                        <td width="20%">Matakuliah</td>
                        <td>:</td>
                        <td>{{ $data_mk->makul }} </td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Deskripsi</center>
                            </th>
                            <th>
                                <center>Nilai 1</center>
                            </th>
                            <th>
                                <center>Nilai 2</center>
                            </th>
                            <th>
                                <center>Nilai 3</center>
                            </th>
                            <th>
                                <center>Nilai 4</center>
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
                                    {{ $item->description }}
                                </td>
                                <td>
                                    <center>
                                        @foreach ($data1 as $item1)
                                            @if ($item->id_edom == $item1->id_edom)
                                                {{ $item1->nilai_1 }}
                                            @endif
                                        @endforeach
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @foreach ($data2 as $item2)
                                            @if ($item->id_edom == $item2->id_edom)
                                                {{ $item2->nilai_2 }}
                                            @endif
                                        @endforeach
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @foreach ($data3 as $item3)
                                            @if ($item->id_edom == $item3->id_edom)
                                                {{ $item3->nilai_3 }}
                                            @endif
                                        @endforeach
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @foreach ($data4 as $item4)
                                            @if ($item->id_edom == $item4->id_edom)
                                                {{ $item4->nilai_4 }}
                                            @endif
                                        @endforeach
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
