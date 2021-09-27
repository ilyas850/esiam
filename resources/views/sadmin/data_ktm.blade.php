@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection
@section('content')
  <section class="content">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Download Data Mahasiswa Politeknik META Industri</h3>
      </div>
      <div class="box-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th><center>No</center></th>
              <th><center>NIM</center></th>
              <th><center>Nama Mahasiswa</center></th>
              <th><center>Program Studi</center></th>
              <th><center>Kelas</center></th>
              <th><center>Angkatan</center></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php $no=1; ?>
            @foreach($mhs as $item)
              <tr>
                <td><center>{{$no++}}</center></td>
                <td><center>{{$item->nim}}</center></td>
                <td>{{$item->nama}}</td>
                <td><center>
                  @foreach ($prd as $keyprd)
                    @if ($item->kodeprodi == $keyprd->kodeprodi)
                      {{$keyprd->prodi}}
                    @endif
                  @endforeach
                </center></td>
                <td><center>
                  @foreach ($kls as $keykls)
                    @if ($item->idstatus == $keykls->idkelas)
                      {{$keykls->kelas}}
                    @endif
                  @endforeach
                </center></td>
                <td><center>
                  @foreach ($ang as $keyang)
                    @if ($item->idangkatan == $keyang->idangkatan)
                      {{$keyang->angkatan}}
                    @endif
                  @endforeach
                </center></td>
                <td><center><a href="/downloadktm/{{$item->idstudent}}" class="btn btn-success btn-xs">Download KTM</a></center></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </section>
@endsection
