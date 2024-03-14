<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    protected $user_test;
    protected $route_prefix;
    protected $accessToken;

    protected function setUp(): void
    {
        parent::setUp();

        DB::beginTransaction();
        $this->user_test    = User::factory()->create();
        $this->route_prefix = env('APP_URL') . '/api/auth';
    }

    public function signin() : void {
        $user = $this->user_test->toArray();
        $user['password'] = 'password';

        $response = $this->post($this->route_prefix . '/signin', $user);

        $this->accessToken = $response['data']['accessToken'];
    }

    public function test_user_can_sign_up()
    {
        $user = User::factory()->make()->toArray();
        $user['password'] = Hash::make('password');

        $response = $this->post($this->route_prefix . '/signup', $user);

        $response->assertOk();
        $response->assertSeeText($user['email']);
    }

    public function test_user_can_sign_in()
    {
        $user = $this->user_test->toArray();
        $user['password'] = 'password';

        $response = $this->post($this->route_prefix . '/signin', $user);

        $response->assertOk();
        $response->assertSeeText($user['email']);

        $this->accessToken = $response['data']['accessToken'];
    }

    public function test_user_can_sign_out()
    {
        $this->signin();

        $response = $this->post($this->route_prefix . '/signout', [], ['Authorization' => 'Bearer ' . $this->accessToken]);

        $response->assertOk();
        $response->assertSeeText('success');
    }

    public function test_user_can_update_profile()
    {
        $this->signin();

        $response = $this->put($this->route_prefix . '/user', ['name' => "testing" ], ['Authorization' => 'Bearer ' . $this->accessToken]);

        $response->assertOk();
        $response->assertSeeText('success');
        $response->assertSeeText('testing');
    }

    public function test_user_can_see_profile()
    {
        $this->signin();

        $response = $this->get($this->route_prefix . '/user', ['Authorization' => 'Bearer ' . $this->accessToken]);

        $response->assertOk();
        $response->assertSeeText('success');
        $response->assertSeeText($this->user_test['email']);
    }
}
