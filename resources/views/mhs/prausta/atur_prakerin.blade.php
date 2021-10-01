@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
<section class="content">
  <div class="box box-info">
    <form class="" action="simpan_atur_prakerin/{{$id}}" method="post" enctype="multipart/form-data">
      <div class="box-header">
        <h3 class="box-title">Setting Jadwal Prakerin</h3>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label>Dosen Penguji<font color ="red-text">*</font></label>
              <input type="text" class="form-control" name="dosen_penguji_1" required>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Tanggal Mulai<font color ="red-text">*</font></label>
              <input type="text" class="form-control" name="tanggal_mulai" required>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Tanggal Selesai<font color ="red-text">*</font></label>
              <input type="text" class="form-control" name="tanggal_selesai" required>
            </div>
          </div>
        </div>


      </div>
    </form>
  </div>
</section>
@endsection
