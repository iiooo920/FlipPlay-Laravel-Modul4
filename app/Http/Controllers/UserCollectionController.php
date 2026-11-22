<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCollectionRequest;
use App\Http\Resources\UserResource;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/** Custom action belongsToMany: tambah/hapus media koleksi pengguna. */
class UserCollectionController extends Controller
{
    /** GET /api/users/{user}/collection */
    public function index(User $user): UserResource
    {
        return new UserResource($user->load('media'));
    }

    /** POST /api/users/{user}/collection body {"media_id": 2} */
    public function store(AddToCollectionRequest $request, User $user): JsonResponse
    {
        $mediaId = $request->validated()['media_id'];

        if ($user->media()->where('media.id', $mediaId)->exists()) {
            return response()->json(['message' => 'Media sudah ada di koleksi pengguna.'], 409);
        }

        $user->media()->attach($mediaId); // perubahan relasi pada pivot media_user
        return (new UserResource($user->load('media')))
            ->additional(['message' => 'Media berhasil ditambahkan ke koleksi.'])
            ->response()->setStatusCode(201);
    }

    /** DELETE /api/users/{user}/collection/{media} */
    public function destroy(User $user, Media $media): Response|JsonResponse
    {
        if (! $user->media()->where('media.id', $media->id)->exists()) {
            return response()->json(['message' => 'Media tidak ada di koleksi pengguna.'], 409);
        }

        $user->media()->detach($media->id); // perubahan relasi pada pivot media_user
        return response()->noContent(); // 204 No Content
    }
}
