<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Pesanan;
use Validator;

class PesananController extends Controller
{
    public function get()
    {
        $pesanan = DB::table('pesanans')
                        ->where('is_Deleted','LIKE','0')
                        ->get();
        return response([
            'message' => 'Retrive All Success',
            'data' => $pesanan
        ]);
    }

    public function getOne($id)
    {
        $pesanan = Pesanan::find($id);

        return response([
            'message' => 'Retrive Pesanan Success',
            'data' => $pesanan
        ]);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'tanggal_pesanan' => 'required|date_format:Y-m-d',
            'total_harga' => 'required|numeric',
            'status_pesanan' => 'required'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $pesanan = new Pesanan();
        $pesanan->tanggal_pesanan       = $storeData['tanggal_pesanan'];
        $pesanan->total_harga           = $storeData['total_harga'];
        $pesanan->status_pesanan        = $storeData['status_pesanan'];
        
        $pesanan->save();

        return response([
            'message' => 'Add Pesanan Succes',
            'data' => $pesanan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $pesanan = Pesanan::find($id);
        if (is_null($pesanan)) {
            return response([
                'message' => 'Pesanan Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'tanggal_pesanan' => 'date_format:Y-m-d',
            'total_harga' => 'numeric',
            'status_pesanan' => ''
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $pesanan->tanggal_pesanan       = $updateData['tanggal_pesanan'];
        $pesanan->total_harga           = $updateData['total_harga'];
        $pesanan->status_pesanan        = $updateData['status_pesanan'];

        $pesanan->save();
        return response([
            'message' => 'Update Pesanan Success',
            'data' => $pesanan,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::find($id);
        if (is_null($pesanan)) {
            return response([
                'message' => 'Pesanan Not Found',
                'data' => null
            ], 404);
        }

        if ($pesanan->status_pesanan == 1) {
            $pesanan->status_pesanan = 0;
        } else if ($pesanan->status_pesanan == 0) {
            $pesanan->status_pesanan = 1;
        }

        $pesanan->save();
        return response([
            'message' => 'Update Status Pesanan Success',
            'data' => $pesanan,
        ]);
    }

    public function delete($id)
    {
        $pesanan = Pesanan::find($id);
        if (is_null($pesanan)) {
            return response([
                'message' => 'Pesanan Not Found',
                'data' => null
            ], 404);
        }

        $pesanan->is_Deleted = 1;

        $pesanan->save();
        return response([
            'message' => 'Delete Pesanan Succes',
            'data' => $pesanan,
        ]);
    }

    public function restore($id)
    {
        $pesanan = Pesanan::find($id);
        if (is_null($pesanan)) {
            return response([
                'message' => 'Pesanan Not Found',
                'data' => null
            ], 404);
        }

        $pesanan->is_Deleted = 0;

        $pesanan->save();
        return response([
            'message' => 'Restore Pesanan Succes',
            'data' => $pesanan,
        ]);
    }
}