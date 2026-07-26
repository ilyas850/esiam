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
            <td width="50%">
                <img src="images/logo meta png.png" height="50" alt="" align="left" />
            </td>
            <td width="50%" align="right">
                <img src="images/kop.png" height="45" alt="" align="right" />
            </td>
        </tr>
    </table>
    <table width="100%" style="margin-top: 5px; margin-bottom: 5px;">
        <tr>
            <td align="center">
                <h4 style="margin: 2px 0;"><b>DAFTAR NILAI AKHIR</b></h4>
            </td>
        </tr>
    </table>
    <table width="100%" style="margin-bottom: 10px;">
        <tr>
            <td width="15%"><b><span style="font-size:85%">Kode Matakuliah </span></b></td>
            <td width="2%"> : </td>
            <td width="33%"><b><span style="font-size:85%"><u>{{ $data->kode }}</u></span></b></td>
            <td width="15%"><b><span style="font-size:85%">Tahun Akademik </span></b></td>
            <td width="2%"> : </td>
            <td width="33%"><b><span style="font-size:85%"><u>{{ $data->periode_tahun }} {{ $data->periode_tipe }}</u></span></b></td>
        </tr>
        <tr>
            <td><b><span style="font-size:85%">Matakuliah</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u>{{ $data->makul }} - {{ $data->akt_sks }} SKS</u></span></b></td>
            <td><b><span style="font-size:85%">Program Studi</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u>{{ $data->prodi }}</u></span></b></td>
        </tr>
        <tr>
            <td><b><span style="font-size:85%">Dosen</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u>{{ $data->nama }}, {{ $data->akademik }}</u></span></b></td>
            <td><b><span style="font-size:85%">Kelas</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u>{{ $data->kelas }}</u></span></b></td>
        </tr>
    </table>
    <table border="1" width="100%" cellpadding="4">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th width="4%"><span style="font-size:85%">No</span></th>
                <th width="14%"><span style="font-size:85%">NIM</span></th>
                <th width="32%"><span style="font-size:85%">Nama Mahasiswa</span></th>
                <th width="10%"><span style="font-size:85%">Nilai KAT</span></th>
                <th width="10%"><span style="font-size:85%">Nilai UTS</span></th>
                <th width="10%"><span style="font-size:85%">Nilai UAS</span></th>
                <th width="10%"><span style="font-size:85%">Nilai AKHIR</span></th>
                <th width="10%"><span style="font-size:85%">Nilai HURUF</span></th>
            </tr>
        </thead>
        <tbody>
            @php $i=1; @endphp
            @foreach ($tb as $item)
                <tr>
                    <td style="font-size:85%">
                        <center>{{ $i++ }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->nim }}</center>
                    </td>
                    <td style="font-size:85%">{{ $item->nama }}</td>
                    <td style="font-size:85%">
                        <center>{{ $item->nilai_KAT }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->nilai_UTS }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->nilai_UAS }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->nilai_AKHIR_angka }}</center>
                    </td>
                    <td style="font-size:85%">
                        <center>{{ $item->nilai_AKHIR }}</center>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <div style="page-break-inside: avoid; break-inside: avoid; margin-top: 20px;">
        <table width="100%">
            <tr>
                <td width="65%"></td>
                <td width="35%" align="left">
                    <span style="font-size:85%">Cikarang, ..............................</span><br>
                    <span style="font-size:85%">Dosen Pengampu</span>
                    <br><br><br><br><br>
                    <span style="font-size:85%"><b>({{ $data->nama }}, {{ $data->akademik }})</b></span>
                </td>
            </tr>
        </table>
    </div>
</body>
