<?php

namespace App\Service\Member;

class MemberService
{
    public function storeMember($request)
    {
        $validationCheckResult = \App\Library\Validation\MemberValidation::memberValidate($request);
        if ($validationCheckResult['isError'] === true) {
            throw new \App\Exceptions\ApplicationException($validationCheckResult['message'], \App\Library\Constant\ApplicationErrorCode::VALIDATION_ERROR, $validationCheckResult['field'], 400);
        }

        // 既に存在するメールアドレスか確認
        $isExistsMember = \App\Models\Member::where('email', $request['email'])->first();
        if (!is_null($isExistsMember)) {
            throw new \App\Exceptions\ApplicationException('Requested email address has already been registered', \App\Library\Constant\ApplicationErrorCode::EMAIL_ADRESS_NOT_FOUND, 'email', 409);
        }

        \App\Models\Member::create(
            [
                'status' => 'active',
                'email'  => $request['email'],
                'name'   => $request['name'],
                'sex'    => $request['sex']
            ]
        );

        $member          = \App\Models\Member::where('email', $request['email'])->first();
        $memberInfoArray = $member->toArray();

        $successResponse['request_id'] = $request['uuid'];
        $successResponse['_links']     = "v1/members/${memberInfoArray['member_id']}";
        $successResponse['member_id']  = $memberInfoArray['member_id'];
        $successResponse['email']      = $memberInfoArray['email'];
        $successResponse['name']       = $memberInfoArray['name'];
        $successResponse['sex']        = $memberInfoArray['sex'];

        return $successResponse;
    }

    public function updateMember($memberId, $request)
    {
        $validationCheckResult = \App\Library\Validation\MemberValidation::memberValidate($request);
        if ($validationCheckResult['isError'] === true) {
            throw new \App\Exceptions\ApplicationException($validationCheckResult['message'], \App\Library\Constant\ApplicationErrorCode::VALIDATION_ERROR, $validationCheckResult['field'], 400);
        }

        $memberInfo = \App\Models\Member::find($memberId);
        if (is_null($memberInfo)) {
            throw new \App\Exceptions\ApplicationException('Request resource not found.', \App\Library\Constant\ApplicationErrorCode::EMAIL_ADRESS_NOT_FOUND, 'email', 404);
        }

        // 指定されたメールアドレスが既に他人に使われているか
        $isExistsMember = \App\Models\Member::where('email', $request['email'])->where('member_id', '!=', $memberId)->first();
        if (!is_null($isExistsMember)) {
            throw new \App\Exceptions\ApplicationException('Request email adress is already exists. Please input other email adress.', \App\Library\Constant\ApplicationErrorCode::EMAIL_ADRESS_ALREADY_EXISTS, 'email', 409);
        }

        // 更新
        \App\Models\Member::where('member_id', $memberId)
          ->update(
              [
                  'email' => $request['email'],
                  'name'  => $request['name'],
                  'sex'   => $request['sex']
              ]
          );

        $successResponse['request_id'] = $request['uuid'];
        $successResponse['member_id']  = $memberId;
        $successResponse['email']      = $request['email'];
        $successResponse['name']       = $request['name'];
        $successResponse['sex']        = $request['sex'];

        return $successResponse;
    }

    public function showMember($memberId, $request)
    {
        $member = \App\Models\Member::find($memberId);

        if (is_null($member)) {
            throw new \App\Exceptions\ApplicationException('Request resource not found.', \App\Library\Constant\ApplicationErrorCode::EMAIL_ADRESS_NOT_FOUND, 'member_id', 404);
        }
        $memberInfoArray = $member->toArray();

        $successResponse['request_id'] = $request['uuid'];
        $successResponse['member_id']  = $memberInfoArray['member_id'];
        $successResponse['email']      = $memberInfoArray['email'];
        $successResponse['name']       = $memberInfoArray['name'];
        $successResponse['sex']        = $memberInfoArray['sex'];

        return $successResponse;
    }

    public function destroyMember($memberId, $request)
    {
        $memberInfo = \App\Models\Member::find($memberId);
        if (is_null($memberInfo)) {
            throw new \App\Exceptions\ApplicationException('Request resource not found.', \App\Library\Constant\ApplicationErrorCode::EMAIL_ADRESS_NOT_FOUND, 'member_id', 404);
        }
        $memberInfo->delete();
    }
}
