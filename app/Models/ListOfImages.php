<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListOfImages extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'order',
        'gallery_id',
    ];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
