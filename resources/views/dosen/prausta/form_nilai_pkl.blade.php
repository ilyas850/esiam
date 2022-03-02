@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Form Penilaian Seminar Prakerin
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('penguji_pkl') }}">Data mahasiswa Seminar Prakerin</a></li>
            <li class="active">Form Nilai Seminar Prakerin</li>
        </ol>
    </section>
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
                        <td>Tempat Prakerin</td>
                        <td>:</td>
                        <td>{{ $data->tempat_prausta }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <form class="" action="{{ url('simpan_nilai_prakerin') }}" method="post"
            enctype="multipart/form-data" name="autoSumForm">
            {{ csrf_field() }}
            <input type="hidden" name="id_settingrelasi_prausta" value="{{ $id }}">
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title"><b>Form Penilaian Pembimbing Lapangan</b> </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nilai Pembimbing Lapangan</label>
                                <font color="red-text">*</font>
                                <span>(tidak wajib untuk kelas karyawan)</span>
                                <input type="number" class="form-control" name="nilai_pembimbing_lapangan">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title"><b>Form Penilaian Dosen Pembimbing</b> </h3>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <tr>
                            <th></th>
                            <th>
                                <center>Parameter Penilaian</center>
                            </th>
                            <th>Bobot (%)</th>
                            <th>Nilai</th>
                        </tr>
                        <tr>
                            <td>A</td>
                            <td>
                                <b>Persiapan Prakerin</b> <br>
                                1. Proses pembuatan proposal dan penentuan tempat Prakerin <br>
                                2. Proses Bimbingan
                            </td>
                            <td>20(%)</td>
                            <td><input type="number" name="nilai_pkl_persiapan" onFocus="startCalc();" onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="2">B</td>
                            <td>
                                <b>Pelaksanaan Prakerin</b> <br>
                                1. Pemahaman akan sistem dan tugas yang dikerjakan
                            </td>
                            <td>40(%)</td>
                            <td><input type="number" name="nilai_pkl_pemahaman" onFocus="startCalc();" onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                2. Motivasi, kreativitas, dan keaktifan mahasiswa
                            </td>
                            <td>10(%)</td>
                            <td><input type="number" name="nilai_pkl_motivasi" onFocus="startCalc();" onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="2">C</td>
                            <td>
                                <b>Laporan Prakerin</b> <br>
                                1. Substansi isi
                            </td>
                            <td>20(%)</td>
                            <td><input type="number" name="nilai_pkl_isi" onFocus="startCalc();" onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                2. Format dan tata tulis
                            </td>
                            <td>10(%)</td>
                            <td><input type="number" name="nilai_pkl_format" onFocus="startCalc();" onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td> <b>Total</b> </td>
                            <td>100</td>
                            <td><input type="text" name="total" value="0" readonly></td>
                        </tr>
                    </table>

                </div>
            </div>
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title"><b>Form Penilaian Seminar</b> </h3>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <tr>
                            <th></th>
                            <th>
                                <center>Parameter Penilaian</center>
                            </th>
                            <th>Bobot (%)</th>
                            <th>Nilai</th>
                        </tr>
                        <tr>
                            <td rowspan="2">A</td>
                            <td>
                                <b>Presentasi</b> <br>
                                1. Komunikasi, penampilan, dan sikap <br>
                            </td>
                            <td>15(%)</td>
                            <td><input type="number" name="nilai_pkl_komunikasi" onFocus="startCalc();"
                                    onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                2. Aspek materi dan estetika slide presentasi
                            </td>
                            <td>20(%)</td>
                            <td><input type="number" name="nilai_pkl_materi" onFocus="startCalc();" onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td>B</td>
                            <td>
                                <b>Penguasaan Materi</b> <br>
                                1. Pemahaman akan sistem <br>
                                2. Pemahaman akan tugas yang dikerjakan
                            </td>
                            <td>50(%)</td>
                            <td><input type="number" name="nilai_pkl_pemahamansistug" onFocus="startCalc();"
                                    onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td>C</td>
                            <td>
                                <b>Kemampuan Menjawab Pertanyaan</b> <br>
                                1. Cara Menjawab <br>
                                2. Substansi jawaban
                            </td>
                            <td>15(%)</td>
                            <td><input type="number" name="nilai_pkl_jawab" onFocus="startCalc();" onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td> <b>Total</b> </td>
                            <td>100</td>
                            <td><input type="text" name="totals" value="0" readonly></td>
                        </tr>
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

        one = document.autoSumForm.nilai_pkl_persiapan.value;

        two = document.autoSumForm.nilai_pkl_pemahaman.value;

        three = document.autoSumForm.nilai_pkl_motivasi.value;

        four = document.autoSumForm.nilai_pkl_isi.value;

        five = document.autoSumForm.nilai_pkl_format.value;

        document.autoSumForm.total.value = (one * 20 / 100) + (two * 40 / 100) + (three * 10 / 100) + (four * 20 /
            100) + (five * 10 / 100);

        one2 = document.autoSumForm.nilai_pkl_komunikasi.value;

        two2 = document.autoSumForm.nilai_pkl_materi.value;

        three2 = document.autoSumForm.nilai_pkl_pemahamansistug.value;

        four2 = document.autoSumForm.nilai_pkl_jawab.value;


        document.autoSumForm.totals.value = (one2 * 15 / 100) + (two2 * 20 / 100) + (three2 * 50 / 100) + (four2 * 15 /
            100);
    }

    function stopCalc() {

        clearInterval(interval);
    }
</script>

{{-- <script>
    function startCalc2() {

        interval = setInterval("calc2()", 1);
    }

    function calc2() {

        one = document.autoSumForm2.nilai_pkl_komunikasi.value;

        two = document.autoSumForm2.nilai_pkl_materi.value;

        three = document.autoSumForm2.nilai_pkl_pemahamansistug.value;

        four = document.autoSumForm2.nilai_pkl_jawab.value;


        document.autoSumForm2.totals.value = (one * 15 / 100) + (two * 20 / 100) + (three * 50 / 100) + (four * 15 /
            100);
    }

    function stopCalc2() {

        clearInterval(interval);
    }
</script> --}}
