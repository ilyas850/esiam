@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
  <section class="content">
    <div class="box box-info">
      <div class="box-header">
          <h3 class="box-title">Data Soal UTS</h3>
      </div>
      <div class="box-body">
        <table id="example1" class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode/Matakuliah</th>
              <th>SKS</th>
              <th>Prodi</th>
              <th>Kelas</th>
              <th>Dosen</th>
              <th>Soal UTS</th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; ?>
            @foreach ($soal as $key)
              <tr>
                <td><center>{{$no++}}</center></td>
                <td>{{$key->kode}}/{{$key->makul}}</td>
                <td><center>{{$key->akt_sks_teori + $key->akt_sks_praktek}}</center></td>
                <td><center>{{$key->prodi}}</center></td>
                <td><center>{{$key->kelas}}</center></td>
                <td><center>{{$key->nama}}</center></td>
                <td><center>
                  <a href="/File_BAP/{{$key->iddosen}}/{{$key->id_kurperiode}}/Materi Kuliah/{{$key->file_materi_kuliah}}" target="_blank" class="btn btn-info btn-xs">Lihat</a>
                
                </center></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
