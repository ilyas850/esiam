@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection
@section('content')
<section class="content">
	 <h1><b>KARTU RENCANA STUDI</b></h1>
			<div class="panel panel-primary">
			  <div class="panel-body">
			    <div class="row">
				  <div class="col-xs-12">
					  	<div class="row">
							    <div class="col-xs-12">
							        <table width="100%">
					            <tr>
					                <td><b>TA Semester</b></td><td> : </td>
					                <td><b><u>
														@foreach ($thn as $TA)
															{{ $TA->periode_tahun }}
													  @endforeach
                            @foreach ($tp as $key)
                              {{ $key->periode_tipe }}
                            @endforeach
												</u></b></td>
												<td align=right>Jumlah SKS Maksimal</td>
												<td>:</td>
												<td>24</td>
											</tr>
											<tr>
					                <td><b>Nama</b></td><td> : </td>
					                <td><b><u>{{ $mhs->nama }}</u></b></td>
													<td align=right>SKS Tempuh&ensp;</td>
													<td>:</td>
													<td>{{$sks}}</td>
					            </tr>
					            <tr>
					              <td><b>NIM</b></td><td> : </td>
					              <td><b><u>{{ $mhs->nim }}</u></b></td>
											</tr>
											<tr>
					              <td><b>Jurusan</b></td><td> : </td>
					              <td><b><u>
                          @if ($mhs->kodeprodi ==23)
                              Teknik Industri
                            @elseif ($mhs->kodeprodi ==22)
                                    Teknik Komputer
                                  @elseif ($mhs->kodeprodi ==24)
                                      Farmasi
                              @endif
											</u></b></td>
					            </tr>
                      <tr>
                        <td><b>Kelas</b></td><td>:</td>
                        <td><b><u>
                          @if ($mhs->idstatus ==1)
                                Reguler A
                              @elseif ($mhs->idstatus ==2)
                                  Reguler B
                                @elseif ($mhs->idstatus ==3)
                                    Reguler C
                            @endif
                          </b></u></td>
                      </tr>
					          </table>
							  </div>
						</div>
					<hr>
          <form class="form" role="form" action="{{url('add_krs')}}" method="POST">
            {{ csrf_field() }}

            <input type="hidden" name="id_student" value="{{$mhs->idstudent}}">
            <input type="hidden" name="idangkatan" value="{{$mhs->idangkatan}}">
            <input type="hidden" name="id_periodetipe" value="@foreach ($tp as $key)
              {{ $key->id_periodetipe }}
            @endforeach">
            <input type="hidden" name="id_periodetahun" value="@foreach ($thn as $TA)
              {{ $TA->id_periodetahun }}
            @endforeach">
            <input type="hidden" name="id_kelas" value="@if ($mhs->idstatus ==1)
                  1
                @elseif ($mhs->idstatus ==2)
                    2
                  @elseif ($mhs->idstatus ==3)
                      3
              @endif">
              <input type="hidden" name="id_prodi" value="@if ($mhs->kodeprodi ==23)
                    2
                  @elseif ($mhs->kodeprodi ==22)
                          1
                        @elseif ($mhs->kodeprodi ==24)
                            3
                    @endif">
            <button type="submit" class="btn btn-info " >Tambah KRS</button>
            <a class="btn btn-warning" href="{{url('unduh_krs')}}">Unduh KRS</a>
						<a class="btn btn-success" href="{{url('print_krs')}}">Print KRS</a>

          </form>
          <br>
          <form class="form" role="form" action="{{url('input_krs')}}" method="POST">
            {{ csrf_field() }}

            <input type="hidden" name="id_student" value="{{$mhs->idstudent}}">
            <input type="hidden" name="idangkatan" value="{{$mhs->idangkatan}}">
            <input type="hidden" name="id_periodetipe" value="@foreach ($tp as $key)
              {{ $key->id_periodetipe }}
            @endforeach">
            <input type="hidden" name="id_periodetahun" value="@foreach ($thn as $TA)
              {{ $TA->id_periodetahun }}
            @endforeach">
            <input type="hidden" name="id_kelas" value="@if ($mhs->idstatus ==1)
                  1
                @elseif ($mhs->idstatus ==2)
                    2
                  @elseif ($mhs->idstatus ==3)
                      3
              @endif">
              <input type="hidden" name="id_prodi" value="@if ($mhs->kodeprodi ==23)
                    2
                  @elseif ($mhs->kodeprodi ==22)
                          1
                        @elseif ($mhs->kodeprodi ==24)
                            3
                    @endif">
            <button type="submit" class="btn btn-info " >Input KRS</button>
          </form>
					<hr>
					   <h3 class="box-title">Matakuliah yang diambil</h3>
              <table class="table table-bordered">
                <thead>
                <tr>

                  <th width="8%">Tanggal KRS</th>
                  <th width="6%">Semester</th>
                  <th width="5%">Kode</th>
                  <th width="17%">Matakuliah</th>
                  <th width="4%">Hari</th>
                  <th width="3%">Jam</th>
                  <th width="8%">Ruangan</th>
                  <th width="2%">SKST</th>
                  <th width="2%">SKSP</th>
                  <th width="18%">Dosen</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($krs as $item)
                  <tr>
                    <td><center>{{$item->tanggal_krs}}</center></td>
                    <td>
                        @foreach ($smt as $semester)
      											@if ($item->id_semester == $semester->idsemester)
      												{{$semester->semester}}
      											@endif
      								@endforeach
                    </td>
                    <td>
                      @foreach ($mk as $makul)
                        @if ($item->id_makul == $makul->idmakul)
                          {{$makul->kode}}
                        @endif
                      @endforeach
                    </td>
                    <td>
                      @foreach ($mk as $makul)
                        @if ($item->id_makul == $makul->idmakul)
                          {{$makul->makul}}
                        @endif
                      @endforeach
                    </td>
                    <td>
                      @foreach ($hr as $hari)
                        @if ($item->id_hari == $hari->idhari)
                          {{$hari->hari}}
                        @endif
                      @endforeach
                    </td>
                    <td>
                      @foreach ($jm as $jam)
                        @if ($item->id_jam == $jam->idjam)
                          {{$jam->jam}}
                        @endif
                      @endforeach
                    </td>
                    <td>
                      @foreach ($rng as $ruang)
                        @if ($item->id_ruangan == $ruang->id_ruangan)
                          {{$ruang->nama_ruangan}}
                        @endif
                      @endforeach
                    </td>
                    <td>{{$item->akt_sks_teori}}</td>
                    <td>{{$item->akt_sks_praktek}}</td>
                    <td>
                      @foreach ($dsn as $dosen)
                        @if ($item->id_dosen == $dosen->iddosen)
                          {{$dosen->nama}}
                        @endif
                      @endforeach
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            <!-- /.box-body -->
											{{-- <p>Cetak mengggunakna kertas A5</p> --}}
											{{-- <a class="btn btn-warning" href="{{url('download_frs')}}">Download pdf</a> --}}
											{{-- <a class="btn btn-info" href="{{url('print_frs')}}">Print</a> --}}
				 </div>
				</div>
		  </div>
    </div>
</section>
@endsection
