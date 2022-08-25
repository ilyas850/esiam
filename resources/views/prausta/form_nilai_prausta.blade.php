@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title"> <b> Data List Mahasiswa </b></h3>

                <table width="100%">
                    <tr>
                        <td>Kode Prausta</td>
                        <td>:</td>
                        <td>{{ $kode_prausta }}</td>
                        <td>Prodi</td>
                        <td>:</td>
                        <td>{{ $prodi }}</td>
                    </tr>
                    <tr>
                        <td>Nama Prausta</td>
                        <td>:</td>
                        <td>{{ $nama_prausta }}</td>
                        <td>Angkatan</td>
                        <td>:</td>
                        <td>{{ $nama_angkatan }}</td>
                    </tr>
                </table>
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
                                    <center>Nilai Prausta</center>
                                </th>
                                <th>
                                    <center>Nilai Transkrip</center>
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
                                            @if ($item->nilai_huruf != null)
                                                <select name="nilai_AKHIR[]">
                                                    <option
                                                        value="{{ $item->id_studentrecord }},{{ $item->nilai_huruf }}">
                                                        {{ $item->nilai_huruf }}</option>
                                                </select>
                                            @elseif ($item->nilai_huruf != null)
                                                {{ $item->nilai_AKHIR }}
                                            @endif

                                            {{-- @if ($item->nilai_AKHIR == '0')
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
                                                {{ $item->nilai_AKHIR }}
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
                                            @elseif($item->nilai_huruf != null)
                                                <select name="nilai_AKHIR[]">
                                                    <option
                                                        value="{{ $item->id_studentrecord }},{{ $item->nilai_huruf }}">
                                                        {{ $item->nilai_huruf }}</option>
                                                </select>
                                            @endif --}}
                                        </center>
                                    </td>
                                    <td align="center">
                                        {{ $item->nilai_AKHIR }}
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
