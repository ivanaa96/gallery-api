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
        $galleries = Gallery::with('comments', 'user', 'images')->orderBy('id', 'desc')->paginate(10);
        return response()->json($galleries);
    }

    public function show(Gallery $id)
    {
        $data = $id->load(['comments', 'images', 'user']);
        return response()->json($data);
    }

    public function store(StoreGalleryRequest $request)
    {
        $image_urls = $request->get('image_urls', []);

        $gallery = Auth::user()->galleries()->create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        $order = 1;
        foreach ($image_urls as $image) {
            $gallery->images()->create([
                'url' => $image['url'],
                'order' => $order
            ]);
            $order++;
        }

        $gallery->load('images');
        return response()->json($gallery);
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
