<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexBookRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexBookRequest $request): AnonymousResourceCollection
    {
        $books = Book::with('author')->filter($request->getFilters())->paginate(
            perPage: $request->getLimit(),
            page: $request->getOffset()
        );

        return BookResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request): BookResource
    {
        $book = Book::create($request->validated());

        return BookResource::make($book->load('author'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): BookResource
    {
        return BookResource::make($book->load('author'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book): BookResource
    {
        $book->update($request->validated());

        return BookResource::make($book->fresh()->load('author'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): Response
    {
        $book->delete();

        return response()->noContent();
    }
}
