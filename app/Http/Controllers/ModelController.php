<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\CarModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ModelController extends Controller
{
    public function index(){

        if (!Auth::check()) {

            return redirect()->route('login_page')->with('error', 'Please LogIn first()');
        }

        $user = Auth::user();

        if (in_array(2, explode(',', $user->permit_type))) {
            $view_brand = Brand::all();
            return view ('car.model',compact('view_brand'));
        } else {

            return redirect()->route('home')->with( 'error', 'You dont have Permission');
        }
    }
    public function show_model()
    {
        $sno=0;

        $view_model= CarModel::all();
        if(count($view_model)>0)
        {
            foreach($view_model as $value)
            {

                $modal='<a class="btn btn-outline-secondary btn-sm edit" data-bs-toggle="modal" data-bs-target="#add_model_modal" onclick=edit("'.$value->id.'") title="Edit">
                            <i class="fas fa-pencil-alt" title="Edit"></i>
                        </a>
                        <a class="btn btn-outline-secondary btn-sm edit" onclick=del("'.$value->id.'") title="Delete">
                            <i class="fas fa-trash" title="Edit"></i>
                        </a>';
                $add_data=get_date_only($value->created_at);
                $brand_name = getColumnValue('brands','id',$value->brand_id,'brand_name');
                $sno++;
                $json[]= array(
                            $sno,
                            $brand_name,
                            $value->model_name,
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

    public function add_model(Request $request){

        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;


        $model = new CarModel();


        $model->brand_id = $request['brand_id'];
        $model->model_name = $request['model_name'];
        $model->added_by = $user;
        $model->user_id = $user_id;
        $model->save();
        return response()->json(['model_id' => $model->id]);

    }

    public function edit_model(Request $request){
        $model = new CarModel();
        $model_id = $request->input('id');

        // Use the Eloquent where method to retrieve the model by column name
        $model_data = CarModel::where('id', $model_id)->first();
        // Add more attributes as needed
        $data = [
            'model_id' => $model_data->id,
            'model_name' => $model_data->model_name,
            'brand_id' => $model_data->brand_id,
            // Add more attributes as needed
        ];

        return response()->json($data);
    }

    public function update_model(Request $request){

        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;

        $model_id = $request->input('model_id');
        $model = CarModel::where('id', $model_id)->first();
        $model->brand_id = $request->input('brand_id');
        $model->model_name = $request->input('model_name');
        $model->updated_by = $user;
        $model->save();

    }

    public function delete_model(Request $request){
        $model_id = $request->input('id');
        $model = CarModel::where('id', $model_id)->first();
        $model->delete();
    }
}
