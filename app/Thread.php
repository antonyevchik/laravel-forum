<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use App\Filters\ThreadFilters;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Thread extends Model
{
    use RecordsActivity;


    protected $guarded=[];
    protected $with=['creator', 'channel'];
    protected $appends = ['isSubscribedTo'];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread){
            $thread->replies->each->delete();
        });

        static::created(function ($thread){
            $thread->update(['slug' => $thread->title]);
        });
    }

    /**
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this -> belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * @param array $reply
     * @return Model
     */
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);
        event(new ThreadReceivedNewReply($reply));

        return $reply;
    }

    /**
     * @param $query
     * @param $filters
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * @param null $userId
     * @return $this
     */
    public function subscribe($userId=null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);
        return $this;
    }

    /**
     * @param null $userId
     */
    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    /**
     * @return bool
     */
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    /**
     * @param $user
     * @return bool
     */
    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);
        return $this->updated_at > cache($key);
    }

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * @param $value
     */
    public function setSlugAttribute($value)
    {
        $slug = Str::slug($value);


        if (static::whereSlug($slug)->exists()) {
            $slug = "{$slug}-".$this->id;
        }

        $this->attributes['slug'] = $slug;
    }
}
