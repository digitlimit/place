<?php

use Tests\CreatesApplication;
use Illuminate\Foundation\Testing\TestCase;
use Place;

class PlaceTest extends TestCase
{
    use CreatesApplication;

    /**
     * Test Place text Search
     *
     * @test
     * @group test_place
     * @group test_place_text_search
     * @return void
     */
    public function test_place_text_search()
    {
        $places = Place::textSearch('gas stations in California')
            ->toArray();

        $this->assertIsArray($places);

        $this->assertArrayHasKey('name', $places[0]);
        $this->assertArrayHasKey('brands', $places[0]);
        $this->assertArrayHasKey('website', $places[0]);
        $this->assertArrayHasKey('formatted_address', $places[0]);
        $this->assertArrayHasKey('lat', $places[0]);
        $this->assertArrayHasKey('lng', $places[0]);
        $this->assertArrayHasKey('opening_hours', $places[0]);
        $this->assertArrayHasKey('photos', $places[0]);
        $this->assertArrayHasKey('rating', $places[0]);
        $this->assertArrayHasKey('user_ratings_total', $places[0]);
        $this->assertArrayHasKey('types', $places[0]);

        $this->assertIsArray($places[0]['types']);
        $this->assertIsArray($places[0]['photos']);
        $this->assertIsArray($places[0]['brands']);
    }

    /**
     * Test Place text Search
     *
     * @test
     * @group test_place
     * @group test_place_get_next_page
     * @return void
     */
    public function test_place_get_next_page()
    {
        $place = Place::textSearch('gas stations in California');

        $place->toArray();

        $this->assertTrue($place->hasNextPage());

        sleep(4);

        $places = $place->getNextPage()->toArray();

        $this->assertIsArray($places);

        $this->assertArrayHasKey('name', $places[0]);
        $this->assertArrayHasKey('brands', $places[0]);
        $this->assertArrayHasKey('website', $places[0]);
        $this->assertArrayHasKey('formatted_address', $places[0]);
        $this->assertArrayHasKey('lat', $places[0]);
        $this->assertArrayHasKey('lng', $places[0]);
        $this->assertArrayHasKey('opening_hours', $places[0]);
        $this->assertArrayHasKey('photos', $places[0]);
        $this->assertArrayHasKey('rating', $places[0]);
        $this->assertArrayHasKey('user_ratings_total', $places[0]);
        $this->assertArrayHasKey('types', $places[0]);

        $this->assertIsArray($places[0]['types']);
        $this->assertIsArray($places[0]['photos']);
        $this->assertIsArray($places[0]['brands']);
    }
}