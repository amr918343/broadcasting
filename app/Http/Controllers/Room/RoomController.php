<?php

namespace App\Http\Controllers\Room;

use App\Http\Requests\Web\Room\StoreRoomRequest;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::latest()->paginate(9);

        return $rooms;
    }

    public function create()
    {
        return view('room.create');
    }

    public function store(StoreRoomRequest $request)
    {
        $room = auth()->user()->create($request->validated());
        return redirect()->to(routes('rooms.show', $room));
    }

    public function show($room)
    {
        return view('room.show');
    }
}
