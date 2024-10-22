<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Attribute;

use Illuminate\Support\Facades\Auth;

class AtrrController extends Controller
{
    public function index(){


        if (!Auth::check()) {

            return redirect()->route('login_page')->with('error', 'Please LogIn first()');
        }

        $user = Auth::user();

        if (in_array(2, explode(',', $user->permit_type))) {

            return view ('car.att');
        } else {

            return redirect()->route('home')->with( 'error', 'You dont have Permission');
        }
    }
    public function show_attr()
    {
        $sno=0;

        $view_attr= Attribute::all();
        if(count($view_attr)>0)
        {
            foreach($view_attr as $value)
            {

                $modal='<a class="btn btn-outline-secondary btn-sm edit" data-bs-toggle="modal" data-bs-target="#add_attr_modal" onclick=edit("'.$value->id.'") title="Edit">
                            <i class="fas fa-pencil-alt" title="Edit"></i>
                        </a>
                        <a class="btn btn-outline-secondary btn-sm edit" onclick=del("'.$value->id.'") title="Delete">
                            <i class="fas fa-trash" title="Edit"></i>
                        </a>';
                $add_data=get_date_only($value->created_at);

                $sno++;
                $json[]= array(
                            $sno,
                            $value->attr_name,
                            $add_data,
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

    public function add_attr(Request $request){

        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;


        $attr = new Attribute();
        $attr->attr_name = $request['attr_name'];
        $attr->added_by = $user;
        $attr->user_id = $user_id;
        $attr->save();
        return response()->json(['attr_id' => $attr->id]);

    }

    public function edit_attr(Request $request){
        $attr = new Attribute();
        $attr_id = $request->input('id');

        // Use the Eloquent where method to retrieve the attr by column name
        $attr_data = Attribute::where('id', $attr_id)->first();
        // Add more attributes as needed
        $data = [
            'attr_id' => $attr_data->id,
            'attr_name' => $attr_data->attr_name,
            // Add more attributes as needed
        ];

        return response()->json($data);
    }

    public function update_attr(Request $request){

        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;
        $attr_id = $request->input('attr_id');
        $attr = Attribute::where('id', $attr_id)->first();
        $attr->attr_name = $request->input('attr_name');
        $attr->updated_by = $user;
        $attr->save();

    }

    public function delete_attr(Request $request){
        $attr_id = $request->input('id');
        $attr = Attribute::where('id', $attr_id)->first();
        $attr->delete();
    }
}
