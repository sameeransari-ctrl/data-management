<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Models\{Country, State};
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StateControllerTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic unit test example.
     * 
     * @return void
     */
    public function testExample(): void
    {
        $this->assertTrue(true);
    }
    
    /**
     * Method testGetCountryIdByStateList
     *
     * @return void
     */
    public function testGetCountryIdByStateList()
    {
        $country = Country::factory()->create();
        $state = [
            'name' => 'Test State',
            'country_id' => $country->id,
        ];
        State::factory()->create($state);

        $response = $this->get('/api/v1/states?country_id='.$country->id);
        $response->assertStatus(200);
        $response->assertSee('Test State');
    }
}
