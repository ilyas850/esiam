@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection
@section('content')
  <section class="content">
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title">Data Mahasiswa Politeknik META Industri</h3>
      </div>
      <div class="box-body">
        <table id="example1" class="table">
          <thead>
            <tr>
              <th>No</th>
              <th>NIM</th>
              <th>Nama Mahasiswa</th>
              <th>Program Studi</th>
              <th>Kelas</th>
              <th>Angkatan</th>
              <th>Cek Transkrip</th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; ?>
            @foreach ($nilai as $key)
              <tr>
                <td>{{$no++}}</td>
                <td>{{$key->nim}}</td>
                <td>{{$key->nama}}</td>
                <td>{{$key->prodi}}</td>
                <td>{{$key->kelas}}</td>
                <td>{{$key->angkatan}}</td>
                <td>
                  <a href="/cek_transkrip/{{$key->idstudent}}" class="btn btn-info btn-xs">Cek Transkrip</a>
                </td>
              </tr>
            @endforeach
            
          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
