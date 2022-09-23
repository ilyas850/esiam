@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data SKPI Mahasiswa</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="form" role="form" action="{{ url('filter_skpi') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="col-xs-3">
                            <select class="form-control" name="kodeprodi" required>
                                <option></option>
                                @foreach ($prodi as $key)
                                    <option value="{{ $key->kodeprodi }}">
                                        {{ $key->prodi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <select class="form-control" name="idangkatan" required>
                                <option></option>
                                @foreach ($angkatan as $keyan)
                                    <option value="{{ $keyan->idangkatan }}">
                                        {{ $keyan->angkatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Filter</button>
                    </form>
                </div>
                <br>
                <table id="example8" class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2">
                                <center>No</center>
                            </th>
                            <th rowspan="2">
                                <center>NIM</center>
                            </th>
                            <th rowspan="2">
                                <center>Nama</center>
                            </th>
                            <th rowspan="2">
                                <center>Tempat, tanggal lahir</center>
                            </th>
                            <th colspan="2">
                                <center>Tanggal, Bulan dan Tahun </center>
                            </th>
                            <th rowspan="2">
                                <center>No. SKPI</center>
                            </th>
                            <th rowspan="2">
                                <center>No. Ijazah</center>
                            </th>
                            <th rowspan="2">
                                <center>Aksi</center>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <center>Masuk</center>
                            </td>
                            <td>
                                <center>Lulus</center>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td align="center">{{ $item->nim }}</td>
                                <td> {{ $item->nama_lengkap }}</td>
                                <td>{{ $item->tmpt_lahir }},
                                    {{ Carbon\Carbon::parse($item->tgl_lahir)->formatLocalized('%d %B %Y') }}
                                </td>
                                <td>{{ $item->date_masuk }}</td>
                                <td>{{ $item->date_lulus }}</td>
                                <td>{{ $item->no_skpi }}</td>
                                <td>{{ $item->no_ijazah }}</td>
                                <td align="center">
                                    @if ($item->id_skpi != null)
                                        <a href="/download_skpi/{{ $item->id_skpi }}"
                                            class="btn btn-info btn-xs">Download</a>
                                    @else
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
