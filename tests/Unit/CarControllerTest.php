<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Car;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Factories\CarFactory;

class CarControllerTest extends TestCase
{
    use WithFaker; use RefreshDatabase;


    // Tests if the index method return the specific view with cars data
    public function testIndex(){

        // Creates a user and authenticate him
        $user = User::factory()->create();
        $this->actingAs($user);

        $car = Car::factory()->count(5)->create(['user_id' => $user->id]);

        $response = $this->get(route('admin.car.index'));

        $response->assertStatus(200);

        $response->assertViewIs('cars.dashboard');

        $response->assertViewHas('cars');
    }

    // Tests if create function returns the specific view
    public function testCreate(){

        // Creates the user and authenicates him
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.car.create'));
        
        $response->assertViewIs('cars.create');
    }

    // Tests if the store function adds a new car
    public function testStore(){
        $user = User::factory()->create();
        $this->actingAs($user);


        $testMake = $this->faker->word;
        $testModel = $this->faker->word;
        $testYear = $this->faker->year;
        // Get the user id that has added the car
        $testUserId= $user['id'];
    
        // Posting of the inputs in the route
        $response = $this->post(route('admin.car.store'), [
            'make' => $testMake,
            'model' => $testModel,
            'year' => $testYear,
            'user_id' => $testUserId,
        ]);
    
        $response->assertRedirect(route('admin.car.index'));
    
        // Checks if the database has the new added car with the specific make
        $this->assertDatabaseHas('cars', [
            'make' => $testMake,
        ]);
    }

    // Tests if the function return the specific view with that specific car id
    public function testEdit(){
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $car = Car::factory()->create();

        $response = $this->get(route('admin.car.edit', ['id' => $car->id]));

        $response->assertSuccessful();

        $response->assertViewIs('cars.edit');

        $response->assertViewHas('car');
    }



    // Tests if the update function updates the car
    public function testUpdate() {
        $user = User::factory()->create();
        $this->actingAs($user);

       
        // Create new car
        $car = Car::factory()->create(['user_id' => $user->id]);

        // Generate random inputs
        $testMake = $this->faker->word;
        $testModel = $this->faker->word;
        $testYear = $this->faker->year;
        $testUserId= $user['id'];
      

        // Posting the random generated inputs
        $response = $this->post(route('admin.car.update', ['id' => $car->id]), [
            'make' =>  $testMake,
            'model' => $testModel,
            'year' => $testYear,
            'user_id' => $testUserId
        ]);

        $response->assertRedirect(route('admin.car.index'));

        // Checks if the database has the new edited input
        $this->assertDatabaseHas('cars', [
            'make' => $testMake,
            'model' => $testModel,
            'year' => $testYear, 
            'user_id' => $testUserId, 
        ]);
    }

    // Tests if the destroy function deletes a car
    public function testDestroy(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $car = Car::factory()->create();

        $response = $this->get(route('admin.car.destroy', ['id' => $car->id]));

        $response->assertRedirect(route('admin.car.index'));

        //Checks if the car is deleted form database
        $this->assertDatabaseMissing('users', ['id' => $car->id]);
    }
}
