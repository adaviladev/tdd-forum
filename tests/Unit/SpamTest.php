<?php

namespace Tests\Feature;

use App\Inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase 
{
    /** @test
     * @throws \Exception
     */
	public function it_checks_for_invalid_keywords()
	{
	    $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent'));

        $this->expectException('Exception');

        $spam->detect('Yahoo customer support');
	}

    /** @test
     * @throws \Exception
     */
	function it_checks_for_any_key_being_held_down()
	{
	    $spam = new Spam();

	    $this->expectException('Exception');

	    $spam->detect('Hello world aaaaaaaaa');
	}

}