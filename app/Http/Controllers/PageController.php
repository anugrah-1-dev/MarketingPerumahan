<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\Agent;
use App\Models\ClientData;
use App\Models\Closing;
use App\Models\Setting;
use App\Models\Unit;
use App\Models\User;

use App\Models\TipeRumah;
use App\Models\SocialMedia;
use App\Models\HeroSlide;
use Illuminate\Validation\Rules\Password;

class PageController extends Controller
{

    /**
     * Landing page utama (/).
     * Gunakan agent pertama yang aktif sebagai default.
     */
    public function landing(Request $request)
    {
        // Homepage langsung (/) → tampilkan "Admin", bukan agent tertentu
        $agent = [
            'nama'    => 'Admin',
            'jabatan' => 'Marketing',
            'slug'    => null,
            'wa'      => Setting::get('wa_admin', '6283876766055'),
        ];

        // Baca ?ref= dari query string dan simpan ke session + cookie
        $this->handleRefParam($request);

        $tipeRumahDiskon = TipeRumah::diskon()->latest()->take(6)->get();
        $semuaTipeRumah  = TipeRumah::orderBy('harga', 'asc')->get();
        $socialMedias    = SocialMedia::aktif()->get();
        $unitStats       = Unit::stats();
        $heroSlides      = $this->resolveHeroSlides();
        $denahImage      = Setting::get('denah_image', '');
        $lokasiVideo     = Setting::get('lokasi_video', '');

        return view('landing', compact('agent', 'tipeRumahDiskon', 'semuaTipeRumah', 'socialMedias', 'unitStats', 'heroSlides', 'denahImage', 'lokasiVideo'));
    }


    /**
     * Landing page dinamis per anggota tim.
     * URL: /{nama}  e.g. /anugrah, /fajar, /rizky
     * Data diambil dari tabel agents di database.
     */
    public function agentLanding(string $nama, Request $request)
    {
        // Cari agent berdasarkan slug & harus aktif — auto 404 jika tidak ada
        $agentModel = Agent::where('slug', strtolower($nama))
                           ->where('aktif', true)
                           ->firstOrFail();

        $agent = [
            'nama'    => $agentModel->nama,
            'jabatan' => $agentModel->jabatan,
            'slug'    => $agentModel->slug,
            // Gunakan nomor phone agent dari database.
            // Jika kosong, fallback ke nomor admin kantor dari Setting.
            'wa'      => $agentModel->phone ?? Setting::get('wa_admin', '6283876766055'),
        ];

        $tipeRumahDiskon = TipeRumah::diskon()->latest()->take(6)->get();
        $semuaTipeRumah  = TipeRumah::orderBy('harga', 'asc')->get();
        $socialMedias    = SocialMedia::aktif()->get();
        $unitStats       = Unit::stats();
        $heroSlides      = $this->resolveHeroSlides();
        $denahImage      = Setting::get('denah_image', '');
        $lokasiVideo     = Setting::get('lokasi_video', '');

        // Catat agent ke session agar tersedia di halaman detail
        session([
            'agent_slug'  => $agentModel->slug,
            'agent_phone' => $agentModel->phone ?? Setting::get('wa_admin', '6283876766055'),
            'agent_nama'  => $agentModel->nama,
        ]);

        // Baca ?ref= dari query string dan simpan ke session + cookie
        $this->handleRefParam($request);

        return view('landing', compact('agent', 'tipeRumahDiskon', 'semuaTipeRumah', 'socialMedias', 'unitStats', 'heroSlides', 'denahImage', 'lokasiVideo'));
    }

    private function resolveHeroSlides(): array
    {
        $slidesFromDb = HeroSlide::aktif()->get()->map(fn (HeroSlide $slide) => $slide->image_url)->all();
        if (!empty($slidesFromDb)) {
            return $slidesFromDb;
        }

        $slideFiles = glob(public_path('assets/landing/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}'), GLOB_BRACE) ?: [];
        $slideFiles = array_values(array_filter($slideFiles, function ($file) {
            $filename = strtolower(pathinfo($file, PATHINFO_FILENAME));
            return !str_contains($filename, 'logo');
        }));

        $slidesFromFolder = array_map(fn ($file) => asset('assets/landing/' . basename($file)), $slideFiles);

        return !empty($slidesFromFolder)
            ? $slidesFromFolder
            : ['https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=1200&q=80'];
    }

    /**
     * Simpan referral code dari ?ref= ke session dan cookie.
     * Dipakai oleh landing() dan agentLanding().
     */
    private function handleRefParam(Request $request): void
    {
        $ref = strtoupper(trim($request->query('ref', '')));
        if (! $ref || ! preg_match('/^BSA-[A-Z0-9]{4}$/', $ref)) {
            return;
        }

        $affiliateUser = User::where('referral_code', $ref)
                             ->where('role', 'affiliate')
                             ->first();
        if (! $affiliateUser) {
            return;
        }

        $request->session()->put('affiliate_ref_code', $ref);
        $request->session()->put('affiliate_user_id',  $affiliateUser->id);
        $request->session()->put('affiliate_name',      $affiliateUser->name);

        Cookie::queue('affiliate_ref_code', $ref,                        60 * 24 * 30);
        Cookie::queue('affiliate_user_id',  (string) $affiliateUser->id, 60 * 24 * 30);
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->isAffiliate()) {
                return redirect()->route('affiliate.dashboard');
            }
            if ($user->isAdmin()) {
                return redirect()->route('manager.dashboard');
            }
            return redirect()->intended('/admin');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function storeClientData(Request $request)
    {
        $request->validate([
            'nama_lengkap'    => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255'],
            'nik'             => ['required', 'string', 'digits:16'],
            'no_whatsapp'     => ['required', 'string', 'max:20'],
            'alamat'          => ['required', 'string'],
            'tipe_rumah_id'   => ['required', 'exists:tipe_rumah,id'],
            'bukti_pembayaran' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'nama_lengkap.required'  => 'Nama lengkap wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'nik.required'           => 'No KTP / NIK wajib diisi.',
            'nik.digits'             => 'NIK harus 16 digit angka.',
            'no_whatsapp.required'   => 'No WhatsApp wajib diisi.',
            'alamat.required'        => 'Alamat wajib diisi.',
            'tipe_rumah_id.required' => 'Tipe rumah wajib dipilih.',
            'tipe_rumah_id.exists'   => 'Tipe rumah tidak valid.',
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.image' => 'File harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format gambar: jpg, jpeg, png, atau webp.',
            'bukti_pembayaran.max'   => 'Ukuran file maksimal 5 MB.',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')
                ->store('bukti-pembayaran', 'uploads');
        }

        $clientData = ClientData::create([
            'nama_lengkap'     => $request->nama_lengkap,
            'email'            => $request->email,
            'nik'              => $request->nik,
            'no_whatsapp'      => $request->no_whatsapp,
            'alamat'           => $request->alamat,
            'bukti_pembayaran' => $buktiPath,
            'tipe_rumah_id'    => $request->tipe_rumah_id,
            'status_pembayaran'=> 'baru',
            'created_by'       => Auth::id(),
        ]);

        $this->autoCreateClosing($clientData);

        if ($request->input('_from') === 'leads') {
            return redirect()->route('affiliate.leads')->with('pengisian_ok', true);
        }

        return redirect()->route('affiliate.pengisian-data')->with('step', 'selesai');
    }

    public function storeRegister(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', Password::min(8)->letters()->mixedCase()->numbers(), 'confirmed'],
            'role' => ['required', 'string', 'in:affiliate'],
        ]);

        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        Auth::login($user);

        if ($user->isAffiliate()) {
            return redirect()->route('affiliate.dashboard');
        }
        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function unitTersedia()
    {
        $units = \App\Models\TipeRumah::orderBy('harga')->get();
        $totalUnit     = $units->sum('stok_tersedia');
        $totalTersedia = $units->where('stok_tersedia', '>', 0)->sum('stok_tersedia');
        $totalTerjual  = $units->sum('stok_terjual');
        return view('unit-tersedia', compact('units', 'totalUnit', 'totalTersedia', 'totalTerjual'));
    }

    public function detailRumah($blok = 'A3')
    {
        $unit = $this->getUnitDetail($blok);
        return view('detail-rumah', compact('unit'));
    }

    // -------------------------------------------------------
    // Data dummy – nanti bisa diganti dengan query Eloquent
    // -------------------------------------------------------

    /**
     * Master unit data – deterministik agar detail-rumah konsisten
     * dengan tampilan di unit-tersedia & site-plan.
     */
    private function masterUnits(): array
    {
        // [blok, tipe, status, harga_raw]
        return [
            // Blok A
            ['blok'=>'A1','tipe'=>'Tipe 36/72', 'status'=>'tersedia','harga_raw'=>310_000_000],
            ['blok'=>'A2','tipe'=>'Tipe 45/90', 'status'=>'terjual', 'harga_raw'=>390_000_000],
            ['blok'=>'A3','tipe'=>'Tipe 36/72', 'status'=>'tersedia','harga_raw'=>310_000_000],
            ['blok'=>'A4','tipe'=>'Tipe 54/108','status'=>'tersedia', 'harga_raw'=>450_000_000],
            ['blok'=>'A5','tipe'=>'Tipe 45/90', 'status'=>'tersedia','harga_raw'=>390_000_000],
            ['blok'=>'A6','tipe'=>'Tipe 36/72', 'status'=>'terjual', 'harga_raw'=>310_000_000],
            // Blok B
            ['blok'=>'B1','tipe'=>'Tipe 54/108','status'=>'tersedia','harga_raw'=>450_000_000],
            ['blok'=>'B2','tipe'=>'Tipe 36/72', 'status'=>'tersedia','harga_raw'=>310_000_000],
            ['blok'=>'B3','tipe'=>'Tipe 45/90', 'status'=>'tersedia','harga_raw'=>390_000_000],
            ['blok'=>'B4','tipe'=>'Tipe 36/72', 'status'=>'tersedia', 'harga_raw'=>310_000_000],
            ['blok'=>'B5','tipe'=>'Tipe 54/108','status'=>'terjual', 'harga_raw'=>450_000_000],
            ['blok'=>'B6','tipe'=>'Tipe 45/90', 'status'=>'tersedia','harga_raw'=>390_000_000],
            // Blok C
            ['blok'=>'C1','tipe'=>'Tipe 36/72', 'status'=>'tersedia','harga_raw'=>310_000_000],
            ['blok'=>'C2','tipe'=>'Tipe 45/90', 'status'=>'tersedia','harga_raw'=>390_000_000],
            ['blok'=>'C3','tipe'=>'Tipe 54/108','status'=>'tersedia', 'harga_raw'=>450_000_000],
            ['blok'=>'C4','tipe'=>'Tipe 36/72', 'status'=>'terjual', 'harga_raw'=>310_000_000],
            ['blok'=>'C5','tipe'=>'Tipe 45/90', 'status'=>'tersedia','harga_raw'=>390_000_000],
            ['blok'=>'C6','tipe'=>'Tipe 54/108','status'=>'tersedia','harga_raw'=>450_000_000],
            // Blok D
            ['blok'=>'D1','tipe'=>'Tipe 45/90', 'status'=>'tersedia','harga_raw'=>390_000_000],
            ['blok'=>'D2','tipe'=>'Tipe 36/72', 'status'=>'tersedia', 'harga_raw'=>310_000_000],
            ['blok'=>'D3','tipe'=>'Tipe 54/108','status'=>'tersedia','harga_raw'=>450_000_000],
            ['blok'=>'D4','tipe'=>'Tipe 45/90', 'status'=>'terjual', 'harga_raw'=>390_000_000],
            ['blok'=>'D5','tipe'=>'Tipe 36/72', 'status'=>'tersedia','harga_raw'=>310_000_000],
            ['blok'=>'D6','tipe'=>'Tipe 54/108','status'=>'tersedia','harga_raw'=>450_000_000],
        ];
    }

    private function getUnits(): array
    {
        return array_map(function ($u) {
            return [
                'blok'   => $u['blok'],
                'tipe'   => $u['tipe'],
                'status' => $u['status'],
                'harga'  => 'Rp ' . number_format($u['harga_raw'], 0, ',', '.'),
            ];
        }, $this->masterUnits());
    }

    private function getUnitDetail(string $blok): array
    {
        // Cari dari master data
        $master = collect($this->masterUnits())->firstWhere('blok', strtoupper($blok));

        // Default jika blok tidak ditemukan
        if (!$master) {
            $master = ['blok'=>$blok,'tipe'=>'Tipe 36/72','status'=>'tersedia','harga_raw'=>350_000_000];
        }

        $harga_raw  = $master['harga_raw'];
        $tipe_str   = str_replace('Tipe ', '', $master['tipe']); // e.g. "54/108"
        $tipe_parts = explode('/', $tipe_str);
        $lb         = ($tipe_parts[0] ?? '54') . ' m²';
        $lt         = ($tipe_parts[1] ?? '108') . ' m²';

        // Hitung KPR
        $dp_raw     = $harga_raw * 0.20;
        $pokok      = $harga_raw - $dp_raw;
        $r          = 0.08 / 12;

        $n15        = 15 * 12;
        $cicilan_15 = ($pokok * $r * pow(1 + $r, $n15)) / (pow(1 + $r, $n15) - 1);

        $n20        = 20 * 12;
        $cicilan_20 = ($pokok * $r * pow(1 + $r, $n20)) / (pow(1 + $r, $n20) - 1);

        // KT/KM berdasarkan tipe
        $kt = (int)($tipe_parts[0] ?? 36) >= 54 ? 3 : ((int)($tipe_parts[0] ?? 36) >= 45 ? 2 : 2);
        $km = (int)($tipe_parts[0] ?? 36) >= 54 ? 2 : 1;

        return [
            'blok'       => $master['blok'],
            'tipe'       => $tipe_str,
            'nama'       => "Detail Unit Blok {$master['blok']} – {$master['tipe']}",
            'deskripsi'  => 'Hunian modern minimalis dengan desain nyaman untuk keluarga muda.',
            'status'     => ucfirst($master['status']),
            'kt'         => $kt,
            'km'         => $km,
            'lantai'     => 1,
            'sertifikat' => 'SHM',
            'lb'         => $lb,
            'lt'         => $lt,
            'harga'      => 'Rp ' . number_format($harga_raw, 0, ',', '.'),
            'dp'         => 'Rp ' . number_format($dp_raw, 0, ',', '.'),
            'cicilan_15' => 'Rp ' . number_format($cicilan_15, 0, ',', '.'),
            'cicilan_20' => 'Rp ' . number_format($cicilan_20, 0, ',', '.'),
            'fasilitas'  => [
                'Listrik 2200W', 'Air PDAM', 'Kitchen Set',
                'Carport 1 Mobil', 'Meja Dapur + Sink', 'Pagar Minimalis',
            ],
        ];
    }

    // ── Pengisian Data Client (Admin) ─────────────────────────────────────────

    public function pengisianDataAdmin()
    {
        $user      = Auth::user();
        $panel     = $user && $user->isAdmin() ? 'manager' : 'admin';
        $tipeRumah = TipeRumah::select('id', 'nama_tipe', 'harga')->orderBy('harga')->get();
        $agents    = Agent::aktif()->select('id', 'nama', 'commission')->orderBy('nama')->get();

        return view("{$panel}.pengisian-data", compact('tipeRumah', 'agents'));
    }

    public function pengisianDataAffiliate()
    {
        $tipeRumah = TipeRumah::select('id', 'nama_tipe', 'harga')->orderBy('harga')->get();
        return view('affiliate.pengisian-data', compact('tipeRumah'));
    }

    public function storeClientDataAdmin(Request $request)
    {
        $request->validate([
            'nama_lengkap'     => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'max:255'],
            'nik'              => ['required', 'string', 'digits:16'],
            'no_whatsapp'      => ['required', 'string', 'max:20'],
            'alamat'           => ['required', 'string'],
            'tipe_rumah_id'    => ['required', 'exists:tipe_rumah,id'],
            'agent_id'         => ['nullable', 'exists:agents,id'],
            'bukti_pembayaran' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'nama_lengkap.required'  => 'Nama lengkap wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'nik.required'           => 'No KTP / NIK wajib diisi.',
            'nik.digits'             => 'NIK harus 16 digit angka.',
            'no_whatsapp.required'   => 'No WhatsApp wajib diisi.',
            'alamat.required'        => 'Alamat wajib diisi.',
            'tipe_rumah_id.required' => 'Tipe rumah wajib dipilih.',
            'tipe_rumah_id.exists'   => 'Tipe rumah tidak valid.',
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.image' => 'File harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format gambar: jpg, jpeg, png, atau webp.',
            'bukti_pembayaran.max'   => 'Ukuran file maksimal 5 MB.',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')
                ->store('bukti-pembayaran', 'uploads');
        }

        $clientData = ClientData::create([
            'nama_lengkap'     => $request->nama_lengkap,
            'email'            => $request->email,
            'nik'              => $request->nik,
            'no_whatsapp'      => $request->no_whatsapp,
            'alamat'           => $request->alamat,
            'bukti_pembayaran' => $buktiPath,
            'tipe_rumah_id'    => $request->tipe_rumah_id,
            'status_pembayaran'=> 'baru',
            'created_by'       => Auth::id(),
        ]);

        $this->autoCreateClosing($clientData, $request->input('agent_id') ? (int) $request->input('agent_id') : null);

        $user  = Auth::user();
        $route = $user->isAdmin() ? 'manager.pengisian-data' : 'admin.pengisian-data';
        return redirect()->route($route)->with('step', 'selesai');
    }

    // ── Auto-create closing record ─────────────────────────────────────────────

    private function autoCreateClosing(ClientData $clientData, ?int $agentIdOverride = null): void
    {
        $creator = User::find($clientData->created_by);

        if ($agentIdOverride) {
            $agent = Agent::find($agentIdOverride);
        } elseif ($creator && $creator->isAffiliate() && $creator->agent) {
            $agent = $creator->agent;
        } else {
            $agent = null;
        }

        $tipeRumah     = TipeRumah::find($clientData->tipe_rumah_id);
        $hargaJual     = (int) ($tipeRumah?->harga ?? 0);
        $komisiPersen  = (float) ($agent?->commission ?? 0);
        $komisiNominal = (int) round($hargaJual * $komisiPersen / 100);

        Closing::create([
            'client_data_id'  => $clientData->id,
            'agent_id'        => $agent?->id,
            'tipe_rumah_id'   => $clientData->tipe_rumah_id,
            'customer_name'   => $clientData->nama_lengkap,
            'customer_phone'  => $clientData->no_whatsapp,
            'harga_jual'      => $hargaJual,
            'komisi_persen'   => $komisiPersen,
            'komisi_nominal'  => $komisiNominal,
            'payment_status'  => 'dp',
            'komisi_status'   => 'pending',
            'tanggal_closing' => now()->toDateString(),
            'created_by'      => $clientData->created_by,
        ]);
    }
}
