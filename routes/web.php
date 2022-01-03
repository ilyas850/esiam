<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/daftar', 'MhsController@daftar');

Route::get('login_adm', 'AdminController@login');

Route::group(['middleware' => 'sadmin'], function () {
    Route::get('change_pass/{id}', 'SadminController@change');
    Route::put('pwd_adm/{id}', 'SadminController@store_new_pass');
    Route::get('show_mhs', 'SadminController@show_mhs');
    Route::get('show_user', 'SadminController@show_user');
    Route::get('usermhs/{id}', 'SadminController@add_user_mhs');
    Route::post('save_usermhs', 'SadminController@store_user_mhs');
    Route::get('show_ta', 'SadminController@show_ta');
    Route::post('save_krs_time', 'SadminController@save_krs_time');
    Route::post('delete_time_krs', 'SadminController@delete_time_krs');
    //data ipk mahasiswa aktif
    Route::get('data_ipk', 'SadminController@data_ipk');
    Route::get('export_nilai_ipk', 'SadminController@export_nilai_ipk');

    //data kaprodi
    Route::get('kaprodi', 'SadminController@kaprodi');
    Route::post('post_kaprodi', 'SadminController@post_kaprodi');
    Route::put('put_kaprodi/{id}', 'SadminController@put_kaprodi');
    Route::post('hapuskaprodi', 'SadminController@hapuskaprodi');

    Route::post('change_ta_thn', 'SadminController@change_ta_thn');
    Route::post('change_ta_tp', 'SadminController@change_ta_tp');
    Route::post('add_ta', 'SadminController@add_ta');
    Route::get('info', 'SadminController@info');
    Route::post('simpan_info', 'SadminController@simpan_info');
    Route::get('hapusinfo/{id}', 'SadminController@hapusinfo');
    Route::get('editinfo/{id}', 'SadminController@editinfo');
    Route::put('simpanedit/{id}', 'SadminController@simpanedit');
    Route::post('resetuser', 'SadminController@resetuser');
    Route::delete('hapususer/{id}', 'SadminController@hapususer');
    Route::get('edom', 'EdomController@edom');
    Route::post('simpanedom', 'EdomController@simpanedom');
    Route::post('edit_time_edom', 'EdomController@edit_edom');
    Route::get('data_edom', 'EdomController@data_edom');
    Route::get('data_foto', 'SadminController@data_foto');
    Route::get('lihat_foto_ti', 'SadminController@lihat_foto_ti');
    Route::get('lihat_foto_tk', 'SadminController@lihat_foto_tk');
    Route::get('lihat_foto_fm', 'SadminController@lihat_foto_fm');
    Route::get('data_nilai', 'SadminController@data_nilai');
    Route::get('tes_table', 'SadminController@tes_table');
    Route::post('save_nilai_angka', 'SadminController@save_nilai_angka');
    Route::post('cek_nilai', 'SadminController@cek_nilai');
    Route::get('pembimbing', 'SadminController@pembimbing');
    Route::get('data_admin', 'SadminController@data_admin');
    Route::get('userdsn/{id}', 'SadminController@add_user_dsn');
    Route::post('saveuser_dsn', 'SadminController@saveuser_dsn');
    Route::post('resetuserdsn', 'SadminController@resetuserdsn');
    Route::delete('hapususerdsn/{id}', 'SadminController@hapususerdsn');
    Route::get('approve_krs', 'SadminController@approve_krs');
    Route::post('view_krs', 'SadminController@view_krs');
    Route::get('data_dosen_luar', 'SadminController@data_dosen_luar');
    Route::post('saveuser_dsn_luar', 'SadminController@saveuser_dsn_luar');
    Route::post('resetuserdsn_luar', 'SadminController@resetuserdsn_luar');
    Route::delete('hapususerdsn_luar/{id}', 'SadminController@hapususerdsn_luar');
    Route::get('pdm_aka', 'SadminController@pedoman');
    Route::post('save_pedoman_akademik', 'SadminController@save_pedoman_akademik');
    Route::get('data_ktm', 'SadminController@data_ktm');
    Route::post('view_ktm', 'SadminController@view_ktm');
    Route::get('downloadktm/{id}', 'SadminController@downloadktm');
    Route::get('cek_krs_admin/{id}', 'SadminController@cek_krs');
    Route::post('batalkrsmhs', 'SadminController@batalkrsmhs');

    //WADIR
    Route::get('wadir', 'SadminController@wadir');
    Route::post('post_wadir', 'SadminController@post_wadir');

    //master khs
    Route::get('nilai_khs', 'SadminController@nilai_khs');
    Route::post('export_nilai_khs', 'SadminController@export_nilai_khs');
    Route::get('nilai_prausta', 'SadminController@nilai_prausta');
    Route::post('export_nilai_prausta', 'SadminController@export_nilai_prausta');

    //master krs
    Route::get('data_krs', 'SadminController@data_krs');
    Route::post('export_krs_mhs', 'SadminController@export_krs_mhs');

    //master nilai
    Route::get('transkrip_nilai', 'SadminController@transkrip_nilai');
    Route::get('cek_transkrip/{id}', 'SadminController@cek_transkrip');
    route::post('lihat_transkrip', 'SadminController@lihat_transkrip');
    Route::get('no_transkrip', 'SadminController@no_transkrip');
    Route::get('print_transkrip/{id}', 'SadminController@print_transkrip');

    Route::get('transkrip_nilai_final', 'SadminController@transkrip_nilai_final');
    Route::get('input_transkrip_final/{id}', 'SadminController@input_transkrip_final');
    Route::post('simpan_transkrip_final', 'SadminController@simpan_transkrip_final');
    Route::get('lihat_transkrip_final/{id}', 'SadminController@lihat_transkrip_final');
    Route::get('print_transkrip_final/{id}', 'SadminController@print_transkrip_final');
    route::get('downloadAbleFile/{id}', 'SadminController@downloadAbleFile');
    Route::get('edit_transkrip_final/{id}', 'SadminController@edit_transkrip_final');
    Route::put('simpanedit_transkrip_final/{id}', 'SadminController@simpanedit_transkrip_final');

    //nilai mahasiswa
    Route::get('nilai_mhs', 'SadminController@nilai_mhs');
    Route::get('cek_nilai_mhs/{id}', 'SadminController@cek_nilai_mhs');

    //master soal
    Route::get('soal_uts', 'SadminController@soal_uts');
    Route::get('download_soal_uts/{id}', 'SadminController@download_soal_uts');
    Route::get('soal_uas', 'SadminController@soal_uas');
    Route::get('download_soal_uas/{id}', 'SadminController@download_soal_uas');

    //master perkuliahan
    Route::get('rekap_perkuliahan', 'SadminController@rekap_perkuliahan');
    Route::get('cek_rekapan/{id}', 'SadminController@cek_rekapan');
    Route::get('cek_view_bap/{id}', 'SadminController@cek_view_bap');
    Route::get('cek_print_bap/{id}', 'SadminController@cek_print_bap');
    Route::get('cek_sum_absen/{id}', 'SadminController@sum_absen');
    Route::get('print_absensi_cek/{id}', 'SadminController@print_absensi');
    Route::get('cek_jurnal_bap/{id}', 'SadminController@jurnal_bap');
    Route::get('print_jurnal_cek/{id}', 'SadminController@print_jurnal');

    //visi misi
    Route::get('visimisi', 'SadminController@visimisi');
    Route::get('add_visimisi', 'SadminController@add_visimisi');
    Route::post('save_visimisi', 'SadminController@save_visimisi');
    Route::get('editvisimisi/{id}', 'SadminController@editvisimisi');
    Route::put('saveeditvisimisi/{id}', 'SadminController@simpaneditvisimisi');

    //PraUSTA
    Route::get('data_prausta', 'SadminController@data_prausta');
    Route::post('filter_prausta', 'SadminController@filter_prausta');

    //Admin Prodi
    Route::get('data_admin_prodi', 'SadminController@data_admin_prodi');
    Route::post('post_adminprodi', 'SadminController@post_adminprodi');
    Route::put('put_adminprodi/{id}', 'SadminController@put_adminprodi');
    Route::get('hapusadminprodi/{id}', 'SadminController@hapusadminprodi');
});

Route::group(['middleware' => 'dosen'], function () {
    Route::get('mhs_bim', 'DosenController@mhs_bim');
    Route::get('record_nilai/{id}', 'DosenController@record_nilai');
    Route::get('val_krs', 'DosenController@val_krs');
    Route::get('cek_krs/{id}', 'DosenController@cek_krs');
    Route::post('hapuskrsmhs', 'DosenController@hapuskrsmhs');
    Route::post('savekrs_new', 'DosenController@savekrs_new');
    Route::post('krs_validasi', 'DosenController@krs_validasi');
    Route::get('change_pwd_dsn/{id}', 'DosenController@change');
    Route::put('pwd_dsn/{id}', 'DosenController@store_pwd_dsn');

    //matakuliah diampu dosen
    Route::get('makul_diampu_dsn', 'DosenController@makul_diampu_dsn');
    Route::get('input_kat_dsn/{id}', 'DosenController@input_kat_dsn');
    Route::post('save_nilai_KAT_dsn', 'DosenController@save_nilai_KAT_dsn');
    Route::get('input_uts_dsn/{id}', 'DosenController@input_uts_dsn');
    Route::post('save_nilai_UTS_dsn', 'DosenController@save_nilai_UTS_dsn');
    Route::get('input_uas_dsn/{id}', 'DosenController@input_uas_dsn');
    Route::post('save_nilai_UAS_dsn', 'DosenController@save_nilai_UAS_dsn');
    Route::get('input_akhir_dsn/{id}', 'DosenController@input_akhir_dsn');
    Route::post('save_nilai_AKHIR_dsn', 'DosenController@save_nilai_AKHIR_dsn');

    //histori matakuliah
    Route::get('history_makul_dsn', 'DosenController@history_makul_dsn');
    Route::get('cekmhs_dsn/{id}', 'DosenController@cekmhs_dsn');
    Route::get('cekmhs_dsn_his/{id}', 'DosenController@cekmhs_dsn_his');
    Route::post('export_xlsnilai', 'DosenController@export_xlsnilai');
    Route::get('view_bap_his/{id}', 'DosenController@view_bap_his');
    Route::get('view_history_bap/{id}', 'DosenController@view_history_bap');
    Route::get('sum_absen_his/{id}', 'DosenController@sum_absen_his');
    Route::get('jurnal_bap_his/{id}', 'DosenController@jurnal_bap_his');

    //BAP
    Route::get('entri_bap/{id}', 'DosenController@entri_bap');
    Route::get('input_bap/{id}', 'DosenController@input_bap');
    Route::post('save_bap', 'DosenController@save_bap');
    Route::get('/entri_absen/{id}', 'DosenController@entri_absen');
    Route::post('save_absensi', 'DosenController@save_absensi');
    Route::get('/edit_absen/{id}', 'DosenController@edit_absen');
    Route::post('save_edit_absensi', 'DosenController@save_edit_absensi');
    Route::get('view_bap/{id}', 'DosenController@view_bap');
    Route::get('print_bap/{id}', 'DosenController@cetak');
    Route::get('edit_bap/{id}', 'DosenController@edit_bap');
    Route::put('simpanedit_bap/{id}', 'DosenController@simpanedit_bap');
    Route::get('delete_bap/{id}', 'DosenController@delete_bap');
    Route::get('sum_absen/{id}', 'DosenController@sum_absen');
    Route::get('print_absensi/{id}', 'DosenController@print_absensi');
    Route::get('jurnal_bap/{id}', 'DosenController@jurnal_bap');
    Route::get('print_jurnal/{id}', 'DosenController@print_jurnal');

    //unduh pdf
    Route::post('unduh_pdf_nilai', 'DosenController@unduh_pdf_nilai');
    Route::get('download_absensi/{id}', 'DosenController@download_absensi');
    Route::get('download_jurnal/{id}', 'DosenController@download_jurnal');

    //PraUSTA
    Route::get('pembimbing_pkl', 'DosenController@pembimbing_pkl');
    Route::get('record_bim_pkl/{id}', 'DosenController@record_bim_pkl');
    Route::post('status_judul', 'DosenController@status_judul');
    Route::get('acc_seminar_pkl/{id}', 'DosenController@acc_seminar_pkl');
    Route::get('tolak_seminar_pkl/{id}', 'DosenController@tolak_seminar_pkl');
});

Route::group(['middleware' => 'mhs'], function () {
    Route::get('change_pwd/{id}', 'MhsController@change');
    Route::put('pwd_user/{id}', 'MhsController@store_new_pwd');
    Route::get('update/{id}', 'MhsController@update');
    Route::post('save_update', 'MhsController@store_update');
    Route::get('change/{id}', 'MhsController@change_update');
    Route::put('save_change/{id}', 'MhsController@store_change');
    Route::get('krs', 'MhsController@krs');
    Route::post('submitdata', 'MhsController@testfunction');
    Route::post('add_krs', 'MhsController@add_krs');
    Route::post('input_krs', 'MhsController@input_krs');
    Route::post('save_krs', 'MhsController@save_krs');
    Route::post('post_krs', 'MhsController@post_krs');
    Route::post('simpan_krs', 'MhsController@simpan_krs');
    Route::get('khs_mid', 'MhsController@khs_mid');
    Route::get('khs_final', 'MhsController@khs_final');
    Route::get('unduh_khs_mid', 'MhsController@unduh_khs_mid');
    Route::get('jadwal', 'MhsController@jadwal');
    Route::get('lihatabsen/{id}', 'MhsController@lihatabsen');
    Route::get('view_bap_mhs/{id}', 'MhsController@view_bap');
    Route::get('view_abs/{id}', 'MhsController@view_abs');
    Route::get('keuangan', 'MhsController@uang');
    Route::get('unduh_krs', 'MhsController@pdf_krs');
    Route::get('lihat_semua', 'MhsController@lihat_semua');
    Route::get('lihat/{id}', 'MhsController@lihat');
    Route::get('isi_krs', 'KrsController@isi_krs');
    Route::post('batalkrs', 'KrsController@batalkrs');
    Route::get('isi_edom', 'EdomController@isi_edom');
    Route::post('form_edom', 'EdomController@form_edom');
    Route::post('save_edom', 'EdomController@save_edom');
    Route::post('edom_kom', 'EdomController@edom_kom');
    Route::post('save_edom_kom', 'EdomController@save_com');
    Route::get('ganti_foto/{id}', 'MhsController@ganti_foto');
    Route::put('simpanfoto/{id}', 'MhsController@simpanfoto');
    Route::post('view_nilai', 'NilaiController@view_nilai');
    Route::get('nilai', 'NilaiController@nilai');
    Route::post('unduh_khs_nilai', 'NilaiController@unduh_khs_nilaipdf');
    Route::get('jdl_uts', 'MhsController@jdl_uts');
    Route::get('jdl_uas', 'MhsController@jdl_uas');
    Route::get('pedoman_akademik', 'MhsController@pedoman_akademik');
    Route::get('download/{id}', 'MhsController@download_pedoman');

    //praUSTA
    Route::get('seminar_prakerin', 'PraustaController@seminar_prakerin');
    Route::get('pengajuan_seminar_prakerin', 'PraustaController@pengajuan_seminar_prakerin');
    Route::post('simpan_ajuan_prakerin', 'PraustaController@simpan_ajuan_prakerin');
    Route::put('edit_ajuan_prakerin/{id}', 'PraustaController@edit_ajuan_prakerin');
    Route::get('ajukan_seminar_pkl/{id}', 'PraustaController@ajukan_seminar_pkl');

    //bimbingan prakerin
    Route::post('simpan_bimbingan', 'PraustaController@simpan_bimbingan');
    Route::put('edit_bimbingan/{id}', 'PraustaController@edit_bimbingan');

    //upload draft prakerin
    Route::post('simpan_draft_prakerin', 'PraustaController@simpan_draft_prakerin');

    //update nisn
    Route::put('put_nisn/{id}', 'MhsController@put_nisn');
});

Route::group(['middleware' => 'nomhs'], function () {
    Route::get('pwd/{id}', 'NoMhsController@get_new_user');
    Route::put('pwd/{id}/store', 'NoMhsController@store_new_user');
});

Route::group(['middleware' => 'dosenluar'], function () {
    Route::get('makul_diampu', 'DosenluarController@makul_diampu');
    Route::get('cekmhs/{id}', 'DosenluarController@cekmhs');
    Route::get('history_makul_dsnlr', 'DosenluarController@history_makul_dsn');
    Route::get('cekmhs_dsn_hislr/{id}', 'DosenluarController@cekmhs_dsn_his');
    Route::get('val_ujian', 'DosenluarController@val_ujian');
    Route::get('input_kat/{id}', 'DosenluarController@input_kat');
    Route::post('save_nilai_KAT', 'DosenluarController@save_nilai_KAT');
    Route::get('input_uts/{id}', 'DosenluarController@input_uts');
    Route::post('save_nilai_UTS', 'DosenluarController@save_nilai_UTS');
    Route::get('input_uas/{id}', 'DosenluarController@input_uas');
    Route::post('save_nilai_UAS', 'DosenluarController@save_nilai_UAS');
    Route::get('input_akhir/{id}', 'DosenluarController@input_akhir');
    Route::post('save_nilai_AKHIR', 'DosenluarController@save_nilai_AKHIR');
    Route::get('change_pass_dsn_luar/{id}', 'DosenluarController@change_dsnluar');
    Route::put('pwd_dsn_luar/{id}', 'DosenluarController@store_pwd_dsn_luar');
    Route::post('export_xlsnilai_dsn', 'DosenluarController@export_xlsnilai');
    //BAP
    Route::get('entri_bap_dsn/{id}', 'DosenluarController@entri_bap');
    Route::get('input_bap_dsn/{id}', 'DosenluarController@input_bap');
    Route::post('save_bap_dsn', 'DosenluarController@save_bap');
    Route::get('/entri_absen_dsn/{id}', 'DosenluarController@entri_absen');
    Route::post('save_absensi_dsn', 'DosenluarController@save_absensi');
    Route::get('/edit_absen_dsn/{id}', 'DosenluarController@edit_absen');
    Route::post('save_edit_absensi_dsn', 'DosenluarController@save_edit_absensi');
    Route::get('view_bap_dsn/{id}', 'DosenluarController@view_bap');
    Route::get('print_bap_dsn/{id}', 'DosenluarController@cetak');
    Route::get('edit_bap_dsn/{id}', 'DosenluarController@edit_bap');
    Route::put('simpanedit_bap_dsn/{id}', 'DosenluarController@simpanedit_bap');
    Route::get('delete_bap_dsn/{id}', 'DosenluarController@delete_bap');
    Route::get('sum_absen_dsn/{id}', 'DosenluarController@sum_absen');
    Route::get('print_absensi_dsn/{id}', 'DosenluarController@print_absensi');
    Route::get('jurnal_bap_dsn/{id}', 'DosenluarController@jurnal_bap');
    Route::get('print_jurnal_dsn/{id}', 'DosenluarController@print_jurnal');

    //unduh pdf
    Route::post('unduh_pdf_nilai_dsn', 'DosenluarController@unduh_pdf_nilai');
    Route::get('download_absensi_dsn/{id}', 'DosenluarController@download_absensi');
    Route::get('download_jurnal_dsn/{id}', 'DosenluarController@download_jurnal');

    //histori matakuliah
    Route::get('view_bap_his_dsn/{id}', 'DosenluarController@view_bap_his');
    Route::get('view_history_bap_dsn/{id}', 'DosenluarController@view_history_bap');
    Route::get('sum_absen_his_dsn/{id}', 'DosenluarController@sum_absen_his');
    Route::get('jurnal_bap_his_dsn/{id}', 'DosenluarController@jurnal_bap_his');
});

Route::group(['middleware' => 'kaprodi'], function () {
    Route::get('change_pass_kaprodi/{id}', 'KaprodiController@change_pass_kaprodi');
    Route::put('pwd_kaprodi/{id}', 'KaprodiController@store_pwd_kaprodi');
    Route::get('lihat_semua_kprd', 'KaprodiController@lihat_semua_kprd');
    Route::get('lihat_kprd/{id}', 'KaprodiController@lihat_kprd');

    //master data
    Route::get('mhs_aktif', 'KaprodiController@mhs_aktif');
    Route::get('export_data_mhs', 'KaprodiController@export_data_mhs_aktif');
    Route::post('cari_mhs_aktif', 'KaprodiController@cari_mhs_aktif');
    Route::post('export_data', 'KaprodiController@export_xls_mhs_aktif');

    //data ipk
    Route::get('data_ipk_kprd', 'KaprodiController@data_ipk_kprd');
    Route::post('filter_ipk_mhs', 'KaprodiController@filter_ipk_mhs');
    Route::get('export_nilai_ipk_kprd', 'KaprodiController@export_nilai_ipk_kprd');
    Route::post('export_nilai_ipk_prodi', 'KaprodiController@export_nilai_ipk_prodi');

    //mahasiswa bimbingan
    Route::get('mhs_bim_kprd', 'KaprodiController@mhs_bim');
    Route::get('record_nilai_kprd/{id}', 'KaprodiController@record_nilai');

    //validasi krs
    Route::get('val_krs_kprd', 'KaprodiController@val_krs');
    Route::post('krs_validasi_kprd', 'KaprodiController@krs_validasi');
    Route::get('cek_krs_kprd/{id}', 'KaprodiController@cek_krs');
    Route::post('savekrs_new_kprd', 'KaprodiController@savekrs_new');
    Route::post('hapuskrsmhs_kprd', 'KaprodiController@hapuskrsmhs');

    //matakuliah diampu dosen
    Route::get('makul_diampu_kprd', 'KaprodiController@makul_diampu_dsn');
    Route::get('cekmhs_dsn_kprd/{id}', 'KaprodiController@cekmhs_dsn');
    Route::post('export_xlsnilai_kprd', 'KaprodiController@export_xlsnilai');
    Route::post('unduh_pdf_nilai_kprd', 'KaprodiController@unduh_pdf_nilai');

    //input nilai
    Route::get('input_kat_kprd/{id}', 'KaprodiController@input_kat_kprd');
    Route::post('save_nilai_KAT_kprd', 'KaprodiController@save_nilai_KAT_kprd');
    Route::get('input_uts_kprd/{id}', 'KaprodiController@input_uts_kprd');
    Route::post('save_nilai_UTS_kprd', 'KaprodiController@save_nilai_UTS_kprd');
    Route::get('input_uas_kprd/{id}', 'KaprodiController@input_uas_kprd');
    Route::post('save_nilai_UAS_kprd', 'KaprodiController@save_nilai_UAS_kprd');
    Route::get('input_akhir_kprd/{id}', 'KaprodiController@input_akhir_kprd');
    Route::post('save_nilai_AKHIR_kprd', 'KaprodiController@save_nilai_AKHIR_kprd');

    //BAP
    Route::get('entri_bap_kprd/{id}', 'KaprodiController@entri_bap');
    Route::get('input_bap_kprd/{id}', 'KaprodiController@input_bap');

    Route::get('autocomplete-search', 'KaprodiController@autocompleteSearch');

    Route::get('autocomplete', ['as' => 'autocomplete', 'uses' => 'KaprodiController@autocomplete']);

    Route::get('/cari', 'KaprodiController@loadData');

    Route::post('/autocomplete/fetch', 'KaprodiController@fetch')->name('autocomplete.fetch');

    Route::post('save_bap_kprd', 'KaprodiController@save_bap');
    //entri absen
    Route::get('/entri_absen_kprd/{id}', 'KaprodiController@entri_absen');
    Route::post('save_absensi_kprd', 'KaprodiController@save_absensi');
    Route::get('/edit_absen_kprd/{id}', 'KaprodiController@edit_absen');
    Route::post('save_edit_absensi_kprd', 'KaprodiController@save_edit_absensi');
    //lihat BAP
    Route::get('view_bap_kprd/{id}', 'KaprodiController@view_bap');
    Route::get('print_bap_kprd/{id}', 'KaprodiController@cetak');
    //edit BAP
    Route::get('edit_bap_kprd/{id}', 'KaprodiController@edit_bap');
    Route::put('simpanedit_bap_kprd/{id}', 'KaprodiController@simpanedit_bap');
    //hapus BAP
    Route::get('delete_bap_kprd/{id}', 'KaprodiController@delete_bap');
    //absensi perkuliahan
    Route::get('sum_absen_kprd/{id}', 'KaprodiController@sum_absen');
    Route::get('print_absensi_kprd/{id}', 'KaprodiController@print_absensi');
    //jurnal perkuliahan
    Route::get('jurnal_bap_kprd/{id}', 'KaprodiController@jurnal_bap');
    Route::get('print_jurnal_kprd/{id}', 'KaprodiController@print_jurnal');
    //histori matakuliah
    Route::get('history_makul_kprd', 'KaprodiController@history_makul_dsn');
    Route::get('cekmhs_dsn_his_kprd/{id}', 'KaprodiController@cekmhs_dsn_his');
    Route::get('view_bap_his_kprd/{id}', 'KaprodiController@view_bap_his');
    Route::get('sum_absen_his_kprd/{id}', 'KaprodiController@sum_absen_his');
    Route::get('print_absensi_kprd/{id}', 'KaprodiController@print_absensi');
    Route::get('jurnal_bap_his_kprd/{id}', 'KaprodiController@jurnal_bap_his');

    //nilai mahasiswa
    Route::get('nilai_mhs_kprd', 'KaprodiController@nilai_mhs_kprd');
    Route::get('cek_nilai_mhs_kprd/{id}', 'KaprodiController@cek_nilai_mhs_kprd');

    //master soal
    Route::get('soal_uts_kprd', 'KaprodiController@soal_uts_kprd');
    Route::get('soal_uas_kprd', 'KaprodiController@soal_uas_kprd');

    //cek rekapan perkuliahan
    Route::get('rekap_perkuliahan_kprd', 'KaprodiController@rekap_perkuliahan');
    Route::get('cek_rekapan_kprd/{id}', 'KaprodiController@cek_rekapan');
    Route::get('cek_view_bap_kprd/{id}', 'KaprodiController@cek_view_bap');
    Route::get('cek_print_bap_kprd/{id}', 'KaprodiController@cek_print_bap');
    Route::get('cek_sum_absen_kprd/{id}', 'KaprodiController@cek_sum_absen');
    Route::get('print_absensi_cek_kprd/{id}', 'KaprodiController@cek_print_absensi');
    Route::get('cek_jurnal_bap_kprd/{id}', 'KaprodiController@cek_jurnal_bap_kprd');
    Route::get('print_jurnal_cek_kprd/{id}', 'KaprodiController@print_jurnal_cek_kprd');
});

Route::group(['middleware' => 'adminprodi'], function () {
    Route::get('dospem_pkl', 'ProdiController@dospem_pkl');
    Route::post('view_mhs_bim_pkl', 'ProdiController@view_mhs_bim_pkl');
    Route::post('save_dsn_bim_pkl', 'ProdiController@save_dsn_bim_pkl');
});

Route::group(['middleware' => 'wadir1'], function () {
    Route::get('data_bap', 'Wadir1Controller@data_bap');
    Route::get('cek_jurnal_bap_wadir/{id}', 'Wadir1Controller@cek_jurnal_bap_wadir');
});

Route::group(['middleware' => 'prausta'], function () {
    Route::get('nilai_prausta', 'PraustaController@nilai_prausta');
    Route::post('kode_prausta', 'PraustaController@kode_prausta');
    Route::post('save_nilai_prausta', 'PraustaController@save_nilai_prausta');

    //data prakerin
    Route::get('data_prakerin', 'PraustaController@data_prakerin');
    Route::get('atur_prakerin/{id}', 'PraustaController@atur_prakerin');
    Route::post('simpan_atur_prakerin', 'PraustaController@simpan_atur_prakerin');
});
