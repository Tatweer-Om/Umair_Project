<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\CarModel;
use App\Models\User;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class YearController extends Controller
{
    public function index(){


        if (!Auth::check()) {

            return redirect()->route('login_page')->with('error', 'Please LogIn first()');
        }

        $user = Auth::user();

        if (in_array(2, explode(',', $user->permit_type))) {
            $view_brand = Brand::all();
            $view_model = CarModel::all();
            return view ('car.year',compact('view_brand','view_model'));

        } else {

            return redirect()->route('home')->with( 'error', 'You dont have Permission');
        }
    }


    public function show_year()
    {
        $sno=0;

        $view_year= year::all();
        if(count($view_year)>0)
        {
            foreach($view_year as $value)
            {

                $modal='<a class="btn btn-outline-secondary btn-sm edit" data-bs-toggle="modal" data-bs-target="#add_year_modal" onclick=edit("'.$value->id.'") title="Edit">
                            <i class="fas fa-pencil-alt" title="Edit"></i>
                        </a>
                        <a class="btn btn-outline-secondary btn-sm edit" onclick=del("'.$value->id.'") title="Delete">
                            <i class="fas fa-trash" title="Edit"></i>
                        </a>';
                $add_data=get_date_only($value->created_at);
                $brand_name = getColumnValue('brands','id',$value->brand_id,'brand_name');
                $model_name = getColumnValue('car_models','id',$value->model_id,'model_name');
                $sno++;
                $json[]= array(
                            $sno,
                            $brand_name,
                            $model_name,
                            $value->year_name,
                            $value->price,
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

    public function add_year(Request $request){

        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;

        $year = new year();



        $year->year_name = $request['year_name'];
        $year->brand_id = $request['brand_id'];
        $year->model_id = $request['model_id'];
        $year->price = $request['price'];
        $year->added_by = $user;
        $year->user_id = $user_id;
        $year->save();
        return response()->json(['year_id' => $year->id]);

    }

    public function edit_year(Request $request){
        $year = new year();
        $year_id = $request->input('id');

        // Use the Eloquent where method to retrieve the year by column name
        $year_data = year::where('id', $year_id)->first();
        $model_data = CarModel::where('brand_id', $year_data->brand_id)->get();
        $models="";
        foreach ($model_data as $key => $value) {
            $selected="";
            if($value->id == $year_data->model_id)
            {
                $selected="selected";
            }
            $models.='<option '.$selected.' value="'.$value->id.'">'.$value->model_name.'</option>';
        }
        // Add more attributes as needed
        $data = [
            'year_id' => $year_data->id,
            'year_name' => $year_data->year_name,
            'brand_id' => $year_data->brand_id,
            'model_id' => $models,
            'price' => $year_data->price,
            // Add more attributes as needed
        ];

        return response()->json($data);
    }

    public function update_year(Request $request){

        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;
        $year_id = $request->input('year_id');
        $year = year::where('id', $year_id)->first();
        $year->year_name = $request->input('year_name');
        $year->brand_id = $request['brand_id'];
        $year->model_id = $request['model_id'];
        $year->price = $request['price'];
        $year->updated_by = $user;
        $year->save();

    }

    public function delete_year(Request $request){
        $year_id = $request->input('id');
        $year = year::where('id', $year_id)->first();
        $year->delete();
    }

    // get models
    public function get_models(Request $request){
        $brand_id = $request->input('brand_id');
        $model_data = CarModel::where('brand_id', $brand_id)->get();
        $models="";
        foreach ($model_data as $key => $value) {
            $models.='<option value="'.$value->id.'">'.$value->model_name.'</option>';
        }
        return response()->json(['models' => $models]);

    }
}
