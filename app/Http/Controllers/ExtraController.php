<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Extras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ExtraController extends Controller
{
    public function index(){

        if (!Auth::check()) {

            return redirect()->route('login_page')->with('error', 'Please LogIn first()');
        }

        $user = Auth::user();

        if (in_array(5, explode(',', $user->permit_type))) {

            return view ('car.extra');
        } else {

            return redirect()->route('home')->with( 'error', 'You dont have Permission');
        }

    }

    public function show_extra()
    {
        $sno=0;

        $view_extra= Extras::all();
        if(count($view_extra)>0)
        {
            foreach($view_extra as $value)
            {

                $extra_name='<a href="javascript:void(0);">'.$value->extra_name.'</a>';
                // cat_name

                 // payment_method
                 $modal='<a class="btn btn-outline-secondary btn-sm edit" data-bs-toggle="modal" data-bs-target="#add_extra_modal" onclick=edit("'.$value->id.'") title="Edit">
                <i class="fas fa-pencil-alt" title="Edit"></i>
                    </a>
                    <a class="btn btn-outline-secondary btn-sm edit" onclick=del("'.$value->id.'") title="Delete">
                        <i class="fas fa-trash" title="Edit"></i>
                    </a>';
                if(!empty($value->extra_image))
                {
                    $modal.=' <a target="_blank" class="btn btn-outline-secondary btn-sm edit" href="'.url('download_extra_image').'/'.$value->extra_image.'"><i class="fas fa-download"></i>
                    </a>';
                }
                $add_data=get_date_only($value->created_at);

                $sno++;
                $json[]= array(
                            $sno,
                            $extra_name,
                            $value->cost,
                            $value->added_by,
                            $add_data,
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

    public function add_extra(Request $request)
    {


        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;

        $extra = new Extras();
        $extra_image = "";

        // Handle the file upload
        if ($request->hasFile('extra_image')) {
            $folderPath = public_path('custom_images/extra_images');

            // Check if the folder doesn't exist, then create it
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            // Create a unique filename
            $extra_image = time() . '.' . $request->file('extra_image')->extension();
            $request->file('extra_image')->move($folderPath, $extra_image);
        }


        $extra->extra_name = $request['extra_name'];
        $extra->cost = $request['cost'];
        $extra->notes = $request['notes'];
        $extra->extra_image = $extra_image;
        $extra->added_by = $user;
        $extra->user_id = $user_id;
        $extra->save();



        return response()->json(['extra_id' => $extra->id]);
    }


    public function edit_extra(Request $request){

        $extra_id = $request->input('id');
        // Use the Eloquent where method to retrieve the extra by column name
        $extra_data = Extras::where('id', $extra_id)->first();

        if (!$extra_data) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.extra_not_found', [], session('locale'))], 404);
        }
        // Add more attributes as needed
        $data = [
            'extra_id' => $extra_data->id,
            'extra_name' => $extra_data->extra_name,
            'cost' => $extra_data->cost,
            'extra_image' => $extra_data->extra_image,
            'notes' => $extra_data->notes,
           // Add more attributes as needed
        ];

        return response()->json($data);
    }

    public function update_extra(Request $request){


        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;
        $extra_id = $request->input('extra_id');
        $extra = Extras::where('id', $extra_id)->first();
        if (!$extra) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.extra_not_found', [], session('locale'))], 404);
        }



        $extra_image = "";

        // Handle the file upload
        if ($request->hasFile('extra_image')) {
            $folderPath = public_path('custom_images/extra_images');

            // Check if the folder doesn't exist, then create it
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }

            // Create a unique filename
            $extra_image = time() . '.' . $request->file('extra_image')->extension();
            $request->file('extra_image')->move($folderPath, $extra_image);
            $extra->extra_image = $extra_image;
        }
        $extra->extra_name = $request['extra_name'];
        $extra->cost = $request['cost'];
        $extra->notes = $request['notes'];
        $extra->updated_by = $user;
        $extra->save();

        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.extra_update_lang', [], session('locale'))
        ]);
    }

    public function delete_extra(Request $request){
        $extra_id = $request->input('id');
        $extra = Extras::where('id', $extra_id)->first();
        if (!$extra) {
            return response()->json([trans('messages.error_lang', [], session('locale')) => trans('messages.extra_not_found', [], session('locale'))], 404);
        }
        $extra->delete();
        return response()->json([
            trans('messages.success_lang', [], session('locale')) => trans('messages.extra_deleted_lang', [], session('locale'))
        ]);
    }

}
