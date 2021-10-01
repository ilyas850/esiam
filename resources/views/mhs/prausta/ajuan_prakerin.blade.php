@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
  <section class="content">
    <div class="box box-primary">
      <form class="" action="{{url('simpan_ajuan_prakerin')}}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="id_student" value="{{$data->idstudent}}">
        <input type="hidden" name="id_masterkode_prausta" value="{{$data->id_masterkode_prausta}}">
        <div class="box-header with-border">
          Form Seminar Prakerin
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Jenis PraUSTA</label>
                <input type="text" class="form-control" value="{{$data->kode_prausta}} - {{$data->nama_prausta}}" readonly>
              </div>
              <div class="form-group">
                <label>Nama Lengkap</label>

                <input type="text" class="form-control" value="{{$data->nama}}" readonly>
              </div>

            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>NIM</label>
                <input type="text" class="form-control" value="{{$data->nim}}" readonly>
              </div>
              <div class="form-group">
                <label>Program Studi</label>
                <input type="text" class="form-control" value="{{$data->prodi}}" readonly>
              </div>

            </div>
          </div>
          <div class="row">
            <div class="col-md-6">

              <div class="form-group">
                <label>Dosen Pembimbing<font color ="red-text">*</font></label>
                <input type="text" class="form-control" name="dosen_pembimbing" required>
              </div>

              <div class="form-group">
                <label>Judul Seminar Prakerin<font color ="red-text">*</font></label>
                <textarea class="form-control" name="judul_prausta" rows="3" cols="60" required></textarea>
              </div>

              <div class="form-group">
                <label>Tempat Prakerin<font color ="red-text">*</font></label>
                <input type="text" class="form-control" name="tempat_prausta" required>
              </div>

            </div>

            <div class="col-md-3">

              <div class="form-group">
                <label >Acc. Dosen Pembimbing<font color ="red-text">*</font></label>
                <input type="file" class="form-control" name="file_acc_dosen" required>
                  @if ($errors->has('file_acc_dosen'))
                      <span class="help-block">
                          <strong>{{ $errors->first('file_acc_dosen') }}</strong>
                      </span>
                  @endif
                  <p class="help-block">Format file .jpg, .jpeg, .png size 2mb</p>
              </div>

              <div class="form-group">
                <label >Kartu Bimbingan<font color ="red-text">*</font></label>
                <input type="file" class="form-control" name="file_kartu_bim" required>
                  @if ($errors->has('file_kartu_bim'))
                      <span class="help-block">
                          <strong>{{ $errors->first('file_kartu_bim') }}</strong>
                      </span>
                  @endif
                  <p class="help-block">Format file .pdf size 5mb</p>
              </div>

              <div class="form-group">
                <label >Surat Balasan dari Instansi</label>
                <input type="file" class="form-control" name="file_surat_balasan">
                  @if ($errors->has('file_surat_balasan'))
                      <span class="help-block">
                          <strong>{{ $errors->first('file_surat_balasan') }}</strong>
                      </span>
                  @endif
                  <p class="help-block">Format file .pdf size 5mb</p>
              </div>

            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Validasi Keuangan<font color ="red-text">*</font></label>
                <input type="file" class="form-control" name="file_val_baku" required>
                @if ($errors->has('file_val_baku'))
                    <span class="help-block">
                        <strong>{{ $errors->first('file_val_baku') }}</strong>
                    </span>
                @endif
                <p class="help-block">Format file .jpg, .jpeg, .png size 2mb</p>
              </div>
              <div class="form-group">
                <label >Draft Laporan Prakerin<font color ="red-text">*</font></label>
                <input type="file" class="form-control" name="file_draft_laporan" required>
                @if ($errors->has('file_draft_laporan'))
                    <span class="help-block">
                        <strong>{{ $errors->first('file_draft_laporan') }}</strong>
                    </span>
                @endif
                <p class="help-block">Format file .pdf size 5mb</p>
              </div>

              <div class="form-group">
                <label >Nilai dari Pembimbing Lapangan</label>
                <input type="file" class="form-control" name="file_nilai_pembim">
                @if ($errors->has('file_nilai_pembim'))
                    <span class="help-block">
                        <strong>{{ $errors->first('file_nilai_pembim') }}</strong>
                    </span>
                @endif
                <p class="help-block">Format file .pdf size 5mb</p>
              </div>
            </div>
          </div>

        </div>
        <div class="box-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </section>
@endsection
