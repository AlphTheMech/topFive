<?php

namespace App\Http\Controllers;

use App\Events\BlockEvent;
use App\Events\MsgReadEvent;
use App\Events\PrivateChatEvent;
use App\Events\SessionEvent;
use App\Http\Resources\ChatResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\UserResource;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MessengerController extends Controller
{
    /**
     * blockUser
     *
     * @param  mixed $session
     * @return JsonResponse
     */
    public function blockUser(Session $session)
    {
        $session->block();
        broadcast(new BlockEvent($session->id, true));
        return response()->json(null, 201);
    }

    /**
     * unblockUser
     *
     * @param  mixed $session
     * @return JsonResponse
     */
    public function unblockUser(Session $session)
    {
        $session->unblock();
        broadcast(new BlockEvent($session->id, false));
        return response()->json(null, 201);
    }
    /**
     * send
     *
     * @param  mixed $session
     * @param  mixed $request
     * @return JsonResponse
     */
    public function send(Session $session, Request $request)
    {
        //Отправлять friend_id ,message. Хранить id чата
        $message = $session->messages()->create([
            'content' => $request->message
        ]);
        $chat = $message->createForSend($session->id);
        $message->createForReceive($session->id, $request->to_user);
        broadcast(new PrivateChatEvent($message->content, $chat));
        return response()->json($chat->id, 200);
    }

    /**
     * chats
     *
     * @param  mixed $session
     * @return JsonResponse
     */
    public function chats(Session $session)
    {
        return response()->json(ChatResource::collection($session->chats->where('user_id', auth('sanctum')->user()->id)));
    }

    /**
     * readMessage
     *
     * @param  mixed $session
     * @return void
     */
    public function readMessage(Session $session)
    {
        $chats = $session->chats->where('read_at', null)->where('type', 0)->where('user_id', '!=', auth('sanctum')->user()->id);
        foreach ($chats as $chat) {
            $chat->update(['read_at' => Carbon::now()]);
            broadcast(new MsgReadEvent(new ChatResource($chat), $chat->session_id));
        }
    }

    /**
     * clearMessages
     *
     * @param  mixed $session
     * @return JsonResponse
     */
    public function clearMessages(Session $session)
    {
        $session->deleteChats();
        $session->chats->count() == 0 ? $session->deleteMessages() : '';
        return response()->json('cleared', 200);
    }
    /**
     * createSession
     *
     * @param  mixed $request
     * @return void
     */
    public function createSession(Request $request)
    {
        $session = Session::create(['user1_id' => auth('sanctum')->user()->id, 'user2_id' => $request->friend_id]);
        $modifiedSession = new SessionResource($session);
        broadcast(new SessionEvent($modifiedSession, auth('sanctum')->user()->id));
        return response()->json($modifiedSession, 200);
    }
    /**
     * getFriends
     *
     * @return JsonResponse
     */
    public function getFriends()
    {
        return response()->json(UserResource::collection(User::where('id', '!=', auth()->id())->get()), 200);
    }
}
