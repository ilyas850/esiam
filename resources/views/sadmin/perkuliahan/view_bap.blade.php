
@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
  <section class="content-header">
      <h1>
        View Berita Acara Perkuliahan
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
        <li><a href="{{ url('rekap_perkuliahan') }}"> Rekap perkuliahan</a></li>
        <li><a href="/cek_rekapan/{{$dtbp->id_kurperiode}}">Cek BAP</a></li>
        <li class="active">View BAP</li>
      </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-body">
                <a class="btn btn-success" href="/cek_rekapan/{{$dtbp->id_kurperiode}}">Kembali</a>

                <a class="btn btn-warning" href="/cek_print_bap/{{$dtbp->id_bap}}" target="_blank"><i class="fa fa-print"></i> PRINT</a>

                    <center>
                        <h2 class="box-title">Laporan Pembelajaran Daring Prodi {{$prd}} </h2>
                        <h3 class="box-title">Semester {{$tipe}} – {{$tahun}}</h3>
                    </center>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>Matakuliah</td>
                            <td>{{$data->makul}}</td>
                        </tr>
                        <tr>
                            <td>Nama Dosen</td>
                            <td>{{$data->nama}}</td>
                        </tr>
                        <tr>
                            <td>Kelas / Semester</td>
                            <td>{{$data->kelas}} / {{$data->semester}}</td>
                        </tr>
                        <tr>
                            <td>Media Pemebelajaran</td>
                            <td>{{$dtbp->media_pembelajaran}}</td>
                        </tr>
                        <tr>
                            <td>Pukul</td>
                            <td>{{$dtbp->jam_mulai}} - {{$dtbp->jam_selsai}}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Perkuliahan</td>
                            <td>{{$dtbp->tanggal}}</td>
                        </tr>
                        <tr>
                            <td>Materi Perkuliahan</td>
                            <td>{{$dtbp->materi_kuliah}}</td>
                        </tr>
                        <tr>
                            <td>Pertemuan</td>
                            <td>Ke-{{$dtbp->pertemuan}}</td>
                        </tr>
                        <tr>
                            <td>Mahasiswa Hadir/Tidak Hadir</td>
                            <td>{{$dtbp->hadir}} / {{$dtbp->tidak_hadir}}</td>
                        </tr>
                    </table>

                <div class="form-group">
                    <h4>1.	Kuliah tatap muka</h4>
                    @if (($dtbp->file_kuliah_tatapmuka) != null)
                    <a href="/File_BAP/{{$data->iddosen}}/{{$dtbp->id_kurperiode}}/Kuliah Tatap Muka/{{$dtbp->file_kuliah_tatapmuka}}"  target="_blank"> Tatap Muka Perkuliahan</a>
                    @else
                    Tidak ada lampiran
                    @endif
                </div>
                <div class="form-group">
                    <h4>2.	Materi Perkuliahan</h4>
                    @if (($dtbp->file_materi_kuliah) != null)
                    <a href="/File_BAP/{{$data->iddosen}}/{{$dtbp->id_kurperiode}}/Materi Kuliah/{{$dtbp->file_materi_kuliah}}"  target="_blank"> Materi Perkuliahan</a>
                    @else
                    Tidak ada lampiran
                    @endif
                </div>
                <div class="form-group">
                    <h4>3.	Materi Tugas</h4>
                    @if (($dtbp->file_materi_tugas) != null)
                    <a href="/File_BAP/{{$data->iddosen}}/{{$dtbp->id_kurperiode}}/Tugas Kuliah/{{$dtbp->file_materi_tugas}}" target="_blank"> Tugas Perkuliahan</a>
                    @else
                    Tidak ada lampiran
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
