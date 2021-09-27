@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection
@section('content')
  <section class="content">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Nomor Transkrip Nilai Sementara</h3>
      </div>
      <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>NIM</th>
              <th>Nama Mahasiswa</th>
              <th>Program Studi</th>
              <th>Kelas</th>
              <th>Nomor Transkrip</th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; ?>
            @foreach ($nomor as $key)
              <tr>
                <td>{{$no++}}</td>
                <td>{{$key->nim}}</td>
                <td>{{$key->nama}}</td>
                <td>{{$key->prodi}}</td>
                <td>{{$key->kelas}}</td>
                <td>{{$key->no_transkrip}}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
