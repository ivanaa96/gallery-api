<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Gallery $gallery, StoreCommentRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $comment = $gallery->comments()->create([
            'body' => $data['body'],
            'user_id' => $user->id,
        ]);

        return response()->json($comment);
    }

    public function delete(Gallery $gallery, Comment $id)
    {
        $id->delete();
        return response()->noContent();
    }
}
