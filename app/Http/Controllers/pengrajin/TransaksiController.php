<?php

namespace App\Http\Controllers\pengrajin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Order;

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    function notif()
    {
        // untuk notifikasi pesanan baru
        $order1 = DB::table('order')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->join('users', 'users.id', '=', 'order.user_id')
            ->select('order.*', 'status_order.name', 'users.name as nama_pemesan')
            ->where('order.status_order_id', 1)
            ->get();

        // untuk notifikasi perlu dicek
        $order2 = DB::table('order')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->join('users', 'users.id', '=', 'order.user_id')
            ->select('order.*', 'status_order.name', 'users.name as nama_pemesan')
            ->where('order.status_order_id', 2)
            ->orWhere('order.status_order_id', 3)
            ->get();

        // untuk notifikasi perlu dikirim
        $order3 = DB::table('order')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->join('users', 'users.id', '=', 'order.user_id')
            ->select('order.*', 'status_order.name', 'users.name as nama_pemesan')
            ->where('order.status_order_id', 4)
            ->get();

        // untuk notifikasi barang dikirim
        $order4 = DB::table('order')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->join('users', 'users.id', '=', 'order.user_id')
            ->select('order.*', 'status_order.name', 'users.name as nama_pemesan')
            ->where('order.status_order_id', 5)
            ->get();

        $notif1 = count($order1);
        $notif2 = count($order2);
        $notif3 = count($order3);
        $notif4 = count($order4);
        return [$notif1, $notif2, $notif3, $notif4];
    }

    public function index()
    {
        //ambil data order yang status nya 1 atau masih baru/belum dikonfirmasi
        $order = DB::table('order')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->join('users', 'users.id', '=', 'order.user_id')
            ->select('order.*', 'status_order.name', 'users.name as nama_pemesan')
            ->where('order.status_order_id', 1)
            ->get();

        foreach ($order as $key => $value) {
            $detail_order = DB::table('detail_order')
                ->join('products', 'products.id', '=', 'detail_order.product_id')
                ->select('products.pengrajin_id')
                ->where('detail_order.order_id', $value->id)
                ->get();
            $isTrue = false;
            foreach ($detail_order as $key => $value) {
                if ($value->pengrajin_id == auth()->user()->id) {
                    $isTrue = true;
                }
            }
            if ($isTrue == false) {
                unset($order[$key]);
            }
        }
        $data = array(
            'orderbaru' => $order,
            'notif' => $this->notif()
        );

        return view('pengrajin.transaksi.index', $data);
    }

    public function detail($id)
    {
        //ambil data detail order sesuai id
        $detail_order = DB::table('detail_order')
            ->join('products', 'products.id', '=', 'detail_order.product_id')
            ->join('order', 'order.id', '=', 'detail_order.order_id')
            ->select('products.name as nama_produk', 'products.image', 'detail_order.*', 'products.price', 'order.*')
            ->where('detail_order.order_id', $id)
            ->get();
        $order = DB::table('order')
            ->join('users', 'users.id', '=', 'order.user_id')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->select('order.*', 'users.name as nama_pelanggan', 'status_order.name as status')
            ->where('order.id', $id)
            ->first();
        $data = array(
            'detail' => $detail_order,
            'order'  => $order
        );
        return view('pengrajin.transaksi.detail', $data);
    }
    public function detail_konfirmasi($id)
    {
        //ambil data detail order sesuai id
        $detail_order = DB::table('detail_order')
            ->join('products', 'products.id', '=', 'detail_order.product_id')
            ->join('order', 'order.id', '=', 'detail_order.order_id')
            ->select('products.name as nama_produk', 'products.image', 'detail_order.*', 'products.price', 'order.*')
            ->where('detail_order.order_id', $id)
            ->get();
        $order = DB::table('order')
            ->join('users', 'users.id', '=', 'order.user_id')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->select('order.*', 'users.name as nama_pelanggan', 'status_order.name as status')
            ->where('order.id', $id)
            ->first();
        $data = array(
            'detail' => $detail_order,
            'order'  => $order
        );
        return view('pengrajin.transaksi.detail_konfirmasi', $data);
    }

    public function perludicek()
    {
        //ambil data order yang status nya 2 atau 3 atau belum di cek / sudah bayar
        $order = DB::table('order')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->join('users', 'users.id', '=', 'order.user_id')
            ->select('order.*', 'status_order.name', 'users.name as nama_pemesan')
            ->where('order.status_order_id', 2)
            ->orWhere('order.status_order_id', 3)
            ->get();
        // return $order;
        foreach ($order as $key => $value) {
            $detail_order = DB::table('detail_order')
                ->join('products', 'products.id', '=', 'detail_order.product_id')
                ->select('products.pengrajin_id')
                ->where('detail_order.order_id', $value->id)
                ->get();
            $isTrue = false;
            foreach ($detail_order as $value) {
                if ($value->pengrajin_id == auth()->user()->id) {
                    $isTrue = true;
                }
            }
            if ($isTrue == false) {
                unset($order[$key]);
            }
        }
        // return auth()->user()->id;
        $data = array(
            'orderbaru' => $order,
            'notif' => $this->notif()
        );

        return view('pengrajin.transaksi.perludicek', $data);
    }

    public function perludikirim()
    {
        //ambil data order yang status nya 4 sudah dicek dan perlu dikirim(input no resi)
        $order = DB::table('order')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->join('users', 'users.id', '=', 'order.user_id')
            ->select('order.*', 'status_order.name', 'users.name as nama_pemesan')
            ->where('order.status_order_id', 4)
            ->get();

        foreach ($order as $key => $value) {
            $detail_order = DB::table('detail_order')
                ->join('products', 'products.id', '=', 'detail_order.product_id')
                ->select('products.pengrajin_id')
                ->where('detail_order.order_id', $value->id)
                ->get();
            $isTrue = false;
            foreach ($detail_order as $value) {
                if ($value->pengrajin_id == auth()->user()->id) {
                    $isTrue = true;
                }
            }
            if ($isTrue == false) {
                unset($order[$key]);
            }
        }
        $data = array(
            'orderbaru' => $order,
            'notif' => $this->notif()
        );

        return view('pengrajin.transaksi.perludikirim', $data);
    }

    public function selesai()
    {
        //ambil data order yang status nya 6 barang sudah diterima pelangan
        $order = DB::table('order')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->join('users', 'users.id', '=', 'order.user_id')
            ->select('order.*', 'status_order.name', 'users.name as nama_pemesan')
            ->where('order.status_order_id', 6)
            ->get();
        foreach ($order as $key => $value) {
            $detail_order = DB::table('detail_order')
                ->join(
                    'products',
                    'products.id',
                    '=',
                    'detail_order.product_id'
                )
                ->select('products.pengrajin_id')
                ->where('detail_order.order_id', $value->id)
                ->get();
            $isTrue = false;
            foreach ($detail_order as $value) {
                if ($value->pengrajin_id == auth()->user()->id) {
                    $isTrue = true;
                }
            }
            if ($isTrue == false) {
                unset($order[$key]);
            }
        }
        $data = array(
            'orderbaru' => $order
        );

        return view('pengrajin.transaksi.selesai', $data);
    }

    public function dibatalkan()
    {
        //ambil data order yang status nya 7 dibatalkan pelanngan
        $order = DB::table('order')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->join('users', 'users.id', '=', 'order.user_id')
            ->select('order.*', 'status_order.name', 'users.name as nama_pemesan')
            ->where('order.status_order_id', 7)
            ->get();
        $notif1 = count($order);
        foreach ($order as $key => $value) {
            $detail_order = DB::table('detail_order')
                ->join('products', 'products.id', '=', 'detail_order.product_id')
                ->select('products.pengrajin_id')
                ->where('detail_order.order_id', $value->id)
                ->get();
            $isTrue = false;
            foreach ($detail_order as $value) {
                if ($value->pengrajin_id == auth()->user()->id) {
                    $isTrue = true;
                }
            }
            if ($isTrue == false) {
                unset($order[$key]);
            }
        }
        $data = array(
            'orderbaru' => $order
        );

        return view('pengrajin.transaksi.dibatalkan', $data);
    }

    public function dikirim()
    {
        //ambil data order yang status nya 5 atau sedang dikirim
        $order = DB::table('order')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->join('users', 'users.id', '=', 'order.user_id')
            ->select('order.*', 'status_order.name', 'users.name as nama_pemesan')
            ->where('order.status_order_id', 5)
            ->get();
        foreach ($order as $key => $value) {
            $detail_order = DB::table('detail_order')
                ->join('products', 'products.id', '=', 'detail_order.product_id')
                ->select('products.pengrajin_id')
                ->where('detail_order.order_id', $value->id)
                ->get();
            $isTrue = false;
            foreach ($detail_order as $value) {
                if ($value->pengrajin_id == auth()->user()->id) {
                    $isTrue = true;
                }
            }
            if ($isTrue == false) {
                unset($order[$key]);
            }
        }

        $data = array(
            'orderbaru' => $order,
            'notif' => $this->notif()
        );

        return view('pengrajin.transaksi.dikirim', $data);
    }

    public function konfirmasi($id)
    {
        //function ini untuk mengkonfirmasi bahwa pelanngan sudah melakukan pembayaran
        $order = Order::findOrFail($id);
        $order->status_order_id = 4;
        $order->save();

        $kurangistok = DB::table('detail_order')->where('order_id', $id)->get();
        foreach ($kurangistok as $kurang) {
            $ambilproduk = DB::table('products')->where('id', $kurang->product_id)->first();
            if ($ambilproduk->stok > 0) {
                $ubahstok = $ambilproduk->stok - $kurang->qty;
            } else {
                $ubahstok = $ambilproduk->stok;
            }

            $update = DB::table('products')
                ->where('id', $kurang->product_id)
                ->update([
                    'stok' => $ubahstok
                ]);
        }
        return redirect()->route('pengrajin.transaksi.perludikirim')->with('status', 'Berhasil Mengonfirmasi Pembayaran Pesanan');
    }

    public function batalkan_pesanan($id)
    {
        //function ini untuk mengkonfirmasi bahwa pelanngan sudah melakukan pembayaran
        $order = Order::findOrFail($id);
        $order->status_order_id = 7;
        $order->save();
        return redirect()->route('pengrajin.transaksi.dibatalkan')->with('status', 'Berhasil Membatalkan Pesanan');
    }

    public function konfirmasi_pesanan($id)
    {
        //function ini untuk mengkonfirmasi bahwa pelanngan sudah oke dalam pesanannya
        $order = Order::findOrFail($id);
        $order->status_order_id = 2;
        $order->save();

        $kurangistok = DB::table('detail_order')->where('order_id', $id)->get();
        foreach ($kurangistok as $kurang) {
            $ambilproduk = DB::table('products')->where('id', $kurang->product_id)->first();
            if ($ambilproduk->stok > 0) {
                $ubahstok = $ambilproduk->stok - $kurang->qty;
            } else {
                $ubahstok = $ambilproduk->stok;
            }

            $update = DB::table('products')
                ->where('id', $kurang->product_id)
                ->update([
                    'stok' => $ubahstok
                ]);
        }
        return redirect()->route('pengrajin.transaksi.perludicek')->with('status', 'Berhasil Mengonfirmasi Pesanan');
    }

    public function inputresi($id, Request $request)
    {
        //funtion untuk menginput no resi pesanan
        $order = Order::findOrFail($id);
        $order->no_resi = $request->no_resi;
        $order->status_order_id = 5;
        $order->save();
        return redirect()->route('pengrajin.transaksi.perludikirim')->with('status', 'Berhasil Menginput No Resi');
    }
}
