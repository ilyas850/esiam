<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Silahkan Filter</h3>
            </div>
            
            <form id="filter-form" class="form" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label for="">Periode Tahun</label>
                            <select class="form-control" name="id_periodetahun" id="id_periodetahun">
                                <option value="">-- Pilih Periode Tahun --</option>
                                <?php $__currentLoopData = $tahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($thn->id_periodetahun); ?>" <?php echo e($thn->status == 'ACTIVE' ? 'selected' : ''); ?>>
                                        <?php echo e($thn->periode_tahun); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Periode Tipe</label>
                            <select class="form-control" name="id_periodetipe" id="id_periodetipe">
                                <option value="">-- Pilih Tipe --</option>
                                <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tipee->id_periodetipe); ?>" <?php echo e($tipee->status == 'ACTIVE' ? 'selected' : ''); ?>><?php echo e($tipee->periode_tipe); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Program Studi</label>
                            <select class="form-control" name="kodeprodi" id="kodeprodi">
                                <option value="">-- Semua Prodi --</option>
                                <?php $__currentLoopData = $prodi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($prd->kodeprodi); ?>"><?php echo e($prd->prodi); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="button" id="btn-filter" class="btn btn-info">Cari Mahasiswa</button>
                    
                    <button type="button" id="btn-export" class="btn btn-success pull-right">Export Excel</button>
                </div>
            </form>

            
            <form id="export-form" action="<?php echo e(url('export_data_mhs_aktif_filter')); ?>" method="POST" style="display:none;">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="id_periodetahun" id="export_id_periodetahun">
                <input type="hidden" name="id_periodetipe" id="export_id_periodetipe">
                <input type="hidden" name="kodeprodi" id="export_kodeprodi">
            </form>
        </div>

        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Data mahasiswa aktif all Prodi</h3>
            </div>
            <div class="box-body">

                <div class="table-responsive">
                    <table id="table-mhs" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <center>No</center>
                                </th>
                                <th>
                                    <center>NIM</center>
                                </th>
                                <th>
                                    <center>Nama Mahasiswa</center>
                                </th>
                                <th>
                                    <center>Program Studi</center>
                                </th>
                                <th>
                                    <center>Kelas</center>
                                </th>
                                <th>
                                    <center>Angkatan</center>
                                </th>
                                <th>
                                    <center>Intake</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <?php $__env->startSection('script'); ?>
        <script>
            $(function () {
                var table = $('#table-mhs').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "<?php echo e(url('data_mahasiswa_aktif_json')); ?>",
                        type: 'POST',
                        data: function (d) {
                            d._token = "<?php echo e(csrf_token()); ?>";
                            d.id_periodetahun = $('#id_periodetahun').val();
                            d.id_periodetipe = $('#id_periodetipe').val();
                            d.kodeprodi = $('#kodeprodi').val();
                        }
                    },
                    columns: [
                        {
                            data: null, searchable: false, orderable: false, render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        { data: 'nim', name: 'mhs.nim' },
                        { data: 'nama', name: 'mhs.nama' },
                        { data: 'prodi', name: 'prd.prodi' },
                        { data: 'kelas', name: 'kls.kelas' },
                        { data: 'angkatan', name: 'ang.angkatan' },
                        { data: 'intake', name: 'intake', searchable: false }
                    ],
                    order: [[1, 'asc']]
                });

                $('#btn-filter').click(function () {
                    table.draw();
                });

                $('#btn-export').click(function () {
                    // Populate hidden form and submit
                    $('#export_id_periodetahun').val($('#id_periodetahun').val());
                    $('#export_id_periodetipe').val($('#id_periodetipe').val());
                    $('#export_kodeprodi').val($('#kodeprodi').val());
                    $('#export-form').submit();
                });
            });
        </script>
    <?php $__env->stopSection(); ?>
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/sadmin/datamahasiswa/data_mhs_aktif.blade.php ENDPATH**/ ?>