<?php declare(strict_types=1);

namespace ProtoneMedia\LaravelEloquentWhereNot\Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Collection;

class ReadmeExampleTest extends TestCase
{
    /** @test */
    public function it_can_fetch_the_posts_that_didnt_make_the_front_page()
    {
        $posts = new Collection;

        foreach (range(1, 10) as $i) {
            $user = User::create(['is_admin' => false]);

            $post = $posts[] = Post::create([
                'title'        => $i,
                'votes'        => 100,
                'is_public'    => false,
                'user_id'      => $user->id,
                'published_at' => now()->subYear(),
            ]);

            foreach (range(1, 19) as $i) {
                $post->comments()->create(['body' => 'ok']);
            }
        }

        $posts->skip(1)->each->update(['votes' => 101]);
        $posts->skip(2)->each->update(['is_public' => true]);
        $posts->skip(3)->each->update(['published_at' => now()]);
        $posts->skip(4)->each->update(['published_at' => now()]);
        $posts->skip(5)->each(function ($post) {
            $post->user->update(['is_admin' => true]);
        });

        $posts->skip(6)->each(function ($post) {
            $post->comments()->create(['body' => 'ok']);
        });

        $onFrontPage = Post::onFrontPage()->get();
        $this->assertCount(4, $onFrontPage);
        $this->assertEquals([7,8,9,10], $onFrontPage->map->title->all());

        $notOnFrontPage = Post::whereNot('onFrontPage')->get();
        $this->assertCount(6, $notOnFrontPage);
        $this->assertEquals([1,2,3,4,5,6], $notOnFrontPage->map->title->all());
    }
}
