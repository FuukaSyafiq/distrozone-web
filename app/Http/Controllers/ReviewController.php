<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Room;
use App\Models\TipeRoom;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store($roomid, Request $request)
    {
        $userId = request()->query('userid');
        // Validate the incoming request data
        $validatedData = $request->validate([
            'review' => ['nullable', 'string'],   // Optional review, must be a string if provided
            'star' => ['required', 'numeric', 'max:5', 'min:1'],    // 'star' is required and must be a number
        ]);
	// $room = Room::where('id', $roomid)->first();

	// $tipeRoom = TipeRoom::where('id', $room->tipe_room_id)->first();

    //     Review::create([
    //         "review" => $validatedData['review'],
    //         "star" => $validatedData['star'],
    //         "user_id" => $userId,
    //         "room_id" => $room->id,
	//     "tipe_room_id" => $tipeRoom->id
    //     ]);

        return redirect()->back();
    }
}
