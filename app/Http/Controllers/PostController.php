<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $path = Storage::disk('public')->put('/images', $data['image']);
        $data['image'] = $path;
        Post::create($data);
    }
}
