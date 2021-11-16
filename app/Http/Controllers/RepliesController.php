<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Notifications\YouWhereMentioned;
use App\Thread;
use App\Reply;
use App\User;


class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }


    /**
     * Persist a new reply.
     *
     * @param integer $channelId
     * @param Thread $thread
     * @param CreatePostRequest $form
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
        try {
            return $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ])->load('owner');
        } catch (\Exception $e) {
            return response('Locked', 422);
        }
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

            $this->validate(request(),['body'=>'required|spamfree']);

            $reply->update(request(['body']));
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();
        if(request()->expectsJson()){
            return response(['status' => 'Reply deleted']);
        }
        return back();
    }

}
