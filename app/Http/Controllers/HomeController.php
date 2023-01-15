<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('ticket_booking');
    }

    public function postBooking(Request $request)
    {
        if($request->ajax())
        {
            $validator = Validator::make($request->all(),[

                'selectedSeats'=>'required',
            ]);

            if($validator->fails()) {

                return response()->json([

                    'error' => 'Please Select Atleast one seat'
                ]);
            }

            else {

                $seats = Str::of($request->selectedSeats)->explode(',');

                foreach($seats as $seat)
                {
                    Booking::create([

                        'user_id' => 1,
                        'seat_no' => $seat,
                    ]);
                }

                return response()->json([
                    'success'=> 'Booked Sucessfully'

                ]);
            }
        }

    }
}
