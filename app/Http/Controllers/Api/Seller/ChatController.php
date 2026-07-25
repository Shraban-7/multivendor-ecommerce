<?php

namespace App\Http\Controllers\Api\Seller;

use App\Domain\Vendor\Models\SellerChat;
use App\Domain\Vendor\Models\SellerChatMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function chatList()
    {
        $chats = SellerChat::where('seller_id', Auth::id())
            ->with(['user:id,name,image', 'latestMessage'])
            ->latest('updated_at')
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'user' => [
                    'id' => $c->user?->id,
                    'name' => $c->user?->name,
                    'avatar' => $c->user?->avatar,
                ],
                'last_message' => $c->latestMessage?->message,
                'last_message_time' => $c->latestMessage?->created_at,
                'unread_count' => $c->messages()->where('is_read', false)->whereNotNull('user_id')->count(),
            ]);

        return apiResponse($chats);
    }

    public function messages(Request $request)
    {
        $validator = validateRequest($request, [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $chat = SellerChat::where('seller_id', Auth::id())
            ->where('user_id', $request->user_id)
            ->firstOrFail();

        $chat->messages()->where('is_read', false)->whereNotNull('user_id')->update(['is_read' => true]);

        $messages = $chat->messages()->orderBy('created_at')->paginate(50);

        return apiResourceResponse($messages->through(fn ($m) => [
            'id' => $m->id,
            'message' => $m->message,
            'from_seller' => ! is_null($m->seller_id),
            'is_read' => (bool) $m->is_read,
            'created_at' => $m->created_at,
        ]));
    }

    public function sendMessage(Request $request)
    {
        $validator = validateRequest($request, [
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $chat = SellerChat::firstOrCreate([
            'seller_id' => Auth::id(),
            'user_id' => $request->user_id,
        ]);

        $chat->messages()->create([
            'seller_id' => Auth::id(),
            'user_id' => null,
            'message' => $request->message,
        ]);

        $chat->touch();

        return successResponse('Message sent successfully.');
    }
}
