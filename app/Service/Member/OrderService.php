<?php

namespace App\Service\Member;

class OrderService
{
    public function showOrderAllByMemberId($memberId, $request)
    {
        $member = \App\Models\Member::find($memberId);
        if (is_null($member)) {
            throw new \App\Exceptions\ApplicationException('Request resource not found.', 40400, 'member_id', 404);
        }
        $orders         = $member->orders;
        $orderInfoArray = [];

        if (!is_null($orders)) {
            $orderInfoArray = $orders->toArray();
        }

        $responseOrderInfo = [];
        foreach ($orderInfoArray as $orderInfo) {
            $responseOrderInfo[] = [
                'order_id' => $orderInfo['order_id'],
                'item_id'  => $orderInfo['item_id'],
                'payment'  => $orderInfo['payment'],
                'price'    => $orderInfo['price'],
                'date'     => $orderInfo['created_at'],
            ];
        }
        $successResponse['request_id'] = $request['uuid'];
        $successResponse['member_id']  = $memberId;
        $successResponse['orders']     = $responseOrderInfo;

        return $successResponse;
    }

    public function showOrderDetailByMemberId($memberId, $orderId, $request)
    {
        $member = \App\Models\Member::find($memberId);
        if (is_null($member)) {
            throw new \App\Exceptions\ApplicationException('Request resource not found.', 40400, 'member_id', 404);
        }
        $order     = $member->orders->where('order_id', $orderId)->first();
        $orderInfo = [];

        if (is_null($order)) {
            throw new \App\Exceptions\ApplicationException('Request resource not found.', 40400, 'order_id', 404);
        }
        $orderInfo = $order->toArray();

        $successResponse['request_id'] = $request['uuid'];
        $successResponse['member_id']  = $memberId;
        $successResponse['order_id']   = $orderInfo['order_id'];
        $successResponse['item_id']    = $orderInfo['item_id'];
        $successResponse['payment']    = $orderInfo['payment'];
        $successResponse['price']      = $orderInfo['price'];
        $successResponse['date']       = $orderInfo['created_at'];

        return $successResponse;
    }
}
