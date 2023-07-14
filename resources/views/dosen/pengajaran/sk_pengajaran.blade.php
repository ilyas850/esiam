@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">SK Mengajar & LKD</h3>
            </div>
            <div class="box-body">
                <table id="example8" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Kode/Matakuliah</center>
                            </th>
                            <th>
                                <center>Program Studi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Tahun Akademik</center>
                            </th>
                            <th>
                                <center>SK Mengajar </center>
                            </th>
                            <th>
                                <center>LKD </center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td>{{ $item->makul }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td>{{ $item->kelas }}</td>
                                <td align="center">{{ $item->periode_tahun }} {{ $item->periode_tipe }}</td>
                                <td align="center">
                                    @if ($item->file == null)
                                    @else
                                        <a href="{{ asset('/SK Mengajar/' . $item->file) }}" target="_blank">File</a>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->file == null)
                                    @else
                                        <a href="/unduh_lkd_dosen_dlm/{{ $item->id_kurperiode }}"
                                            class="btn btn-danger btn-xs"><i class="fa fa-file-pdf-o"
                                                title="Klik untuk unduh LKD .pdf">
                                            </i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
