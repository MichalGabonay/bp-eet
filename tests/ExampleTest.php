<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function prihlasenie_bez_firmy()
    {
        $this->visit('/login')
            ->type('admin', 'username')
            ->type('Poklop12', 'password')
            ->press('Prihlásiť')
            ->seePageIs('/dashboard');
    }
//
//    /** @test */
//    public function prihlasenie_a_vytvorenie_firmy()
//    {
//        $this->visit('/login')
//            ->type('admin', 'username')
//            ->type('Poklop12', 'password')
//            ->press('Prihlásiť')
//            ->click('pridajte do systému novú spoločnosť.')
//            ->seePageIs('/companies/create');
//
//        $this->type('nazov', 'name')
//            ->press('Vytvorit')
//            ->see('Společnost bola úspešne vytvorená!');
//    }
}
