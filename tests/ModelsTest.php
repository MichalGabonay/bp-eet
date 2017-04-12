<?php

use App\User;
use App\Model\Notes;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ModelsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function users_can_be_created()
    {
        // Given
        factory(User::class, 3)->create();

        // When
        $users = User::all();

        // Than
        $this->assertEquals(7, count($users));
        $this->assertCount(7, $users);

    }

    /** @test */
    function giving_good_type_of_notes(){

        factory(Notes::class, 3)->create();
        factory(Notes::class)->create(['type' => 1]);

        $notes = new Notes();

        $period_notes = $notes->getAllPeriod(1)->get();
        $sales_notes = $notes->getAllSale(1)->get();
        $sales_all = $notes->getAllFromCompany(1)->get();

        $this->assertCount(1, $period_notes);
        $this->assertCount(3, $sales_notes);
        $this->assertCount(4, $sales_all);

    }

    /** @test */
    function check_if_are_all_roles_seeded(){

        $roles = new \App\Model\Roles();

        $roles = $roles->getAll();

        $this->assertCount(5, $roles);
    }

}