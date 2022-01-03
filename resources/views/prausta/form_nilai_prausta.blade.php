@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data List Mahasiswa</h3>
            </div>
            <form action="{{ url('save_nilai_prausta') }}" method="post">
                {{ csrf_field() }}
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">
                                    <center>No</center>
                                </th>
                                <th width="10%">
                                    <center>NIM </center>
                                </th>
                                <th width="25%">
                                    <center>Nama</center>
                                </th>
                                <th width="15%">
                                    <center>Program Studi</center>
                                </th>
                                <th width="10%">
                                    <center>Kelas</center>
                                </th>
                                <th width="10%">
                                    <center>Angkatan</center>
                                </th>
                                <th>
                                    <center>Nilai AKHIR</center>
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
                                    <td>{{ $item->prodi }}</td>
                                    <td>
                                        <center>{{ $item->kelas }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $item->angkatan }}</center>
                                    </td>
                                    <td>
                                        <center>
                                            @if ($item->nilai_AKHIR == '0')
                                                <select name="nilai_AKHIR[]">
                                                    <option value="{{ $item->id_studentrecord }},0"></option>
                                                    <option value="{{ $item->id_studentrecord }},A">A</option>
                                                    <option value="{{ $item->id_studentrecord }},B+">B+</option>
                                                    <option value="{{ $item->id_studentrecord }},B">B</option>
                                                    <option value="{{ $item->id_studentrecord }},C+">C+</option>
                                                    <option value="{{ $item->id_studentrecord }},C">C</option>
                                                    <option value="{{ $item->id_studentrecord }},D">D</option>
                                                    <option value="{{ $item->id_studentrecord }},E">E</option>
                                                </select>
                                            @elseif ($item->nilai_AKHIR != '0')
                                                <select name="nilai_AKHIR[]">
                                                    <option
                                                        value="{{ $item->id_studentrecord }},{{ $item->nilai_AKHIR }}">
                                                        {{ $item->nilai_AKHIR }}</option>
                                                    <option value="{{ $item->id_studentrecord }},A">A</option>
                                                    <option value="{{ $item->id_studentrecord }},B+">B+</option>
                                                    <option value="{{ $item->id_studentrecord }},B">B</option>
                                                    <option value="{{ $item->id_studentrecord }},C+">C+</option>
                                                    <option value="{{ $item->id_studentrecord }},C">C</option>
                                                    <option value="{{ $item->id_studentrecord }},D">D</option>
                                                    <option value="{{ $item->id_studentrecord }},E">E</option>
                                                </select>
                                            @endif
                                        </center>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>

                    <input class="btn btn-info" type="submit" name="submit" value="Simpan">
                </div>
            </form>
        </div>
    </section>
@endsection
