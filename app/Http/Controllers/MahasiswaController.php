<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Add this line

class MahasiswaController extends Controller
{
    public function index()
    {
        return Mahasiswa::all(); // Mengambil semua data mahasiswa
    }

    public function store(Request $request)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswas,nim',
            'email' => 'required|email|unique:mahasiswas,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:mahasiswa', // Adjust according to your requirements
        ]);

        // Create a new Mahasiswa
        $mahasiswa = Mahasiswa::create([
            'name' => $validatedData['name'],
            'nim' => $validatedData['nim'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), // Assuming you want to hash the password
            'role' => $validatedData['role'],
        ]);

        return response()->json($mahasiswa, 201);
    }

    public function show($id)
    {
        return Mahasiswa::findOrFail($id); // Mengambil data berdasarkan ID
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->update($request->all()); // Mengupdate data
        return $mahasiswa;
    }

    public function destroy(Request $request, $id)
    {
        // Check if the user is an admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403); // Return unauthorized error if not admin
        }

        Mahasiswa::destroy($id); // Menghapus data
        return response()->json(['message' => 'Deleted']);
    }
}
