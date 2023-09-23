<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\CustomerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function createData(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nama_barang' => 'required',
            'qty' => 'required|numeric',
            'harga_satuan' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'Check your validation',
                'errors' => $validation->errors(),
            ]);
        }

        try {
            $keranjang = Session::get('keranjang', []);

            $data = new BarangModel;
            $data->id = uniqid();
            $data->nama_barang = $request->input('nama_barang');
            $data->qty = $request->input('qty');
            $data->harga_satuan = $request->input('harga_satuan');
            $data->total_harga = $request->input('qty') * $request->input('harga_satuan');

            $keranjang[] = $data;

            Session::put('keranjang', $keranjang);

            $value = Session::get('keranjang');
        } catch (\Throwable $th) {
            return response()->json([
                'errors' => $th->getMessage()
            ]);
        }

        return response()->json([
            'message' => 'success',
            'data' => $value
        ]);
    }


    public function getDataById($id)
    {
        $keranjang = Session::get('keranjang', []);

        foreach ($keranjang as $item) {
            if ($item->id == $id) {
                return response()->json([
                    'message' => 'success get data by ID',
                    'data' => $item
                ]);
            }
        }

        return response()->json([
            'message' => 'Data not found'
        ]);
    }


    public function updateDataById(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'nama_barang' => 'required',
            'qty' => 'required|numeric',
            'harga_satuan' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'Check your validation',
                'errors' => $validation->errors(),
            ]);
        }

        $keranjang = Session::get('keranjang', []);

        foreach ($keranjang as $key => $item) {
            if ($item->id == $id) {
                // Mengganti nilai data dengan data yang baru
                $keranjang[$key]->nama_barang = $request->input('nama_barang');
                $keranjang[$key]->qty = $request->input('qty');
                $keranjang[$key]->harga_satuan = $request->input('harga_satuan');
                $keranjang[$key]->total_harga = $request->qty * $request->harga_satuan;

                Session::put('keranjang', $keranjang);

                return response()->json([
                    'message' => 'success update data by ID',
                    'data' => $keranjang[$key]
                ]);
            }
        }

        return response()->json([
            'message' => 'Data not found'
        ]);
    }

    public function deleteData($id)
    {

        try {
            $keranjang = Session::get('keranjang', []);
            foreach ($keranjang as $key => $item) {
                if ($item->id == $id) {
                    unset($keranjang[$key]);
                    Session::put('keranjang', $keranjang);

                    return response()->json([
                        'message' => 'Data berhasil dihapus dari session',
                    ]);
                }
            }

            return response()->json([
                'message' => 'Data dengan ID tertentu tidak ditemukan dalam session',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'errors' => $th->getMessage()
            ]);
        }
    }


    public function createDataCustomerAndBarang(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nama_customer' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'check your validation',
                'errors' => $validation->errors(),
            ]);
        }

        try {
            // Buat data customer baru
            $customer = new CustomerModel();
            $customer->nama_customer = $request->input('nama_customer');
            $customer->save();

            $id_customer = $customer->id;

            $keranjang = Session::get('keranjang', []);

            foreach ($keranjang as $item) {
                $barang = new BarangModel();
                $barang->nama_barang = $item->nama_barang;
                $barang->qty = $item->qty;
                $barang->harga_satuan = $item->harga_satuan;
                $barang->total_harga = $item->total_harga;
                $barang->id_customer = $id_customer;
                $barang->save();
            }

            Session::forget('keranjang');

            return response()->json([
                'message' => 'Data customer dan barang telah disimpan ke database.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'errors' => $th->getMessage()
            ]);
        }
    }

    public function getDataFromSession()
    {
        $value = Session::get('keranjang');

        return response()->json(
            [
                'message' => 'success get session',
                'data' => $value
            ]
        );
    }

    public function saveToDatabase()
    {
        $dataSesi = Session::get('keranjang');

        foreach ($dataSesi as $item) {
            BarangModel::create([
                'nama_barang' => $item->nama_barang,
                'qty' => $item->qty,
                'harga_satuan' => $item->harga_satuan,
                'total_harga' => $item->total_harga,
            ]);
        }

        Session::forget('keranjang');

        return response()->json([
            'message' => 'Data telah disimpan ke database.',
        ]);
    }
}
