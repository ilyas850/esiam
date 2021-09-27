@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
  <section class="content">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Data Mahasiswa PraUSTA Politeknik META Industri</h3>
      </div>
      <div class="box-body">
          <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th><center>No</center></th>
              <th><center>Prodi</center></th>
              <th><center>Kelas</center></th>
              <th><center>NIM</center></th>
              <th><center>Nama</center></th>
              <th><center>Kode Matkul</center></th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; ?>
            @foreach ($data as $key)
              <tr>
                <td><center>{{$no++}}</center></td>
                <td>{{$key->prodi}}</td>
                <td>{{$key->kelas}}</td>
                <td>{{$key->nim}}</td>
                <td>{{$key->nama}}</td>
                <td>{{$key->makul}}</td>
              </tr>
            @endforeach

          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
