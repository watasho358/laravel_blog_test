<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    /** @test user */
    function userリレーションを返す()
    {
        $blog = Blog::factory()->create();

        // $blog->userがUserクラスのインスタンスかどうかをチェック
        $this->assertInstanceOf(User::class, $blog->user);
        
    }

    /** @test comments */
    function commentsリレーションを返す()
    {
        $blog = Blog::factory()->create();

        $this->assertInstanceOf(Collection::class, $blog->comments);
    }

    /** @test scopeOnlyOpen */
    function ブログの公開・非公開のscope()
    {
        $blog1 = Blog::factory()->create([
            'status' => Blog::CLOSED,
            'title' => 'ブログA',
        ]);
        $blog2 = Blog::factory()->create(['title' => 'ブログB']);
        $blog3 = Blog::factory()->create(['title' => 'ブログC']);

        $blogs = Blog::onlyOpen()->get();

        $this->assertFalse($blogs->contains($blog1));
        $this->assertTrue($blogs->contains($blog2));
        $this->assertTrue($blogs->contains($blog3));
    }
}
