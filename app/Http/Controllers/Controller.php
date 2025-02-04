<?php

namespace App\Http\Controllers;

use App\Models\resto;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function dashboard()
    {
        $model = new resto();

        $id_level = session()->get('id_level');
        if (!$id_level) {
            return redirect()->route('login');
        }
        $data['darren2'] = $model->getWhere('setting', ['id_setting' => 1]);
 
        echo view('header', $data);
        echo view('menu', $data);
        echo view('dashboard', $data);
        echo view('footer');
    }

    public function login()
    {
        $model = new resto();
        $data['darren2'] = $model->getWhere('setting', ['id_setting' => 1]);
        echo view('header', $data);
        echo view('login', $data);
        echo view('footer');
    }

    public function aksi_login(Request $request)
    {
        // Mengakses input dari request
        $name = $request->input('username');
        $pw = $request->input('password');
        $captchaResponse = $request->input('g-recaptcha-response');
        $backupCaptcha = $request->input('backup_captcha');
        
        // Secret key untuk Google reCAPTCHA
        $secretKey = '6LdFhCAqAAAAAM1ktawzN-e2ebDnMnUQgne7cy53'; 
        $recaptchaSuccess = false;
        
        // Membuat instance model
        $model = new resto(); 
        
        // Cek koneksi internet dari sisi server
        if ($this->isInternetAvailable()) {
            // Server terhubung ke internet, gunakan Google reCAPTCHA
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captchaResponse");
            $responseKeys = json_decode($response, true);
            $recaptchaSuccess = $responseKeys["success"];
        }
        
        // Jika reCAPTCHA Google berhasil diverifikasi
        if ($recaptchaSuccess) {
            // Dapatkan pengguna berdasarkan username
            $user = $model->getWhere('user', ['username' => $name]);
            
            if ($user && $user->password === $pw) { // Verifikasi password tanpa hash
                // Set session
                session()->put('username', $user->username);
                session()->put('id_user', $user->id_user);
                session()->put('id_level', $user->id_level);
    
                return redirect()->to('dashboard');
            } else {
                return redirect()->to('login')->with('error', 'Invalid username or password.');
            }
        } else {
            $storedCaptcha = session()->get('captcha_code'); 
            
            if ($storedCaptcha !== null) {
                // Verifikasi backup CAPTCHA (offline)
                if ($storedCaptcha === $backupCaptcha) {
                    // CAPTCHA valid, lanjutkan login
                    $user = $model->getWhere('user', ['username' => $name]);
    
                    if ($user && $user->password === $pw) { // Verifikasi password tanpa hash
                        // Set session
                        session()->put('username', $user->username);
                        session()->put('id_user', $user->id_user);
                        session()->put('id_level', $user->id_level);
    
                        return redirect()->to('dashboard');
                    } else {
                        return redirect()->to('login')->with('error', 'Invalid username or password.');
                    }
                } else {
                    // CAPTCHA tidak valid
                    return redirect()->to('login')->with('error', 'Invalid CAPTCHA.');
                }
            } else {
                return redirect()->to('login')->with('error', 'CAPTCHA session is not set.');
            }
        }
    }
    
    private function isInternetAvailable()
    {

        $connected = @fsockopen("www.google.com", 80); 
        if ($connected){
            fclose($connected);
            return true;
        }
        return false;
    }
    

    public function generateCaptcha()
    {
        $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
        session()->put('captcha_code', $code);
    
        $image = imagecreatetruecolor(120, 40);
        $bgColor = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 0, 0, 0);
    
        imagefilledrectangle($image, 0, 0, 120, 40, $bgColor);
        imagestring($image, 5, 10, 10, $code, $textColor);
    
        ob_start();
        imagepng($image);
        $imageData = ob_get_contents();
        ob_end_clean();
    
        imagedestroy($image);
    
        return response($imageData)
                    ->header('Content-Type', 'image/png'); 
    }

    public function logout()
    {
        $model = new resto();
        $id_user = session()->get('id_user');
    

        session()->flush();
        return redirect()->route('login'); 
    }

    public function register()
    {
        $model = new resto();
        $data['darren2'] = $model->getWhere('setting', ['id_setting' => 1]);
        echo view('header', $data);
        echo view('register', $data);
        echo view('footer');
    }

    public function aksiregister(Request $request)
    {
        $model = new resto();
    
        $username = $request->input('username');
        $email = $request->input('email');
        $nohp = $request->input('nohp');
        $password = $request->input('password');
        $confirmPassword = $request->input('confirm_password');
    
        // Validasi konfirmasi password
        if ($password !== $confirmPassword) {
            return redirect()->back()->withErrors(['confirm_password' => 'Password dan konfirmasi password harus sama']);
        }
    
        $data = [
            'username' => $username,
            'email' => $email,
            'nohp' => $nohp,
            'password' => $password,
            'id_level' => 3 // Default level untuk user baru
        ];
    
        // Simpan data ke database
        $model->tambah('user', $data);
        return redirect('login')->with('success', 'Registrasi berhasil, silakan login');
    }

    public function menus()
    {
        $model = new resto();

        $id_level = session()->get('id_level');
        if (!$id_level) {
            return redirect()->route('login');
        }

        $data['darren2'] = $model->getWhere('setting', ['id_setting' => 1]);
        $data['menus'] = $model->tampil('menu');
        echo view('header', $data);
        echo view('menu', $data);
        echo view('menus', $data);
        echo view('footer');
    }

    public function member()
    {
        $model = new resto();

        $id_level = session()->get('id_level');
        if (!$id_level) {
            return redirect()->route('login');
        }

        $data['darren2'] = $model->getWhere('setting', ['id_setting' => 1]);
        $data['members'] = $model->tampil('member');
        echo view('header', $data);
        echo view('menu', $data);
        echo view('member', $data);
        echo view('footer');
    }

    public function tambahmember(Request $request)
    {
        $model = new resto(); 
        $kode = 'MEM-' . mt_rand(10000000, 99999999); 
        
        $nama = $request->input('nama');
        $nohp = $request->input('nohp');
        $email = $request->input('email');
        $exp = $request->input('exp'); 

        $data = [
            'kode' => $kode, 
            'nama' => $nama,
            'nohp' => $nohp,
            'email' => $email,
            'exp' => $exp,
        ];
    
        $model->tambah('member', $data);
        return redirect('member')->with('success', 'Member berhasil ditambah');
    }
    
    public function editmember(Request $request)
{
    $model = new resto(); // Memanggil model resto untuk akses database
    
    // Ambil data dari form yang dikirim melalui modal
    $id_member = $request->input('id_member');
    $nama = $request->input('nama');
    $nohp = $request->input('nohp');
    $email = $request->input('email');
    $exp = $request->input('exp');
    
    // Siapkan data untuk update
    $data = [
        'nama' => $nama,
        'nohp' => $nohp,
        'email' => $email,
        'exp' => $exp,
    ];
    
    $model->edit('member', ['id_member' => $id_member], $data);
    return redirect()->route('member')->with('success', 'Member berhasil diperbarui');
}

public function hapusmember($id_member)
{
    $model = new resto(); // Memanggil model resto untuk akses database
    $model->hapus('member', ['id_member' => $id_member]); // Hapus data member berdasarkan id
    return redirect()->route('member')->with('success', 'Member berhasil dihapus');
}


    public function voucher()
    {
        $model = new resto();

        $id_level = session()->get('id_level');
        if (!$id_level) {
            return redirect()->route('login');
        }

        $data['darren2'] = $model->getWhere('setting', ['id_setting' => 1]);
        $data['vouchers'] = $model->tampil('voucher');
        echo view('header', $data);
        echo view('menu', $data);
        echo view('voucher', $data);
        echo view('footer');
    }

    public function tambahvoucher(Request $request)
    {
        $model = new resto();

        $kode_voucher = $request->input('kode_voucher');
        $diskon = $request->input('discount');
        $exp_voucher = $request->input('exp_voucher');

        // Siapkan data yang akan disimpan
        $data = [
            'kode_voucher' => $kode_voucher,
            'discount' => $diskon,
            'exp_voucher' => $exp_voucher,
        ];

        $model->tambah('voucher', $data);
        return redirect()->route('voucher')->with('success', 'Voucher berhasil ditambah');
    }

    // Fungsi untuk mengedit voucher
    public function editvoucher(Request $request)
    {
        $model = new resto();
        $id_voucher = $request->input('id_voucher');
        $kode_voucher = $request->input('kode_voucher');
        $diskon = $request->input('discount');
        $exp_voucher = $request->input('exp_voucher');

        // Siapkan data untuk update
        $data = [
            'kode_voucher' => $kode_voucher,
            'discount' => $diskon,
            'exp_voucher' => $exp_voucher,
        ];

        $model->edit('voucher', ['id_voucher' => $id_voucher], $data);
        return redirect()->route('voucher')->with('success', 'Voucher berhasil diperbarui');
    }

    // Fungsi untuk menghapus voucher
    public function hapusvoucher($id_voucher)
    {
        $model = new resto();
    $model->hapus('voucher', ['id_voucher' => $id_voucher]); 
    return redirect()->route('voucher')->with('success', 'Voucher berhasil dihapus');
    }

    public function kasir()
    {
        $model = new resto();

        $id_level = session()->get('id_level');
        if (!$id_level) {
            return redirect()->route('login');
        }

        $data['darren2'] = $model->getWhere('setting', ['id_setting' => 1]);
        $data['menus'] = $model->tampilmenu('menu');
        echo view('header', $data);
        echo view('menu', $data);
        echo view('kasir', $data);
        echo view('footer');
    }

    public function checkMembership(Request $request)
{
    $member = DB::table('member')->where('kode', $request->kode)->first();
    if ($member) {
        return response()->json(['valid' => true]);
    }
    return response()->json(['valid' => false]);
}

public function checkVoucher(Request $request)
{
    $voucher = DB::table('voucher')
        ->where('kode_voucher', $request->kode_voucher)
        ->where('exp_voucher', '>=', now())
        ->first();

    if ($voucher) {
        return response()->json(['valid' => true, 'discount' => $voucher->discount]);
    }
    return response()->json(['valid' => false]);
}


    public function tambahmenu(Request $request)
    {
        $model = new resto();
    
        $nama = $request->input('nama_menu');
        $kat = $request->input('kategori');
        $des = $request->input('deskripsi');
        $harga = $request->input('harga');
        $foto = $request->file('foto');
        $fotoName = time() . '_' . $foto->getClientOriginalName();
        $fotoPath = public_path('menu');
        $foto->move($fotoPath, $fotoName);

        $data = [
            'nama_menu' => $nama,
            'kategori' => $kat,
            'deskripsi' => $des,
            'harga' => $harga,
            'foto' => $fotoName,
        ];
    
        // Simpan data ke database
        $model->tambah('menu', $data);
        return redirect('menus')->with('success', 'Lowongan berhasil ditambah');
    }

    public function editmenu(Request $request)
    {
        $model = new resto();  // Memanggil model resto untuk akses database
    
        // Ambil data dari form yang dikirim melalui modal
        $id_menu = $request->input('id_menu');
        $nama = $request->input('nama_menu');
        $kategori = $request->input('kategori');
        $deskripsi = $request->input('deskripsi');
        $harga = $request->input('harga');
        $foto = $request->file('foto');
    
        // Siapkan array data untuk update
        $data = [
            'nama_menu' => $nama,
            'kategori' => $kategori,
            'deskripsi' => $deskripsi,
            'harga' => $harga,
        ];
    
        // Cek apakah ada foto baru yang diupload
        if ($foto) {
            // Menyimpan foto baru
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = public_path('menu');
            $foto->move($fotoPath, $fotoName);
    
            // Tambahkan nama foto baru ke array data
            $data['foto'] = $fotoName;
        }
    
        // Gunakan model untuk mengupdate data menu berdasarkan id_menu
        $model->edit('menu', ['id_menu' => $id_menu], $data);
    
        // Redirect kembali ke halaman menu dengan pesan sukses
        return redirect()->route('menus')->with('success', 'Menu berhasil diperbarui');
    }
    

    public function hapusmenu(Request $request, $id_menu)
    {
        $model = new resto();
    $model->hapus('menu', ['id_menu' => $id_menu]);
    return redirect()->route('menus')->with('success', 'Menu dihapus');
    }

public function setting()
{
    $id_level = session()->get('id_level');	

    // Cek apakah pengguna sudah login
    if (!$id_level) {
        return redirect()->route('login'); // Redirect ke halaman login
    } elseif ($id_level != 1) {
        return redirect()->route('error404'); // Redirect ke halaman error
    } else {
        // Ambil data dari database
        $model = new resto();
        $data['darren2'] = $model->getWhere('setting', ['id_setting' => 1]);

        // Log aktivitas pengguna
        $id_user = session()->get('id_user');
     

        $data['id_level'] = $id_level; 

        echo view('header', $data);
        echo view('menu', $data);
        echo view('setting', $data);
        echo view('footer');
    }
}

public function editsetting(Request $request)
    {
        // Initialize the model
        $model = new resto();
        $namawebsite = $request->input('namaweb');
    
        $data = ['namawebsite' => $namawebsite];
    
        // Process upload for tab icon
        if ($request->hasFile('tab') && $request->file('tab')->isValid()) {
            $tab = $request->file('tab');
            $tabName = time() . '_' . $tab->getClientOriginalName(); // Save file with unique name
            $tab->move(public_path('img'), $tabName);
            $data['icontab'] = $tabName; // Save file name to database
        }
    
        // Process upload for menu icon
        if ($request->hasFile('menu') && $request->file('menu')->isValid()) {
            $menu = $request->file('menu');
            $menuName = time() . '_' . $menu->getClientOriginalName();
            $menu->move(public_path('img'), $menuName);
            $data['iconmenu'] = $menuName;
        }
    
        // Process upload for login icon
        if ($request->hasFile('login') && $request->file('login')->isValid()) {
            $login = $request->file('login');
            $loginName = time() . '_' . $login->getClientOriginalName();
            $login->move(public_path('img'), $loginName);
            $data['iconlogin'] = $loginName;
        }
    
        $where = ['id_setting' => 1];
        $model->edit('setting',$where, $data ); 
    
       
        return redirect()->route('setting')->with('success', 'Settings updated successfully!'); // Adjust as necessary
    }

    public function user()
{
    $model = new resto(); // Model yang digunakan
    $data['darren2'] = $model->getWhere('setting', ['id_setting' => 1]);
    $data['users'] = $model->join('user', 'level', 'user.id_level', 'level.id_level');
    echo view('header', $data);
    echo view('menu', $data);
    echo view('user', $data);
    echo view('footer');
}

public function prosesTransaksi(Request $request)
{
    $model = new resto();  

    // Generate kode transaksi di backend
    $kodeTransaksi = 'TR-' . time(); // Contoh: TR-1707053456

    // Mengambil data dari request
    $tanggal = $request->input('tanggal');
    $kodeMember = $request->input('kode_member');
    $kodeVoucher = $request->input('kode_voucher');
    $menu = json_encode($request->input('menu'));  // Simpan menu dalam format JSON
    $subtotal = $request->input('subtotal');
    $discount = $request->input('discount');
    $totalAkhir = $request->input('total_akhir');
    $bayar = $request->input('bayar');
    $kembalian = $request->input('kembalian');

    // Menyusun data transaksi
    $data = [
        'tanggal' => $tanggal,
        'kode_transaksi' => $kodeTransaksi,
        'kode_member' => $kodeMember,
        'kode_voucher' => $kodeVoucher,
        'menu' => $menu, 
        'total' => $subtotal,
        'discount' => $discount,
        'total_akhir' => $totalAkhir,
        'bayar' => $bayar,
        'kembalian' => $kembalian,
    ];

    // Simpan data transaksi ke database
    $model->tambah('transaksi', $data);

    // Kembalikan response JSON agar frontend bisa memproses lebih lanjut
    return response()->json([
        'success' => true,
        'kode_transaksi' => $kodeTransaksi
    ]);
}


public function transaksi()
{
    $model = new resto();

    $id_level = session()->get('id_level');
    if (!$id_level) {
        return redirect()->route('login');
    }

    $data['darren2'] = $model->getWhere('setting', ['id_setting' => 1]);
    $data['transaksis'] = $model->tampil('transaksi');
    echo view('header', $data);
    echo view('menu', $data);
    echo view('transaksi', $data);
    echo view('footer');
}

public function cetakNota($kode_transaksi)
{
    // Ambil data transaksi dari tabel 'transaksi' berdasarkan kode_transaksi
    $transaksi = resto::where('kode_transaksi', $kode_transaksi)->first();

    if (!$transaksi) {
        abort(404, "Transaksi tidak ditemukan.");
    }

    // Decode menu jika disimpan dalam format JSON
    $menuList = json_decode($transaksi->menu, true);

    // Generate PDF
    $pdf = Pdf::loadView('nota', compact('transaksi', 'menuList'));

    return $pdf->stream('Nota_Transaksi_' . $kode_transaksi . '.pdf');
}


}
