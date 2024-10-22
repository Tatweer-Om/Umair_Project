<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function index(){


        if (!Auth::check()) {

            return redirect()->route('login_page')->with('error', 'Please LogIn first()');
        }

        $user = Auth::user();

        if (in_array(2, explode(',', $user->permit_type))) {

            return view ('booking.location');
        } else {

            return redirect()->route('home')->with( 'error', 'You dont have Permission');
        }
    }
    public function show_location()
    {
        $sno=0;

        $view_location= Location::all();
        if(count($view_location)>0)
        {
            foreach($view_location as $value)
            {

                $modal='<a class="btn btn-outline-secondary btn-sm edit" data-bs-toggle="modal" data-bs-target="#add_location_modal" onclick=edit("'.$value->id.'") title="Edit">
                            <i class="fas fa-pencil-alt" title="Edit"></i>
                        </a>
                        <a class="btn btn-outline-secondary btn-sm edit" onclick=del("'.$value->id.'") title="Delete">
                            <i class="fas fa-trash" title="Edit"></i>
                        </a>';
                $add_data=get_date_only($value->created_at);

                $sno++;
                $json[]= array(
                            $sno,
                            $value->location_name,
                            $value->location_cost,
                            $value->added_by,
                            $modal
                        );
            }
            $response = array();
            $response['success'] = true;
            $response['aaData'] = $json;
            echo json_encode($response);
        }
        else
        {
            $response = array();
            $response['sEcho'] = 0;
            $response['iTotalRecords'] = 0;
            $response['iTotalDisplayRecords'] = 0;
            $response['aaData'] = [];
            echo json_encode($response);
        }
    }

    public function add_location(Request $request){

        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;


        $location = new Location();
        $location->location_name = $request['location_name'];
        $location->location_cost = $request['location_cost'];
        $location->added_by = $user;
        $location->user_id = $user_id;
        $location->save();
        return response()->json(['location_id' => $location->id]);

    }

    public function edit_location(Request $request){
        $location = new Location();
        $location_id = $request->input('id');

        // Use the Eloquent where method to retrieve the location by column name
        $location_data = Location::where('id', $location_id)->first();
        // Add more locationibutes as needed
        $data = [
            'location_id' => $location_data->id,
            'location_name' => $location_data->location_name,
            'location_cost' => $location_data->location_cost,
            // Add more locationibutes as needed
        ];

        return response()->json($data);
    }

    public function update_location(Request $request){

        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;
        $location_id = $request->input('location_id');
        $location = Location::where('id', $location_id)->first();
        $location->location_name = $request->input('location_name');
        $location->location_cost = $request->input('location_cost');
        $location->updated_by = $user;
        $location->save();

    }

    public function delete_location(Request $request){
        $location_id = $request->input('id');
        $location = Location::where('id', $location_id)->first();
        $location->delete();
    }
}
