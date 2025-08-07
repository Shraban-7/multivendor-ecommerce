<?php

namespace App\Http\Controllers\Seller;

use App\Models\SellerChat;
use Illuminate\Http\Request;
use App\Models\SellerChatMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\SellerChatMessageResource;

class SellerChatController extends Controller
{
    public function chatList()
    {
        $sellerId = auth('seller')->id();

        $chats = SellerChat::with(['user'])
            ->where('seller_id', $sellerId)
            ->with(['messages' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(1);
            }])
            ->orderByDesc(
                SellerChatMessage::select('created_at')
                    ->whereColumn('seller_chat_id', 'seller_chats.id')
                    ->orderByDesc('created_at')
                    ->limit(1)
            )
            ->get();

        return view('seller.chats.index', compact('chats'));
    }

    public function messages(Request $request)
    {
        $sellerId = auth('seller')->id();

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $chat = SellerChat::firstOrCreate([
            'seller_id' => $sellerId,
            'user_id' => $request->user_id,
        ]);

        $messages = $chat->messages()->orderBy('created_at')->get();

        return view('seller.chats.messages', compact('messages'));
    }

    public function sendMessage(Request $request)
    {
        $sellerId = auth('seller')->id();

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $chat = SellerChat::firstOrCreate([
            'seller_id' => $sellerId,
            'user_id' => $request->user_id,
        ]);

        $chat->messages()->create([
            'seller_id' => $sellerId,
            'user_id' => null,
            'message' => $request->message,
        ]);

        return redirect()->back();
    }
}
