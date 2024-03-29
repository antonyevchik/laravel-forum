<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Favorite;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Store a new favorite in the database.
     *
     * @param  Reply $reply
     */
    public function store(Reply $reply)
    {
        $reply->favorite();
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();
    }
}
