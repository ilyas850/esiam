@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Form Penilaian Tugas Akhir
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('penguji_ta_dsnlr') }}">Data mahasiswa Tugas Akhir</a></li>
            <li class="active">Form Nilai Tugas Akhir</li>
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
        <form class="" action="{{ url('simpan_nilai_ta_dospem_dsnlr') }}" method="post"
            enctype="multipart/form-data" name="autoSumForm">
            {{ csrf_field() }}
            <input type="hidden" name="id_settingrelasi_prausta" value="{{ $id }}">

            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title"><b>Form Penilaian Dosen Pembimbing</b> </h3>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <tr>
                            <th></th>
                            <th>
                                <center>Komponen Penilaian</center>
                            </th>
                            <th>
                                <center>Acuan Penilaian</center>
                            </th>
                            <th>Bobot (%)</th>
                            <th>Nilai</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>
                                Kualitas Judul
                            </td>
                            <td>- Jelas keterkaitannya dengan bidang ilmu di Program Studi <br>
                                - Memiliki aspek kebaruan <br>
                                - Kesesuaian dengan permasalahan akademik
                                dan isi pembahasan
                            </td>
                            <td>10(%)</td>
                            <td><input type="number" name="nilai_ta_1" onFocus="startCalc();" onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>
                                Kualitas Abstrak
                            </td>
                            <td>- Memuat minimal 4 aspek (Latar Belakang,
                                Rumusan Masalah, Metodologi, dan Hasil
                                Peneltitian) <br>
                                - Kurang lebih 400 kata</td>
                            <td>10(%)</td>
                            <td><input type="number" name="nilai_ta_2" onFocus="startCalc();" onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>
                                Kajian Pustakaan
                            </td>
                            <td>- Penguasaan literatur <br>
                                - Kebaruan pustaka <br>
                                - Kerangka pemikiran</td>
                            <td>10(%)</td>
                            <td><input type="number" name="nilai_ta_3" onFocus="startCalc();" onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>
                                Aspek Metodologi (Logic of
                                Discovery)
                            </td>
                            <td>- Ada teori / framework <br>
                                - Kejelasan dalam fungsi teori (apakah sebagai
                                landasan [kuantitatif] atau sebagai landasan
                                [kualitatif] <br>
                                - Kaitan teori dengan desain penelitian <br>
                                - Penggunaan metode pengumpulan data dan
                                analisis</td>
                            <td>10(%)</td>
                            <td><input type="number" name="nilai_ta_4" onFocus="startCalc();" onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>
                                Organisasi / Logika
                                Sistematika
                            </td>
                            <td>- Antar bab, antar sub-bab, dan antar bab dengan
                                sub-bab menunjukkan adanya satu kesatuan
                                logis <br>
                                - Judul, rumusan masalah, dan bab pembahasan
                                koheren</td>
                            <td>20(%)</td>
                            <td><input type="number" name="nilai_ta_5" onFocus="startCalc();" onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>
                                Aspek Pembahasan
                            </td>
                            <td>- Kejelasan pembahasan <br>
                                - Terkontrol (tidak melebar ke pembahasan yang
                                tidak terkait) <br>
                                - Ketajaman analisis</td>
                            <td>20(%)</td>
                            <td><input type="number" name="nilai_ta_6" onFocus="startCalc();" onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>
                                Sumber Rujukan

                            </td>
                            <td>- Ketersediaan sumber otoritatif dan jumlahnya <br>
                                - Ketersediaan sumber primer dan jumlahnya <br>
                                - Jumlah rujukan yang digunakan</td>
                            <td>10(%)</td>
                            <td><input type="number" name="nilai_ta_7" onFocus="startCalc();" onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>
                                Kualitas Bahasa
                            </td>
                            <td>- Ketetapan dalam penggunaan kata kunci
                                - Ketetapan dalam pembuatan kalimat, paragraf,
                                dan uraian pada umumnya
                                - Ketetapan dalam gramatika</td>
                            <td>10(%)</td>
                            <td><input type="number" name="nilai_ta_8" onFocus="startCalc();" onBlur="stopCalc();">
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td> <b>Total</b> </td>
                            <td>100</td>
                            <td><input type="text" name="total" value="0" readonly></td>
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

        one = document.autoSumForm.nilai_ta_1.value;

        two = document.autoSumForm.nilai_ta_2.value;

        three = document.autoSumForm.nilai_ta_3.value;

        four = document.autoSumForm.nilai_ta_4.value;

        five = document.autoSumForm.nilai_ta_5.value;

        six = document.autoSumForm.nilai_ta_6.value;

        seven = document.autoSumForm.nilai_ta_7.value;

        eight = document.autoSumForm.nilai_ta_8.value;

        document.autoSumForm.total.value = (one * 10 / 100) + (two * 10 / 100) + (three * 10 / 100) + (four * 10 /
            100) + (five * 20 / 100) + (six * 20 / 100) + (seven * 10 / 100) + (eight * 10 / 100);

    }

    function stopCalc() {

        clearInterval(interval);
    }
</script>
