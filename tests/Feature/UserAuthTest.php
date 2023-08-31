<?php

namespace Tests\Feature;

use App\Enum\Country;
use App\Enum\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'user_name'    => 'john_doe',
            'first_name'   => 'John',
            'last_name'    => 'Doe',
            'email'        => 'john.doe@email.com',
            'password'     => 'password',
            'role'         => Role::ADMIN,
            'country_code' => Country::US,
        ]);
    }

    public function testLoginRevokesExistingAccessToken(): void
    {
        // simulate login
        $this->post('/api/oauth/token', [
            'grant_type'    => 'password',
            'client_id'     => 2,
            'client_secret' => 'admin_secret',
            'username'      => 'john.doe@email.com',
            'password'      => 'password',
        ])->assertOk();

        // assert that an access_token was created and it's not revoked
        $userAccessTokenCount = DB::table('oauth_access_tokens')->where([
            'user_id' => $this->admin->id,
            'revoked' => false,
        ])->count();
        $this->assertEquals(1, $userAccessTokenCount);

        // assert that the token created by this time is only 1 (first login)
        $userAllAccessTokenCount = DB::table('oauth_access_tokens')->where([
            'user_id' => $this->admin->id,
        ])->count();
        $this->assertEquals(1, $userAllAccessTokenCount);

        // simulate different login for user (say, from another browser)
        $this->post('/api/oauth/token', [
            'grant_type'    => 'password',
            'client_id'     => 2,
            'client_secret' => 'admin_secret',
            'username'      => 'john.doe@email.com',
            'password'      => 'password',
        ])->assertOk();

        // assert again that an access token was created and it's the only non-revoked token
        $userAccessTokenCount = DB::table('oauth_access_tokens')->where([
            'user_id' => $this->admin->id,
            'revoked' => false,
        ])->count();
        $this->assertEquals(1, $userAccessTokenCount);

        // assert again that the token created by this time is now 2 (from first and second login, revoked and non-revoked)
        $userAllAccessTokenCount = DB::table('oauth_access_tokens')->where([
            'user_id' => $this->admin->id,
        ])->count();
        $this->assertEquals(2, $userAllAccessTokenCount);
    }

    public function testLogout(): void
    {
        // login and get token
        $accessToken = $this->post('/api/oauth/token', [
            'grant_type'    => 'password',
            'client_id'     => 2,
            'client_secret' => 'admin_secret',
            'username'      => 'john.doe@email.com',
            'password'      => 'password',
        ])->json('access_token');

        // logout and assert status 200
        $this->withHeader('Authorization', 'Bearer '.$accessToken)
            ->post('/api/users/logout')
            ->assertOk();
    }

    public function testUserIsUnauthorizedForAdminClient(): void
    {
        // create a normal user
        $user = User::factory()->create([
             'user_name'    => 'normal_user',
             'first_name'   => 'Jane',
             'last_name'    => 'Doe',
             'email'        => 'user@email.com',
             'password'     => 'password',
             'role'         => Role::USER,
             'country_code' => Country::US,
         ]);

        // login with app client
        $this->post('/api/oauth/token', [
            'grant_type'    => 'password',
            'client_id'     => 1,
            'client_secret' => 'app_secret',
            'username'      => 'user@email.com',
            'password'      => 'password',
        ])->assertOk();

        // assert that the normal user has 1 un-revoked access token
        $tokens = $user->tokens()->get()->filter(fn ($token) => !$token->revoked);
        $this->assertCount(1, $tokens);

        // login with admin client and assert that the action is unauthorized
        $this->post('/api/oauth/token', [
            'grant_type'    => 'password',
            'client_id'     => 2,
            'client_secret' => 'admin_secret',
            'username'      => 'user@email.com',
            'password'      => 'password',
        ])->assertUnauthorized();

        // assert that the previous token is still un-revoked
        $this->assertCount(1, $tokens);

        // assert that the previous token is equal with the current token
        $this->assertEquals(
            $tokens->first()->id,
            $user->tokens()->get()->filter(fn ($token) => !$token->revoked)->first()->id
        );
    }
}
