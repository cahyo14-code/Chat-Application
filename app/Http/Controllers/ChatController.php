<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Halaman utama chat
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        $conversations = Auth::user()->conversations()
            ->with(['users', 'lastMessage.user'])
            ->get();

        return view('chat.index', compact('users', 'conversations'));
    }

    // Buka percakapan private
    public function openPrivate(User $user)
    {
        // Cek apakah conversation sudah ada
        $conversation = Conversation::where('type', 'private')
            ->whereHas('users', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->whereHas('users', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->first();

        // Kalau belum ada, buat baru
        if (!$conversation) {
            $conversation = Conversation::create(['type' => 'private']);
            $conversation->users()->attach([Auth::id(), $user->id]);
        }

        return redirect()->route('chat.show', $conversation->id);
    }

    // Tampilkan conversation
    public function show(Conversation $conversation)
    {
        // Pastikan user adalah anggota conversation ini
        if (!$conversation->users->contains(Auth::id())) {
            abort(403);
        }

        $messages = $conversation->messages()->with('user')->get();
        $users = User::where('id', '!=', Auth::id())->get();
        $conversations = Auth::user()->conversations()
            ->with(['users', 'lastMessage.user'])
            ->get();

        return view('chat.index', compact('conversation', 'messages', 'users', 'conversations'));
    }

    // Kirim pesan
    public function sendMessage(Request $request, Conversation $conversation)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        // Pastikan user adalah anggota conversation ini
        if (!$conversation->users->contains(Auth::id())) {
            abort(403);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id'         => Auth::id(),
            'body'            => $request->body,
        ]);

        $message->load('user');

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json([
            'message' => $message,
            'status'  => 'success',
        ]);
    }

    // Buat group chat
    public function createGroup(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'user_ids' => 'required|array|min:1',
        ]);

        $conversation = Conversation::create([
            'name' => $request->name,
            'type' => 'group',
        ]);

        // Tambah anggota group + diri sendiri
        $members = array_merge($request->user_ids, [Auth::id()]);
        $conversation->users()->attach($members);

        return redirect()->route('chat.show', $conversation->id);
    }
}