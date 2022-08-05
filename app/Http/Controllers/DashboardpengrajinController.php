<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardpengrajinController extends Controller
{
       public function __construct()
    {
        $this->middleware('auth');
    }

        public function index()
        {

            //ambil data data untuk ditampilkan di card pada dashboard
            $dataTransaction = DB::table('order')
                ->select('order.*')
                ->where('status_order_id', 6)
                ->orderBy('created_at', 'asc')
                ->get();
            $income = 0;
            $transaction_count = 0;
            foreach ($dataTransaction as $transaction) {
                $detail_order = DB::table('detail_order')
                    ->join('products', 'products.id', '=', 'detail_order.product_id')
                    ->join('order', 'order.id','=', 'detail_order.order_id')
                    ->select('products.id as product_id', 'products.name as nama_produk', 'products.image', 'detail_order.*', 'products.price', 'order.*', 'products.pengrajin_id')
                    ->where('detail_order.order_id', $transaction->id)
                    ->get();
                $transaction_count_flag = false;
                foreach ($detail_order as $product) {
                    $price = (int) str_replace('.', '',
                        $product->price
                    );
                    $subtotal = $product->qty * $price;
                    if ($product->pengrajin_id == auth()->user()->id) {
                        $income += $subtotal;
                        $transaction_count_flag = true;
                    }
                }
                if ($transaction_count_flag) {
                    $transaction_count++;
                }
            }
            $pelanggan = DB::table('users')
                            ->select(DB::raw('COUNT(id) as total_user'))
                            ->where('roles','=','customer')
                            ->first();
            $order_terbaru = DB::table('order')
                ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
                ->join('users', 'users.id', '=', 'order.user_id')
                ->select('order.*', 'status_order.name', 'users.name as nama_pemesan')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            foreach ($order_terbaru as $key => $value) {
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
                    unset($order_terbaru[$key]);
                }
            }
            
            $data = array(
                'pendapatan' => $income,
                'transaksi'  => $transaction_count,
                'pelanggan'  => $pelanggan,
                'order_baru' => $order_terbaru
            );
            
            return view('pengrajin/dashboardpengrajin',$data);
        }
    }
