<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexAuthorRequest;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexAuthorRequest $request): AnonymousResourceCollection
    {
        return AuthorResource::collection(Author::paginate(
            perPage: $request->getLimit(),
            page: $request->getOffset()
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request): Response
    {
        Author::create($request->validated());

        return response(status: 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author): AuthorResource
    {
        return AuthorResource::make($author);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, Author $author): AuthorResource
    {
        $author->update($request->validated());

        return AuthorResource::make($author->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author): Response
    {
        $author->delete();

        return response()->noContent();
    }
}
