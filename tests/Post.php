<?php declare(strict_types=1);

namespace ProtoneMedia\LaravelEloquentWhereNot\Tests;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeTitleIs($query, $title)
    {
        $query->where($query->qualifyColumn('title'), $title);
    }

    public function scopeTitleIsFoo($query)
    {
        $query->titleIs('foo');
    }

    public function scopeHasMoreCommentsThan($query, $value)
    {
        $query->has('comments', '>', $value);
    }

    public function scopeHasSixOrMoreComments($query)
    {
        $query->hasMoreCommentsThan(5);
    }

    public function scopeOnFrontPage($query)
    {
        $query->where('is_public', 1)
            ->where($query->qualifyColumn('votes'), '>', 100)
            ->has('comments', '>=', 20)
            ->whereHas('user', fn ($user) => $user->isAdmin())
            ->whereYear('published_at', date('Y'));
    }
}
