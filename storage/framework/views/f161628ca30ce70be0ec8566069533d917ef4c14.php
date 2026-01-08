<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        /* Scroll wrapper */
        .datatable-scroll-wrap {
            width: 100%;
            overflow-x: auto;
            overflow-y: visible;
            border: 1px solid #ddd;
        }

        /* Table must be wider than wrapper to enable scroll */
        #table-penangguhan-bauk {
            min-width: 2200px;
            border-collapse: collapse;
            margin-bottom: 0 !important;
        }

        /* Sticky Columns Base - CRITICAL: z-index and background */
        #table-penangguhan-bauk th.sticky,
        #table-penangguhan-bauk td.sticky {
            position: sticky !important;
            background-color: #ffffff !important;
            z-index: 100 !important;
        }

        /* Header sticky cells - Higher z-index than body */
        #table-penangguhan-bauk thead th.sticky {
            background-color: #00a65a !important;
            color: #fff !important;
            z-index: 200 !important;
            border-bottom: 2px solid #008d4c !important;
        }

        /* Column 1: No */
        #table-penangguhan-bauk .sticky-col-1 {
            left: 0px;
            width: 50px;
            min-width: 50px;
            max-width: 50px;
            border-right: 1px solid #ddd !important;
        }

        /* Column 2: Tanggal Dibuat */
        #table-penangguhan-bauk .sticky-col-2 {
            left: 50px;
            width: 130px;
            min-width: 130px;
            max-width: 130px;
            border-right: 1px solid #ddd !important;
        }

        /* Column 3: Tahun Akademik */
        #table-penangguhan-bauk .sticky-col-3 {
            left: 180px;
            width: 150px;
            min-width: 150px;
            max-width: 150px;
            border-right: 1px solid #ddd !important;
        }

        /* Column 4: Mahasiswa - Last sticky column, stronger border */
        #table-penangguhan-bauk .sticky-col-4 {
            left: 330px;
            width: 250px;
            min-width: 250px;
            max-width: 250px;
            border-right: 3px solid #00a65a !important;
            box-shadow: 4px 0 8px -2px rgba(0, 0, 0, 0.2);
        }

        /* Striped rows - MUST use !important to override base sticky background */
        #table-penangguhan-bauk.table-striped>tbody>tr:nth-of-type(odd)>td.sticky {
            background-color: #f9f9f9 !important;
        }

        #table-penangguhan-bauk.table-striped>tbody>tr:nth-of-type(even)>td.sticky {
            background-color: #ffffff !important;
        }

        /* Hover state - Also must use !important */
        #table-penangguhan-bauk.table-hover>tbody>tr:hover>td.sticky {
            background-color: #e8e8e8 !important;
        }

        /* DataTables controls should not scroll */
        .dataTables_wrapper .row:first-child,
        .dataTables_wrapper .row:last-child {
            margin-left: 0;
            margin-right: 0;
        }
    </style>

    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Data Penangguhan <?php echo e($kategori->kategori ?? ''); ?>

                    <b><?php echo e($thn_aktif->periode_tahun ?? ''); ?> - <?php echo e($tp_aktif->periode_tipe ?? ''); ?></b>
                </h3>
            </div>
            <div class="box-body">
                <table id="table-penangguhan-bauk" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center sticky sticky-col-1">No</th>
                            <th class="text-center sticky sticky-col-2">Tanggal Dibuat</th>
                            <th class="text-center sticky sticky-col-3">Tahun Akademik</th>
                            <th class="text-center sticky sticky-col-4">Mahasiswa</th>
                            <th class="text-center" style="min-width: 150px;">Prodi</th>
                            <th class="text-center" style="min-width: 100px;">Kelas</th>
                            <th class="text-center" style="min-width: 130px;">Jenis Penangguhan</th>
                            <th class="text-center" style="min-width: 150px;">Total Tunggakan</th>
                            <th class="text-center" style="min-width: 180px;">Rencana Pembayaran</th>
                            <th class="text-center" style="min-width: 200px;">Alasan</th>
                            <th class="text-center" style="min-width: 100px;">BAUK</th>
                            <th class="text-center" style="min-width: 80px;">Dosen PA</th>
                            <th class="text-center" style="min-width: 80px;">Kaprodi</th>
                            <th class="text-center" style="min-width: 80px;">BAAK</th>
                            <th class="text-center" style="min-width: 100px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Modal Input Tunggakan -->
    <div class="modal fade" id="modalTunggakan" tabindex="-1" aria-labelledby="modalTunggakanLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tunggakan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formTunggakan" action="" method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('put'); ?>
                        <div class="form-group">
                            <label>Nominal Tunggakan</label>
                            <input type="number" class="form-control" name="total_tunggakan" id="input_tunggakan" required>
                        </div>
                        <input type="hidden" name="id_penangguhan_kategori"
                            value="<?php echo e($kategori->id_penangguhan_kategori ?? ''); ?>">
                        <input type="hidden" name="id_periodetahun" value="<?php echo e($thn_aktif->id_periodetahun ?? ''); ?>">
                        <input type="hidden" name="id_periodetipe" value="<?php echo e($tp_aktif->id_periodetipe ?? ''); ?>">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function () {
            var table = $('#table-penangguhan-bauk').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo e(url('data_penangguhan_bauk')); ?>",
                    type: 'POST',
                    data: function (d) {
                        d._token = '<?php echo e(csrf_token()); ?>';
                        d.id_periodetahun = '<?php echo e($thn_aktif->id_periodetahun ?? ''); ?>';
                        d.id_periodetipe = '<?php echo e($tp_aktif->id_periodetipe ?? ''); ?>';
                        d.id_penangguhan_kategori = '<?php echo e($kategori->id_penangguhan_kategori ?? ''); ?>';
                    }
                },
                autoWidth: false,
                // DOM: Controls outside scroll, table inside scroll wrapper
                dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                    "<'row'<'col-sm-12'<'datatable-scroll-wrap'tr>>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                columns: [
                    {
                        data: null,
                        searchable: false,
                        orderable: false,
                        className: "text-center sticky sticky-col-1",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'created_at',
                        className: "text-center sticky sticky-col-2",
                        render: function (data) {
                            if (!data) return '-';
                            var date = new Date(data);
                            var bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                                'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                            var hari = date.getDate();
                            var bln = bulan[date.getMonth()];
                            var tahun = date.getFullYear();
                            var jam = ('0' + date.getHours()).slice(-2);
                            var menit = ('0' + date.getMinutes()).slice(-2);
                            return hari + ' ' + bln + ' ' + tahun + '<br><small>' + jam + ':' + menit + '</small>';
                        }
                    },
                    {
                        data: null,
                        className: "text-center sticky sticky-col-3",
                        render: function (data, type, row) {
                            return (row.periode_tahun || '-') + ' - ' + (row.periode_tipe || '-');
                        }
                    },
                    {
                        data: 'nama',
                        name: 'student.nama',
                        className: "sticky sticky-col-4",
                        render: function (data, type, row) {
                            return '<strong>' + (row.nama || '-') + '</strong><br><small class="text-muted">' + (row.nim || '-') + '</small>';
                        }
                    },
                    { data: 'prodi', name: 'prodi.prodi', defaultContent: '-' },
                    { data: 'kelas', name: 'kelas.kelas', className: "text-center", defaultContent: '-' },
                    { data: 'kategori', name: 'penangguhan_master_kategori.kategori', className: "text-center", defaultContent: '-' },
                    {
                        data: 'total_tunggakan',
                        name: 'penangguhan_master_trans.total_tunggakan',
                        className: "text-right",
                        render: function (data, type, row) {
                            if (!data || data == null) {
                                return '<button class="btn btn-info btn-xs btn-tunggakan" data-id="' + row.id_penangguhan_trans + '" data-tunggakan="">Input</button>';
                            } else {
                                return 'Rp ' + parseInt(data).toLocaleString('id-ID') +
                                    ' <button class="btn btn-warning btn-xs btn-tunggakan" data-id="' + row.id_penangguhan_trans + '" data-tunggakan="' + data + '"><i class="fa fa-edit"></i></button>';
                            }
                        }
                    },
                    { data: 'rencana_bayar', name: 'penangguhan_master_trans.rencana_bayar', defaultContent: '-' },
                    { data: 'alasan', name: 'penangguhan_master_trans.alasan', defaultContent: '-' },
                    {
                        data: null,
                        className: "text-center",
                        render: function (data, type, row) {
                            if (row.validasi_bauk === 'BELUM') {
                                return '<a href="/validasi_penangguhan_bauk/' + row.id_penangguhan_trans + '" class="btn btn-success btn-xs" title="klik untuk validasi"><i class="fa fa-check"></i></a> ' +
                                    '<a href="/tolak_penangguhan_bauk/' + row.id_penangguhan_trans + '" class="btn btn-danger btn-xs" title="klik untuk tolak"><i class="fa fa-close"></i></a>';
                            } else if (row.validasi_bauk === 'SUDAH') {
                                return '<a href="/batal_validasi_penangguhan_bauk/' + row.id_penangguhan_trans + '" class="btn btn-warning btn-xs" title="klik untuk batal"><i class="fa fa-rotate-left"></i></a> ' +
                                    '<a href="/tolak_penangguhan_bauk/' + row.id_penangguhan_trans + '" class="btn btn-danger btn-xs" title="klik untuk tolak"><i class="fa fa-close"></i></a>';
                            } else if (row.validasi_bauk === 'TOLAK') {
                                return '<a href="/batal_validasi_penangguhan_bauk/' + row.id_penangguhan_trans + '" class="btn btn-info btn-xs" title="klik untuk batal tolak"><i class="fa fa-rotate-right"></i></a>';
                            }
                            return '-';
                        }
                    },
                    {
                        data: 'validasi_dsn_pa',
                        className: "text-center",
                        render: function (data) {
                            return data === 'BELUM'
                                ? '<span class="badge bg-yellow">' + data + '</span>'
                                : '<span class="badge bg-green">' + data + '</span>';
                        }
                    },
                    {
                        data: 'validasi_kaprodi',
                        className: "text-center",
                        render: function (data) {
                            return data === 'BELUM'
                                ? '<span class="badge bg-yellow">' + data + '</span>'
                                : '<span class="badge bg-green">' + data + '</span>';
                        }
                    },
                    {
                        data: 'validasi_baak',
                        className: "text-center",
                        render: function (data) {
                            return data === 'BELUM'
                                ? '<span class="badge bg-yellow">' + data + '</span>'
                                : '<span class="badge bg-green">' + data + '</span>';
                        }
                    },
                    {
                        data: null,
                        className: "text-center",
                        render: function (data, type, row) {
                            if (row.status_penangguhan === 'OPEN' || row.status_penangguhan === null) {
                                return '<a href="/close_penangguhan/' + row.id_penangguhan_trans + '" class="btn btn-info btn-xs" title="Klik untuk CLOSE"><i class="fa fa-check"></i></a>';
                            } else if (row.status_penangguhan === 'CLOSE') {
                                return '<a href="/open_penangguhan/' + row.id_penangguhan_trans + '" class="btn btn-danger btn-xs" title="Klik untuk OPEN"><i class="fa fa-close"></i></a>';
                            }
                            return '-';
                        }
                    }
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    processing: "Memuat...",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "Lanjut",
                        previous: "Kembali"
                    }
                },
                // Force inline styles on sticky cells to ensure opaque backgrounds
                createdRow: function (row, data, dataIndex) {
                    var bgColor = (dataIndex % 2 === 0) ? '#ffffff' : '#f9f9f9';
                    $(row).find('td.sticky').each(function () {
                        $(this).css({
                            'background-color': bgColor,
                            'position': 'sticky',
                            'z-index': '100'
                        });
                    });
                }
            });

            // Handle button click for tunggakan modal
            $(document).on('click', '.btn-tunggakan', function () {
                var id = $(this).data('id');
                var tunggakan = $(this).data('tunggakan');
                $('#formTunggakan').attr('action', '/put_tunggakan/' + id);
                $('#input_tunggakan').val(tunggakan);
                $('#modalTunggakan').modal('show');
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/bauk/penangguhan/data_penangguhan.blade.php ENDPATH**/ ?>