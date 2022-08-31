@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Data Mahasiswa Politeknik META Industri</h3>
            </div>
            <div class="box-body">

                <table id="example1" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Program Studi</th>
                            <th>Kelas</th>
                            <th>Angkatan</th>
                            <th>NISN</th>
                            <th>Intake</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($mhss as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->nim }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->prodi }} - {{ $item->konsentrasi }}</td>
                                <td>{{ $item->kelas }}</td>
                                <td>{{ $item->angkatan }}</td>
                                <td>{{ $item->nisn }}</td>
                                <td>
                                    @if ($item->intake == 1)
                                        Ganjil
                                    @elseif($item->intake == 2)
                                        Genap
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
