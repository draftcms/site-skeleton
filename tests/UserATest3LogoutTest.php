<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserATest3LogoutTest extends TestCase
{

    /**
     * Test for logging out a user who is logged in.
     */
    public function testUserLogout()
    {
        $this->visit('/logout');
        $this->seePageIs('/');
    }

}
