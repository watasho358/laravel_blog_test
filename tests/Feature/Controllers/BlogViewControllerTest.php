<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogViewControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test Index */
    function ブログのトップページを開ける()
    {
        // ブログを3件登録
        $blog1 = Blog::factory()->hasComments(1)->create();
        $blog2 = Blog::factory()->hasComments(3)->create();
        $blog3 = Blog::factory()->hasComments(2)->create();

        $this->get('/')
             ->assertOK()
             ->assertSee($blog1->title)
             ->assertSee($blog2->title)
             ->assertSee($blog3->title)
             ->assertSee($blog1->user->name)
             ->assertSee($blog2->user->name)
             ->assertSee($blog3->user->name)
             ->assertSeeInOrder([$blog2->title, $blog3->title, $blog1->title]);
    }

    /** @test index */
    function ブログの一覧、非公開のブログは表示されない()
    { 
        Blog::factory()->create([
            'status' => Blog::CLOSED,
            'title' => 'ブログA',
        ]);
        Blog::factory()->create(['title' => 'ブログB']);
        Blog::factory()->create(['title' => 'ブログC']);

        $this->get('/')
             ->assertOK()
             ->assertDontSee('ブログA')
             ->assertSee('ブログB')
             ->assertSee('ブログC');
    }

    /** @test */
    function factoryの観察()
    {
        $blog = Blog::factory()->create();

        dump($blog->toArray());

        dump(User::get()->toArray());

        $this->assertTrue(true);
    }
}
