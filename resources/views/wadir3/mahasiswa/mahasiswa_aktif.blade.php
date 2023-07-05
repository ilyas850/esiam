@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Export Data Mahasiswa</h3>
            </div>
            <form class="form" role="form" action="{{ url('export_xls_data_mhs_wadir3') }}" method="POST">
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
                <h3 class="box-title">Mahasiswa Aktif Politeknik META Industri</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered">
                    <thead>
                        <tr>
                            <th><center>No</center></th>
                            <th><center>NIM</center></th>
                            <th><center>Nama Mahasiswa</center></th>
                            <th><center>Program Studi</center></th>
                            <th><center>Kelas</center></th>
                            <th><center>Angkatan</center></th>
                            <th><center>NISN</center></th>
                            <th><center>Intake</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td align="center">{{ $item->nim }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->prodi }} - {{ $item->konsentrasi }}</td>
                                <td align="center">{{ $item->kelas }}</td>
                                <td align="center">{{ $item->angkatan }}</td>
                                <td align="center">{{ $item->nisn }}</td>
                                <td align="center">
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
