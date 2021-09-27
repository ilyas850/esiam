@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection
@section('content')
  <section class="content">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Rekap Nilai Mahasiswa</h3>
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
              <th>Jumlah Mahasiswa</th>
              <th>Dosen</th>
              <th>Nilai</th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; ?>
            @foreach ($nilai as $key)
              <tr>
                <td>{{$no++}}</td>
                <td>{{$key->kode}}/{{$key->makul}}</td>
                <td><center>{{$key->akt_sks_teori + $key->akt_sks_praktek}}</center></td>
                <td>{{$key->prodi}}</td>
                <td>{{$key->kelas}}</td>
                <td><center>{{$key->jml_mhs}}</center></td>
                <td>{{$key->nama}}</td>
                <td>
                  <a href="cek_nilai_mhs_kprd/{{$key->id_kurperiode}}" class="btn btn-info btn-xs">Cek</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
