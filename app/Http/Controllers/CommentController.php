<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;

class CommentController extends Controller
{
    public function store(Gallery $gallery, StoreCommentRequest $request)
    {
        $data = $request->validated();
        $comment = $gallery->comments()->create($data);
        return response()->json($comment);
    }

    public function delete(Comment $comment)
    {
        $comment->delete();
        return response()->noContent();
    }
}
