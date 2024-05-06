<?php

use App\Http\Controllers\Admin\LaporanController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false]);
Route::get('logout', 'Auth\LoginController@logout');


Route::middleware(['guest'])->group(function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login');
});

Route::middleware(['admin', 'auth'])->group(function () {

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/aktivitas', 'HomeController@logIndex')->name('log');

    //biaya
    Route::get('/biaya', 'Admin\akunBiayaController@show')->name('akun-biaya');
    Route::get('/add/biaya', 'Admin\akunBiayaController@create')->name('tambah-akunB');
    Route::post('/create/biaya', 'Admin\akunBiayaController@insert')->name('insert-akunB');
    Route::delete('/detelet/biaya/{id_akb}', 'Admin\akunBiayaController@hapusAkb')->name('hapus-akunb');
    Route::get('/fetch-kontak-data', 'Admin\akunBiayaController@select2kontak');
    Route::get('/edit/Biaya/{id_akb}', 'Admin\akunBiayaController@editKosong')->name('edit-biaya');
    Route::post('/update/biaya/{id_akb}', 'Admin\akunBiayaController@updateKosong')->name('update-biaya');


    //kas dan bank
    Route::get('/kasbank', 'Admin\kasBankController@index')->name('kasbank');
    Route::get('/create/kasbank', 'Admin\kasBankController@create')->name('create.kasbank');
    Route::post('/add/kasbank', 'Admin\kasBankController@store')->name('add-kasbank');
    Route::get('/edit/kasbank/{id_kas}', 'Admin\kasBankController@edit')->name('edit.kasbank');
    Route::post('/update/kasbank/{id_kas}', 'Admin\kasBankController@update')->name('update-kasbank');
    Route::delete('/delete/kasbank/{id_kas}', 'Admin\kasBankController@destroy')->name('delete-kas');

    //detail kas dan bank
    Route::get('/detailkasbank/{id}', 'Admin\kasBankController@detkasbank')->name('detailkasbank');

    //user
    Route::resource('/users', 'Admin\UserController');
    Route::get('/users/data/json', 'Admin\UserController@getData')->name('users.data');

    //kontak
    Route::get('/kontak', 'Admin\kontakController@index')->name('users.kontak');
    Route::get('/add/kontak', 'Admin\kontakController@create')->name('kontak-tambah');
    Route::post('/kontak/store', 'Admin\kontakController@store')->name('kontak.store');
    Route::get('/edit/kontak/{id}', 'Admin\kontakController@edit')->name('kontak-edit');
    Route::post('/update/kontak/{id}', 'Admin\kontakController@update')->name('kontak-update');
    Route::delete('/delete/kontak/{id}', 'Admin\kontakController@destroy')->name('kontak-delete');

    //kategoribiaya
    Route::get('/kategori-biaya', 'Admin\kategoribiayaController@index')->name('kategori-biaya');
    Route::get('/kategori-edit/{id}', 'Admin\kategoribiayaController@edit')->name('kategori-edit');
    Route::get('/kategori-create', 'Admin\kategoribiayaController@create')->name('kategori-create');
    Route::post('/tambah/kategori', 'Admin\kategoribiayaController@store')->name('tambah-kategori');
    Route::post('/update/kategori/{id}', 'Admin\kategoribiayaController@update')->name('update-kategori');
    Route::delete('/delete/kategori{id}', 'Admin\kategoribiayaController@destroy')->name('delete-kategori');
    Route::get('/kategori-select-2', 'Admin\akunBiayaController@select2kategori');


    //transaksi pengeluaran
    Route::get('/transaksi', 'Admin\TransaksiController@view')->name('transaksi.index');
    Route::get('/create/transaksi', 'Admin\TransaksiController@create')->name('transaksi.create');
    Route::post('/add/transaksi', 'Admin\TransaksiController@insert')->name('transaksi.add');
    Route::get('/edit/transaksi/{id}', 'Admin\TransaksiController@edit')->name('transaksi.edit');
    Route::post('/update/transaksi/{id}', 'Admin\TransaksiController@update')->name('transaksi.update');
    Route::delete('/delete/{id}', 'Admin\TransaksiController@deleteDoc')->name('delete.doc');
    Route::get('/peng-produk-select-2', 'Admin\TransaksiController@select2pengproduk');
    Route::get('/peng-kontak-select-2', 'Admin\TransaksiController@select2pengkontak');
    Route::get('/harga-jual-produk/{id}', 'Admin\TransaksiController@hargaJProduk');
    Route::get('/faktur/pembelian/{id_trans}', 'Admin\TransaksiController@unduhPengeluaran')->name('data.pengeluaran');
    Route::get('/invoice/pembelian/{id}', 'Admin\TransaksiController@unduhInvoice')->name('invoice.transaksi');
    Route::delete('/delete/transaksi/{id}', 'Admin\TransaksiController@hapustransaksi')->name('transaksi.delete');

    //transaksi pemasukan
    Route::get('/pemasukan', 'Admin\pemasukanController@view')->name('pemasukan.index');
    Route::get('/create/pemasukan', 'Admin\pemasukanController@create')->name('pemasukan.create');
    Route::post('/add/pemasukan', 'Admin\pemasukanController@insert')->name('pemasukan.add');
    Route::get('/edit/pemasukan/{id}', 'Admin\pemasukanController@edit')->name('pemasukan.edit');
    Route::post('/update/pemasukan/{id}', 'Admin\pemasukanController@update')->name('pemasukan.update');
    Route::delete('/delete/{id}', 'Admin\pemasukanController@deleteDoc')->name('delete.doc');
    Route::get('/faktur/penjualan/{id_trans}', 'Admin\pemasukanController@unduhPemasukan')->name('data.pemasukan');
    Route::get('/invoice/penjualan/{id}', 'Admin\pemasukanController@unduhInvoicePem')->name('invoice.pemasukan');
    Route::delete('/delete/pemasukan/{id}', 'Admin\pemasukanController@hapustransaksipemasukan')->name('pemasukan.delete');


    //laporan
    Route::get('/laporan', 'Admin\LaporanController@index')->name('laporan.index');
    Route::get('/unduh/pdf/pengeluaran', 'Admin\LaporanController@laporanPeng')->name('unduh.pengeluaran');
    Route::get('/unduh/pdf/pemasukan', 'Admin\LaporanController@laporanPem')->name('unduh.pemasukan');
    Route::get('/unduh/pdf/biaya', 'Admin\LaporanController@laporanBiaya')->name('unduh.biaya');


    //produk
    Route::get('/produk', 'Admin\produkController@index')->name('produk');
    Route::get('/create/produk', 'Admin\produkController@create')->name('produk-tambah');
    Route::post('/add/produk', 'Admin\produkController@store')->name('produk-add');
    Route::get('/edit/produk/{id}', 'Admin\produkController@edit')->name('produk-edit');
    Route::post('/update/produk/{id}', 'Admin\produkController@update')->name('produk-update');
    Route::delete('/delete/{id}', 'Admin\produkController@destroy')->name('delete-produk');
    Route::get('/kat-produk-select-2', 'Admin\produkController@select2katprod');

    //produk kategori
    Route::get('/kategori-produk', 'Admin\kategoriProdukController@index')->name('produk-kategori');
    Route::get('/kategori/produk/tambah', 'Admin\kategoriProdukController@create')->name('produk-kategori-tambah');
    Route::post('/kategori/produk/store', 'Admin\kategoriProdukController@store')->name('produk-kategori-store');
    Route::get('/kategori/produk/edit/{id}', 'Admin\kategoriProdukController@edit')->name('produk-kategori-edit');
    Route::post('/kategori/produk/update/{id}', 'Admin\kategoriProdukController@update')->name('produk-kategori-update');
    Route::delete('/kategori/produk/delete/{id}', 'Admin\KategoriProdukController@destroy')->name('produk-kategori-delete');

    //backup data
    Route::get('/backupDB', [LaporanController::class, 'backupDBS']);
    Route::post('/restoreDatabase', [LaporanController::class, 'restoreDB']);

    //jurnal
    Route::get('/jurnal', 'Admin\jurnalController@index')->name('jurnal');
    Route::get('/pdf/jurnal', 'Admin\LaporanController@laporanJun')->name('pdf.jurnal');

    //mutasi
    Route::get('/mutasi', 'Admin\mutasiController@index')->name('mutasi-view');
    Route::get('/mutasi/tambah', 'Admin\mutasiController@create')->name('mutasi-tambah');
    Route::post('/mutasi/add', 'Admin\mutasiController@store')->name('mutasi-add');
    Route::delete('/mutasi/delete/{id}', 'Admin\mutasiController@destroy')->name('mutasi-delete');
    Route::get('/nama-kasbank-select-2', 'Admin\mutasiController@select2namaKB');
    Route::get('/get-saldo-akun/{id}', 'Admin\mutasiController@getSaldoAkun');

    //setting
    Route::get('/setting', 'Admin\settingController@index')->name('setting-index');
    Route::get('/setting/tambah', 'Admin\settingController@create')->name('setting-tambah');
    Route::post('/setting/add', 'Admin\settingController@store')->name('setting-add');
});
