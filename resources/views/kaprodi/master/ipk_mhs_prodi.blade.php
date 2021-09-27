<table>
  <thead>
    <tr>
      <th><center>No</center></th>
      <th><center>NIM</center></th>
      <th><center>Nama</center></th>
      <th><center>Program Studi</center></th>
      <th><center>Kelas</center></th>
      <th><center>Jumlah SKS</center></th>
      <th><center>IPK</center></th>
    </tr>
  </thead>
  <tbody>
    <?php $no=1; ?>
    @foreach ($ipk as $key)
      <tr>
        <td><center>{{$no++}}</center></td>
        <td><center>{{$key->nim}}</center></td>
        <td>{{$key->nama}}</td>
        <td><center>
          @if ($key->kodeprodi ==23)
            Teknik Industri
              @elseif ($key->kodeprodi ==22)
                  Teknik Komputer
                @elseif ($key->kodeprodi ==24)
                    Farmasi
            @endif
          </center></td>
          <td><center>
            @if ($key->idstatus ==1)
                    Reguler A
                  @elseif ($key->idstatus ==2)
                      Reguler C
                    @elseif ($key->idstatus ==3)
                        Reguler B
                @endif
          </center></td>
          <td><center>
            {{ $key->total_sks}} SKS
          </center></td>
          <td><center>{{ $key->IPK }}</center></td>
      </tr>
    @endforeach
  </tbody>
</table>
