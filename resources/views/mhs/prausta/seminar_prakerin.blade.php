@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
  <section class="content">
    @if ($cekdata == 0)
      <div class="col-md-6 ">
        <div class="box box-primary">
          <div class="box-header with-border">
            <a class="btn btn-danger" href="{{url('pengajuan_seminar_prakerin')}}">Ajukan Seminar Prakerin</a>
          </div>
        </div>
      </div>

    @elseif ($cekdata != 0)
      <div class="col-md-12 ">
        <div class="box box-primary">
          <div class="box-header with-border">
            Data Pengajuan Seminar Prakerin
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Jenis PraUSTA</label>
                  <input type="text" class="form-control" value="{{$usta->kode_prausta}} - {{$usta->nama_prausta}}" readonly>
                </div>
                <div class="form-group">
                  <label>Nama Lengkap</label>
                  <input type="text" class="form-control" value="{{$usta->nama}}" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>NIM</label>
                  <input type="text" class="form-control" value="{{$usta->nim}}" readonly>
                </div>
                <div class="form-group">
                  <label>Program Studi</label>
                  <input type="text" class="form-control" value="{{$usta->prodi}}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Dosen Pembimbing</label>
                  <input type="text" class="form-control"value="{{$usta->dosen_pembimbing}}" readonly>
                </div>
                <div class="form-group">
                  <label>Judul Seminar Prakerin</label>
                  <textarea class="form-control" rows="3" cols="60" readonly>{{$usta->judul_prausta}}</textarea>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label >Acc. Dosen Pembimbing</label>
                  <input type="file" class="form-control" name="file_acc_dosen" required>
                </div>

                <div class="form-group">
                  <label >Kartu Bimbingan</label>
                  <input type="file" class="form-control" name="file_kartu_bim" required>
                </div>

                <div class="form-group">
                  <label >Surat Balasan dari Instansi</label>
                  <input type="file" class="form-control" name="file_surat_balasan">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  </section>
@endsection
