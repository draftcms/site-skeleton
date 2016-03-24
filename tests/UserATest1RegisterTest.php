<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserATest1RegisterTest extends TestCase
{
  
    /**
     * My test implementation
     */
    public function testRegisterNewUser()
    {
        $this->visit('/register');
        $this->type('John Doe', 'name');
        $this->type('john@example.com', 'email');
        $this->type('password', 'password');
        $this->type('password', 'password_confirmation');
        $this->press('Register');
        $this->seePageIs('/home');
        $this->see('You are logged in!');
    }
}
