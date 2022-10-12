@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Kritik & Saran Mahasiswa - <b>{{ $kat->kategori_kritiksaran }}</b></h3>
            </div>
            <div class="box-body">
                <table id="example8" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>Tahun Akademik</center>
                            </th>
                            <th>
                                <center>Mahasiswa</center>
                            </th>
                            <th>
                                <center>Kritik</center>
                            </th>
                            <th>
                                <center>Saran</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td align="center">{{ $item->periode_tahun }} - {{ $item->periode_tipe }}</td>
                                <td>{{ $item->nim }}/{{ $item->nama }}</td>
                                <td>{{ $item->kritik }}</td>
                                <td>{{ $item->saran }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
