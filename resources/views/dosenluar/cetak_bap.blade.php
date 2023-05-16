<!DOCTYPE html>
<html>
<head>
    <title>Politeknik META Industri Cikarang</title>
</head>
<body>
    <table width="100%">
		<tr>
			<td>
					<img src="{{asset('images/logo meta png.png')}}" width="200" height="75" alt="" align="left"/>
			</td>
			<td><center>
					<img src="{{asset('images/kop.png')}}" width="200" height="70" alt="" align="right"/>
        </center>
			</td>
		</tr>
	</table><br>
    <center>
        <h2 class="box-title">Laporan BAP Prodi {{$prd}} </h2>
        <h3 class="box-title">Semester {{$tipe}} – {{$tahun}}</h3>
    </center>
    <table border="1" width="100%">
        <tr>
            <td>Matakuliah</td>
            <td>{{$data->makul}}</td>
        </tr>
        <tr>
            <td>Nama Dosen</td>
            <td>{{$data->nama}}</td>
        </tr>
        <tr>
            <td>Kelas / Semester</td>
            <td>{{$data->kelas}} / {{$data->semester}}</td>
        </tr>
        <tr>
            <td>Media Pembelajaran</td>
            <td>{{$dtbp->media_pembelajaran}}</td>
        </tr>
        <tr>
            <td>Pukul</td>
            <td>{{$dtbp->jam_mulai}} - {{$dtbp->jam_selsai}}</td>
        </tr>
        <tr>
            <td>Tanggal Perkuliahan</td>
            <td>{{ Carbon\Carbon::parse($dtbp->tanggal)->formatLocalized('%d %B %Y') }}</td>
        </tr>
        <tr>
            <td>Materi Perkuliahan</td>
            <td>{{$dtbp->materi_kuliah}}</td>
        </tr>
        <tr>
            <td>Pertemuan</td>
            <td>Ke-{{$dtbp->pertemuan}}</td>
        </tr>
        <tr>
            <td>Mahasiswa Hadir/Tidak Hadir</td>
            <td>{{$dtbp->hadir}} / {{$dtbp->tidak_hadir}}</td>
        </tr>
    </table>
    <div class="form-group">
        <h4>1.	Kuliah tatap muka</h4>
        @if (($dtbp->file_kuliah_tatapmuka) != null)
        <img src="/File_BAP/{{$data->iddosen}}/{{$dtbp->id_kurperiode}}/Kuliah Tatap Muka/{{$dtbp->file_kuliah_tatapmuka}}"  width="60%" height="300px" />
        @else
        Tidak ada lampiran
        @endif
    </div>
    <div class="form-group">
        <h4>2.	Materi Perkuliahan</h4>
        @if (($dtbp->file_materi_kuliah) != null)
        <img src="/File_BAP/{{$data->iddosen}}/{{$dtbp->id_kurperiode}}/Materi Kuliah/{{$dtbp->file_materi_kuliah}}" type="application/pdf" width="60%" height="300px" />
        @else
        Tidak ada lampiran
        @endif
    </div>
    <div class="form-group">
        <h4>3.	Materi Tugas</h4>
        @if (($dtbp->file_materi_tugas) != null)
        <img src="/File_BAP/{{$data->iddosen}}/{{$dtbp->id_kurperiode}}/Tugas Kuliah/{{$dtbp->file_materi_tugas}}" type="application/pdf" width="60%" height="300px" />
        @else
        Tidak ada lampiran
        @endif
    </div>
    <br><br><br>
    <table width="100%">
     <tr>
            <td width="20%" ><span style="font-size:85%">Cikarang, {{$d}} {{$m}} {{$y}}</span></td>
     </tr>
    </table>
    <br><br><br><br>
	<table width="100%">
		<tr>
			<td width="30%" ><span style="font-size:85%">{{Auth::user()->name}}</span></td>
		</tr>
	</table>
    <script>
        window.print();
    </script>    
</body>
</html>