<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
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

Route::get('/dashboard', [Controller::class, 'dashboard'])->name('dashboard');
Route::get('/generateCaptcha', [Controller::class, 'generateCaptcha'])->name('generateCaptcha');
Route::get('/logout', [Controller::class, 'logout'])->name('logout');
Route::get('/error404', [Controller::class, 'error404'])->name('error404');


// page
Route::get('/login',  [Controller::class, 'login'])->name('login');
Route::get('/setting', [Controller::class, 'setting'])->name('setting');
Route::get('/register', [Controller::class, 'register'])->name('register');
Route::get('/menus', [Controller::class, 'menus'])->name('menus');
Route::get('/member', [Controller::class, 'member'])->name('member');
Route::get('/voucher', [Controller::class, 'voucher'])->name('voucher');
Route::get('/kasir', [Controller::class, 'kasir'])->name('kasir');
Route::get('/transaksi', [Controller::class, 'transaksi'])->name('transaksi');

// aksi
Route::post('/aksi_login', [Controller::class, 'aksi_login'])->name('aksi_login');
Route::post('/editsetting', [Controller::class, 'editsetting']);
Route::post('/aksi_register', [Controller::class, 'aksiregister'])->name('aksi_register');
Route::post('/check-membership', [Controller::class, 'checkMembership'])->name('checkMembership');
Route::post('/check-voucher', [Controller::class, 'checkVoucher'])->name('checkVoucher');
Route::post('/tambahmenu', [Controller::class, 'tambahmenu'])->name('tambahmenu');
Route::post('/editmenu', [Controller::class, 'editmenu'])->name('editmenu');
Route::post('/hapusmenu/{id}', [Controller::class, 'hapusmenu'])->name('hapusmenu');
Route::post('/tambahmember', [Controller::class, 'tambahmember'])->name('tambahmember');
Route::post('/editmember', [Controller::class, 'editmember'])->name('editmember');
Route::post('/hapusmember/{id}', [Controller::class, 'hapusmember'])->name('hapusmember');
Route::post('/tambahvoucher', [Controller::class, 'tambahvoucher'])->name('tambahvoucher');
Route::post('/editvoucher', [Controller::class, 'editvoucher'])->name('editvoucher');
Route::post('/hapusvoucher/{id}', [Controller::class, 'hapusvoucher'])->name('hapusvoucher');
Route::post('/prosesTransaksi', [Controller::class, 'prosesTransaksi'])->name('prosesTransaksi');
