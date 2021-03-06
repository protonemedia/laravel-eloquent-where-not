<?php declare(strict_types=1);

namespace ProtoneMedia\LaravelEloquentWhereNot\Tests;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function comments()
    {
        return $this->hasMany(Comment::class, 'parent_comment_id');
    }
}
