<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use WithFaker; use RefreshDatabase;

    
    public function testIndex(){

        // Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.user.index'));

        $response->assertStatus(200);

        $response->assertViewIs('users.dashboard');

        $response->assertViewHas('users');


    }


    public function testCreate(){
        $user = User::factory()->create();
        $this->actingAs($user);

        // Perform a GET request to the create route
        $response = $this->get(route('admin.user.create'));

        // Assert that the response status is 200 or whatever is expected
        $response->assertStatus(200);

        // Assert that the response view is 'users.create'
        $response->assertViewIs('users.create');


    }

    public function testStore(){
        $user = User::factory()->create();
        $this->actingAs($user);
    
        // Generate a unique email for the test
        $testEmail = $this->faker->unique()->safeEmail;
    
        $response = $this->post(route('admin.user.store'), [
            'name' => $this->faker->name,
            'email' => $testEmail,
            'phone_number' => $this->faker->phoneNumber,
            'password' => bcrypt($this->faker->password),
        ]);
    
        $response->assertRedirect(route('admin.user.index'));
    
        // Use the same test email for the assertDatabaseHas assertion
        $this->assertDatabaseHas('users', [
            'email' => $testEmail,
        ]);

    }


    public function testEdit(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.user.edit', ['id' => $user->id]));

        $response->assertSuccessful();

        $response->assertViewIs('users.edit');

        $response->assertViewHas('user');
    }


    public function testUpdate(){
        
        $user = User::factory()->create();
        $this->actingAs($user);


        $updateEmail = $this->faker->unique()->safeEmail;
        $updateName =  $this->faker->name;
        $updatePhone  = $this->faker->phoneNumber;
        $updatePass  = bcrypt($this->faker->password);

        $response = $this->post(route('admin.user.update', ['id' => $user->id]), [
            'name' =>  $updateName,
            'email' => $updateEmail,
            'phone' => $updatePhone,
            'password' => $updatePass,
        ]);

        $response->assertRedirect(route('admin.user.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $updateName,
            'email' => $updateEmail, 
            'phone_number' => $updatePhone, 
        ]);
    }

    public function testDestroy(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.user.destroy', ['id' => $user->id]));

        $response->assertRedirect(route('admin.user.index'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
