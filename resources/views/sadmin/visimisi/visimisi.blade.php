@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
  <section class="content">
    <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title">Visi Misi Politeknik META Industri Cikarang</h3>
        </div>
        <div class="box-body">
          <a href="{{url('add_visimisi')}}" class="btn btn-info"> Tambah Visi Misi</a>
          <br><br>
          <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
              <th width="4px"><center>No</center></th>
              <th><center>Visi</center></th>
              <th><center>Visi</center></th>
              <th><center>Tujuan</center></th>
              <th><center>Aksi</center></th>
            </tr>
            </thead>
            <tbody>
              <?php $no=1; ?>
              @foreach ($vm as $item)
                <tr>
                  <td><center>{{$no++}}</center></td>
                  <td>{{$item->visi}}</td>
                  <td>{{$item->misi}}</td>
                  <td>{{$item->tujuan}}</td>
                  <td><center>
                    <a class="btn btn-success btn-xs" href="/editvisimisi/{{ $item->id_visimisi }}">Edit</a>
                    <a class="btn btn-danger btn-xs" href="/hapusvisimisi/{{ $item->id_visimisi }}">Hapus</a>
                  <center></td>
                </tr>
              @endforeach
            </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
