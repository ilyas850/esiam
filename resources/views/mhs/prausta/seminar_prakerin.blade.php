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
                <div class="tab-pane active" id="tab_1">
                  @if ($cekdata == 0)
                    <a class="btn btn-danger" href="{{url('pengajuan_seminar_prakerin')}}">Masukan Data Prakerin</a>
                  @elseif ($cekdata != 0)
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
                          <div class="form-group">
                            <label>Dosen Pembimbing</label>
                            <input type="text" class="form-control"value="{{$usta->dosen_pembimbing}}" readonly>
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
                            <div class="form-group">
                              <label>Tempat PraUSTA</label>
                              <input type="text" class="form-control" value="{{$usta->tempat_prausta}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                              <label>Judul Seminar Prakerin</label>
                              <textarea class="form-control" rows="2" cols="60" readonly>{{$usta->judul_prausta}}</textarea>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                              <label >Acc. Dosen Pembimbing</label><br>
                                  @if ($usta->file_acc_dosen == NULL)
                                    tidak ada file
                                  @elseif ($usta->file_acc_dosen != NULL)
                                    <a href="/File Acc Dosen/{{Auth::user()->id_user}}/{{$usta->file_acc_dosen}}"  target="_blank"> File Acc. Dosen</a>
                                  @endif
                            </div>
                            <div class="form-group">
                              <label >Kartu Bimbingan</label><br>
                                  @if ($usta->file_kartu_bim == NULL)
                                    tidak ada file
                                  @elseif ($usta->file_kartu_bim != NULL)
                                    <a href="/File Kartu Bimbingan/{{Auth::user()->id_user}}/{{$usta->file_kartu_bim}}"  target="_blank"> File Kartu Bimbingan</a>
                                  @endif
                            </div>
                            <div class="form-group">
                              <label >Surat Balasan dari Instansi</label><br>
                                  @if ($usta->file_surat_balasan == NULL)
                                    tidak ada file
                                  @elseif ($usta->file_surat_balasan != NULL)
                                    <a href="/File Surat Balasan/{{Auth::user()->id_user}}/{{$usta->file_surat_balasan}}"  target="_blank"> File Susat Balasan</a>
                                  @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                              <label>Validasi Keuangan</label><br>
                                  @if ($usta->file_val_baku == NULL)
                                    tidak ada file
                                  @elseif ($usta->file_val_baku != NULL)
                                    <a href="/File Validasi BAKU/{{Auth::user()->id_user}}/{{$usta->file_val_baku}}"  target="_blank"> File Validasi BAKU</a>
                                  @endif
                            </div>
                            <div class="form-group">
                              <label >Draft Laporan Prakerin</label><br>
                                  @if ($usta->file_draft_laporan == NULL)
                                    tidak ada file
                                  @elseif ($usta->file_draft_laporan != NULL)
                                    <a href="/File Draft Laporan/{{Auth::user()->id_user}}/{{$usta->file_draft_laporan}}"  target="_blank"> File Draf Laporan</a>
                                  @endif
                            </div>
                            <div class="form-group">
                              <label >Nilai dari Pembimbing Lapangan</label><br>
                                @if ($usta->file_nilai_pembim == NULL)
                                  tidak ada file
                                @elseif ($usta->file_nilai_pembim != NULL)
                                  <a href="/File Nilai Pembimbing/{{Auth::user()->id_user}}/{{$usta->file_nilai_pembim}}"  target="_blank"> File Nilai Pembimbing</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
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
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Kategori PraUSTA</label>
                          <input type="text" class="form-control"value="{{$usta->id_kategori_prausta}}" readonly>
                        </div>
                      </div>
                    </div>
                  @endif
                </div>
                <!-- /.tab-pane -->
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
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no=1; ?>
                          @foreach ($bim as $key)
                            <tr>
                              <td>{{$no++}}</td>
                              <td>{{$key->tanggal_bimbingan}}</td>
                              <td>{{$key->remark_bimbingan}}</td>
                              <td></td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>

                  </div>


                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_3">
                  Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                  Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                  when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                  It has survived not only five centuries, but also the leap into electronic typesetting,
                  remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                  sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                  like Aldus PageMaker including versions of Lorem Ipsum.
                </div>
                <!-- /.tab-pane -->
              </div>
              <!-- /.tab-content -->
            </div>
            <!-- nav-tabs-custom -->
          </div>
    </div>



  </section>
@endsection
