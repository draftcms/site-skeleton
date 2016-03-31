<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserATest4ContactFormTest extends TestCase
{
    /**
     * A test for successful contact form submission for a user who's logged in.
     *
     * @return void
     */
    public function testContactFormLoggedIn()
    {
        $this->visit('/contact');
        $this->seePageIs('/contact');

        /* fill out form and submit */
        $this->type('John Doe', 'name');
        $this->type('john@example.com', 'email');
        $this->type('Hello. This is a test message.', 'notes');
        $this->press('Submit');

        /* make sure form is returned with no input values (i.e., successful submission) */
        $this->seePageIs('/contact');

        //$this->assertTrue(empty($_GET['email']));
        //$this->assertTrue(empty($_GET['notes']));
        //$this->assertEquals($_GET['notes'], '');

        /* make sure database entries now exists */
        $this->seeInDatabase(
        	'contact_forms', 
        	[
        		'name' 		=> 'John Doe',
        		'email' 	=> 'john@example.com',
        		'notes'		=> 'Hello. This is a test message.',
        	]
        );

        $this->seeInDatabase(
        	'contact_form_responses', 
        	[
        		'name' 		=> 'John Doe',
        		'email' 	=> 'john@example.com',
        		'notes'		=> 'Hello. This is a test message.',
        	]
        );
    }

    /**
     * A test for unsuccessful contact form submission for a user who's logged in.
     *
     * @return void
     */

}
