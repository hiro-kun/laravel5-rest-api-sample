<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    // GET v1/members
    public function index()
    {
        return \App\Models\Member::all();
    }

    // POST /v1/members
    public function store()
    {
        $request = \Request::all();

        try {
            $memberService = \App\Service\ServiceFactory::create('Member', 'Member');
            $successResponse = $memberService->storeMember($request);
        } catch (\App\Exceptions\ApplicationException $e) {

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
                    \App\Library\Constant\ApplicationErrorCode::DB_ERROR,
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
                    \App\Library\Constant\ApplicationErrorCode::SYSTEM_ERROR,
                    $request['uuid']
                ),
                500
            );
        }

        return response()->json(
            $successResponse,
            201
        );

    }

    // GET /v1/members/33
    public function show($id)
    {
       $request = \Request::all();

       try {
           $memberService   = \App\Service\ServiceFactory::create('Member', 'Member');
           $successResponse = $memberService->showMember($id, $request);
       } catch (\App\Exceptions\ApplicationException $e) {

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
                   \App\Library\Constant\ApplicationErrorCode::DB_ERROR,
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
                   \App\Library\Constant\ApplicationErrorCode::SYSTEM_ERROR,
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

    // PUT /v1/members/33
    public function update($id)
    {
        $request = \Request::all();

        try {
            $memberService   = \App\Service\ServiceFactory::create('Member', 'Member');
            $successResponse = $memberService->updateMember($id, $request);
        } catch (\App\Exceptions\ApplicationException $e) {

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
                    \App\Library\Constant\ApplicationErrorCode::DB_ERROR,
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
                    \App\Library\Constant\ApplicationErrorCode::SYSTEM_ERROR,
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

    // DELETE /v1/members/11
    public function destroy($id)
    {
        $request = \Request::all();

        try {
            $memberService = \App\Service\ServiceFactory::create('Member', 'Member');
            $memberService->showMember($id, $request);
        } catch (\App\Exceptions\ApplicationException $e) {

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
                    \App\Library\Constant\ApplicationErrorCode::DB_ERROR,
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
                    \App\Library\Constant\ApplicationErrorCode::SYSTEM_ERROR,
                    $request['uuid']
                ),
                500
            );
        }

        return response()->json('', 204);
    }
}
