@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Mahasiswa</h3>
            </div>
            <form action="{{ url('save_nilai_UAS_admin') }}" method="post">
                {{ csrf_field() }}
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <center>No</center>
                                </th>
                                <th>
                                    <center>NIM </center>
                                </th>
                                <th>
                                    <center>Nama</center>
                                </th>
                                <th>
                                    <center>Program Studi</center>
                                </th>
                                <th>
                                    <center>Kelas</center>
                                </th>
                                <th>
                                    <center>Angkatan</center>
                                </th>
                                <th>
                                    <center>Nilai UAS</center>
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
                                        <center>{{ $item->nim }}</center>
                                    </td>
                                    <td>{{ $item->nama }}</td>
                                    <td> {{ $item->prodi }} </td>
                                    <td>
                                        <center> {{ $item->kelas }} </center>
                                    </td>
                                    <td>
                                        <center> {{ $item->angkatan }} </center>
                                    </td>
                                    <td>
                                        <center>
                                            @if ($item->nilai_UAS == 0)
                                                <input type="hidden" name="id_student[]"
                                                    value="{{ $item->id_student }},{{ $item->id_kurtrans }}">
                                                <input type="hidden" name="id_studentrecord[]"
                                                    value="{{ $item->id_studentrecord }}">
                                                <input type="text" name="nilai_UAS[]">
                                            @elseif ($item->nilai_UAS != 0)
                                                <input type="hidden" name="id_student[]"
                                                    value="{{ $item->id_student }},{{ $item->id_kurtrans }}">
                                                <input type="hidden" name="id_studentrecord[]"
                                                    value="{{ $item->id_studentrecord }}">
                                                <input type="number" name="nilai_UAS[]" value="{{ $item->nilai_UAS }}">
                                            @endif
                                        </center>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <input type="hidden" name="id_kurperiode" value="{{ $id }}">
                    <input class="btn btn-info btn-block" type="submit" name="submit" value="Simpan">
                </div>
            </form>
        </div>
    </section>
@endsection
