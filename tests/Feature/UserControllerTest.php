<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\UserController;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class UserControllerTest extends TestCase
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
     * Method testIndexFunction
     *
     * @return void
     */
    public function testShowFunction()
    {
       
        $user =Mockery::mock(User::class)->makePartial();
        
        $this->mock(
            UserRepository::class, function (MockInterface $mock) use ($user) {
                $mock->shouldReceive('getUserDetail')->with($user->id)->once();
            }
        );

        //$response = $this->get(route('admin.user.show', ['user'=>$user->id]));
        //$response->assertViewHas('admin.user.user-details');
        
        app(UserController::class)->show($user->id);
        
    }
    
    /**
     * Method testChangeStatus
     *
     * @return void
     */
    public function testChangeStatusForUser()
    {
        $user = User::factory()->create();
       
        $data = ['id'=>$user->id, 'status'=>'inactive'];
        
        $this->mock(
            UserRepository::class, function (MockInterface $mock) use ($data) {
                $mock->shouldReceive('changeStatus')->with($data, $data['id'])->once();
            }
        );
       
        $request = Request::create('/user/changeStatus', 'POST', $data);
       
        app(UserController::class)->changeStatus($request);
    }
}
