<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function prihlasenie_bez_firmy()
    {
        $this->visit('/login')
            ->type('test1', 'username')
            ->type('Poklop12', 'password')
            ->press('Prihlásiť')
            ->see('Nemáte pridelenú žiadnu spoločnosť!');
    }

    /** @test */
    public function prihlasenie_a_vytvorenie_firmy()
    {
        $this->visit('/login')
            ->type('test1', 'username')
            ->type('Poklop12', 'password')
            ->press('Prihlásiť')
            ->click('pridajte do systému novú spoločnosť.')
            ->seePageIs('/companies/create');

        $this->type('nazov', 'name')
            ->press('Vytvorit')
            ->see('Společnost bola úspešne vytvorená!');
    }

    /** @test */
    public function kontrola_certifikatu()
    {
        $this->visit('/login')
            ->type('admin', 'username')
            ->type('Poklop12', 'password')
            ->press('Prihlásiť');

        $this->visit('/companies/1/detail')
            ->see('certifikát je platný');
    }

    /** @test */
    public function vytvorenie_uzivatela()
    {
        $this->visit('/login')
            ->type('admin', 'username')
            ->type('Poklop12', 'password')
            ->press('Prihlásiť');

        $response = $this->call('POST', '/users/store', ['name' => 'Taylor', 'username' => 'Taylor', 'email' => 'Taylor@test.test', 'password' => 'Taylor',]);
        $this->assertEquals(302, $response->status());
    }

}
