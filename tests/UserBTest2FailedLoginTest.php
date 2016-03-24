<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserBTest2FailedLoginTest extends TestCase
{
    /**
     * Test for a malformed email, no atmark
     */
    public function testFailedLoginNoAtmark()
    {
        $this->visit('/login');
        $this->type('guy', 'email');
        $this->type('123456', 'password');
        $this->press('Login');
        $this->seePageIs('/login');
    }

    /**
     * Test for a malformed email address, missing TLD
     */
    public function testFailedLoginBadEmail()
    {
        $this->visit('/login');
        $this->type('guy@example', 'email');
        $this->type('123456', 'password');
        $this->press('Login');
        $this->seePageIs('/login');
        $this->see('These credentials do not match our records.');
    }

    /**
     * Test for no password.
     */
    public function testFailedLoginNoPassword()
    {
        $this->visit('/login');
        $this->type('guy@example', 'email');
        $this->press('Login');
        $this->seePageIs('/login');
        $this->see('The password field is required.');
    }
}
