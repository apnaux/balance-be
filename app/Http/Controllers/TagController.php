<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function list(Request $request)
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
        Tag::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'icon' => $request->icon
        ]);

        return response()->json([
            'message' => 'Tag created successfully.'
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:tags,id',
            'name' => 'string|max:50|required',
            'icon' => 'string|max:50|required'
        ]);

        Tag::where('id', $request->id)
            ->where('user_id', Auth::id())
            ->update([
                'name' => $request->name,
                'icon' => $request->icon
            ]);

        return response()->json([
            'message' => 'Tag updated successfully.'
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:tags,id'
        ]);

        Tag::where('id', $request->id)
            ->where('user_id', Auth::id())
            ->delete();

        return response()->json([
            'message' => 'Tag deleted successfully.'
        ]);
    }
}
