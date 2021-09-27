@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection
@section('content')
<section class="content">
  <div class="box">
            <div class="box-header">
              <h3 class="box-title">Data Mahasiswa Politeknik META Industri</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

              <table id="mhs" class="table table-bordered">
                <thead>
                <tr>
                  <th width="3%">No</th>
                  <th>NIM</th>
                  <th>Nama Mahasiswa</th>
                  <th class="select-filter">Program Studi</th>
                  <th class="select-filter">Kelas</th>
                  <th class="select-filter">Angkatan</th>
                </tr>
                </thead>
                <tbody>
                  <?php $no=1; ?>
                  @foreach($mhss as $item)
                    <tr>
                      <td>{{$no++}}</td>
                      <td>{{$item->nim}}</td>
                      <td>{{$item->nama}}</td>
                      <td>@if ($item->kodeprodi ==23)
                          Teknik Industri
                            @elseif ($item->kodeprodi ==22)
                                Teknik Komputer
                              @elseif ($item->kodeprodi ==24)
                                  Farmasi
                          @endif
                      </td>
                      <td>@if ($item->idstatus ==1)
                              Reguler A
                            @elseif ($item->idstatus ==2)
                                Reguler C
                              @elseif ($item->idstatus ==3)
                                  Reguler B
                          @endif
                        </td>
                      <td>
                        @if ($item->idangkatan ==19)
                            2019
                          @elseif ($item->idangkatan ==18)
                              2018
                            @elseif ($item->idangkatan ==17)
                                2017
                              @elseif ($item->idangkatan ==16)
                                  2016
                                @elseif ($item->idangkatan ==15)
                                    2015
                                  @elseif ($item->idangkatan ==14)
                                      2014
                                    @elseif ($item->idangkatan ==13)
                                        2013
                                      @elseif ($item->idangkatan ==12)
                                          2012
                                        @elseif ($item->idangkatan ==11)
                                            2011
                                            @elseif ($item->idangkatan ==20)
                                            2020
                                          @elseif ($item->idangkatan ==21)
                                          2021
                            @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>

              </table>
            </div>
            <!-- /.box-body -->
          </div>

          <script type="text/javascript">
			$(document).ready(function() {
					$('#mhs').DataTable({
	        initComplete: function () {
	            this.api().columns('.select-filter').every( function () {
	                var column = this;
	                var select = $('<select class="form-control"><option value=""></option></select>')
	                    .appendTo( $(column.footer()).empty() )
	                    .on( 'change', function () {
	                        var val = $.fn.dataTable.util.escapeRegex(
	                            $(this).val()
	                        );

	                        column
	                            .search( val ? '^'+val+'$' : '', true, false )
	                            .draw();
	                    } );

	                column.data().unique().sort().each( function ( d, j ) {
	                    select.append( '<option value="'+d+'">'+d+'</option>' )
	                } );
	            } );
	        }
	    });
		} );
	</script>
</section>
@endsection
