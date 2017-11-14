<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client as Client;
use App\Room as Room;

class RoomsController extends Controller
{
    /**
     * Check available rooms.
     */
    public function checkAvailableRooms($client_id, Request $request)
    {
        // Set date from and date to from request input.
        $dateFrom = $request->input('dateFrom');
        $dateTo = $request->input('dateTo');

        // Instantiate new client and room.
        $client = new Client();
        $room = new Room();
        
        // Set data.
        $data = [];
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;
        $data['rooms'] = $room->getAvailableRooms($dateFrom, $dateTo);
        $data['client'] = $client->find($client_id);

        return view('rooms/checkAvailableRooms', $data);
    }
}
