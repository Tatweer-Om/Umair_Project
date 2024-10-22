<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use App\Models\Brand;
use App\Models\Service;
use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class ServiceController extends Controller
{
    public function index(){
        $cars= Car::all();

        $view_car = [];

        foreach ($cars as $car) {
            $brand = Brand::where('id', $car->brand_name)->value('brand_name');
            $model = CarModel::where('id', $car->model_name)->value('model_name');

            // Store each car's details in the view_car array
            $view_car[] = [
                'id' => $car->id,
                'plate_no' => $car->plate_no,
                'chassis_no' => $car->chassis_no,
                'brand' => $brand,
                'model' => $model,
            ];
        }


        if (!Auth::check()) {

            return redirect()->route('login_page')->with('error', 'Please LogIn first()');
        }

        $user = Auth::user();

        if (in_array(5, explode(',', $user->permit_type))) {

            return view('service.service',compact('view_car'));
        } else {

            return redirect()->route('home')->with( 'error', 'You dont have Permission');
        }



    }

    public function show_service()
    {
        $sno=0;

        $view_service= Service::all();
        if(count($view_service)>0)
        {
            foreach($view_service as $value)
            {

                $car = Car::where('id', $value->search_car)->first();

                $car_chess = $car->chassis_no ?? '';
                $car_plt = $car->plate_no ?? '';

                if ($car) {
                    $brand = Brand::where('id', $car->brand_name)->value('brand_name') ?? 'Unknown Brand';
                    $model = CarModel::where('id', $car->model_name)->value('model_name') ?? 'Unknown Model';
                } else {
                    $brand = 'Unknown Brand';
                    $model = 'Unknown Model';
                }


                $search_car='<a href="javascript:void(0);">'.$brand.'</a>';
                $modal='<a class="btn btn-outline-secondary btn-sm edit" data-bs-toggle="modal" data-bs-target="#add_service_modal" onclick=edit("'.$value->id.'") title="Edit">
                            <i class="fas fa-pencil-alt" title="Edit"></i>
                        </a>
                    <a class="btn btn-outline-secondary btn-sm edit" onclick=del("'.$value->id.'") title="Delete">
                        <i class="fas fa-trash" title="Edit"></i>
                    </a>';
                $add_data=get_date_only($value->created_at);
                if($value->service_type)
                {
                    $service_type='Normal service';
                }
                else
                {
                    $service_type='Saving service';
                }
                $sno++;
                $json[] = array(
                    $sno,

                    '<strong>' . trans('messages.car_lang', [], session('locale')) . ':</strong> ' . $search_car . '<br>' .
                    '<strong>' . trans('messages.model_lang', [], session('locale')) . ':</strong> ' . $model . '<br>' .
                    '<strong>' . trans('messages.plate_no_lang', [], session('locale')) . ':</strong> ' . $car_plt . '<br>' .
                    '<strong>' . trans('messages.chassis_no_lang', [], session('locale')) . ':</strong> ' . $car_chess,
                    '<strong>' . trans('messages.current_km_lang', [], session('locale')) . ':</strong> ' .   $value->current_km . '<br>' .
                    '<strong>' . trans('messages.service_duration_lang', [], session('locale')) . ':</strong> ' .   $value->service_duration,
                    '<strong>' . trans('messages.service_date_lang', [], session('locale')) . ':</strong> ' . $value->service_date . '<br>' .
                    '<strong>' . trans('messages.next_service_date_lang', [], session('locale')) . ':</strong> ' .  $value->next_service_date . '<br>' .
                    '<strong>' . trans('messages.service_expense_lang', [], session('locale')) . ':</strong> ' . $value->service_expense,
                    '<div style="white-space: pre-line; text-align:justify; width: 120px; overflow-wrap: break-word;">' . $value->notes . '</div>',
                    '<strong>' . trans('messages.added_by_lang', [], session('locale')) . ':</strong> ' .  $value->added_by .'<br>'. '<strong>' . trans('messages.created_at_lang', [], session('locale')) . ':</strong> ' .  $add_data,
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

    public function add_service(Request $request){

        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;



        $service = new service();
        $service->search_car = $request['search_car'];
        $service->current_km = $request['current_km'];
        $service->service_duration = $request['service_duration'];
        $service->service_expense = $request['service_expense'];
        $service->service_date = $request['service_date'];
        $service->next_service_date = $request['next_service_date'];
        $service->notes = $request['notes'];
        $service->added_by = $user;
        $service->user_id =  $user_id;
        $service->save();
        return response()->json(['service_id' => $service->id]);

    }

    public function edit_service(Request $request){
        $service = new service();
        $service_id = $request->input('id');
        $service_data = Service::where('id', $service_id)->first();

        if (!$service_data) {
            return response()->json(['error' => trans('messages.service_not_found_lang', [], session('locale'))], 404);
        }
        // Add more attributes as needed
        $data = [
            'service_id' => $service_data->id,
            'search_car' => $service_data->search_car,
            'current_km' => $service_data->current_km,
            'service_duration' => $service_data->service_duration,
            'service_date' => $service_data->service_date,
            'next_service_date' => $service_data->next_service_date,
            'service_expense' => $service_data->service_expense,
            'notes' => $service_data->notes,
            // Add more attributes as needed
        ];

        return response()->json($data);
    }

    public function update_service(Request $request){
        $service_id = $request->input('service_id');
        $service = Service::where('id', $service_id)->first();
        if (!$service) {
            return response()->json(['error' => trans('messages.service_not_found_lang', [], session('locale'))], 404);
        }

        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;

        $service->search_car = $request['search_car'];
        $service->current_km = $request['current_km'];
        $service->service_duration = $request['service_duration'];
        $service->service_expense = $request['service_expense'];
        $service->service_date = $request['service_date'];
        $service->next_service_date = $request['next_service_date'];
        $service->notes = $request['notes'];
        $service->updated_by = $user;
        $service->save();
        return response()->json(['success' => trans('messages.data_update_success_lang', [], session('locale'))]);
    }

    public function delete_service(Request $request){
        $service_id = $request->input('id');
        $service = Service::where('id', $service_id)->first();
        if (!$service) {
            return response()->json(['error' => trans('messages.service_not_found_lang', [], session('locale'))], 404);
        }
        $service->delete();
        return response()->json(['success' => trans('messages.delete_success_lang', [], session('locale'))]);
    }


    public function search_car(Request $request) {
        $request->validate([
            'term' => 'required|string|max:255',
        ]);

        $term = $request->input('term');

        $cars = Car::where(function($query) use ($term) {
            $query->where('plate_no', 'like', '%' . $term . '%')
                  ->orWhere('chassis_no', 'like', '%' . $term . '%');
        })
        ->get(['id', 'plate_no', 'chassis_no']); // Fetch only the necessary columns

        $response = array_map(function($car) {
            return [
                'label' => $car['id'] . '-' . $car['plate_no'] . '+' . $car['chassis_no'],
                'value' => $car['id'] . '-' . $car['plate_no'] . '+' . $car['chassis_no'],
                'car_id' => $car['id'],
            ];
        }, $cars->toArray());

        return response()->json($response);
    }

}
