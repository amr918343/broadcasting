<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoomUserController extends Controller
{
        public function index(Room $room)
    {
        $users = $room->users()->get();

        // Your logic for displaying the list of users in the room
    }

    public function store(Room $room, User $user)
    {
        $room->users()->attach($user);

        // Your logic for adding a user to a room
    }

    public function destroy(Room $room, User $user)
    {
        $room->users()->detach($user);

        // Your logic for removing a user from a room
    }
}
