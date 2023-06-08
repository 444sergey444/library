<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Tests\TestCase;

class BookTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_index_returns_data_in_valid_format()
    {
        $author = $this->createAuthor();
        $this->createBook(authorId: $author->id, count: 5);

        $this->json('get', 'api/books')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'author' => [
                                'id',
                                'name'
                            ],
                            'description'
                        ]
                    ]
                ]
            );
    }

    public function test_book_is_created_successfully()
    {
        $author = $this->createAuthor();;

        $payload = [
            'title' => $this->faker->title,
            'author_id' => $author->id,
            'description' => $this->faker->text
        ];

        $this->json('post', 'api/books', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'title',
                        'author' => [
                            'id',
                            'name'
                        ],
                        'description'
                    ]
                ]
            );

        $this->assertDatabaseHas('books', $payload);
    }

    public function test_book_is_shown_correctly() {
        $author = $this->createAuthor();
        $book = $this->createBook($author->id);

        $this->json('get', "api/books/$book->id")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => [
                        'id' => $author->id,
                        'name' => $author->name
                    ],
                    'description' => $book->description
                ]
            ]);
    }

    public function test_update_book_returns_correct_data() {
        $author = $this->createAuthor();
        $book = $this->createBook($author->id);;

        $payload = [
            'title' => $this->faker->title,
            'author_id' => $author->id,
            'description' => $this->faker->text
        ];

        $this->json('put', "api/books/$book->id", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([
                'data' => [
                    'id' => $book->id,
                    'title' => $payload['title'],
                    'author' => [
                        'id' => $author->id,
                        'name' => $author->name
                    ],
                    'description' => $payload['description']
                ]
            ]);
    }

    public function test_book_is_destroyed() {
        $author = $this->createAuthor();

        $bookData = [
            'title' => $this->faker->title,
            'description' => $this->faker->text
        ];

        $book = $this->createBook($author->id, $bookData);

        $this->json('delete', "api/books/$book->id")
            ->assertNoContent();

        $this->assertDatabaseMissing('books', $bookData);
    }

    public function createBook(int $authorId, array $attributes = [], int $count = 1): Collection|Book
    {
        if ($attributes) {
            $attributes['author_id'] = $authorId;
        }

        $books = Book::factory()->count($count)->create($attributes ?: [
            'title' => $this->faker->title,
            'author_id' => $authorId,
            'description' => $this->faker->text
        ]);

        return $count === 1 ? $books->first() : $books;
    }
}
