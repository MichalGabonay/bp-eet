<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function all_main_sites_have_authentication(){
        $this->visit('/dashboard')
            ->see('Prihlásenie');
        $this->visit('/users')
            ->see('Prihlásenie');
        $this->visit('/companies')
            ->see('Prihlásenie');
        $this->visit('/sales')
            ->see('Prihlásenie');
        $this->visit('/notes')
            ->see('Prihlásenie');
        $this->visit('/import')
            ->see('Prihlásenie');
        $this->visit('/export')
            ->see('Prihlásenie');
    }

    /** @test */
    public function IAmLoggedIn()
    {
        $this->visit('/login')
            ->type('admin', 'username')
            ->type('Poklop12', 'password')
            ->press('Prihlásiť')
            ->seePageIs('/dashboard');
    }
}
