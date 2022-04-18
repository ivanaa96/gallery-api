<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\ListOfImages;
use App\Http\Requests\StoreGalleryRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class GalleryController extends Controller
{
    public function index()
    {
        // $galleries = Gallery::all();
        $galleries = Gallery::with('comments', 'user')->paginate(10);
        return response()->json($galleries);
    }

    public function show(Gallery $id)
    {
        $data = $id->load(['comments']);
        return response()->json($data);
    }

    public function store(StoreGalleryRequest $request)
    {
        $gallery = Auth::user()->galleries()->create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
        ]);

        $list =  ListOfImages::create([
            'gallery_id' => $gallery->id,
        ]);

        foreach ($request->url as $image) {
            $list->create(['url' => $image]);
        }

        $result = array_merge($gallery, $list);
        return response()->json($result);
    }

    public function update(StoreGalleryRequest $request, Gallery $gallery)
    {
        $data = $request->validated();
        $gallery->update($data);
        return response()->json($gallery);
    }

    public function delete(Gallery $id)
    {
        $id->delete();
        return response()->noContent();
    }

    public function getMyGalleries()
    {
        $galleries = Auth::user()->galleries()->get();
        return response()->json($galleries);
    }
}
