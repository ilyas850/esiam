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
                            <th>NISN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($mhss as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->nim }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td>{{ $item->kelas }}</td>
                                <td>{{ $item->angkatan }}</td>
                                <td>{{ $item->nisn }}</td>
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
                    initComplete: function() {
                        this.api().columns('.select-filter').every(function() {
                            var column = this;
                            var select = $(
                                    '<select class="form-control"><option value=""></option></select>'
                                )
                                .appendTo($(column.footer()).empty())
                                .on('change', function() {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });

                            column.data().unique().sort().each(function(d, j) {
                                select.append('<option value="' + d + '">' + d +
                                    '</option>')
                            });
                        });
                    }
                });
            });
        </script>
    </section>
@endsection
