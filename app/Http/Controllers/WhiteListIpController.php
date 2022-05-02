<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostIpRequest;
use App\Http\Resources\GetIpResource;
use App\Models\WhiteListIP;
use Illuminate\Http\Request;

class WhiteListIpController extends Controller
{
    public function postIp(PostIpRequest $request)
    {
        WhiteListIP::create([
            'ip_address' => $request->ip_address,
            'user_id' => $request->id
        ]);

        return response()->json([
            'data' => [
                'code' => 201,
                'message' => 'Данные о ip-адресе успешно добавлено'
            ]
        ], 201);
    }
    public function updateIp(WhiteListIP $id, Request $request)
    {
        $id->update([
            'ip_address' => $request->ip_address,
        ]);
        return response()->json([
            'data' => [
                'code' => 200,
                'message' => 'Данные о ip-адресе успешно обновлено'
            ]
        ], 200);
    }
    public function getIp()
    {
        // return WhiteListIP::with('responsible')->with('personalData')->paginate(25);
        $ip = GetIpResource::collection(WhiteListIP::with('responsible')->with('personalData')->paginate(25));
        return response()->json([
            'data' => [
                'items' =>  $ip,
                'paginate' => [
                    'total' => $ip->total(),
                    'per_page' => $ip->perPage(),
                    'current_page' => $ip->currentPage(),
                    'last_page' => $ip->lastPage(),
                    'from' => $ip->firstItem(),
                    'to' => $ip->lastItem(),
                    'count' => $ip->count(),
                    'total_pages' => $ip->lastPage()
                ],
                'code' => 200,
                'message' => "Держи солнышко"
            ]
        ], 200);
    }
    public function deleteIp(WhiteListIP $id)
    {
        $id->delete();
        return response()->json([
            'data' => [
                'code' => 200,
                'message' => 'Данные о ip-адресе успешно удалены'
            ]
        ], 200);
    }
}
