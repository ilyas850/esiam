@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection
@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-success">
          <div class="box-header with-border">
              <h3 class="box-title">Form Transkrip Nilai Sementara</h3>
          </div>
          <form class="form" role="form" action="{{url('lihat_transkrip')}}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="id_student" value="{{$item->idstudent}}">
            <div class="box-body">
              <div class="form-group">
                <div class="col-md-6">
                  <label>Nama Lengkap</label>
                  <input type="text" class="form-control" value="{{$item->nama}}" readonly >
                </div>
                <div class="col-md-6">
                  <label>No. Transkrip Sementara</label>
                  <input type="text" class="form-control" name="no_transkrip" required >
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-6">
                  <label>NIM</label>
                  <input type="text" class="form-control" value="{{$item->nim}}" readonly >
                </div>
                <div class="col-md-6">
                  <label>Tanggal Lulus</label>
                  <input type="text" class="form-control" value="-" readonly>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-6">
                  <label>Tempat & Tanggal Lahir</label>
                  <input type="text" class="form-control" value="{{$item->tmptlahir}}, {{$item->tgllahir->isoFormat('D MMMM Y')}}" readonly >
                </div>
                <div class="col-md-6">
                  <label>Nomor Induk Ijazah</label>
                  <input type="text" class="form-control" value="-" readonly >
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-6">
                  <label>Program Studi</label>
                  <input type="text" class="form-control" value="{{$item->prodi}}" readonly >
                </div>
                <div class="col-md-6">
                  <label>Jenjang Pendidikan</label>
                  <input type="text" class="form-control" value="DIPLOMA III (D-3)" readonly >
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-6">
                  <label>Judul Laporan</label>
                  <textarea rows="4" class="form-control" cols="80" readonly> - </textarea>
                </div>
                <div class="col-md-6">
                  <label>Pembimbingan</label>
                  <input type="text" class="form-control" value="-" readonly >
                </div>
              </div>
            </div>
            <div class="box-footer">
              <div class="form-group">
                <div class="col-md-6">
                  <button type="submit" class="btn btn-primary">
                      Simpan
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    {{-- <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
          <div class="box-header with-border">
              <h3 class="box-title">Transkrip Nilai Sementara</h3>
          </div>
          <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <td>No</td>
                  <td>Kode MK</td>
                  <td>Matakuliah</td>
                  <td>sks</td>
                  <td>Huruf</td>
                  <td>Angkat</td>
                  <td>Nilai x SKS</td>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div> --}}

  </section>
@endsection
