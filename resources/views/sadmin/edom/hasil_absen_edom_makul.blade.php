@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Rekap Absen EDOM Matakuliah {{ $idtahun->periode_tahun }} - {{ $idtipe->periode_tipe }}
                </h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode - Matakuliah</th>
                            <th>Prodi</th>
                            <th>Qty EDOM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->kode }} - {{ $item->makul }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td>{{ $item->jml_mhs }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
