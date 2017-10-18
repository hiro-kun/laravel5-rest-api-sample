<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    // /v1/members/1/orders
    public function orderAllByMemberId($memberId)
    {
        $request = \Request::all();

        try {
            $member = \App\models\Member::find($memberId);
        } catch (\Exception $e) {
            $errorInfo['file']      = __FILE__;
            $errorInfo['line']      = __LINE__;
            $errorInfo['message']   = $e->getMessage();
            $errorInfo['request']   = $request;

            \App\library\Log\ApplicationLog::makeErrorLog($errorInfo);

            return response()->json(
                $this->errorResponse(
                    'System error',
                    '',
                    40500,
                    $request['uuid']
                ),
                500
            );
        }
        if (is_null($member)) {
            return response()->json(
                $this->errorResponse('Request resource not found', 'member_id', 40400, $request['uuid']),
                404
            );
        }
        try {
            $orders = $member->orders;
        } catch (\Exception $e) {
            $errorInfo['file']      = __FILE__;
            $errorInfo['line']      = __LINE__;
            $errorInfo['message']   = $e->getMessage();
            $errorInfo['request']   = $request;

            \App\library\Log\ApplicationLog::makeErrorLog($errorInfo);

            return response()->json(
                $this->errorResponse(
                    'System error',
                    '',
                    40500,
                    $request['uuid']
                ),
                500
            );
        }
        if (is_null($orders)) {
            return response()->json(
                $this->errorResponse('Request resource not found', 'orders', 40500, $request['uuid']),
                404
            );
        }
        $orderInfoArray = $orders->toArray();

        $responseOrderInfo = [];
        foreach ($orderInfoArray as $orderInfo) {
            $responseOrderInfo[] = [
                'item_id'    => $orderInfo['item_id'],
                'payment'    => $orderInfo['payment'],
                'price'      => $orderInfo['price'],
                'date'       => $orderInfo['created_at'],
            ];
        }
        $successResponse['request_id'] = $request['uuid'];
        $successResponse['member_id']  = $request['uuid'];
        $successResponse['orders']     = $responseOrderInfo;

        return response()->json(
            $successResponse,
            200
        );
    }

    // /v1/members/1/orders/1
    public function orderDetailByMemberId($memberId, $orderId)
    {
        $request = \Request::all();

        try {
            $member = \App\models\Member::find($memberId);
        } catch (\Exception $e) {
            $errorInfo['file']      = __FILE__;
            $errorInfo['line']      = __LINE__;
            $errorInfo['message']   = $e->getMessage();
            $errorInfo['request']   = $request;

            \App\library\Log\ApplicationLog::makeErrorLog($errorInfo);

            return response()->json(
                $this->errorResponse(
                    'System error',
                    '',
                    40500,
                    $request['uuid']
                ),
                500
            );
        }
        if (is_null($member)) {
            return response()->json(
                $this->errorResponse('Request resource not found', 'member_id', 40400, $request['uuid']),
                404
            );
        }

        try {
            $order = $member->orders->where('order_id', $orderId)->first();
        } catch (\Exception $e) {
            $errorInfo['file']      = __FILE__;
            $errorInfo['line']      = __LINE__;
            $errorInfo['message']   = $e->getMessage();
            $errorInfo['request']   = $request;

            \App\library\Log\ApplicationLog::makeErrorLog($errorInfo);

            return response()->json(
                $this->errorResponse(
                    'System error',
                    '',
                    40500,
                    $request['uuid']
                ),
                500
            );
        }
        if (is_null($order)) {
            return response()->json(
                $this->errorResponse('Request resource not found', 'order_id', 40500, $request['uuid']),
                404
            );
        }
        $orderInfoArray = $order->toArray();

        $successResponse['request_id'] = $request['uuid'];
        $successResponse['member_id']  = $memberId;
        $successResponse['item_id']    = $orderInfoArray['item_id'];
        $successResponse['payment']    = $orderInfoArray['payment'];
        $successResponse['price']      = $orderInfoArray['price'];
        $successResponse['date']       = $orderInfoArray['created_at'];

        return response()->json(
            $successResponse,
            200
        );
    }
}
