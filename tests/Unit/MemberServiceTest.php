<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    // 実行コマンド
    // ./vendor/bin/phpunit --coverage-html /opt/laravel/coverage/ tests/Unit/MemberServiceTest.php

    public function setUp()
    {
        parent::setUp();
        \Artisan::call('migrate');
    }

    public function tearDown()
    {
        \Artisan::call('migrate:reset');
        parent::tearDown();
    }

    public function testSuccessStoreMemberResponse()
    {
        $request['email']  = 'hoge@huga.com';
        $request['name']   = 'piyopiyo';
        $request['sex']    = 'male';
        $request['uuid']   = '210215a4-c3c1-11e7-977a-0800276252b8';

        $memberService   = \App\Service\ServiceFactory::create('Member', 'Member');
        $successResponse = $memberService->storeMember($request);

        $expectResponse['request_id'] = '210215a4-c3c1-11e7-977a-0800276252b8';
        $expectResponse['_links']     = 'v1/members/1';
        $expectResponse['member_id']  = 1;
        $expectResponse['email']      = 'hoge@huga.com';
        $expectResponse['name']       = 'piyopiyo';
        $expectResponse['sex']        = 'male';

        $this->assertSame($expectResponse, $successResponse);
    }

    public function testSuccessStoreMemberDb()
    {
        $request['email'] = 'hoge@huga.com';
        $request['name']  = 'piyopiyo';
        $request['sex']   = 'male';
        $request['uuid']  = '210215a4-c3c1-11e7-977a-0800276252b8';

        $memberService   = \App\Service\ServiceFactory::create('Member', 'Member');
        $memberService->storeMember($request);

        $memberinfo = \DB::table('members')->where('email', $request['email'])->select('member_id', 'status', 'email', 'name', 'sex')->first();

        $actual['member_id'] = $memberinfo->member_id;
        $actual['status']    = $memberinfo->status;
        $actual['email']     = $memberinfo->email;
        $actual['name']      = $memberinfo->name;
        $actual['sex']       = $memberinfo->sex;

        $expectResponse['member_id'] = 1;
        $expectResponse['status']    = 'active';
        $expectResponse['email']     = $request['email'];
        $expectResponse['name']      = $request['name'];
        $expectResponse['sex']       = $request['sex'];

        $this->assertSame($expectResponse, $actual);
    }

    public function testSuccessShowMember()
    {
        // database/factories/ModelFactory.phpで定義
        factory(\App\Models\Member::class)->create();

        $request['uuid'] = '210215a4-c3c1-11e7-977a-0800276252b8';
        $memberId        = 1;

        $memberService = \App\Service\ServiceFactory::create('Member', 'Member');
        $actual        = $memberService->showMember($memberId, $request);

        $expectResponse['request_id'] = $request['uuid'];
        $expectResponse['member_id']  = $memberId;
        $expectResponse['email']      = 'hoge@huga.com';
        $expectResponse['name']       = 'piyopiyo';
        $expectResponse['sex']        = 'male';

        $this->assertSame($expectResponse, $actual);
    }

    public function testFailedStoreMemberDuplicationEmail()
    {
        factory(\App\Models\Member::class)->create();

        $request['email'] = 'hoge@huga.com';
        $request['name']  = 'piyopiyo';
        $request['sex']   = 'male';
        $request['uuid']  = '210215a4-c3c1-11e7-977a-0800276252b8';

        try {
            $memberService   = \App\Service\ServiceFactory::create('Member', 'Member');
            $memberService->storeMember($request);
        } catch (\App\Exceptions\ApplicationException $e) {
            $this->assertSame('Requested email address has already been registered', $e->getMessage());
        }
    }
}
