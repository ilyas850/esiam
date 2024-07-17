@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h4 class="box-title"><b>Data Mahasiswa</b> </h4>
            </div>
            <div class="box-body">
                <table width="100%">

                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $data->nama }}</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td>{{ $data->nim }}</td>
                    </tr>
                    <tr>
                        <td>Tempat PraUSTA</td>
                        <td>:</td>
                        <td>{{ $data->tempat_prausta }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <form class="" action="{{ url('simpan_nilai_sempro_skripsi_dosji1') }}" method="post"
            enctype="multipart/form-data" name="autoSumForm">
            {{ csrf_field() }}
            <input type="hidden" name="id_settingrelasi_prausta" value="{{ $id }}">
            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title"><b>Form Penilaian Dosen Penguji I</b> </h3>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="3%" align="center">No</th>
                                <th width="35%">
                                    <center>Komponen Penilaian</center>
                                </th>
                                <th width="47%">
                                    <center>Acuan Penilaian</center>
                                </th>
                                <th width="10%">
                                    <center>Bobot (%)</center>
                                </th>
                                <th width="5%">
                                    <center>Nilai</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($form_peng1 as $item)
                                <tr>
                                    <td>
                                        <center>{{ $no++ }}</center>
                                    </td>
                                    <td>{{ $item->komponen }}</td>
                                    <td>{{ $item->acuan }}</td>
                                    <td>
                                        <center>{{ $item->bobot }}%</center>
                                    </td>
                                    <td>
                                        <center>
                                            <input type="hidden" name="id_penilaian_prausta[]"
                                                value="{{ $item->id_penilaian_prausta }}">
                                            <input type="number" name="nilai[]" required>
                                            <center>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <button type="submit" class="btn btn-info">Simpan</button>
        </form>
    </section>
@endsection

<script>
    function startCalc() {

        interval = setInterval("calc()", 1);
    }

    function calc() {

        one = document.autoSumForm.nilai_sempro_1.value;

        two = document.autoSumForm.nilai_sempro_2.value;

        three = document.autoSumForm.nilai_sempro_3.value;

        four = document.autoSumForm.nilai_sempro_4.value;

        five = document.autoSumForm.nilai_sempro_5.value;

        document.autoSumForm.total.value = (one * 30 / 100) + (two * 20 / 100) + (three * 35 / 100) + (four * 10 /
            100) + (five * 5 / 100);

    }

    function stopCalc() {

        clearInterval(interval);
    }
</script>
