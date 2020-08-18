<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWhereMentioned;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyMentionedUsers
{

    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        $user = User::whereIn('name', $event->reply->mentionedUsers())
            ->get()
            ->each(function($user) use ($event) {
                $user->notify(new YouWhereMentioned($event->reply));
            });
    }
}
