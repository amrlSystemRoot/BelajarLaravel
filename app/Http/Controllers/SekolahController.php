<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SekolahController extends Controller
{
    public function index()
    {
        $sekolah = Sekolah::with('user')->get();
        return response()->json([
            'status' => 'success',
            'masssage' => 'Berhasil mengambil data',
            'data' => $sekolah
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $val = Validator::make($data,
        [
            'prov' => 'required|string',
            'kota' => 'required|string',
            'jurusan' => 'required|string', 
            'nim' => 'required|integer|min:10', 
            'nama' => 'required|string',
        ]);

        if ($val->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => $val->errors()
                ], 200
            );
        }

        $data['user_id'] = $request->user()->id;
        $sekolah = Sekolah::create($data);
        return response()->json(
            [
                'status' => 'success',
                'massage' => 'Berhasi ditambah',
                'data' => $sekolah
            ], 200
        );
    }

    public function update($id, Request $request)
    {
        $sekolah = Sekolah::find($id);
        if (!$sekolah) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => 'Data sekolah tidak ditemukan'
                ], 404
            );
        }

        $val = Validator::make($request->all(),[
            'prov' => 'required|string',
            'kota' => 'required|string',
            'jurusan' => 'required|string', 
            'nim' => 'required|integer|min:10', 
            'nama' => 'required|string',
        ]);
        if ($val->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => $val->errors()
                ]
                , 400
            );
        }

        $sekolah->update($request->all());
        return response()->json(
            [
                'status' => 'success',
                'massage' => 'Data berhasil diubah',
                'data' => $sekolah
            ], 200
        );
    }

    public function destroy($id)
    {
        $sekolah = Sekolah::find($id);
        if (!$sekolah) {
            return response()->json(
                [
                    'status' => 'error',
                    'massage' => 'Data sekolah tidak ditemukan'
                ], 404
            );
        }

        $sekolah->delete();
        return response()->json(
            [
                'status' => 'success',
                'massage' => 'Data berhasil dihapus'
            ], 200,
        );
    }
}



