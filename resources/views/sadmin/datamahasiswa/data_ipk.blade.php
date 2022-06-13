@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Export Excel</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-3">
                        <a class="btn btn-success " href="{{ url('export_nilai_ipk') }}">Export Nilai IPK Mahasiswa</a>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data IPK Mahasiswa Politeknik META Industri</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>NIM</center>
                            </th>
                            <th>
                                <center>Nama</center>
                            </th>
                            <th>
                                <center>Program Studi</center>
                            </th>
                            <th width="10%">
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Jumlah SKS</center>
                            </th>
                            <th>
                                <center>IPK</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($ipk as $key)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nim }}</center>
                                </td>
                                <td>{{ $key->nama }}</td>
                                <td>
                                    <center>
                                        @if ($key->kodeprodi == 23)
                                            Teknik Industri
                                        @elseif ($key->kodeprodi == 22)
                                            Teknik Komputer
                                        @elseif ($key->kodeprodi == 24)
                                            Farmasi
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($key->idstatus == 1)
                                            Reguler A
                                        @elseif ($key->idstatus == 2)
                                            Reguler C
                                        @elseif ($key->idstatus == 3)
                                            Reguler B
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $key->total_sks }} SKS
                                    </center>
                                </td>
                                <td>
                                    <center>{{ $key->IPK }}</center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
