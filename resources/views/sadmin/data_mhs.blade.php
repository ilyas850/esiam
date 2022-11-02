@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Export KRS Mahasiswa</h3>
            </div>
            <form class="form" role="form" action="{{ url('export_xls_data_mhs') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">

                        <div class="col-xs-6">
                            <label for="">Angkatan</label>
                            <select class="form-control" name="idangkatan" required>
                                <option></option>
                                @foreach ($angkatan as $angk)
                                    <option value="{{ $angk->idangkatan }}">{{ $angk->angkatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">Export Xls</button>
                </div>
            </form>
        </div>
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
