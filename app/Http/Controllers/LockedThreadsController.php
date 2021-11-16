<?php

namespace App\Http\Controllers;

use App\Thread;

class LockedThreadsController extends Controller
{

    /**
     * @param Thread $thread
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|void
     */
    public function store(Thread $thread)
    {
        $thread->lock();
    }
}
