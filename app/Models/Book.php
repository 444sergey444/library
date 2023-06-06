<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['author_id', 'title', 'description'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        foreach ($filters as $name => $filter) {
            if ($name === 'title') {
                $query->where('title', $filter);
            }

            if ($name === 'author_name') {
                $query->whereRelation('author', 'name', $filter);
            }

            if ($name === 'author_id') {
                $query->where('author_id', $filter);
            }

            if ($name === 'description') {
                $query->where('description', 'like', "%$filter%");
            }
        }
    }
}
