<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['conversation_id', 'user_id', 'body', 'read_at'];

    // Pesan dikirim oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Pesan masuk ke satu conversation
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}