<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Title;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    public function testTitlesModelCount() {
        $title = new Title();
        $this->assertTrue(count($title->all()) === 6, 'It should have 6 titles');
    }

    public function testLastTitleShouldBeProfessor() {
        $title = new Title();
        $titles_array = $title->all();
        $this->assertEquals('Professor', array_pop($titles_array), 'Titles last element should be Professor');
    }
}
