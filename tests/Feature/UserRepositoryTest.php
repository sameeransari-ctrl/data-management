<?php

namespace Tests\Feature;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    
    /**
     * Method testGetUser
     *
     * @return void
     */
    public function testCreateUser() :void
    {
        $data = [
            'name' => 'sam',
            'user_type' => 'user',
            'email' => "sam@mailinator.com",
            'status'=>'active',
            'password'=>Hash::make(123456)
        ];
      
        $userRepository = new UserRepository(new User);
        $userRepository->createUser($data);
        $this->assertDatabaseHas(
            'users', 
            [
            'email' => 'sam@mailinator.com',
            ]
        );
    }
    
    /**
     * Method testGetUser
     *
     * @return void
     */
    public function testGetUser() :void
    {
        $user = User::factory()->create();
      
        $userRepository = new UserRepository(new User);
        
        $userInfo =$userRepository->getUser($user->id);

        $this->assertEquals($user['email'], $userInfo->email);
        $this->assertEquals($user['name'], $userInfo->name);
    }
    
    /**
     * Method testUpdateUser
     *
     * @return void
     */
    public function testUpdateUser() :void
    {
        $user = User::factory()->create(); // Create User
      
        $userRepository = new UserRepository(new User);
        
        $userInfo = $userRepository->getUser($user->id); // get User
        $this->assertEquals($user['email'], $userInfo->email); // asert user

        $data = [
            'name' => 'Tom',
            'email' => "Tom@mailinator.com",
        ];
        
        $updatedUser =$userRepository->updateUser($data, $user->id); // Update user

        // Check assertion
        $this->assertEquals($data['email'], $updatedUser->email);
        $this->assertDatabaseMissing(
            'users', [
            'email' => $userInfo->email,
            ]
        );

    }
    
    /**
     * Method testGetUserList
     *
     * @return void
     */
    public function testGetUserList() :void
    {
        // Create 6 user in database
        User::factory()->count(6)->create();
        $userRepository = new UserRepository(new User);

        $userList = $userRepository->getUserList([]);
        //assert return count is 6
        $this->assertEquals(count($userList), 6);

    }

    /**
     * Method testGetUserList
     *
     * @return void
     */
    public function testSearchUserList() :void
    {
        // Create 6 user in database
        $data = [
            [
            'name' => 'sam',
            'user_type' => 'user',
            'email' => "sam@mailinator.com",
            'status'=>'active',
            'password'=>Hash::make(123456)
            ],
            [
                'name' => 'samst',
                'user_type' => 'user',
                'email' => "samst@mailinator.com",
                'status'=>'active',
                'password'=>Hash::make(123456)
            ],
            [
                'name' => 'samuals',
                'user_type' => 'user',
                'email' => "samuals@mailinator.com",
                'status'=>'active',
                'password'=>Hash::make(123456)
            ],
            [
                'name' => 'robin',
                'user_type' => 'user',
                'email' => "robin@mailinator.com",
                'status'=>'active',
                'password'=>Hash::make(123456)
            ]
        ];
        User::insert($data);
        $userRepository = new UserRepository(new User);

        $userList = $userRepository->getUserList(['search'=>'sam']);
        //assert return count is 6
        $this->assertEquals(count($userList), 3);

    }
    
    /**
     * Method testChangeStatus
     *
     * @return void
     */
    public function testChangeStatus()
    {
        $user = User::factory()->create(); // Create User
        $data = ['status'=>'inactive'];
      
        $userRepository = new UserRepository(new User);

        // Change user status
        $userRepository->changeStatus($data, $user->id);

        $this->assertDatabaseHas(
            'users', 
            [
            'email' => $user->email,
            'status'=>'inactive'
            ]
        );
    }

    /**
     * Method testChangeStatus
     *
     * @return void
     */
    // public function testUserNotFound()
    // {
    //     $user = User::factory()->create(); // Create User
    //     $data = ['status'=>'inactive'];
    //     $response = $this->actingAs($user)->get('/login');
      
    //     $userRepository = new UserRepository(new User);

    //     $this->assertDatabaseHas(
    //         'users', 
    //         [
    //         'email' => $user->email,
    //         'status'=>'active'
    //         ]
    //     );

    //     // Change user status
    //     $userRepository->changeStatus($data, $user->id);

    //     $this->assertDatabaseHas(
    //         'users', 
    //         [
    //         'email' => $user->email,
    //         'status'=>'inactive'
    //         ]
    //     );
    // }
}
