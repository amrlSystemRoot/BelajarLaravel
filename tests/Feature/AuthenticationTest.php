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

    protected function setUp(): void
    {
        parent::setUp();

        DB::beginTransaction();
        $this->user_test    = User::factory()->create();
        $this->route_prefix = env('APP_URL') . '/api/auth';
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
    }

    public function test_user_can_sign_out()
    {
        $user = $this->user_test->toArray();
        $user['password'] = 'password';

        $signIn   = $this->post($this->route_prefix . '/signin', $user);
        $response = $this->post($this->route_prefix . '/signout', [], ['Authorization' => 'Bearer ' . $signIn['data']['accessToken']]);

        $response->assertOk();
        $response->assertSeeText('success');
    }
}
