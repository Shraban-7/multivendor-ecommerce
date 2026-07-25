<?php

namespace App\Http\Controllers\Api;

use App\Domain\Vendor\Models\SellerChat;
use App\Http\Controllers\Controller;
use App\Http\Resources\SellerChatMessageResource;
use Illuminate\Http\Request;

class SellerChatController extends Controller
{
    public function messages(Request $request)
    {
        $userId = auth()->id();

        $chat = SellerChat::firstOrCreate([
            'seller_id' => $request->seller_id,
            'user_id' => $userId,
        ]);

        $messages = $chat->messages()->orderBy('created_at')->get();

        return apiResponse(SellerChatMessageResource::collection($messages));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:sellers,id',
            'message' => 'required|string',
        ]);

        $userId = auth()->id();

        $chat = SellerChat::firstOrCreate([
            'seller_id' => $request->seller_id,
            'user_id' => $userId,
        ]);

        $chat->messages()->create([
            'seller_id' => null,
            'user_id' => $userId,
            'message' => $request->message,
        ]);

        return successResponse('Message send successfully');
    }
}
