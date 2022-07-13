<style media="screen">
    table {
        border-collapse: collapse;
    }

    tr.b {
        line-height: 80px;
    }
</style>

<body>
    <table width="100%">
        <tr>
            <td>
                <img src="images/logo meta png.png" width="200" height="75" alt="" align="left" />
            </td>
            <td>
                <center>
                    <img src="images/kop.png" width="200" height="70" alt="" align="right" />
                </center>
            </td>
        </tr>
    </table>
    <br><br><br><br>
    <table width="100%">
        <tr>
            <td>
                <center>
                    <h4><b>REPORT KUISIONER DOSEN PENGUJI 1 TA </b></h4>
                </center>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="15%"><span style="font-size:85%">Tahun Akademik / Semester</span></td>
            <td width="1%"><span style="font-size:85%">:</span></td>
            <td width="40%"><span style="font-size:85%">{{ $periodetahun }} - {{ $periodetipe }}</span></td>
        </tr>
        <tr>
            <td width="15%"><span style="font-size:85%">Dosen</span></td>
            <td><span style="font-size:85%">:</span></td>
            <td><span style="font-size:85%">{{ $nama_dosen }}</span> </td>
        </tr>
    </table>
    <br>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>
                    <center><span style="font-size:85%">No</span></center>
                </th>
                <th>
                    <center><span style="font-size:85%">Komponen Kuisioner</span></center>
                </th>
                <th>
                    <center><span style="font-size:85%">Nilai 1</span></center>
                </th>
                <th>
                    <center><span style="font-size:85%">Nilai 2</span></center>
                </th>
                <th>
                    <center><span style="font-size:85%">Nilai 3</span></center>
                </th>
                <th>
                    <center><span style="font-size:85%">Nilai 4</span></center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @foreach ($data as $item)
                <tr>
                    <td>
                        <center><span style="font-size:85%">{{ $no++ }}</span></center>
                    </td>
                    <td>
                        <span style="font-size:85%">{{ $item->komponen_kuisioner }}</span>
                    </td>
                    <td>
                        <center>
                            <span style="font-size:85%">{{ $item->nilai_1 }}</span>
                        </center>
                    </td>
                    <td>
                        <center>
                            <span style="font-size:85%">{{ $item->nilai_2 }}</span>
                        </center>
                    </td>
                    <td>
                        <center>
                            <span style="font-size:85%">{{ $item->nilai_3 }}</span>
                        </center>
                    </td>
                    <td>
                        <center>
                            <span style="font-size:85%">{{ $item->nilai_4 }}</span>
                        </center>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
