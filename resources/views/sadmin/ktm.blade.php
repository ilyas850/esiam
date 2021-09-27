
<!DOCTYPE html>
<html>
<head>
	<title>Cara Menampilkan teks di atas gambar </title>
	<style type="text/css">
	body
		/* .container{
			width: 800px;
			margin: auto;
		} */
		.pembungkus{
			position: relative;
		}
		img {
    	/* border: 5px dotted aqua; */
		}
		h1{
			color: white;
		}
		h2{
			position: absolute;
			left: 120px;
			top: 200px;
			color: black;
		}
		h3{
      position: absolute;
			left: 120px;
			top: 200px;
			color: black;

		}
		h4{
			position: absolute;
			left: 40px;
			top: 170px;
			color: magenta;
		}
    .foto{
      position: absolute;
			left: 719px;
			top: 73px;
			color: black;
      border-radius: 9px 9px 9px 9px;
    }

    table{
      position: absolute;
			left: 140px;
			top: 370px;
			color: #001885;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 26px;

		}
	</style>
</head>
<body>
<div class="container">
<h1>Cara Menampilkan teks di atas gambar</h1>
	<div class="pembungkus">
    @if ($mhs->foto == null)
      <img class="foto" height="167px" width="133px" src="{{ public_path("adminlte/img/default.jpg") }}">
    @elseif ($mhs->foto != null)
      <img class="foto" height="167px" width="133px" src="{{ public_path("foto_mhs/".$mhs->foto) }}">
    @endif

		<center><img height="539px" width="856px" src="images/KTM 2020 1_page-0002.jpg"></center>
    <table>
      <tr height="35px">
        <td>Nama</td>
        <td >:</td>
        <td> {{$mhs->nama}}</td>
      </tr>
      <tr height="35px">
        <td>NIM</td>
        <td>:</td>
        <td>{{$mhs->nim}}</td>
      </tr>
      <tr height="35px">
        <td>Program Studi</td>
        <td>:</td>
        <td>
          @if ($mhs->kodeprodi == 22)
            Teknik Komputer
          @elseif ($mhs->kodeprodi == 23)
            Teknik Industri
          @elseif ($mhs->kodeprodi == 24)
            Farmasi
          @endif
        </td>
      </tr>
    </table>
	</div>
</div>
</body>
</html>
