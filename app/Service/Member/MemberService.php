<?php

namespace App\Service\Member;

/*
・エラーの番号等はどこかのクラス等で定数化しておく
*/

class MemberService
{
    public function storeMember($request)
    {
        // 既に存在するメールアドレスか確認
        $isExistsMember = \App\Models\Member::where('email', $request['email'])->first();
        if (!is_null($isExistsMember)) {
            throw new \App\Exceptions\ApplicationException('Requested email address has already been registered', 40900, 'email', 409);
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
        $memberInfo = \App\Models\Member::find($memberId);
        if (is_null($memberInfo)) {
            throw new \App\Exceptions\ApplicationException('Request resource not found.', 40400, 'email', 404);
        }

        // 指定されたメールアドレスが既に他人に使われているか
        $isExistsMember = \App\Models\Member::where('email', $request['email'])->where('member_id', '!=', $memberId)->first();
        if (!is_null($isExistsMember)) {
            throw new \App\Exceptions\ApplicationException('Request email adress is already exists. Please input other email adress.', 40400, 'email', 409);
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
        $member          = \App\Models\Member::find($memberId);
        $memberInfoArray = $member->toArray();

        if (is_null($memberInfoArray)) {
            throw new \App\Exceptions\ApplicationException('Request resource not found.', 40400, 'member_id', 404);
        }

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
            throw new \App\Exceptions\ApplicationException('Request resource not found.', 40400, 'member_id', 404);
        }
        $memberInfo->delete();
    }
}
