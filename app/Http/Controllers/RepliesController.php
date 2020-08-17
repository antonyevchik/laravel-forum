<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Reply;
use Illuminate\Http\Request;

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
     * @param  $channelId
     * @param \App\Thread $thread
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store($channelId, Thread $thread)
    {
        try {
            $this->validate(request(),['body'=>'required|spamfree']);

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
        } catch (\Exception $e) {
            return response(
                'Sorry, you reply could not be saved at this time.', 422
            );
        }

        return $reply->load('owner');
    }



    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            $this->validate(request(),['body'=>'required|spamfree']);

            $reply->update(request(['body']));
        } catch (\Exception $e) {
            return response(
                'Sorry, you reply could not be saved at this time.', 422
            );
        }


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
