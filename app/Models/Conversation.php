<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['name', 'type'];

    // Conversation punya banyak anggota (users)
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // Conversation punya banyak pesan
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Ambil pesan terakhir
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}