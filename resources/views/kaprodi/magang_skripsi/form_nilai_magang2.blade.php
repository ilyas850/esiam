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
                        <td>Tempat Magang</td>
                        <td>:</td>
                        <td>{{ $data->tempat_prausta }}</td>
                    </tr>
                </table>
            </div>
        </div>
       

        <form class="" action="{{ url('simpan_nilai_magang2_kaprodi') }}" method="post"
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
                    <h3 class="box-title"><b>Form Penilaian Pembimbing</b> </h3>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="3%" align="center">No</th>
                                <th width="82%">
                                    <center>Parameter Penilaian</center>
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
                            @foreach ($form_dosbing as $item)
                                <tr>
                                    <td>
                                        <center>{{ $no++ }}</center>
                                    </td>
                                    <td>{{ $item->komponen }}</td>
                                    <td>
                                        <center>{{ $item->bobot }}%</center>
                                    </td>
                                    <td>
                                        <center>
                                            <input type="hidden" name="id_penilaian_prausta1[]"
                                                value="{{ $item->id_penilaian_prausta }}">
                                            <input type="number" name="nilai1[]" required>
                                            <center>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title"><b>Form Penilaian Seminar</b> </h3>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="3%" align="center">No</th>
                                <th width="82%">
                                    <center>Parameter Penilaian</center>
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
                            @foreach ($form_seminar as $item)
                                <tr>
                                    <td>
                                        <center>{{ $no++ }}</center>
                                    </td>
                                    <td>{{ $item->komponen }}</td>
                                    <td>
                                        <center>{{ $item->bobot }}%</center>
                                    </td>
                                    <td>
                                        <center>
                                            <input type="hidden" name="id_penilaian_prausta2[]"
                                                value="{{ $item->id_penilaian_prausta }}">
                                            <input type="number" name="nilai2[]" required>
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
