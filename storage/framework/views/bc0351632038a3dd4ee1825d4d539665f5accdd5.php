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
        #table-pindahkelas {
            min-width: 1800px;
            border-collapse: collapse;
            margin-bottom: 0 !important;
        }

        /* Sticky Columns Base - CRITICAL: z-index and background */
        #table-pindahkelas th.sticky,
        #table-pindahkelas td.sticky {
            position: sticky !important;
            background-color: #ffffff !important;
            z-index: 100 !important;
        }

        /* Header sticky cells - Higher z-index than body */
        #table-pindahkelas thead th.sticky {
            background-color: #00c0ef !important;
            color: #fff !important;
            z-index: 200 !important;
            border-bottom: 2px solid #00a7d0 !important;
        }

        /* Column 1: No */
        #table-pindahkelas .sticky-col-1 {
            left: 0px;
            width: 50px;
            min-width: 50px;
            max-width: 50px;
            border-right: 1px solid #ddd !important;
        }

        /* Column 2: Tanggal Dibuat */
        #table-pindahkelas .sticky-col-2 {
            left: 50px;
            width: 130px;
            min-width: 130px;
            max-width: 130px;
            border-right: 1px solid #ddd !important;
        }

        /* Column 3: Tahun Akademik */
        #table-pindahkelas .sticky-col-3 {
            left: 180px;
            width: 150px;
            min-width: 150px;
            max-width: 150px;
            border-right: 1px solid #ddd !important;
        }

        /* Column 4: Mahasiswa - Last sticky column, stronger border */
        #table-pindahkelas .sticky-col-4 {
            left: 330px;
            width: 250px;
            min-width: 250px;
            max-width: 250px;
            border-right: 3px solid #00c0ef !important;
            box-shadow: 4px 0 8px -2px rgba(0, 0, 0, 0.2);
        }

        /* Striped rows - MUST use !important to override base sticky background */
        #table-pindahkelas.table-striped>tbody>tr:nth-of-type(odd)>td.sticky {
            background-color: #f9f9f9 !important;
        }

        #table-pindahkelas.table-striped>tbody>tr:nth-of-type(even)>td.sticky {
            background-color: #ffffff !important;
        }

        /* Hover state - Also must use !important */
        #table-pindahkelas.table-hover>tbody>tr:hover>td.sticky {
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
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Data Pengajuan Pindah Kelas Mahasiswa</h3>
                <div class="box-tools pull-right">
                    <div class="btn-group">
                        <div class="input-group input-group-sm" style="width: 220px; display: inline-table; margin-right: 10px;">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <select id="filter-tahun" class="form-control">
                                <option value="">-- Semua Tahun --</option>
                                <?php $__currentLoopData = $tahun_akademik; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($thn->id_periodetahun); ?>"><?php echo e($thn->periode_tahun); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="input-group input-group-sm" style="width: 180px; display: inline-table;">
                            <span class="input-group-addon"><i class="fa fa-bookmark"></i></span>
                            <select id="filter-tipe" class="form-control">
                                <option value="">-- Semua Semester --</option>
                                <?php $__currentLoopData = $periode_tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tipe->id_periodetipe); ?>"><?php echo e($tipe->periode_tipe); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <table id="table-pindahkelas" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center sticky sticky-col-1">No</th>
                            <th class="text-center sticky sticky-col-2">Tanggal Dibuat</th>
                            <th class="text-center sticky sticky-col-3">Tahun Akademik</th>
                            <th class="text-center sticky sticky-col-4">Mahasiswa</th>
                            <th class="text-center" style="min-width: 150px;">Prodi</th>
                            <th class="text-center" style="min-width: 100px;">Kelas Asal</th>
                            <th class="text-center" style="min-width: 100px;">Kelas Tujuan</th>
                            <th class="text-center" style="min-width: 100px;">SKS Tempuh</th>
                            <th class="text-center" style="min-width: 250px;">Alasan</th>
                            <th class="text-center" style="min-width: 120px;">No. HP</th>
                            <th class="text-center" style="min-width: 200px;">Alamat</th>
                            <th class="text-center" style="min-width: 80px;">BAUK</th>
                            <th class="text-center" style="min-width: 80px;">Dosen PA</th>
                            <th class="text-center" style="min-width: 80px;">Kaprodi</th>
                            <th class="text-center" style="min-width: 120px;">BAAK (Action)</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function () {
            var table = $('#table-pindahkelas').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "<?php echo e(url('cek_trans_pengajuan/3')); ?>",
                    data: function (d) {
                        d.tahun_akademik = $('#filter-tahun').val();
                        d.periode_tipe = $('#filter-tipe').val();
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
                            return row.periode_tahun + ' - ' + row.periode_tipe;
                        }
                    },
                    {
                        data: 'nama',
                        name: 'student.nama',
                        className: "sticky sticky-col-4",
                        render: function (data, type, row) {
                            return '<strong>' + row.nama + '</strong><br><small class="text-muted">' + row.nim + '</small>';
                        }
                    },
                    { data: 'prodi', name: 'prodi.prodi' },
                    { data: 'kelas', name: 'kelas.kelas', className: "text-center" },
                    { data: 'kelas_tujuan', name: 'kelas_tujuan.kelas', className: "text-center" },
                    {
                        data: 'sks_ditempuh',
                        name: 'pengajuan_trans.sks_ditempuh',
                        className: "text-center",
                        render: function (data) {
                            return data + ' SKS';
                        }
                    },
                    { data: 'alasan', name: 'pengajuan_trans.alasan' },
                    { data: 'no_hp', className: "text-center" },
                    { data: 'alamat' },
                    {
                        data: 'val_bauk',
                        className: "text-center",
                        render: function (data) {
                            return data === 'BELUM'
                                ? '<span class="badge bg-yellow">' + data + '</span>'
                                : '<span class="badge bg-green">' + data + '</span>';
                        }
                    },
                    {
                        data: 'val_dsn_pa',
                        className: "text-center",
                        render: function (data) {
                            return data === 'BELUM'
                                ? '<span class="badge bg-yellow">' + data + '</span>'
                                : '<span class="badge bg-green">' + data + '</span>';
                        }
                    },
                    {
                        data: 'val_kaprodi',
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
                            if (row.val_kaprodi === 'BELUM') {
                                return '<span class="label label-danger">Menunggu Kaprodi</span>';
                            } else if (row.val_kaprodi === 'SUDAH') {
                                if (row.val_baak === 'BELUM') {
                                    return '<a href="/val_pengajuan_baak/' + row.id_trans_pengajuan + '" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Validasi</a>';
                                } else if (row.val_baak === 'SUDAH') {
                                    return '<a href="/batal_val_pengajuan_baak/' + row.id_trans_pengajuan + '" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Batal</a>';
                                }
                            }
                            return '';
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

            // Reload table when filter changes
            $('#filter-tahun, #filter-tipe').on('change', function () {
                table.ajax.reload();
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/sadmin/pengajuan/hasil_transaction_pengajuan_pindahkelas.blade.php ENDPATH**/ ?>