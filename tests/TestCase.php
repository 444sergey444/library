<?php

namespace Tests;

use App\Models\Author;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker;

    public function createAuthor(array $attributes = [], int $count = 1): Collection|Author
    {
        $authors = Author::factory()->count($count)->create($attributes ?: [
            'name' => $this->faker->name,
        ]);

        return $count === 1 ? $authors->first() : $authors;
    }
}
