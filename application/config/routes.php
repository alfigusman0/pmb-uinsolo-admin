<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'dashboard';
$route['404_override'] = 'Pages/Err404';
$route['translate_uri_dashes'] = FALSE;

$route['login-as/(:num)'] = 'Auth/LoginAs/$1';
$route['login-back'] = 'Auth/Notifikasi';
$route['logs'] = 'Logs';

$route['dashboard'] = 'Dashboard';
$route['dashboard/d1'] = 'Dashboard/D1';

// Cronejob
$route['cronejob/cj1'] = 'Cronejob/CJ1';
$route['cronejob/cj2'] = 'Cronejob/CJ2';
$route['cronejob/cj3/(:any)'] = 'Cronejob/CJ3/$1';
$route['cronejob/cj4'] = 'Cronejob/CJ4';
$route['cronejob/cj5'] = 'Cronejob/CJ5';
$route['cronejob/cj6/(:any)'] = 'Cronejob/CJ6/$1';
$route['cronejob/cj7/(:any)'] = 'Cronejob/CJ7/$1';
$route['cronejob/cj8'] = 'Cronejob/CJ8';
$route['cronejob/cj9'] = 'Cronejob/CJ9';
$route['cronejob/cj10/(:any)/(:any)/(:any)/(:any)'] = 'Cronejob/CJ10/$1/$2/$3/$4';
$route['cronejob/cj11'] = 'Cronejob/CJ11';
$route['cronejob/cj12'] = 'Cronejob/CJ12';
$route['cronejob/cj13'] = 'Cronejob/CJ13';
$route['cronejob/cj14'] = 'Cronejob/CJ14';
$route['cronejob/cj15'] = 'Cronejob/CJ15';
$route['cronejob/cj16'] = 'Cronejob/CJ16';
$route['cronejob/cj17/(:any)'] = 'Cronejob/CJ17/$1';
$route['cronejob/cj18/(:any)'] = 'Cronejob/CJ18/$1';
$route['cronejob/cj19'] = 'Cronejob/CJ19';
$route['cronejob/cj20/(:any)'] = 'Cronejob/CJ20/$1';
$route['cronejob/cj21/(:any)'] = 'Cronejob/CJ21/$1';
$route['cronejob/cj22/(:any)/(:any)'] = 'Cronejob/CJ22/$1/$2';

// Daftar
$route['daftar/import/kelulusan'] = 'Daftar/Import/Kelulusan';
$route['daftar/import/kelulusan/import'] = 'Daftar/Import/Kelulusan/Import';
$route['daftar/import/mandiri'] = 'Daftar/Import/Mandiri';
$route['daftar/import/mandiri/import'] = 'Daftar/Import/Mandiri/Import';
$route['daftar/export/kelulusan'] = 'Daftar/Export/Kelulusan';
$route['daftar/export/kelulusan/export'] = 'Daftar/Export/Kelulusan/Export';
$route['daftar/export/kelas'] = 'Daftar/Export/Kelas';
$route['daftar/export/kelas/export'] = 'Daftar/Export/Kelas/Export';
$route['daftar/export/mahasiswa'] = 'Daftar/Export/Mahasiswa';
$route['daftar/export/mahasiswa/export'] = 'Daftar/Export/Mahasiswa/Export';
$route['daftar/export/ukuran-baju'] = 'Daftar/Export/UkuranBaju';
$route['daftar/export/ukuran-baju/export'] = 'Daftar/Export/UkuranBaju/Export';
$route['daftar/mahasiswa'] = 'Daftar/Mahasiswa';
$route['daftar/mahasiswa/detail/(:num)'] = 'Daftar/Mahasiswa/Detail/$1';
$route['daftar/mahasiswa/jsondatatable'] = 'Daftar/Mahasiswa/JsonFormFilter';
$route['daftar/mahasiswa/data-kelulusan/save'] = 'Daftar/Mahasiswa/simpanDataKelulusan';
$route['daftar/mahasiswa/data-diri/save'] = 'Daftar/Mahasiswa/simpanDataDiri';
$route['daftar/mahasiswa/data-sekolah/save'] = 'Daftar/Mahasiswa/simpanDataSekolah';
$route['daftar/mahasiswa/data-rumah/save'] = 'Daftar/Mahasiswa/simpanDataRumah';
$route['daftar/mahasiswa/data-orangtua/save'] = 'Daftar/Mahasiswa/simpanDataOrangtua';
$route['daftar/mahasiswa/data-orangtua/delete'] = 'Daftar/Mahasiswa/HapusDataOrangtua';
$route['daftar/mahasiswa/data-berkas/save'] = 'Daftar/Mahasiswa/simpanDataBerkas';
$route['daftar/mahasiswa/data-foto/save'] = 'Daftar/Mahasiswa/simpanDataFoto';
$route['daftar/mahasiswa/file/get/(:any)'] = 'Daftar/Mahasiswa/GetFile/$1';
$route['daftar/mahasiswa/file/get/(:any)/(:num)'] = 'Daftar/Mahasiswa/GetFile/$1/$2';
$route['daftar/mahasiswa/mahasiswa/get'] = 'Daftar/Mahasiswa/GetMahasiswa';
$route['daftar/mahasiswa/mahasiswa/get/(:num)'] = 'Daftar/Mahasiswa/GetDaftar/Mahasiswa/$1';
$route['daftar/mahasiswa/orangtua-ayah/get'] = 'Daftar/Mahasiswa/GetOrangtuaAyah';
$route['daftar/mahasiswa/orangtua-ayah/get/(:num)'] = 'Daftar/Mahasiswa/GetOrangtuaAyah/$1';
$route['daftar/mahasiswa/orangtua-ibu/get'] = 'Daftar/Mahasiswa/GetOrangtuaIbu';
$route['daftar/mahasiswa/orangtua-ibu/get/(:num)'] = 'Daftar/Mahasiswa/GetOrangtuaIbu/$1';
$route['daftar/mahasiswa/orangtua-wali/get'] = 'Daftar/Mahasiswa/GetOrangtuaWali';
$route['daftar/mahasiswa/orangtua-wali/get/(:num)'] = 'Daftar/Mahasiswa/GetOrangtuaWali/$1';
$route['daftar/mahasiswa/rumah/get'] = 'Daftar/Mahasiswa/GetRumah';
$route['daftar/mahasiswa/rumah/get/(:num)'] = 'Daftar/Mahasiswa/GetRumah/$1';
$route['daftar/mahasiswa/sekolah/get'] = 'Daftar/Mahasiswa/GetSekolah';
$route['daftar/mahasiswa/sekolah/get/(:num)'] = 'Daftar/Mahasiswa/GetSekolah/$1';
$route['daftar/mahasiswa/users/get'] = 'Daftar/Mahasiswa/GetUsers';
$route['daftar/mahasiswa/users/get/(:num)'] = 'Daftar/Mahasiswa/GetUsers/$1';
$route['daftar/mahasiswa/kelulusan/get'] = 'Daftar/Mahasiswa/GetKelulusan';
$route['daftar/mahasiswa/kelulusan/get/(:num)'] = 'Daftar/Mahasiswa/GetKelulusan/$1';
$route['daftar/mahasiswa/kelulusan2/get'] = 'Daftar/Mahasiswa/GetKelulusan2';
$route['daftar/mahasiswa/kelulusan/pindah'] = 'Daftar/Mahasiswa/PindahKelulusan';
$route['daftar/mahasiswa/tipe-file/get/(:any)'] = 'Daftar/Mahasiswa/GetTipeFile/$1';
$route['daftar/mahasiswa/tipe-file/get/(:any)/(:num)'] = 'Daftar/Mahasiswa/GetTipeFile/$1/$2';
$route['daftar/statistik'] = 'Daftar/Statistik';
$route['daftar/pembayaran'] = 'Daftar/Pembayaran';
$route['daftar/pembayaran/jsondatatable'] = 'Daftar/Pembayaran/JsonFormFilter';
$route['daftar/pembayaran/update'] = 'Daftar/Pembayaran/Update';
$route['daftar/pembayaran/get/(:num)'] = 'Daftar/Pembayaran/GetPembayaran/$1';
$route['daftar/pembayaran/pilih-bank'] = 'Daftar/Pembayaran/PilihBank';
$route['daftar/pembayaran/batalkan-bank/(:num)/(:num)'] = 'Daftar/Pembayaran/BatalkanBank/$1/$2';

//notifikasi
$route['notifikasi']['GET'] = 'Notifikasi';
$route['notifikasi/akun']['GET'] = 'Notifikasi/GetAkun';
$route['notifikasi/get']['GET'] = 'Notifikasi/Get';
$route['notifikasi']['POST'] = 'Notifikasi/KirimNotifikasi';
$route['notifikasi/update'] = 'Notifikasi/Update';
$route['notifikasi/(:num)']['DELETE'] = 'Notifikasi/Delete/$1';
$route['notifikasi/jsondatatable'] = 'Notifikasi/JsonFormFilter';

//Mandiri
$route['mandiri/mahasiswa'] = 'Mandiri/Mahasiswa';
$route['mandiri/mahasiswa/detail/(:num)'] = 'Mandiri/Mahasiswa/Detail/$1';
$route['mandiri/mahasiswa/jsondatatable'] = 'Mandiri/Mahasiswa/JsonFormFilter';
$route['mandiri/mahasiswa/formulir/get'] = 'Mandiri/Mahasiswa/GetFormulir';
$route['mandiri/mahasiswa/formulir/get/(:num)'] = 'Mandiri/Mahasiswa/GetFormulir/$1';
$route['mandiri/mahasiswa/data-formulir/save'] = 'Mandiri/Mahasiswa/simpanDataFormulir';
$route['mandiri/kelulusan'] = 'Mandiri/Kelulusan';
$route['mandiri/kelulusan/getKelulusan/(:num)'] = 'Mandiri/Kelulusan/GetKelulusan/$1';
$route['mandiri/kelulusan/getGrade/(:num)'] = 'Mandiri/Kelulusan/GetGrade/$1';
$route['mandiri/kelulusan/jsondatatable'] = 'Mandiri/Kelulusan/JsonFormFilter';
$route['mandiri/pembayaran'] = 'Mandiri/Pembayaran';
$route['mandiri/pembayaran/get'] = 'Mandiri/Pembayaran/GetPembayaran';
$route['mandiri/pembayaran/edit-bank'] = 'Mandiri/Pembayaran/Update';
$route['mandiri/pembayaran/get/(:num)'] = 'Mandiri/Pembayaran/GetPembayaran/$1';
$route['mandiri/pembayaran/jsondatatable'] = 'Mandiri/Pembayaran/JsonFormFilter';
$route['mandiri/pembayaran/pilih-bank'] = 'Mandiri/Pembayaran/PilihBank';
$route['mandiri/pembayaran/batalkan-bank/(:num)/(:num)'] = 'Mandiri/Pembayaran/BatalkanBank/$1/$2';
$route['mandiri/penilaian'] = 'Mandiri/Penilaian';
$route['mandiri/penilaian/get-users'] = 'Mandiri/Penilaian/GetUsers';
$route['mandiri/penilaian/get-formulir'] = 'Mandiri/Penilaian/GetFormulir';
$route['mandiri/penilaian/get-biodata'] = 'Mandiri/Penilaian/GetBiodata';
$route['mandiri/penilaian/get-file'] = 'Mandiri/Penilaian/GetFile';
$route['mandiri/penilaian/get-nilai'] = 'Mandiri/Penilaian/GetNilai';
$route['mandiri/penilaian/get-users2'] = 'Mandiri/Penilaian/GetUsers2';
$route['mandiri/penilaian/get-rekap-nilai'] = 'Mandiri/Penilaian/GetRekapNilai';
$route['mandiri/penilaian/update/studi-naskah/(:num)'] = 'Mandiri/Penilaian/UpdateNilaiStudiNaskah/$1';
$route['mandiri/penilaian/update/proposal/(:num)'] = 'Mandiri/Penilaian/UpdateNilaiProposal/$1';
$route['mandiri/penilaian/update/moderasi/(:num)'] = 'Mandiri/Penilaian/UpdateNilaiModerasi/$1';
$route['mandiri/sanggah'] = 'Mandiri/Sanggah';
$route['mandiri/sanggah/update'] = 'Mandiri/Sanggah/Update';
$route['mandiri/sanggah/get/(:num)'] = 'Mandiri/Sanggah/Get/$1';
$route['mandiri/sanggah/jsondatatable'] = 'Mandiri/Sanggah/JsonFormFilter';
$route['mandiri/sanggah/generate'] = 'Mandiri/Sanggah/Generate';
$route['mandiri/sanggah2'] = 'Mandiri/Sanggah2';
$route['mandiri/sanggah2/jsondatatable'] = 'Mandiri/Sanggah2/JsonFormFilter';
$route['mandiri/statistik'] = 'Mandiri/Statistik';
$route['mandiri/setting'] = 'Mandiri/Setting';
$route['mandiri/setting/add'] = 'Mandiri/Setting/Add';
$route['mandiri/setting/update'] = 'Mandiri/Setting/Update';
$route['mandiri/setting/delete/(:num)'] = 'Mandiri/Setting/Delete/$1';
$route['mandiri/setting/get'] = 'Mandiri/Setting/Get';
$route['mandiri/setting/get/(:num)'] = 'Mandiri/Setting/Get/$1';
$route['mandiri/setting/jsondatatable'] = 'Mandiri/Setting/JsonFormFilter';
$route['mandiri/export/abhp'] = 'Mandiri/Export/ABHP';
$route['mandiri/export/abhp/export'] = 'Mandiri/Export/ABHP/Export';
$route['mandiri/export/abhp/tanggal/get'] = 'Mandiri/Export/ABHP/getTanggal';
$route['mandiri/export/abhp/jam/get'] = 'Mandiri/Export/ABHP/getJam';
$route['mandiri/export/abhp/program/get'] = 'Mandiri/Export/ABHP/getProgram';
$route['mandiri/export/abhp/tipe-ujian/get'] = 'Mandiri/Export/ABHP/getTipeUjian';
$route['mandiri/export/abhp/area/get'] = 'Mandiri/Export/ABHP/getArea';
$route['mandiri/export/abhp/gedung/get'] = 'Mandiri/Export/ABHP/getGedung';
$route['mandiri/export/abhp/ruangan/get'] = 'Mandiri/Export/ABHP/getRuangan';
$route['mandiri/export/berita-acara'] = 'Mandiri/Export/BeritaAcara';
$route['mandiri/export/berita-acara/export'] = 'Mandiri/Export/BeritaAcara/Export';
$route['mandiri/export/berita-acara/tanggal/get'] = 'Mandiri/Export/BeritaAcara/getTanggal';
$route['mandiri/export/berita-acara/jam/get'] = 'Mandiri/Export/BeritaAcara/getJam';
$route['mandiri/export/berita-acara/program/get'] = 'Mandiri/Export/BeritaAcara/getProgram';
$route['mandiri/export/berita-acara/tipe-ujian/get'] = 'Mandiri/Export/BeritaAcara/getTipeUjian';
$route['mandiri/export/berita-acara/area/get'] = 'Mandiri/Export/BeritaAcara/getArea';
$route['mandiri/export/berita-acara/gedung/get'] = 'Mandiri/Export/BeritaAcara/getGedung';
$route['mandiri/export/berita-acara/ruangan/get'] = 'Mandiri/Export/BeritaAcara/getRuangan';
$route['mandiri/export/formulir'] = 'Mandiri/Export/Formulir';
$route['mandiri/export/formulir/export'] = 'Mandiri/Export/Formulir/Export';
$route['mandiri/export/akademik'] = 'Mandiri/Export/Akademik';
$route['mandiri/export/akademik/export'] = 'Mandiri/Export/Akademik/Export';
$route['mandiri/export/wilayah3t'] = 'Mandiri/Export/Wilayah3t';
$route['mandiri/export/wilayah3t/export'] = 'Mandiri/Export/Wilayah3t/Export';
$route['mandiri/export/pembayaran'] = 'Mandiri/Export/Pembayaran';
$route['mandiri/export/pembayaran/export'] = 'Mandiri/Export/Pembayaran/Export';
$route['mandiri/export/kelulusan'] = 'Mandiri/Export/Kelulusan';
$route['mandiri/export/kelulusan/export'] = 'Mandiri/Export/Kelulusan/Export';
$route['mandiri/export/jadwal'] = 'Mandiri/Export/Jadwal';
$route['mandiri/export/jadwal/export'] = 'Mandiri/Export/Jadwal/Export';
$route['mandiri/export/kebutuhan-khusus'] = 'Mandiri/Export/KebutuhanKhusus';
$route['mandiri/export/kebutuhan-khusus/export'] = 'Mandiri/Export/KebutuhanKhusus/Export';
$route['mandiri/export/sanggah'] = 'Mandiri/Export/Sanggah';
$route['mandiri/export/sanggah/export'] = 'Mandiri/Export/Sanggah/Export';
$route['mandiri/export/pilihan'] = 'Mandiri/Export/Pilihan';
$route['mandiri/export/pilihan/export'] = 'Mandiri/Export/Pilihan/Export';

// Kelulusan
$route['kelulusan/import/afirmasi'] = 'Kelulusan/Import/Afirmasi';
$route['kelulusan/import/afirmasi/import'] = 'Kelulusan/Import/Afirmasi/Import';
$route['kelulusan/import/bebas'] = 'Kelulusan/Import/Bebas';
$route['kelulusan/import/bebas/import'] = 'Kelulusan/Import/Bebas/Import';
$route['kelulusan/import/nilai'] = 'Kelulusan/Import/Nilai';
$route['kelulusan/import/nilai/import'] = 'Kelulusan/Import/Nilai/Import';
$route['kelulusan/import/cek-kelulusan'] = 'Kelulusan/Import/CekKelulusan';
$route['kelulusan/import/cek-kelulusan/import'] = 'Kelulusan/Import/CekKelulusan/Import';
$route['kelulusan/import/cek-sanggah'] = 'Kelulusan/Import/CekSanggah';
$route['kelulusan/import/cek-sanggah/import'] = 'Kelulusan/Import/CekSanggah/Import';
$route['kelulusan/import/kelulusan'] = 'Kelulusan/Import/Kelulusan';
$route['kelulusan/import/kelulusan/import'] = 'Kelulusan/Import/Kelulusan/Import';
$route['kelulusan/export/kelulusan'] = 'Kelulusan/Export/Kelulusan';
$route['kelulusan/export/kelulusan/export'] = 'Kelulusan/Export/Kelulusan/Export';
$route['kelulusan/export/biodata'] = 'Kelulusan/Export/Biodata';
$route['kelulusan/export/biodata/export'] = 'Kelulusan/Export/Biodata/Export';
$route['kelulusan/export/pilihan'] = 'Kelulusan/Export/Pilihan';
$route['kelulusan/export/pilihan/export'] = 'Kelulusan/Export/Pilihan/Export';
$route['kelulusan/export/sk'] = 'Kelulusan/Export/SK';
$route['kelulusan/export/sk/export'] = 'Kelulusan/Export/SK/Export';
$route['kelulusan/export/sk_sanggah'] = 'Kelulusan/Export/SKSanggah';
$route['kelulusan/export/sk_sanggah/export'] = 'Kelulusan/Export/SKSanggah/Export';
$route['kelulusan/export/nilai'] = 'Kelulusan/Export/Nilai';
$route['kelulusan/export/nilai/export'] = 'Kelulusan/Export/Nilai/Export';
$route['kelulusan/cbt'] = 'Kelulusan/CBT';
$route['kelulusan/cbt/generate'] = 'Kelulusan/CBT/Generate';
$route['kelulusan/cbt/tanggal/get'] = 'Kelulusan/CBT/GetTanggal';
$route['kelulusan/cbt'] = 'Kelulusan/CBT';
$route['kelulusan/cbt/generate'] = 'Kelulusan/CBT/Generate';
$route['kelulusan/lulus'] = 'Kelulusan/Lulus';
$route['kelulusan/lulus/generate'] = 'Kelulusan/Lulus/prosesLulus';
$route['kelulusan/tidak-lulus'] = 'Kelulusan/Lulus/TidakLulus';
$route['kelulusan/tidak-lulus/generate'] = 'Kelulusan/Lulus/prosesTidakLulus';
$route['kelulusan/reset'] = 'Kelulusan/Lulus/Reset';
$route['kelulusan/reset/generate'] = 'Kelulusan/Lulus/prosesReset';

// Api
// Kelulusan
$route['api/daftar/kelulusan']['GET'] = 'Api/Daftar/Kelulusan/Read/';
$route['api/daftar/kelulusan/(:num)']['GET'] = 'Api/Daftar/Kelulusan/Single/$1';
$route['api/daftar/kelulusan']['POST'] = 'Api/Daftar/Kelulusan/Create/';
$route['api/daftar/kelulusan']['PUT'] = 'Api/Daftar/Kelulusan/Update/';
$route['api/daftar/kelulusan']['DELETE'] = 'Api/Daftar/Kelulusan/Delete/';
$route['api/daftar/salam']['GET'] = 'Api/Daftar/Kelulusan/Full/';
$route['api/daftar/ukt']['GET'] = 'Api/Daftar/Kelulusan/UKT/';

// Daftar - Pembayaran
$route['api/daftar/pembayaran/payment']['POST'] = 'Api/Daftar/Pembayaran/Payment';
$route['api/daftar/pembayaran/reversal']['PUT'] = 'Api/Daftar/Pembayaran/Reversal';
// Mandiri - Pembayaran
$route['api/mandiri/pembayaran/payment']['POST'] = 'Api/Mandiri/Pembayaran/Payment';
$route['api/mandiri/pembayaran/reversal']['PUT'] = 'Api/Mandiri/Pembayaran/Reversal';
// Mandiri - Jadwal
$route['api/mandiri/jadwal']['GET'] = 'Api/Mandiri/Jadwal/Read/';
// Statistik Daftar
$route['api/daftar/statistik/d1']['GET'] = 'Api/Daftar/Statistik/D1';
$route['api/daftar/statistik/d2']['GET'] = 'Api/Daftar/Statistik/D2';
$route['api/daftar/statistik/d3']['GET'] = 'Api/Daftar/Statistik/D3';
$route['api/daftar/statistik/d4']['GET'] = 'Api/Daftar/Statistik/D4';
$route['api/daftar/statistik/d5']['GET'] = 'Api/Daftar/Statistik/D5';
$route['api/daftar/statistik/d6']['GET'] = 'Api/Daftar/Statistik/D6';
$route['api/daftar/statistik/d7']['GET'] = 'Api/Daftar/Statistik/D7';
$route['api/daftar/statistik/d8']['GET'] = 'Api/Daftar/Statistik/D8';
$route['api/daftar/statistik/d9']['GET'] = 'Api/Daftar/Statistik/D9';
$route['api/daftar/statistik/d10']['GET'] = 'Api/Daftar/Statistik/D10';
$route['api/daftar/statistik/d11']['GET'] = 'Api/Daftar/Statistik/D11';
$route['api/daftar/statistik/d12']['GET'] = 'Api/Daftar/Statistik/D12';
// Statistik Mandiri
$route['api/mandiri/statistik/m1']['GET'] = 'Api/Mandiri/Statistik/M1';
$route['api/mandiri/statistik/m2']['GET'] = 'Api/Mandiri/Statistik/M2';
$route['api/mandiri/statistik/m3']['GET'] = 'Api/Mandiri/Statistik/M3';
$route['api/mandiri/statistik/m4']['GET'] = 'Api/Mandiri/Statistik/M4';
$route['api/mandiri/statistik/m5']['GET'] = 'Api/Mandiri/Statistik/M5';
$route['api/mandiri/statistik/m6']['GET'] = 'Api/Mandiri/Statistik/M6';
$route['api/mandiri/statistik/m7']['GET'] = 'Api/Mandiri/Statistik/M7';
$route['api/mandiri/statistik/m8']['GET'] = 'Api/Mandiri/Statistik/M8';
$route['api/mandiri/statistik/m9']['GET'] = 'Api/Mandiri/Statistik/M9';
$route['api/mandiri/statistik/m10']['GET'] = 'Api/Mandiri/Statistik/M10';
$route['api/mandiri/statistik/m11']['GET'] = 'Api/Mandiri/Statistik/M11';
$route['api/mandiri/statistik/m12']['GET'] = 'Api/Mandiri/Statistik/M12';
$route['api/mandiri/statistik/m13']['GET'] = 'Api/Mandiri/Statistik/M13';
$route['api/mandiri/statistik/m14']['GET'] = 'Api/Mandiri/Statistik/M14';
$route['api/mandiri/statistik/m15']['GET'] = 'Api/Mandiri/Statistik/M15';

// UKT
$route['ukt/histori/bobot-nilai-ukt'] = 'UKT/Histori/BobotNilaiUkt';
$route['ukt/histori/bobot-range-ukt'] = 'UKT/Histori/BobotRangeUkt';
$route['ukt/import/penetapan'] = 'UKT/Import/Penetapan';
$route['ukt/import/penetapan/import'] = 'UKT/Import/Penetapan/Import';
$route['ukt/penetapan'] = 'UKT/Penetapan';
$route['ukt/penetapan/swa'] = 'UKT/Penetapan/SWA';
$route['ukt/rekap-jurusan'] = 'UKT/RekapJurusan';
$route['ukt/rekap-jurusan/search/(:any)'] = 'UKT/RekapJurusan/Search/';
$route['ukt/rekap-nilai'] = 'UKT/RekapNilai';
$route['ukt/histori/bobot-nilai'] = 'UKT/Histori/BobotNilaiUkt';
$route['ukt/histori/bobot-nilai/jsondatatable'] = 'UKT/Histori/BobotNilaiUkt/JsonFormSearch';
$route['ukt/histori/bobot-range'] = 'UKT/Histori/BobotRangeUkt';
$route['ukt/histori/bobot-range/jsondatatable'] = 'UKT/Histori/BobotRangeUkt/JsonFormSearch';
$route['ukt/penetapan'] = 'UKT/Penetapan';
$route['ukt/penetapan/swa'] = 'UKT/Penetapan/SWA';
$route['ukt/rekap-jurusan'] = 'UKT/RekapJurusan';
$route['ukt/rekap-jurusan/search/(:num)/(:num)/(:any)'] = 'UKT/RekapJurusan/Search/$1/$2/$3';
$route['ukt/rekap-nilai'] = 'UKT/RekapNilai';

//users
$route['akun/user'] = 'Users';
$route['akun/user/add'] = 'Users/Add';
$route['akun/user/update'] = 'Users/Update';
$route['akun/user/delete/(:num)'] = 'Users/Delete/$1';
$route['akun/user/get'] = 'Users/Get';
$route['akun/user/get/(:num)'] = 'Users/Get/$1';
$route['akun/user/jsondatatable'] = 'Users/JsonFormFilter';

//setting
$route['setting/import/ukt'] = 'Setting/Import/UKT';
$route['setting/import/ukt/import'] = 'Setting/Import/UKT/Import';
$route['setting/jalur-masuk'] = 'Setting/JalurMasuk';
$route['setting/jalur-masuk/add'] = 'Setting/JalurMasuk/Add';
$route['setting/jalur-masuk/update'] = 'Setting/JalurMasuk/Update';
$route['setting/jalur-masuk/delete/(:num)'] = 'Setting/JalurMasuk/Delete/$1';
$route['setting/jalur-masuk/get'] = 'Setting/JalurMasuk/Get';
$route['setting/jalur-masuk/get/(:num)'] = 'Setting/JalurMasuk/Get/$1';
$route['setting/sanggah'] = 'Setting/Sanggah';
$route['setting/sanggah/add'] = 'Setting/Sanggah/Add';
$route['setting/sanggah/update'] = 'Setting/Sanggah/Update';
$route['setting/sanggah/delete/(:num)'] = 'Setting/Sanggah/Delete/$1';
$route['setting/sanggah/get'] = 'Setting/Sanggah/Get';
$route['setting/sanggah/get/(:num)'] = 'Setting/Sanggah/Get/$1';
$route['setting/sanggah/jsondatatable'] = 'Setting/Sanggah/JsonFormFilter';
$route['setting/bobot-jurusan'] = 'Setting/BobotJurusan';
$route['setting/bobot-jurusan/add'] = 'Setting/BobotJurusan/Add';
$route['setting/bobot-jurusan/update'] = 'Setting/BobotJurusan/Update';
$route['setting/bobot-jurusan/delete/(:num)'] = 'Setting/BobotJurusan/Delete/$1';
$route['setting/bobot-jurusan/get'] = 'Setting/BobotJurusan/Get';
$route['setting/bobot-jurusan/get/(:num)'] = 'Setting/BobotJurusan/Get/$1';
$route['setting/bobot-jurusan/jsondatatable'] = 'Setting/BobotJurusan/JsonFormFilter';
$route['setting/jadwal'] = 'Setting/Jadwal';
$route['setting/jadwal/detail/(:num)'] = 'Setting/Jadwal/Detail/$1';
$route['setting/jadwal/add'] = 'Setting/Jadwal/Add';
$route['setting/jadwal/update'] = 'Setting/Jadwal/Update';
$route['setting/jadwal/delete/(:num)'] = 'Setting/Jadwal/Delete/$1';
$route['setting/jadwal/get'] = 'Setting/Jadwal/Get';
$route['setting/jadwal/get/(:num)'] = 'Setting/Jadwal/Get/$1';
$route['setting/jadwal/get2'] = 'Setting/Jadwal/Get2';
$route['setting/jadwal/jsondatatable'] = 'Setting/Jadwal/JsonFormFilter';
$route['setting/import/kelas'] = 'Setting/Import/Kelas';
$route['setting/import/kelas/import'] = 'Setting/Import/Kelas/Import';
$route['setting/import/daya-tampung'] = 'Setting/Import/DayaTampung';
$route['setting/import/daya-tampung/import'] = 'Setting/Import/DayaTampung/Import';
$route['setting/import/pengurangan-kuota'] = 'Setting/Import/PenguranganKuota';
$route['setting/import/pengurangan-kuota/import'] = 'Setting/Import/PenguranganKuota/Import';
$route['setting/daya-tampung'] = 'Setting/DayaTampung';
$route['setting/daya-tampung/add'] = 'Setting/DayaTampung/Add';
$route['setting/daya-tampung/update'] = 'Setting/DayaTampung/Update';
$route['setting/daya-tampung/delete/(:num)'] = 'Setting/DayaTampung/Delete/$1';
$route['setting/daya-tampung/get'] = 'Setting/DayaTampung/Get';
$route['setting/daya-tampung/get/(:num)'] = 'Setting/DayaTampung/Get/$1';
$route['setting/daya-tampung/jsondatatable'] = 'Setting/DayaTampung/JsonFormFilter';
$route['setting/daya-tampung/generate'] = 'Setting/DayaTampung/Generate';
$route['setting/daya-tampung/tambah-daya-tampung'] = 'Setting/DayaTampung/TambahDayaTampung';
$route['setting/sub-daya-tampung'] = 'Setting/SubDayaTampung';
$route['setting/import/sub-daya-tampung'] = 'Setting/Import/SubDayaTampung';
$route['setting/import/sub-daya-tampung/import'] = 'Setting/Import/SubDayaTampung/Import';
$route['setting/sub-daya-tampung/add'] = 'Setting/SubDayaTampung/Add';
$route['setting/sub-daya-tampung/update'] = 'Setting/SubDayaTampung/Update';
$route['setting/sub-daya-tampung/delete/(:num)'] = 'Setting/SubDayaTampung/Delete/$1';
$route['setting/sub-daya-tampung/get'] = 'Setting/SubDayaTampung/Get';
$route['setting/sub-daya-tampung/get/(:num)'] = 'Setting/SubDayaTampung/Get/$1';
$route['setting/sub-daya-tampung/jsondatatable'] = 'Setting/SubDayaTampung/JsonFormFilter';
$route['setting/tipe-file'] = 'Setting/TipeFile';
$route['setting/tipe-file/add'] = 'Setting/TipeFile/Add';
$route['setting/tipe-file/update'] = 'Setting/TipeFile/Update';
$route['setting/tipe-file/delete/(:num)'] = 'Setting/TipeFile/Delete/$1';
$route['setting/tipe-file/get'] = 'Setting/TipeFile/Get';
$route['setting/tipe-file/get/(:num)'] = 'Setting/TipeFile/Get/$1';
$route['setting/tipe-file/jsondatatable'] = 'Setting/TipeFile/JsonFormFilter';
$route['setting/tipe-ujian'] = 'Setting/TipeUjian';
$route['setting/tipe-ujian/add'] = 'Setting/TipeUjian/Add';
$route['setting/tipe-ujian/update'] = 'Setting/TipeUjian/Update';
$route['setting/tipe-ujian/delete/(:num)'] = 'Setting/TipeUjian/Delete/$1';
$route['setting/tipe-ujian/get'] = 'Setting/TipeUjian/Get';
$route['setting/tipe-ujian/get/(:num)'] = 'Setting/TipeUjian/Get/$1';
$route['setting/tipe-ujian/jsondatatable'] = 'Setting/TipeUjian/JsonFormFilter';
$route['setting/program'] = 'Setting/Program';
$route['setting/program/add'] = 'Setting/Program/Add';
$route['setting/program/update'] = 'Setting/Program/Update';
$route['setting/program/delete/(:num)'] = 'Setting/Program/Delete/$1';
$route['setting/program/get'] = 'Setting/Program/Get';
$route['setting/program/get/(:num)'] = 'Setting/Program/Get/$1';
$route['setting/program/jsondatatable'] = 'Setting/Program/JsonFormFilter';
$route['setting/slider'] = 'Setting/Slider';
$route['setting/slider/add'] = 'Setting/Slider/Add';
$route['setting/slider/update'] = 'Setting/Slider/Update';
$route['setting/slider/delete/(:num)'] = 'Setting/Slider/Delete/$1';
$route['setting/slider/get'] = 'Setting/Slider/Get';
$route['setting/slider/get/(:num)'] = 'Setting/Slider/Get/$1';
$route['setting/slider/jsondatatable'] = 'Setting/Slider/JsonFormFilter';
$route['setting/bobot-range'] = 'Setting/BobotRangeUkt';
$route['setting/bobot-range/add'] = 'Setting/BobotRangeUkt/Add';
$route['setting/bobot-range/update'] = 'Setting/BobotRangeUkt/Update';
$route['setting/bobot-range/delete/(:num)'] = 'Setting/BobotRangeUkt/Delete/$1';
$route['setting/bobot-range/get'] = 'Setting/BobotRangeUkt/Get';
$route['setting/bobot-range/get/(:num)'] = 'Setting/BobotRangeUkt/Get/$1';
$route['setting/bobot-range/jsondatatable'] = 'Setting/BobotRangeUkt/JsonFormFilter';
$route['setting/bobot-range/generate'] = 'Setting/BobotRangeUkt/Generate';
$route['setting/bobot-range/simpan'] = 'Setting/BobotRangeUkt/Simpan';
$route['setting/bobot-nilai'] = 'Setting/BobotNilaiUkt';
$route['setting/bobot-nilai/add'] = 'Setting/BobotNilaiUkt/Add';
$route['setting/bobot-nilai/update'] = 'Setting/BobotNilaiUkt/Update';
$route['setting/bobot-nilai/delete/(:num)'] = 'Setting/BobotNilaiUkt/Delete/$1';
$route['setting/bobot-nilai/get'] = 'Setting/BobotNilaiUkt/Get';
$route['setting/bobot-nilai/get/(:num)'] = 'Setting/BobotNilaiUkt/Get/$1';
$route['setting/bobot-nilai/jsondatatable'] = 'Setting/BobotNilaiUkt/JsonFormFilter';
$route['setting/bobot-nilai/generate'] = 'Setting/BobotNilaiUkt/Generate';
$route['setting/bobot-nilai/simpan'] = 'Setting/BobotNilaiUkt/Simpan';
$route['setting/export/users'] = 'Setting/Export/Users';
$route['setting/export/users/export'] = 'Setting/Export/Users/Export';
$route['setting/export/daya-tampung'] = 'Setting/Export/DayaTampung';
$route['setting/export/daya-tampung/export'] = 'Setting/Export/DayaTampung/Export';
$route['setting/export/jurusan'] = 'Setting/Export/Jurusan';
$route['setting/export/jurusan/export'] = 'Setting/Export/Jurusan/Export';
