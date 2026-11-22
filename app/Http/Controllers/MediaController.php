<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use App\Support\AuraGenres;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class MediaController extends Controller
{
    /** GET /api/media?search=naruto&type=Anime&aura=High%20Energy */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->validate([
            'search' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', Rule::in(['Anime', 'Manga', 'Drama'])],
            'aura' => ['sometimes', Rule::in(array_keys(AuraGenres::MAP))],
        ]);

        $query = Media::query()->with('users:id,name'); // eager loading (tanpa N+1)

        if (isset($filters['search']) && trim($filters['search']) !== '') {
            $query->where('title', 'like', '%' . trim($filters['search']) . '%');
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['aura'])) {
            $genres = AuraGenres::MAP[$filters['aura']];
            $query->where(function ($q) use ($genres) {
                foreach ($genres as $genre) {
                    $q->orWhereJsonContains('genres', $genre);
                }
            });
        }

        return MediaResource::collection($query->orderBy('id')->paginate(10));
    }

    /** GET /api/media/{media} */
    public function show(Media $media): MediaResource
    {
        return new MediaResource($media->load('users:id,name'));
    }

    /** POST /api/media */
    public function store(StoreMediaRequest $request)
    {
        $media = Media::create($request->validated());
        return (new MediaResource($media->load('users:id,name')))
            ->response()->setStatusCode(201);
    }

    /** PATCH /api/media/{media} */
    public function update(UpdateMediaRequest $request, Media $media): MediaResource
    {
        $media->update($request->validated());
        return new MediaResource($media->refresh()->load('users:id,name'));
    }

    /** DELETE /api/media/{media} */
    public function destroy(Media $media): Response
    {
        $media->delete(); // FK cascade juga menghapus relasi di media_user
        return response()->noContent(); // 204 No Content
    }
}
