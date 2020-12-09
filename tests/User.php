<?php declare(strict_types=1);

namespace ProtoneMedia\LaravelEloquentWhereNot\Tests;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function scopeIsAdmin($query)
    {
        $query->where('is_admin', 1);
    }
}
