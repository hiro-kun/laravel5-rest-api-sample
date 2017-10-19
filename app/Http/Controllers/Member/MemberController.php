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

        $validationCheckResult = \App\Library\Validation\MemberValidation::memberValidate($request);
        // バリデーションエラー確認
        if ($validationCheckResult['isError'] === true) {
            return response()->json(
                $this->errorResponse(
                    $validationCheckResult['message'],
                    $validationCheckResult['field'],
                    40001,
                    $request['uuid']
                ),
                400
            );
        }

        // 既に存在するメールアドレスか確認
        try {
            $isExistsMember = \App\Models\Member::where('email', $request['email'])->first();
        } catch (\Exception $e) {

            $errorInfo['file']      = __FILE__;
            $errorInfo['line']      = __LINE__;
            $errorInfo['message']   = $e->getMessage();
            $errorInfo['request']   = $request;

            \App\Library\Log\ApplicationLog::makeErrorLog($errorInfo);

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

        // 既に存在しているメールアドレス
        if (!is_null($isExistsMember)) {
            return response()->json(
                $this->errorResponse(
                    'Requested email address has already been registered',
                    'email',
                    40900,
                    $request['uuid']
                ),
                409
            );
        }

        // 登録処理
        try {
            \App\Models\Member::create(
                [
                    'status' => 'active',
                    'email'  => $request['email'],
                    'name'   => $request['name'],
                    'sex'    => $request['sex']
                ]
            );
        } catch (\Exception $e) {
            $errorInfo['file']      = __FILE__;
            $errorInfo['line']      = __LINE__;
            $errorInfo['message']   = $e->getMessage();
            $errorInfo['request']   = $request;

            \App\Library\Log\ApplicationLog::makeErrorLog($errorInfo);

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

        try {
            $member = \App\Models\Member::where('email', $request['email'])->first();
        } catch (\Exception $e) {
            $errorInfo['file']      = __FILE__;
            $errorInfo['line']      = __LINE__;
            $errorInfo['message']   = $e->getMessage();
            $errorInfo['request']   = $request;

            \App\Library\Log\ApplicationLog::makeErrorLog($errorInfo);

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

        $memberInfoArray = $member->toArray();

        $successResponse['request_id'] = $request['uuid'];
        $successResponse['_links']     = "v1/members/${memberInfoArray['member_id']}";
        $successResponse['member_id']  = $memberInfoArray['member_id'];
        $successResponse['email']      = $memberInfoArray['email'];
        $successResponse['name']       = $memberInfoArray['name'];
        $successResponse['sex']        = $memberInfoArray['sex'];


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
            $memberInfo = \App\Models\Member::find($id);
        } catch (\Exception $e) {
            $errorInfo['file']      = __FILE__;
            $errorInfo['line']      = __LINE__;
            $errorInfo['message']   = $e->getMessage();
            $errorInfo['request']   = $request;

            \App\Library\Log\ApplicationLog::makeErrorLog($errorInfo);

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

        if (is_null($memberInfo)) {
            return response()->json(
                $this->errorResponse('Request resource not found', 'member_id', 40400, $request['uuid']),
                404
            );
        }

        $memberInfoArray = $memberInfo->toArray();

        $successResponse['request_id'] = $request['uuid'];
        $successResponse['member_id']  = $memberInfoArray['member_id'];
        $successResponse['email']      = $memberInfoArray['email'];
        $successResponse['name']       = $memberInfoArray['name'];
        $successResponse['sex']        = $memberInfoArray['sex'];

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
            $memberInfo = \App\Models\Member::find($id);
        } catch (\Exception $e) {
            $errorInfo['file']      = __FILE__;
            $errorInfo['line']      = __LINE__;
            $errorInfo['message']   = $e->getMessage();
            $errorInfo['request']   = $request;

            \App\Library\Log\ApplicationLog::makeErrorLog($errorInfo);

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

        if (is_null($memberInfo)) {
            return response()->json(
                $this->errorResponse('RESOURCE_ERROR', '', 'Request resource not found.', 40400, $request['uuid']),
                404
            );
        }

        $validationCheckResult = \App\Library\Validation\MemberValidation::memberValidate($request);
        if ($validationCheckResult['isError'] === true) {
            return response()->json(
                $this->errorResponse(
                    $validationCheckResult['message'],
                    $validationCheckResult['field'],
                    40001,
                    $request['uuid']
                ),
                400
            );
        }

        try {
            // 指定されたメールアドレスが既に他人に使われているか
            $isExistEmail = \App\Models\Member::where('email', $request['email'])->where('member_id', '!=', $id)->first();
        } catch (\Exception $e) {
            $errorInfo['file']      = __FILE__;
            $errorInfo['line']      = __LINE__;
            $errorInfo['message']   = $e->getMessage();
            $errorInfo['request']   = $request;

            \App\Library\Log\ApplicationLog::makeErrorLog($errorInfo);

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

        if (!is_null($isExistEmail)) {
            return response()->json(
                $this->errorResponse('Request email adress is already exists. Please input other email adress', 'email', 40900, $request['uuid']),
                409
           );
        }

        try {
            // 更新
            \App\Models\Member::where('member_id', $id)
              ->update(
                  [
                      'email' => $request['email'],
                      'name'  => $request['name'],
                      'sex'   => $request['sex']
                  ]
              );
        } catch (\Exception $e) {
            $errorInfo['file']      = __FILE__;
            $errorInfo['line']      = __LINE__;
            $errorInfo['message']   = $e->getMessage();
            $errorInfo['request']   = $request;

            \App\Library\Log\ApplicationLog::makeErrorLog($errorInfo);

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

         $successResponse['request_id'] = $request['uuid'];
         $successResponse['member_id']  = $id;
         $successResponse['email']      = $request['email'];
         $successResponse['name']       = $request['name'];
         $successResponse['sex']        = $request['sex'];

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
            $memberInfo = \App\Models\Member::find($id);
        } catch (\Exception $e) {
            $errorInfo['file']      = __FILE__;
            $errorInfo['line']      = __LINE__;
            $errorInfo['message']   = $e->getMessage();
            $errorInfo['request']   = $request;

            \App\Library\Log\ApplicationLog::makeErrorLog($errorInfo);

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

        if (is_null($memberInfo)) {
            return response()->json(
                $this->errorResponse('Request resource not found', 'member_id', 40400, $request['uuid']),
                404
            );
        }

        try {
            $memberInfo->delete();
        } catch (\Exception $e) {
            $errorInfo['file']      = __FILE__;
            $errorInfo['line']      = __LINE__;
            $errorInfo['message']   = $e->getMessage();
            $errorInfo['request']   = $request;

            \App\Library\Log\ApplicationLog::makeErrorLog($errorInfo);

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

        return response()->json('', 204);
    }
}
