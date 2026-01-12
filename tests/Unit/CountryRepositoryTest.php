<?php
namespace Tests\Unit;

use App\Models\Country;
use Tests\TestCase;
use App\Repositories\CountryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountryRepositoryTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Method testCreateCountry
     *
     * @return void
     */
    public function testCreateCountry() :void
    {
        $data = [
            'code' => 'IN',
            'name' => 'India',
            'phone_code' => "+91",
            'status'=>'active',
        ];

        $countryRepository = new CountryRepository(new Country());
        $countryRepository->createCountry($data);
        $this->assertDatabaseHas(
            'countries',
            [
            'name' => 'India',
            ]
        );
    }
    /**
     * Method testGetCountries
     *
     * @return void
     */
    public function testGetCountries() :void
    {
        $country = Country::factory()->create();

        $countryRepository = new CountryRepository(new Country);

        $countryInfo = $countryRepository->getCountry($country->id);

        $this->assertEquals($country['name'], $countryInfo->name);
    }

    /**
     * Method testChangeStatus
     *
     * @return void
     */
    public function testChangeStatus()
    {
        $country = Country::factory()->create();
        $data = ['status'=>'inactive'];

        $countryRepository = new CountryRepository(new Country);

        $countryRepository->changeStatus($data, $country->id);

        $this->assertDatabaseHas(
            'countries',
            [
            'name' => $country->name,
            'status'=>'inactive'
            ]
        );
    }
}
