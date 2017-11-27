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

        $memberService   = \App\Service\ServiceFactory::create('Member', 'Member');
        $successResponse = $memberService->storeMember($request);

        return response()->json(
            $successResponse,
            201
        );
    }

    // GET /v1/members/33
    public function show($id)
    {
        $request = \Request::all();

        $memberService   = \App\Service\ServiceFactory::create('Member', 'Member');
        $successResponse = $memberService->showMember($id, $request);

        return response()->json(
           $successResponse,
           200
       );
    }

    // PUT /v1/members/33
    public function update($id)
    {
        $request = \Request::all();

        $memberService   = \App\Service\ServiceFactory::create('Member', 'Member');
        $successResponse = $memberService->updateMember($id, $request);

        return response()->json(
            $successResponse,
            200
        );
    }

    // DELETE /v1/members/11
    public function destroy($id)
    {
        $request = \Request::all();

        $memberService = \App\Service\ServiceFactory::create('Member', 'Member');
        $memberService->showMember($id, $request);

        return response()->json('', 204);
    }
}
