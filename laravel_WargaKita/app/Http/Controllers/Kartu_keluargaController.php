<?php

namespace App\Http\Controllers;

use App\Models\Kartu_keluarga;
use App\Models\Rukun_tetangga;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Kartu_keluargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $search = $request->search;
    $filterRt = $request->rt;

    $kartu_keluarga = Kartu_keluarga::with('rukunTetangga')
        ->when($search, function ($query) use ($search) {
            $query->where('kepala_kk', 'like', '%' . $search . '%')
                ->orWhere('no_kk', 'like', '%' . $search . '%');
        })
        ->when($filterRt, function ($query) use ($filterRt) {
            $query->whereHas('rukunTetangga', function ($q) use ($filterRt) {
                $q->where('nomor_rt', $filterRt);
            });
        })
        ->paginate(5)
        ->withQueryString();

    $rukun_tetangga = Rukun_tetangga::all();
    $title = 'Kartu Keluarga';

    return view('kartu_keluarga', compact('kartu_keluarga', 'rukun_tetangga', 'title'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'no_kk' => 'required|unique:kartu_keluarga,no_kk|max:16',
            'id_rt' => 'required|exists:rukun_tetangga,id',
            'kepala_kk' => 'required|unique:kartu_keluarga,kepala_kk|max:16',
        ]);

        Kartu_keluarga::create([
            'no_kk' => $request->no_kk,
            'id_rt' => $request->id_rt,
            'kepala_kk' => $request->kepala_kk,
        ]);
        return redirect()->route('kartu_keluarga.index')->with('success', 'Kartu keluarga berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $kartu_keluarga = Kartu_keluarga::findOrFail($id);
        return view('kartu_keluarga.show', compact('kartu_keluarga'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $kartu_keluarga = Kartu_keluarga::findOrFail($id);
        return view('kartu_keluarga.edit', compact('kartu_keluarga'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $no_kk)
{

    $kartu_keluarga = Kartu_keluarga::where('no_kk', $no_kk)->firstOrFail();
    $request->validate([
        'no_kk' => [
            'required',
            'max:16',
            Rule::unique('kartu_keluarga', 'no_kk')->ignore($no_kk, 'no_kk'),
        ],
        'id_rt' => 'required|exists:rukun_tetangga,id',
        'kepala_kk' => [
            'required',
            'max:16',
            Rule::unique('kartu_keluarga', 'kepala_kk')->ignore($no_kk, 'no_kk'),
        ],
    ]);

    $kartu_keluarga = Kartu_keluarga::findOrFail($no_kk);

    $kartu_keluarga->update([
        'no_kk' => $request->no_kk,
        'id_rt' => $request->id_rt,
        'kepala_kk' => $request->kepala_kk,
    ]);

    return redirect()->route('kartu_keluarga.index')->with('success', 'Kartu keluarga berhasil diperbarui.');
}
    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
    {
        //
         try {
        Kartu_keluarga::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'KK berhasil dihapus.');
    } catch (\Illuminate\Database\QueryException $e) {
        return redirect()->back()->with('error', 'Tidak bisa menghapus KK karena masih digunakan.');
    }
    }
}
