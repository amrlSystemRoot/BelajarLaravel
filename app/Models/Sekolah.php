<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    protected $table = 'sekolah';

    protected $fillable = [
        'user_id',
        'prov',
        'kota',
        'jurusan', 
        'nim', 
        'nama',
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
