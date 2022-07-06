@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"> <b> Detail Rekapitulasi Kuisioner Dosen Pembimbing PKL</b></h3>
                <table width="100%">
                    <tr>
                        <td width="20%">Tahun Akademik / Semester</td>
                        <td>:</td>
                        <td>{{ $periodetahun }} / {{ $periodetipe }}</td>
                    </tr>
                    <tr>
                        <td width="20%">Dosen</td>
                        <td>:</td>
                        <td>{{ $nama_dosen }} </td>
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
                                <center>Komponen Kuisioner</center>
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
                                    {{ $item->komponen_kuisioner }}
                                </td>
                                <td>
                                    <center>
                                        {{ $item->nilai_1 }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $item->nilai_2 }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $item->nilai_3 }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $item->nilai_4 }}
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
