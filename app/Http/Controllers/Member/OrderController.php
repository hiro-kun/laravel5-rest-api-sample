<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    // /v1/members/1/orders
    public function showOrderAllByMemberId($memberId)
    {
        $request = \Request::all();

        try {
            $orderService    = \App\Service\ServiceFactory::create('Member', 'Order');
            $successResponse = $orderService->showOrderAllByMemberId($memberId, $request);

        }  catch (\App\Exceptions\ApplicationException $e) {

            return response()->json(
                $this->errorResponse(
                    $e->getMessage(),
                    $e->getErrorField(),
                    $e->getCode(),
                    $request['uuid']
                ),
                $e->getHttpStatus()
            );

        } catch (\PDOException $e) {

            \App\Library\Log\ApplicationLog::makeErrorLog($e);

            return response()->json(
                $this->errorResponse(
                    'DB Error.',
                    '',
                    40500,
                    $request['uuid']
                ),
                400
            );

        } catch (\Exception $e) {

            \App\Library\Log\ApplicationLog::makeErrorLog($e);

            return response()->json(
                $this->errorResponse(
                    'System Error.',
                    '',
                    40500,
                    $request['uuid']
                ),
                500
            );
        }

        return response()->json(
            $successResponse,
            200
        );
    }

    // /v1/members/1/orders/1
    public function showOrderDetailByMemberId($memberId, $orderId)
    {
        $request = \Request::all();

        try {
            $orderService    = \App\Service\ServiceFactory::create('Member', 'Order');
            $successResponse = $orderService->showOrderDetailByMemberId($memberId, $orderId, $request);

        }  catch (\App\Exceptions\ApplicationException $e) {

            return response()->json(
                $this->errorResponse(
                    $e->getMessage(),
                    $e->getErrorField(),
                    $e->getCode(),
                    $request['uuid']
                ),
                $e->getHttpStatus()
            );

        } catch (\PDOException $e) {

            \App\Library\Log\ApplicationLog::makeErrorLog($e);

            return response()->json(
                $this->errorResponse(
                    'DB Error.',
                    '',
                    40500,
                    $request['uuid']
                ),
                400
            );

        } catch (\Exception $e) {
            
            \App\Library\Log\ApplicationLog::makeErrorLog($e);

            return response()->json(
                $this->errorResponse(
                    'System Error.',
                    '',
                    40500,
                    $request['uuid']
                ),
                500
            );
        }

        return response()->json(
            $successResponse,
            200
        );
    }
}
