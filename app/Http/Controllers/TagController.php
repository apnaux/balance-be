<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tags = Tag::select([
            'id as value',
            'name as label',
            'icon'
        ])
        ->where(function ($query) {
            $query->where('user_id', Auth::id())
                ->orWhereNull('user_id');
        })
        ->orderBy('user_id')
        ->get();

        return response()->json($tags);
    }

    public function create(Request $request)
    {

    }
}
