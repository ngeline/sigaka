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

$route['dashboard'] = 'DashboardController';

// $route['jabatan'] = 'JabatanController';
// $route['jabatan/tambah'] = 'JabatanController/tambah';
// $route['jabatan/updateForm/(:any)'] = 'JabatanController/updateForm/$1';
// $route['jabatan/update'] = 'JabatanController/update';
// $route['jabatan/hapus/(:any)'] = 'JabatanController/hapus/$1';

$route['login'] = 'AuthController/login';
$route['logout'] = 'AuthController/logout';
$route['pengguna/ubah/password'] = 'AuthController/ubahPassword';

$route['default_controller'] = 'Welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// ADDITIONAL BY NGELINE
$route['potongan_gaji'] = 'PotonganGajiController';
$route['potongan_gaji/store'] = 'PotonganGajiController/store';
$route['potongan_gaji/edit/(:any)'] = 'PotonganGajiController/edit/$1';
$route['potongan_gaji/update'] = 'PotonganGajiController/update';
$route['potongan_gaji/delete/(:any)'] = 'PotonganGajiController/delete/$1';

$route['karyawan'] = 'KaryawanController';
$route['karyawan/store'] = 'KaryawanController/store';
$route['karyawan/show/(:any)'] = 'KaryawanController/show/$1';
$route['karyawan/update'] = 'KaryawanController/update';
$route['karyawan/delete/(:any)/(:any)'] = 'KaryawanController/delete/$1/$2';
$route['karyawan/ajaxIndex'] = 'KaryawanController/ajaxIndex';

$route['absensi'] = 'AbsensiController';
$route['absensi/edit/(:any)/(:any)'] = 'AbsensiController/edit/$1/$2';
$route['absensi/update'] = 'AbsensiController/update';

$route['pinjaman'] = 'PinjamanController';
$route['pinjaman/store'] = 'PinjamanController/store';
$route['pinjaman/edit/(:any)'] = 'PinjamanController/edit/$1';
$route['pinjaman/update'] = 'PinjamanController/update';
$route['pinjaman/validasi'] = 'PinjamanController/validasi';

$route['tabungan'] = 'TabunganController';
$route['tabungan/show/(:any)'] = 'TabunganController/show/$1';

$route['storting'] = 'StortingController';
$route['storting/detail/(:any)'] = 'StortingController/detail/$1';
$route['storting/kemacetan-store'] = 'StortingController/kemacetanStore';
$route['storting/store'] = 'StortingController/store';
$route['storting/edit/(:any)'] = 'StortingController/edit/$1';
$route['storting/update'] = 'StortingController/update';
$route['storting/delete/(:any)'] = 'StortingController/delete/$1';
$route['storting/riwayat'] = 'StortingController/indexRiwayat';
$route['storting/riwayat/update'] = 'StortingController/updateRiwayat';
$route['storting/riwayat/update/semua'] = 'StortingController/updateRiwayatSemua';
$route['storting/riwayat/update/status/validasi'] = 'StortingController/updateRiwayatStatusValidasi';
$route['storting/riwayat/update/status/pending'] = 'StortingController/updateRiwayatStatusPending';

$route['gaji'] = 'GajiController';
$route['gaji/hitung/(:any)/(:any)/(:any)'] = 'GajiController/hitung/$1/$2/$3';
$route['gaji/store'] = 'GajiController/store_hitung';
$route['gaji/edit/(:any)/(:any)'] = 'GajiController/edit/$1/$2';
$route['gaji/update'] = 'GajiController/update';
$route['gaji/slip/(:any)/(:any)'] = 'GajiController/slip/$1/$2';

$route['laporan'] = 'LaporanController';
$route['laporan/cetak/(:any)'] = 'LaporanController/cetak/$1';
