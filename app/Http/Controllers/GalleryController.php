<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\User;
use App\Http\Requests\StoreGalleryRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\UpdateGalleryRequest;
use Illuminate\Support\Facades\DB;


class GalleryController extends Controller
{
    public function index(Request $request)
    {
        // /api/galleries?filter=asdf
        $filter = $request->query('filter');
        $query = Gallery::with('comments', 'user', 'images')->orderBy('id', 'desc');

        if ($filter) {
            $query->where('title', 'like', "%$filter%")->orWhere('description', 'like', "%$filter%")->get();
        }

        $galleries = $query->paginate(10);
        return response()->json($galleries);
    }

    public function show(Gallery $id)
    {
        $data = $id->load(['comments.user', 'images', 'user']);
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

    public function update(UpdateGalleryRequest $request, Gallery $gallery)
    {
        $image_urls = $request->get('image_urls', []);
        $gallery->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        foreach ($image_urls as $image) {
            $gallery->images()->update([
                'url' => $image['url'],
            ]);
        }

        return response()->json($gallery);
    }

    public function delete(Gallery $gallery)
    {
        $gallery->delete();
        return response()->noContent();
    }

    public function getMyGalleries()
    {
        $galleries = Auth::user()->load(['galleries.images']);
        return response()->json($galleries);
    }

    public function getAuthorsGalleries($id)
    {
        $user = User::findOrFail($id);
        $user->load(['galleries.images']);
        return response()->json($user);
    }

    public function getMyProfile()
    {
        $activeUser = Auth::user();
        return response()->json($activeUser);
    }
}
