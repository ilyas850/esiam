@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
<section class="content">
    <div class="box box-info">
        <div class="box-header with-border">
            <table width="100%">
                <tr>
                    <td>Matakuliah</td><td>:</td>
                    <td>{{$bap->makul}} - {{$bap->akt_sks}} SKS</td>
                    <td>Tahun Akademik</td><td>:</td>
                    <td>{{$bap->periode_tahun}} {{$bap->periode_tipe}}</td>
                </tr>
                <tr>
                    <td>Waktu / Ruangan</td><td>:</td>
                    <td>{{$bap->hari}},
                        @if ($bap->id_kelas == 1)
                            {{$bap->jam}} - {{ date('H:i', strtotime($bap->jam) + (60*$bap->akt_sks_teori * 50) + (60*$bap->akt_sks_praktek * 120))}}
                        @elseif ($bap->id_kelas == 2)
                            {{$bap->jam}} - {{ date('H:i', strtotime($bap->jam) + (60*$bap->akt_sks_teori * 45) + (60*$bap->akt_sks_praktek * 90))}}
                        @elseif ($bap->id_kelas == 3)
                            {{$bap->jam}} - {{ date('H:i', strtotime($bap->jam) + (60*$bap->akt_sks_teori * 45) + (60*$bap->akt_sks_praktek * 90))}}
                        @endif
                    / {{$bap->nama_ruangan}}</td>
                    <td>Program Studi</td><td>:</td>
                    <td>{{$bap->prodi}}</td>
                </tr>
                <tr>
                    <td>Dosen</td><td>:</td>
                    <td>{{$bap->nama}}, {{$bap->akademik}}/{{$nama_dosen_2}}</td>
                    <td>Kelas</td><td>:</td>
                    <td>{{$bap->kelas}}</td>
                </tr>
            </table>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th ><center>No</center></th>
                        <th ><center>Tanggal </center></th>
                        <th ><center>Jam</center></th>
                        <th ><center>Materi</center></th>
                        <th ><center>Paraf Dosen</center></th>
                        <th ><center>Validasi</center></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; ?>
                    @foreach ($data as $item)
                        <tr>
                            <td><center>{{$no++}}</center></td>
                            <td><center>{{$item->tanggal}}</center></td>
                            <td><center>{{$item->jam_mulai}} - {{$item->jam_selsai}}</center></td>
                            <td>{{$item->materi_kuliah}}</td>
                            <td><center>By System</center></td>
                            <td><center>{{$item->payroll_check}}</center></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
