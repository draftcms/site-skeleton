<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserATest2LoginTest extends TestCase
{
    /**
     * My test implementation
     */
    public function testLogRegisteredUserIn()
    {
        $this->visit('/');
        $this->visit('/login');
        $this->type('john@example.com', 'email');
        $this->type('password', 'password');
        $this->press('Login');
        $this->seePageIs('/home');
        $this->see('You are logged in!');
    }
}
