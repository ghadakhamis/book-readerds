<?php

namespace Tests;

use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    protected $faker;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        $this->createFaker();
    }

    public function createFaker() 
    {
        $this->faker = Faker::create();
    }
}
