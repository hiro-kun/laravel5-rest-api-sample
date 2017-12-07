<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    // GET /v1/members/1/orders
    public function showOrderAllByMemberId($memberId)
    {
        $request = \Request::all();

        $orderService    = \App\Service\ServiceFactory::create('Member', 'Order');
        $successResponse = $orderService->showOrderAllByMemberId($memberId, $request);

        return response()->json(
            $successResponse,
            200
        );
    }

    // GET /v1/members/1/orders/1
    public function showOrderDetailByMemberId($memberId, $orderId)
    {
        $request = \Request::all();

        $orderService    = \App\Service\ServiceFactory::create('Member', 'Order');
        $successResponse = $orderService->showOrderDetailByMemberId($memberId, $orderId, $request);

        return response()->json(
            $successResponse,
            200
        );
    }
}
