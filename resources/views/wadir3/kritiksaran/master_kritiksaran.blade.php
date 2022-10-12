@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Kritik & Saran Mahasiswa</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table no-margin">
                        <thead>
                            <tr>
                                <th>
                                    <center>No</center>
                                </th>
                                <th>
                                    <center>Kategori</center>
                                </th>
                                <th>
                                    <center>Jumlah</center>
                                </th>
                                <th>
                                    <center>Aksi</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($data as $item)
                                <tr>
                                    <td align="center">{{ $no++ }}</td>
                                    <td>{{ $item->kategori_kritiksaran }}</td>
                                    <td align="center">{{ $item->jml }}</td>
                                    <td align="center">
                                        <a href="/cek_kritiksaran/{{$item->id_kategori_kritiksaran}}" class="btn btn-info btn-xs">Cek data</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
