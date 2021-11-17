@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
    <script>
        window.thread = <?= json_encode($thread); ?>
    </script>
@endsection

@section('content')
    <thread-view :data-replies-count="{{$thread->replies_count}}" :data-locked="{{ $thread->locked }}" inline-template>
        <div class="container ">
            <div class="row mt-4">
                <div class="col-md-8">
                    <div class="card mb-4" >
                        <div class="card-header">
                            <div class="level">
                                <img src="{{asset($thread->creator->avatar_path)}}" alt="{{ $thread->creator->name }}" width="3%" height="3%" class="mr-1">
                                <span class="flex">
                                    <a href="{{route('profile', $thread->creator)}}"> {{$thread->creator->name}}</a> posted:
                                    {{$thread->title}}
                                </span>
                                @can('update',$thread)
                                    <form action="{{$thread->path()}}" method="POST">
                                        {{csrf_field()}}
                                        {{method_field("DELETE")}}
                                        <button type="submit" class="btn btn-link">
                                            Delete Thread
                                        </button>
                                    </form>
                                @endcan

                            </div>

                        </div>

                        <div class="card-body">
                                   {{$thread->body}}
                        </div>
                    </div>

                    <replies @added="repliesCount++" @removed="repliesCount--"></replies>

                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p>
                                This thread was published {{$thread->created_at->diffForHumans()}} by
                                <a href="#">{{$thread->creator->name}}</a>, and currently
                                has <span v-text="repliesCount"></span> {{ Str::plural('comment',$thread->replies_count)  }}.
                            </p>

                            <p>
                                <subscribe-button :active="{{json_encode($thread->isSubscribedTo)}}" v-if="signedIn"></subscribe-button>

                                <button class="btn btn-outline-primary" v-if="authorize('isAdmin') && ! locked" @click="locked = true">Lock</button>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection

