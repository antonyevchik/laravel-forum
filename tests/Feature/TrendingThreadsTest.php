<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function it_increments_a_threads_score_each_time_it_is_read()
    {
        $thread = create('App\Thread');

        $this->call('GET', $thread->path());

        $d = Redis::zrevrange('trending_threads', 0, -1);

        dd($d);
    }
}
