<?php

namespace App\Http\Controllers;

use App\Models\siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class siswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $katakunci = $request->katakunci;
        $jumlahbaris = 4;
        if(strlen($katakunci)) {
            $data = siswa::where('ukey', 'like', "%$katakunci%")
                ->orWhere('nama', 'like', "%$katakunci%")
                ->orWhere('jurusan', 'like', "%$katakunci%")
                ->paginate($jumlahbaris);
        } else {
            $data = siswa::orderBy('ukey', 'desc')->paginate(2);
        }
        return view('barunawati.dataSiswa')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barunawati.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Session::flash('ukey', $request->ukey);
        Session::flash('nama', $request->nama);
        Session::flash('jurusan', $request->jurusan);

        $request->validate([
            'ukey' => 'required|numeric|unique:siswa,ukey'
        ], [
            'ukey.unique' => 'U-key sudah dipakai',
        ]);
        $data = [
            'ukey' => $request->ukey,
            'nama' => $request->nama,
            'jurusan' => $request->jurusan,
        ];
        siswa::create($data);
        return redirect()->to('siswa')->with('success', 'Data berhasil ditambahkan');
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
        $data = siswa::where('ukey', $id)->first();
        return view('barunawati.edit')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $request->validate([
        // ], [
    
        // ]);
        $data = [
            'nama' => $request->nama,
            'jurusan' => $request->jurusan,
        ];
        siswa::where('ukey',$id)->update($data);
        return redirect()->to('siswa')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        siswa::where('ukey', $id)->delete();
        return redirect()->to('siswa')->with('success', 'Data berhasil dihapus');
    }
}
