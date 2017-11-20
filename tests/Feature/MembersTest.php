<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MembersTest extends TestCase
{
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

    public function testSuccessShowMember()
    {
        factory(\App\Models\Member::class)->create();

        $response = $this->get('/v1/members/1');

        $expectResponse['member_id']  = 1;
        $expectResponse['email']      = 'hoge@huga.com';
        $expectResponse['name']       = 'piyopiyo';
        $expectResponse['sex']        = 'male';

        $responseArray = json_decode($response->content(), true);
        // request_idの中身は桁数のみのテスト
        $this->assertSame(36, mb_strlen($responseArray['request_id']));
        unset($responseArray['request_id']);

        $this->assertSame($expectResponse, $responseArray);

        $response->assertStatus(200);
    }

    public function testFailedShowMember()
    {
        $response      = $this->get('/v1/members/2');
        $responseArray = json_decode($response->content(), true);
        // request_idの中身は桁数のみのテスト
        $this->assertSame(36, mb_strlen($responseArray['request_id']));
        unset($responseArray['request_id']);

        $expectResponse['message']         = 'Request resource not found.';
        $expectResponse['errors']['field'] = 'member_id';
        $expectResponse['errors']['code']  = 4002;

        $this->assertSame($expectResponse, $responseArray);

        $response->assertStatus(404);
    }
}
