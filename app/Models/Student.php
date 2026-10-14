<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'address', 'birthdate', 'license_status',
    ];

    protected function casts(): array
    {
        return ['birthdate' => 'date'];
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}
