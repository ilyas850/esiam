@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content_header')
  <section class="content-header">
      <h1>
        Data Nilai Mahasiswa
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>

        <li class="active">Data Nilai Mahasiswa</li>
      </ol>
    </section>
@endsection
@section('content')
  <section class="content">
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title">Tabel Nilai</h3>
      </div>
      {{-- <form action="{{url('save_nilai_angka')}}" method="post">
        {{ csrf_field() }} --}}
      <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
          <tr>
            <th width="4px"><center>No</center></th>
            <th><center>NIM</center></th>
            <th><center>Nama</center></th>
            <th><center>Program Studi</center></th>
            <th width="10%"><center>Kelas</center></th>

            <th><center>Pilih</center></th>
          </tr>
          </thead>
          <tbody>
            <?php $no=1; ?>
            @foreach ($nilai as $key)
              <tr>
                <td><center>{{$no++}}</center></td>
                <td><center>{{$key->nim}}</center></td>
                <td>{{$key->nama}}</td>
                <td><center>
                  @if ($key->kodeprodi ==23)
                    Teknik Industri
                      @elseif ($key->kodeprodi ==22)
                          Teknik Komputer
                        @elseif ($key->kodeprodi ==24)
                            Farmasi
                    @endif
                  </center></td>
                <td><center>
                  @if ($key->idstatus ==1)
                          Reguler A
                        @elseif ($key->idstatus ==2)
                            Reguler C
                          @elseif ($key->idstatus ==3)
                              Reguler B
                      @endif
                </center></td>

                <td><center>
                  <form action="{{url('cek_nilai')}}" method="post">
                    <input type="hidden" name="id_student" value="{{$key->id_student}}">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="right">Cek Nilai</button>
                  </form>
                </center></td>
              </tr>
            @endforeach
          </tbody>
      </table>

    </div>
  {{-- </form> --}}
  </div>
  </section>

@endsection
