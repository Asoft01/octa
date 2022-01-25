<?php

namespace App\Http\Controllers\Backend\Order;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\AcOrder;
/**
 * Class UserController.
 */
class OrderController extends Controller
{

    public function index()
    {
        $totalCAD = AcOrder::with(['orderitem.product', 'orderitem.reviewer', 'user', 'delivery', 'status_order'])
        ->where('status_payment_id', 4)
        ->where('currency_id', 2)
        ->sum('amount_paid');

        $totalUSD = AcOrder::with(['orderitem.product', 'orderitem.reviewer', 'user', 'delivery', 'status_order'])
        ->where('status_payment_id', 4)
        ->where('currency_id', 1)
        ->sum('amount_paid');

        $orders = AcOrder::with(['orderitem.product', 'orderitem.reviewer', 'user', 'delivery', 'status_order'])->where('status_order_id', 4)->orderBy('id', 'desc')->paginate(15);

        return view('backend.order.index', compact('orders', 'totalCAD', 'totalUSD'));
    }

    public function todo()
    {
        $totalCAD = AcOrder::with(['orderitem.product', 'orderitem.reviewer', 'user', 'delivery', 'status_order'])
        ->where('status_payment_id', 4)
        ->where('currency_id', 2)
        ->sum('amount_paid');

        $totalUSD = AcOrder::with(['orderitem.product', 'orderitem.reviewer', 'user', 'delivery', 'status_order'])
        ->where('status_payment_id', 4)
        ->where('currency_id', 1)
        ->sum('amount_paid');

        $orders = AcOrder::with(['orderitem.product', 'orderitem.reviewer', 'user', 'delivery', 'status_order'])->where('status_delivery_id', 2)->orderBy('id', 'desc')->paginate(15);

        return view('backend.order.index', compact('orders', 'totalCAD', 'totalUSD'));
    }

    public function summary()
    {
        $totalCAD = AcOrder::with(['orderitem.product', 'orderitem.reviewer', 'user', 'delivery', 'status_order'])
        ->where('status_payment_id', 4)
        ->where('currency_id', 2)
        ->sum('amount_paid');

        $qst = AcOrder::with(['orderitem.product', 'orderitem.reviewer', 'user', 'delivery', 'status_order'])
        ->where('status_payment_id', 4)
        ->where('currency_id', 2)
        ->sum('amount_qst');

        $pst = AcOrder::with(['orderitem.product', 'orderitem.reviewer', 'user', 'delivery', 'status_order'])
        ->where('status_payment_id', 4)
        ->where('currency_id', 2)
        ->sum('amount_pst');

        $gst = AcOrder::with(['orderitem.product', 'orderitem.reviewer', 'user', 'delivery', 'status_order'])
        ->where('status_payment_id', 4)
        ->where('currency_id', 2)
        ->sum('amount_gst');

        $hst = AcOrder::with(['orderitem.product', 'orderitem.reviewer', 'user', 'delivery', 'status_order'])
        ->where('status_payment_id', 4)
        ->where('currency_id', 2)
        ->sum('amount_hst');

        $totalUSD = AcOrder::with(['orderitem.product', 'orderitem.reviewer', 'user', 'delivery', 'status_order'])
        ->where('status_payment_id', 4)
        ->where('currency_id', 1)
        ->sum('amount_paid');

        $totalTODO = AcOrder::with(['orderitem.product', 'orderitem.reviewer', 'user', 'delivery', 'status_order'])
        ->where('status_delivery_id', 2)->count();

        $totalDONE = AcOrder::with(['orderitem.product', 'orderitem.reviewer', 'user', 'delivery', 'status_order'])
        ->where('status_delivery_id', 4)->count();
       

        return view('backend.order.summary', compact('totalCAD', 'totalUSD', 'totalTODO', 'totalDONE', 'gst', 'qst', 'hst', 'pst'));
    }

}
