@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Data Prakerin</a></li>
              <li><a href="#tab_2" data-toggle="tab">Data Bimbingan Prakerin</a></li>
              <li><a href="#tab_3" data-toggle="tab">Pengajuan Seminar Prakerin</a></li>
            </ul>
              <div class="tab-content">
                @if ($usta->judul_prausta == null)
                  <a class="btn btn-danger" href="{{url('pengajuan_seminar_prakerin')}}">Masukan Data Prakerin</a>
                @elseif ($cekdata != null)
                  <div class="tab-pane active" id="tab_1">
                    <form action="edit_ajuan_prakerin/{{$usta->id_settingrelasi_prausta}}" method="post" enctype="multipart/form-data">
                      <input type="hidden" name="_method" value="PUT">
                      {{ csrf_field() }}

                      <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Jenis PraUSTA</label>
                              <input type="text" class="form-control" value="{{$usta->kode_prausta}} - {{$usta->nama_prausta}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                              <div class="form-group">
                                <label>Kategori PraUSTA</label>
                                <input type="text" class="form-control" value="" readonly>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control" value="{{$usta->nama}}" readonly>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>NIM</label>
                            <input type="text" class="form-control" value="{{$usta->nim}}" readonly>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Program Studi</label>
                            <input type="text" class="form-control" value="{{$usta->prodi}}" readonly>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label>Judul Prakerin</label>
                              <textarea class="form-control" rows="2" cols="60" name="judul_prausta">{{$usta->judul_prausta}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Tempat Prakerin</label>
                            <input type="text" class="form-control" value="{{$usta->tempat_prausta}}" name="tempat_prausta">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label >Draft Laporan Prakerin</label><br>
                                @if ($usta->file_draft_laporan == NULL)
                                  <input type="file" name="file_draft_laporan" class="form-control">
                                  <span>Format file pdf max. size 5mb</span>
                                @elseif ($usta->file_draft_laporan != NULL)
                                  <a href="/File Draft Laporan/{{Auth::user()->id_user}}/{{$usta->file_draft_laporan}}"  target="_blank"> File Draf Laporan</a>
                                @endif
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label >Nilai dari Pembimbing Lapangan</label><br>
                              @if ($usta->file_nilai_pembim == NULL)
                                <input type="file" name="file_nilai_pembim" class="form-control">
                                <span>Format file pdf max. size 5mb</span>
                              @elseif ($usta->file_nilai_pembim != NULL)
                                <a href="/File Nilai Pembimbing/{{Auth::user()->id_user}}/{{$usta->file_nilai_pembim}}"  target="_blank"> File Nilai Pembimbing</a>
                              @endif
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label >Kartu Bimbingan</label><br>
                                @if ($usta->file_kartu_bim == NULL)
                                  <input type="file" name="file_kartu_bim" class="form-control">
                                  <span>Format file pdf max. size 5mb</span>
                                @elseif ($usta->file_kartu_bim != NULL)
                                  <a href="/File Kartu Bimbingan/{{Auth::user()->id_user}}/{{$usta->file_kartu_bim}}"  target="_blank"> File Kartu Bimbingan</a>
                                @endif
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label >Surat Balasan dari Instansi</label><br>
                                @if ($usta->file_surat_balasan == NULL)
                                  <input type="file" name="file_surat_balasan" class="form-control">
                                  <span>Format file pdf max. size 5mb</span>
                                @elseif ($usta->file_surat_balasan != NULL)
                                  <a href="/File Surat Balasan/{{Auth::user()->id_user}}/{{$usta->file_surat_balasan}}"  target="_blank"> File Susat Balasan</a>
                                @endif
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Acc. Judul Prakerin</label>
                            <input type="text" class="form-control" value="{{$usta->acc_judul}}" readonly>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Validasi Keuangan</label>
                            <input type="text" class="form-control" value="{{$validasi}}" readonly>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Dosen Pembimbing</label>
                            <input type="text" class="form-control"value="{{$usta->dosen_pembimbing}}" readonly>
                          </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label>Dosen Penguji</label>
                              <input type="text" class="form-control" value="{{$usta->dosen_penguji1_1}}" readonly>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Tanggal Mulai Seminar</label>
                            <input type="text" class="form-control"value="{{$usta->tanggal_mulai}}" readonly>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Tanggal Selesai Seminar</label>
                            <input type="text" class="form-control"value="{{$usta->tanggal_selesai}}" readonly>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Ruangan Seminar</label>
                            <input type="text" class="form-control"value="{{$usta->ruangan}}" readonly>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Jam Mulai Seminar</label>
                            <input type="text" class="form-control"value="{{$usta->jam_mulai_sidang}}" readonly>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Jam Selesai Seminar</label>
                            <input type="text" class="form-control"value="{{$usta->jam_selesai_sidang}}" readonly>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3">
                          <button type="submit" class="btn btn-info">Simpan</button>
                        </div>
                      </div>
                  </form>
                  </div>
                  <div class="tab-pane" id="tab_2">
                    <div class="box box-info">
                      <div class="box-header with-border">
                        Form Bimbingan Prakerin
                      </div>
                      <div class="box-body">
                        <form class="" action="{{url('simpan_bimbingan')}}" method="post">
                            {{ csrf_field() }}
                          <div class="row">
                            <div class="col-md-5">
                              <div class="form-group">
                                <label>Tanggal Bimbingan</label>
                                <input type="date" class="form-control" name="tanggal_bimbingan" required>
                              </div>
                            </div>
                            <div class="col-md-5">
                              <div class="form-group">
                                <label>Isi Bimbingan</label>
                                <input type="text" class="form-control" name="remark_bimbingan" required>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-2">
                              <input type="hidden" name="id_settingrelasi_prausta" value="{{$usta->id_settingrelasi_prausta}}">
                              <button type="submit" class="btn btn-info">Simpan</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    <div class="box box-info">
                      <div class="box-header with-border">
                        Tabel Bimbingan
                      </div>
                      <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th>Tanggal Bimbingan</th>
                              <th>Remark Bimbingan</th>
                              <th><center>Aksi</center></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php $no=1; ?>
                            @foreach ($bim as $key)
                              <tr>
                                <td>{{$no++}}</td>
                                <td>{{$key->tanggal_bimbingan}}</td>
                                <td>{{$key->remark_bimbingan}}</td>
                                <td><center>
                                  <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modalUpdatebimbingan{{ $key->id_transbimb_prausta }}">Update</button>

                                 </center></td>
                              </tr>

                              <div class="modal fade" id="modalUpdatebimbingan{{ $key->id_transbimb_prausta }}" tabindex="-1" aria-labelledby="modalUpdatebimbingan" aria-hidden="true">
                              <div class="modal-dialog">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title">Update Bimbingan</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                          </button>
                                      </div>
                                      <div class="modal-body">
                                        <form action="/edit_bimbingan/{{ $key->id_transbimb_prausta }}" method="post">
                                            @csrf
                                            @method('put')
                                            <div class="form-group">
                                                <label>Tingkat</label>
                                                <input type="date" class="form-control" name="tanggal_bimbingan" value="{{$key->tanggal_bimbingan}}">
                                            </div>
                                            <div class="form-group">
                                              <label>Isi Bimbingan</label>
                                              <input type="text" class="form-control" name="remark_bimbingan" value="{{$key->remark_bimbingan}}">
                                            </div>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Perbarui Data</button>
                                        </form>
                                      </div>
                                  </div>
                              </div>
                          </div>

                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>

                  </div>
                  <div class="tab-pane" id="tab_3">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                    when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                    It has survived not only five centuries, but also the leap into electronic typesetting,
                    remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                    sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                    like Aldus PageMaker including versions of Lorem Ipsum.
                  </div>
                @endif
              </div>
            </div>
          </div>
    </div>
  </section>
@endsection
