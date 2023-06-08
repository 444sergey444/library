<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_index_returns_data_in_valid_format()
    {
        $this->createAuthor(count: 5);

        $this->json('get', 'api/authors')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                    ]
                ]
            ]);
    }

    public function test_author_is_created_successfully() {

        $payload = [
            'name' => $this->faker->name,
        ];

        $this->json('post', 'api/authors', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                ]
            ]);

        $this->assertDatabaseHas('authors', $payload);
    }

    public function test_update_author_returns_correct_data() {
        $author = $this->createAuthor();

        $payload = [
            'name' => $this->faker->name,
        ];

        $this->json('put', "api/authors/$author->id", $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(
                [
                    'data' => [
                        'id' => $author->id,
                        'name' => $payload['name']
                    ]
                ]
            );
    }

    public function test_author_is_destroyed() {
        $authorData = [
            'name' => $this->faker->name,
        ];

        $author = $this->createAuthor($authorData);

        $this->json('delete', "api/authors/$author->id")
            ->assertNoContent();

        $this->assertDatabaseMissing('authors', $authorData);
    }
}
