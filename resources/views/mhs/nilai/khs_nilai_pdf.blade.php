<style media="screen">
	table {
		border-collapse: collapse;
	}
	tr.b{
		line-height:80px;
	}

</style>
<body>
  <table width="100%">
		<tr>
			<td>
					<img src="images/logo meta png.png" width="200" height="75" alt="" align="left"/>
			</td>
			<td><center>
					<img src="images/kop.png" width="200" height="70" alt="" align="right"/>
        </center>
			</td>
		</tr>
	</table><br><br><br>
  <table  width="100%">
    <tr>
      <td>
        <center><h4><b>KARTU HASIL STUDI MAHASISWA </b></h4></center>
      </td>
    </tr>
  </table>
  <table width="100%">
    <tr>
      <td><b>TA Semester</b></td>
      <td>:</td>
      <td><b><u>
				{{$periodetahun}}
				{{$periodetipe}}
        </b></u>
      </td>
      <td align=right>Jumlah SKS Maksimal  </td>
      <td> : </td>
      <td> 24</td>
    </tr>
    <tr>
      <td><b>Nama</b></td>
      <td>:</td>
      <td><b><u>{{ $mhs->nama }}</b></u></td>
      <td align=right>SKS Tempuh  </td>
      <td> : </td>
      <td> {{$sks}}</td>
    </tr>
    <tr>
      <td><b>NIM</b></td>
      <td>:</td>
      <td><b><u>{{ $mhs->nim }}</u></b></td>
    </tr>
    <tr>
      <td><b>Program Studi</b></td><td> : </td>
      <td><b><u>{{ $mhs->prodi }}</u></b>
      </td>
    </tr>
    <tr>
      <td><b>Kelas</b></td><td>:</td>
      <td><b><u>{{ $mhs->kelas }}</b></u> </td>
    </tr>
  </table>
  <br>
  <table border="1" width="100%">
    <thead>
      <tr>
        <th rowspan="2"style="width: 10px" align=center><center>No</center></th>
        <th rowspan="2"><center>Kode</center></th>
        <th rowspan="2"><center>Matakuliah</center></th>
        <th colspan="2"><center>SKS</center></th>
        <th colspan="2"><center>Nilai</center></th>
        <th rowspan="2"><center>Nilai x SKS</center></th>
      </tr>
      <tr>
        <th><center>Teori</center></th>
        <th><center>Praktek</center></th>
        <th><center>Huruf</center></th>
        <th><center>Angka</center></th>
      </tr>
    </thead>
    <tbody>
      <?php $no=1; ?>
      @foreach ($data as $item)
        <tr>
          <td><center>{{$no++}}</center></td>
          <td><center>
            @foreach ($mk as $makul)
              @if ($item->id_makul == $makul->idmakul)
                {{$makul->kode}}
              @endif
            @endforeach
          </center></td>
          <td>
            @foreach ($mk as $makul)
              @if ($item->id_makul == $makul->idmakul)
                {{$makul->makul}}
              @endif
            @endforeach
          </td>
					<td><center>{{$item->akt_sks_teori}}
					</center></td>
					<td><center>{{$item->akt_sks_praktek}}</center></td>
          <td><center>{{$item->nilai_AKHIR}}</center></td>
          <td><center>{{$item->nilai_ANGKA}}</center></td>
          <td>
            <center>
              @foreach ($mk as $makul)
                @if ($item->id_makul == $makul->idmakul)
                  @if ($item->nilai_AKHIR == 'A')
                    {{($makul->akt_sks_praktek+$makul->akt_sks_teori)*4}}
                  @elseif ($item->nilai_AKHIR == 'B+')
                    {{($makul->akt_sks_praktek+$makul->akt_sks_teori)*3.5}}
                  @elseif ($item->nilai_AKHIR == 'B')
                    {{($makul->akt_sks_praktek+$makul->akt_sks_teori)*3.0}}
                  @elseif ($item->nilai_AKHIR == 'C+')
                    {{($makul->akt_sks_praktek+$makul->akt_sks_teori)*2.5}}
                  @elseif ($item->nilai_AKHIR == 'C')
                    {{($makul->akt_sks_praktek+$makul->akt_sks_teori)*2.0}}
                  @elseif ($item->nilai_AKHIR == 'D')
                    {{($makul->akt_sks_praktek+$makul->akt_sks_teori)*1.0}}
                  @elseif ($item->nilai_AKHIR == 'E')
                    {{($makul->akt_sks_praktek+$makul->akt_sks_teori)*0.0}}
                  @elseif ($item->nilai_AKHIR == null)
                    0
                  @endif
                @endif
              @endforeach
            </center>
          </td>
        </tr>
      @endforeach
      <tr>
        <td colspan="3"><center>Jumlah</center></td>
        <td colspan="2"><center><b>{{$sks}}</b></center></td>
        <td colspan="2"></td>
        <td><center><b>{{$nia}}</b></center></td>
      </tr>
      <tr>
        <td colspan="3"><center>Indeks Prestasi Semester (IPS)</center></td>
        <td colspan="2"><center><b>{{ round($nia/$sks, 2)}}</b></center></td>
        <td colspan="3"></td>
      </tr>
    </tbody>
  </table>
</body>
