<?php

namespace Tests\Feature;

use App\Services\Youtube\YoutubeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $service = new YoutubeService();
        $this->assertNotEmpty($service);
    }
}
