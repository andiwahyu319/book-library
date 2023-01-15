<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LendDetail extends Model
{
    use HasFactory;

    protected $fillable = ["lend_id", "book_id", "qty"];
}
