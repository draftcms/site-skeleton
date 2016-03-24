<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserBTest1FailedRegisterTest extends TestCase
{
    /**
     * Test for a malformed email address, missing TLD
     */
    public function testFailedResisterNewUserBadEmail()
    {
        $this->visit('/register');
        $this->type('Guy', 'name');
        $this->type('guy21@example', 'email');
        $this->type('123456', 'password');
        $this->type('123456', 'password_confirmation');
        $this->press('Register');
        $this->seePageIs('/register');
        $this->see('The email must be a valid email address.');
    }

    /**
     * Test for a malformed email, no atmark
     */
    public function testFailedResisterNewUserNoAtmark()
    {
        $this->visit('/register');
        $this->type('Guy', 'name');
        $this->type('guy21', 'email');
        $this->type('123456', 'password');
        $this->type('123456', 'password_confirmation');
        $this->press('Register');
        $this->seePageIs('/register');
        // this only returns a JavaScript message currently, no text to see
    }

    /**
     * Test for a malformed email address, missing TLD
     */
    public function testFailedResisterNewUserBlankEmail()
    {
        $this->visit('/register');
        $this->type('Guy', 'name');
        $this->type('123456', 'password');
        $this->type('123456', 'password_confirmation');
        $this->press('Register');
        $this->seePageIs('/register');
        $this->see('The email field is required.');
    }

     /**
     * Test for no user name input
     */
    public function testFailedResisterNewUserBlankName()
    {
        $this->visit('/register');
        $this->type('guy21@example.com', 'email');
        $this->type('123456', 'password');
        $this->type('123456', 'password_confirmation');
        $this->press('Register');
        $this->seePageIs('/register');
        $this->see('The name field is required.');
    }

    /**
     * Test for short password
     */
    public function testFailedResisterNewUserShortPasword()
    {
        $this->visit('/register');
        $this->type('Guy', 'name');
        $this->type('guy@example.com', 'email');
        $this->type('12345', 'password');
        $this->type('12345', 'password_confirmation');
        $this->press('Register');
        $this->seePageIs('/register');
        $this->see('The password must be at least 6 characters.');
    }

    /**
     * Test for missing password
     */
    public function testFailedResisterNewUserMissingPasword()
    {
        $this->visit('/register');
        $this->type('Guy', 'name');
        $this->type('guy@example.com', 'email');
        $this->press('Register');
        $this->seePageIs('/register');
        $this->see('The password field is required.');
    }

    /**
     * Test for password conformation mismatch
     */
    public function testFailedResisterNewUserPaswordMismatch()
    {
        $this->visit('/register');
        $this->type('Guy', 'name');
        $this->type('guy@example.com', 'email');
        $this->type('123456', 'password');
        $this->type('123457', 'password_confirmation');
        $this->press('Register');
        $this->seePageIs('/register');
        $this->see('The password confirmation does not match.');
    }

}
