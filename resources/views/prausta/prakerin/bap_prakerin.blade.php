@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data BAP Prakerin Mahasiswa</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="form" role="form" action="{{ url('filter_bap_prakerin_use_prodi') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="col-xs-3">
                            <select class="form-control" name="id_prodi" required>
                                <option></option>
                                @foreach ($prodi as $key)
                                    <option value="{{ $key->id_prodi }}">
                                        {{ $key->prodi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Filter Prodi</button>
                    </form>
                </div>
                <br>
                <form action="{{ url('download_bap_prakerin_all') }}" method="POST">
                    {{ csrf_field() }}

                    <table id="example8" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="3%">
                                    <center>No</center>
                                </th>
                                <th>
                                    <center>Nama Mahasiswa</center>
                                </th>
                                <th width="6%">
                                    <center>NIM</center>
                                </th>
                                <th width="11%">
                                    <center>Program Studi</center>
                                </th>
                                <th width="11%">
                                    <center>Kelas</center>
                                </th>
                                <th width="11%">
                                    <center>Angkatan</center>
                                </th>
                                <th>
                                    <center>Aksi</center>
                                </th>
                                {{-- <th width="8%">
                                    <center>Pilih</center>
                                </th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($data as $key)
                                <tr>
                                    <td>
                                        <center>{{ $no++ }}</center>
                                    </td>
                                    <td>{{ $key->nama }}</td>
                                    <td>
                                        <center>{{ $key->nim }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $key->prodi }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $key->kelas }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $key->angkatan }}</center>
                                    </td>
                                    <td>
                                        <center>
                                            <form action="{{ url('download_bap_prakerin') }}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id_settingrelasi_prausta"
                                                    value="{{ $key->id_settingrelasi_prausta }}">
                                                <button class="btn btn-danger btn-xs"> Download BAP</button>
                                            </form>
                                        </center>
                                    </td>
                                    {{-- <td>
                                        <center><input type="checkbox" name="id_settingrelasi_prausta[]"
                                                value="{{ $key->id_settingrelasi_prausta }}">

                                        </center>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    {{-- <input name="Check_All" value="Tandai Semua" onclick="check_all()" type="button"
                        class="btn btn-warning">
                    <input name="Un_CheckAll" value="Hilangkan Semua Tanda" onclick="uncheck_all()" type="button"
                        class="btn btn-warning">
                    <input class="btn btn-info full-right" type="submit" name="submit" value="Download"> --}}
                </form>
            </div>
        </div>
    </section>
    <script language="javascript">
        function check_all() {
            var chk = document.getElementsByName('id_settingrelasi_prausta[]');
            for (i = 0; i < chk.length; i++)
                chk[i].checked = true;
        }

        function uncheck_all() {
            var chk = document.getElementsByName('id_settingrelasi_prausta[]');
            for (i = 0; i < chk.length; i++)
                chk[i].checked = false;
        }
    </script>
@endsection
