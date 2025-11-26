<?php

namespace App\Http\Controllers;

use App\Helpers\imageHelper;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::orderBy('updated_at', 'desc')->get(); 
        return view('backend.v_user.index', [ 
            'judul' => 'Data User', 
            'index' => $user 
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.v_user.create', [ 
            'judul' => 'Tambah User', 
        ]); 
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
{
    try {
        DB::beginTransaction();

        $messages = [
            'foto.image' => 'Format gambar gunakan file jpeg, jpg, png, atau gif.',
            'foto.max' => 'Ukuran file maksimal 1024 KB.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi Password tidak cocok.',
        ];

        $validatedData = $request->validate([
            'name' => 'required|max:255', 
            'email' => 'required|max:255|email|unique:user,email', 
            'role' => 'required', 
            'hp' => 'required|min:10|max:13', 
            'password' => 'required|min:4|confirmed', 
            'foto' => 'image|mimes:jpeg,jpg,png,gif|max:1024', 
        ], $messages);

        $validatedData['status'] = 0;

        // Upload foto
        if ($request->file('foto')) {
            $file = $request->file('foto');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension;
            $directory = 'storage/img-user/';

            imageHelper::uploadAndResize($file, $directory, $originalFileName, 385, 400);
            $validatedData['foto'] = $originalFileName;
        }

        // Cek kombinasi password
        $password = $request->password;
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/';

        if (!preg_match($pattern, $password)) {
            return redirect()->back()
                ->withErrors(['password' => 'Password harus terdiri dari huruf besar, huruf kecil, angka, dan simbol karakter.'])
                ->withInput();
        }

        // Hash password
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Simpan user
        User::create($validatedData);

        DB::commit();

        return redirect()->route('backend.user.index')
            ->with('success', 'Data berhasil tersimpan');

    } catch (Exception $e) {
        DB::rollBack();

        return response()->json([
            'message' => 'Terjadi kesalahan',
            'error' => $e->getMessage()
        ], 500);
    }
}

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('backend.v_user.edit', [
            'judul' => 'Ubah User',
            'edit' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //ddd($request);
        try {
        $user = User::findOrFail($id);
        $rules = [
            'name' => 'required|max:255',
            'role' => 'required',
            'status' => 'required',
            'hp' => 'required|min:10|max:13',
            'foto' => 'image|mimes:jpeg,jpg,png,gif|file|max:1024',
        ];
        $messages = [ 
            'foto.image' => 'Format gambar gunakan file dengan ekstensi jpeg, jpg, png, atau gif.', 
            'foto.max' => 'Ukuran file gambar Maksimal adalah 1024 KB.' 
        ]; 
 
        if ($request->email != $user->email) { 
            $rules['email'] = 'required|max:255|email|unique:user'; 
        } 
        $validatedData = $request->validate($rules, $messages); 
 
        // menggunakan ImageHelper 
        if ($request->file('foto')) { 
            //hapus gambar lama 
            if ($user->foto) { 
                $oldImagePath = public_path('storage/img-user/') . $user->foto; 
                if (file_exists($oldImagePath)) { 
                    unlink($oldImagePath); 
                } 
            } 
            $file = $request->file('foto'); 
            $extension = $file->getClientOriginalExtension(); 
            $originalFileName = date('YmdHis') . '_' . uniqid() . '.' . $extension; 
            $directory = 'storage/img-user/'; 
            // Simpan gambar dengan ukuran yang ditentukan 
            ImageHelper::uploadAndResize($file, $directory, $originalFileName, 385, 400); 
// null (jika tinggi otomatis) 
            // Simpan nama file asli di database 
            $validatedData['foto'] = $originalFileName; 
        }
    }
    catch (Exception $e) {
        throw new Exception("Gagal memperbarui user: " . $e->getMessage());
    }  
 
        $user->update($validatedData); 
        return redirect()->route('backend.user.index')->with('success', 'Data berhasil diperbaharui'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if ($user->foto) {
            $oldImagePath = public_path('storage/img-user/') . $user->foto;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        $user->delete();
        return redirect()->route('backend.user.index')->with('success', 'Data berhasil dihapus');
    }
}
