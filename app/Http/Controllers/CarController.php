<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Car;
use App\Models\Size;
use App\Models\User;
use App\Models\Year;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Booking;
use App\Models\Maintgo;
use App\Models\CarImage;
use App\Models\CarModel;
use App\Models\Category;
use App\Models\Customer;
use App\Models\BookingBill;
use Illuminate\Http\Request;
use App\Models\BookingPayment;
use App\Models\InsuranceCompany;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function index(){
        $view_color= Color::all();
        $view_insurance_company= InsuranceCompany::all();
        $view_brand= Brand::all();

        if (!Auth::check()) {

            return redirect()->route('login_page')->with('error', 'Please LogIn first()');
        }

        $user = Auth::user();

        if (in_array(2, explode(',', $user->permit_type))) {

            return view ('car.car', compact('view_color','view_insurance_company','view_brand'));

        } else {

            return redirect()->route('home')->with( 'error', 'You dont have Permission');
        }
    }
    public function show_car()
    {
        $sno=0;

        $view_car= Car::all();
        if(count($view_car)>0)
        {
            foreach($view_car as $value)
            {
                $brand = getColumnValue('brands','id',$value->brand_name,'brand_name');
                $model = getColumnValue('car_models','id',$value->model_name,'model_name');
                $year = getColumnValue('years','id',$value->year_name,'year_name');
                $modal = '<a class="btn btn-outline-secondary btn-sm edit" data-bs-toggle="modal" data-bs-target="#add_car_modal" onclick="edit(' . $value->id . ')" title="Edit">
                <i class="fas fa-pencil-alt" title="Edit"></i>
                </a>
                <a class="btn btn-outline-secondary btn-sm delete" onclick="del(' . $value->id . ')" title="Delete">
                        <i class="fas fa-trash" title="Delete"></i>
                </a>';

                // Conditional rendering based on car status
                if ($value->status == 1) {
                    $modal .= ' <a class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#return_maint_modal" onclick="maint(' . $value->id . ')" title="Maintenance">
                                    <i class="fa-solid fa-gears"> Maintenance</i>
                            </a>';
                } else {
                    $modal .= ' <a class="btn btn-outline-secondary btn-sm" title="Under Maintenance">
                                    <i class="fa-solid fa-gears"> Under Maint</i>
                            </a>';
                }
                $add_data=get_date_only($value->created_at);

                $sno++;
                $json[]= array(
                            $sno,
                            $value->chassis_no,
                            $brand."<br>".$model."<br>".$year,
                            $value->plate_no,
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

    public function add_car(Request $request){

        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;


        $car = new Car();
        $car_img="";
        if ($request->hasFile('car_image')) {
            $folderPath = public_path('custom_images/car_image');

            // Check if the folder doesn't exist, then create it
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }
            $car_img = time() . '.' . $request->file('car_image')->extension();
            $request->file('car_image')->move(public_path('custom_images/car_image'), $car_img);
        }

        $car->car_image = $car_img;
        $car->model_name = $request['model_name'];
        $car->color_name = $request['color_name'];
        $car->year_name = $request['year_name'];
        $car->insurance_company = $request['insurance_company'];
        $car->brand_name = $request['brand_name'];
        $car->insurance_expiry_date = $request['insurance_expiry_date'];
        $car->price = $request['price'];
        $car->plate_no = $request['plate_no'];
        $car->chassis_no = $request['chassis_no'];
        $car->mulkia_expiry_date = $request['mulkia_expiry_date'];
        $car->trans_min_expiry = $request['trans_min_expiry'];
        $car->vms_expiry = $request['vms_expiry'];
        $car->notes = $request['notes'];
        $car->added_by = $user;
        $car->user_id = $user_id;
        $car->save();
        $car_id = $car->id;

        // multiple images
        // Check if path exists and get the list of files from the directory
        // Check if path exists and get the list of files from the directory
        $sourcePath = public_path('custom_images/temp_data'); // Source directory
        $destinationDir = public_path('custom_images/car_image/'); // Destination directory

        if (is_dir($sourcePath)) {
            $files = File::files($sourcePath); // Fetch files using File facade

            // Create the destination directory if it doesn't exist
            if (!File::isDirectory($destinationDir)) {
                File::makeDirectory($destinationDir, 0777, true, true);
            }

            foreach ($files as $file) {
                // Get the file extension
                $ext = pathinfo($file, PATHINFO_EXTENSION);

                // Generate the new file name
                $newFileName = 'car_' . time() . '_' . rand(1000, 9999) . '.' . $ext;

                // Define the destination path
                $destinationPath = $destinationDir . $newFileName;

                // Move the file to the new folder
                if (File::move($file->getPathname(), $destinationPath)) {
                    // Generate the URL for the new file location
                    $url = asset('custom_images/car_image/' . $newFileName);

                    // Save the file information in the carImage model
                    $carImage = new CarImage();
                    $carImage->car_id = $car_id; // Assuming $car_id is provided
                    $carImage->car_image = $newFileName; // Save the image URL
                    $carImage->save();
                } else {
                    // Handle the error if the file could not be moved
                    return response()->json(['success' => false, 'message' => 'Failed to move file: ' . $file->getFilename()]);
                }
            }
        }


        return response()->json(['car_id' => $car_id]);

    }

    public function edit_car(Request $request){
        $car = new Car();
        $car_id = $request->input('id');

        // Use the Eloquent where method to retrieve the car by column name
        $car_data = Car::where('id', $car_id)->first();

        // images
        $images = null;
        $car_image = CarImage::where('car_id', $car_id)->get();
        if(!empty($car_image))
        {
            foreach($car_image as $rows)
            {
                // Generate the URL for the file
                $url = asset('custom_images/car_image/' . basename($rows->car_image));
                $images .= '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <img class="img-thumbnail mb-1" src="'.$url.'" style="max-height:60px !important;min-height:60px !important;max-width:60px;min-width:60px;">
                                        <p class="text-center">
                                        <a href="#" class="card-link e-rmv-attachment" id="'.$rows->id.'">
                                            <i class="fa fa-times"></i>
                                        </a>
                                        </p>
                                </div>';
            }
        }

        $model_data = CarModel::where('brand_id', $car_data->brand_name)->get();
        $models="<option value=''>".trans('messages.choose_lang',[],session('locale'))."</option>";
        foreach ($model_data as $key => $value) {
            $selected="";
            if($value->id == $car_data->model_name)
            {
                $selected= "selected";
            }
            $models.='<option '.$selected.' value="'.$value->id.'">'.$value->model_name.'</option>';
        }
        $year_data = Year::where('brand_id', $car_data->brand_name)->where('model_id', $car_data->model_name)->get();
        $years="<option value=''>".trans('messages.choose_lang',[],session('locale'))."</option>";
        foreach ($year_data as $key => $years) {
            $selected_year="";
            if($years->id == $car_data->year_name)
            {
                $selected_model= "selected";
            }
            $years.='<option '.$selected_year.' value="'.$years->id.'">'.$years->year_name.'</option>';
        }
        // Add more attributes as needed
        $data = [
            'car_id' => $car_data->id,
            'model_name' => $models,
            'insurance_company' => $car_data->insurance_company,
            'brand_name' => $car_data->brand_name,
            'year_name' => $years,
            'color_name' => $car_data->color_name,
            'plate_no' => $car_data->plate_no,
            'chassis_no' => $car_data->chassis_no,
            'insurance_expiry_date' => $car_data->insurance_expiry_date,
            'price' => $car_data->price,
            'mulkia_expiry_date' => $car_data->mulkia_expiry_date,
            'trans_min_expiry' => $car_data->trans_min_expiry,
            'vms_expiry' => $car_data->vms_expiry,
            'notes' => $car_data->notes,
            'car_image' => $car_data->car_image,
            'all_images' => $images,
            // Add more attributes as needed
        ];

        return response()->json($data);
    }

    public function update_car(Request $request){

        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;

        $car_id = $request->input('car_id');
        $car = Car::where('id', $car_id)->first();
        $car_img="";
        if ($request->hasFile('car_image')) {
            $folderPath = public_path('custom_images/car_image');

            // Check if the folder doesn't exist, then create it
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }
            $car_img = time() . '.' . $request->file('car_image')->extension();
            $request->file('car_image')->move(public_path('custom_images/car_image'), $car_img);
            $car->car_image = $car_img;
        }


        $car->model_name = $request['model_name'];
        $car->color_name = $request['color_name'];
        $car->year_name = $request['year_name'];
        $car->insurance_company = $request['insurance_company'];
        $car->brand_name = $request['brand_name'];
        $car->insurance_expiry_date = $request['insurance_expiry_date'];
        $car->price = $request['price'];
        $car->plate_no = $request['plate_no'];
        $car->chassis_no = $request['chassis_no'];
        $car->mulkia_expiry_date = $request['mulkia_expiry_date'];
        $car->trans_min_expiry = $request['trans_min_expiry'];
        $car->vms_expiry = $request['vms_expiry'];
        $car->notes = $request['notes'];
        $car->added_by = $user;
        $car->user_id = $user_id;
        $car->save();



    }



    public function delete_car(Request $request)
    {
        // Get the car ID from the request
        $car_id = $request->input('id');

        // Find the car by ID
        $car = Car::where('id', $car_id)->first();

        // Check if the car exists
        if ($car) {
            // Retrieve related carImage records
            $images = CarImage::where('car_id', $car_id)->get();

            // Loop through each image record
            foreach ($images as $image) {
                // Define the image path
                $imagePath = public_path('custom_images/car_image/' . $image->car_image);

                // Check if the image file exists and delete it
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }

                // Delete the image record from the carImage table
                $image->delete();
            }

            // Define the image path
            $imagePath = public_path('custom_images/car_image/' . $car->car_image);

            // Check if the image file exists and delete it
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            // Delete the car record
            $car->delete();
        }

        // You can return a response or redirect as needed
        return response()->json(['message' => 'car and associated images deleted successfully.']);
    }

    public function upload_attachments(Request $request)
    {
        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;

        $car_id      = $request->input('car_id');
		$msg=null;

        if(!empty($car_id))
        {
            // Check if the request contains files
            if ($request->hasFile('attachments')) {
                $files = $request->file('attachments');
                $folderPath = public_path('custom_images/car_image');

                // Check if the folder doesn't exist, then create it
                if (!File::isDirectory($folderPath)) {
                    File::makeDirectory($folderPath, 0777, true, true);
                }

                foreach ($files as $file) {
                    $fileExtension = $file->extension();
                    $fileName = 'car_' . time() . '_' . rand(1000, 9999) . '.' . $fileExtension;
                    $file->move($folderPath, $fileName);
                }
                $car_image = new CarImage();


                $car_image->car_image = $fileName;
                $car_image->car_id = $car_id;
                $car_image->added_by = $user;
                $car_image->user_id = $user_id;
                $car_image->save();
                // Assuming image_preview is a helper function to preview images
                $images = CarImage::where('car_id', $car_id)->get();
                if(!empty($images))
                {
                    foreach($images as $rows)
                    {
                        // Generate the URL for the file
                        $url = asset('custom_images/car_image/' . basename($rows->car_image));
                        $msg .= '
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                            <img class="img-thumbnail mb-1" src="'.$url.'" style="max-height:60px !important;min-height:60px !important;max-width:60px;min-width:60px;">
                                            <p class="text-center">
                                                <a href="#" class="card-link e-rmv-attachment" id="'.$rows->car_id.'">
                                                <i class="fa fa-times"></i>
                                                </a>
                                            </p>
                                        </div>';
                    }
                }
            }
        }
        else
        {
            // Check if the request contains files
            if ($request->hasFile('attachments')) {
                $files = $request->file('attachments');
                $folderPath = public_path('custom_images/temp_data');

                // Check if the folder doesn't exist, then create it
                if (!File::isDirectory($folderPath)) {
                    File::makeDirectory($folderPath, 0777, true, true);
                }

                foreach ($files as $file) {
                    $fileExtension = $file->extension();
                    $fileName = 'att_' . rand(100000, 999999) . '_' . date('His_dmY') . '.' . $fileExtension;
                    $file->move($folderPath, $fileName);
                }

                // Assuming image_preview is a helper function to preview images
                $msg = image_preview($folderPath);
            }
        }

        return response()->json(['images' => $msg]);
    }

    public function remove_attachments(Request $request)
    {
        $filePath = $request->input('img');
        $fileName = basename($filePath); // Extract the file name from the file path
        $path = public_path('custom_images/temp_data/') . $fileName; // Full path to the file

        // Check if the file exists
        if (file_exists($path)) {
            // Delete the file
            unlink($path);
            return response()->json(['success' => true]);
        }

        // Return file not found error
        return response()->json(['success' => false, 'message' => 'File not found.']);
    }

    // delete edit attachments
    public function e_remove_attachments(Request $request)
	{
        $msg="";
		$image_id = $request->input('image_id');
        $car_id = $request->input('car_id');
		$path = public_path('custom_images/car_image/');
		$img = $request->input('img');
		$img = explode('/',$img);
		$img = end($img);
		if(unlink($path.$img))
		{
            $car_image = CarImage::where('id', $image_id)->first();
            $car_image->delete();
		}
        $images = CarImage::where('car_id', $car_id)->get();
        if(!empty($images))
        {
            foreach($images as $rows)
            {
                // Generate the URL for the file
                $url = asset('custom_images/car_image/' . basename($rows->car_image));
                $msg .= '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <img class="img-thumbnail mb-1" src="'.$url.'" style="max-height:60px !important;min-height:60px !important;max-width:60px;min-width:60px;">
                            <p class="text-center">
                                <a href="#" class="card-link e-rmv-attachment" id="'.$rows->id.'">
                                <i class="fa fa-times"></i>
                                </a>
                            </p>
                        </div>';
            }
        }
        return response()->json(['images' => $msg]);
	}



    public function maint_car_all(){

        if (!Auth::check()) {

            return redirect()->route('login_page')->with('error', 'Please LogIn first()');
        }

        $user = Auth::user();

        if (in_array(7, explode(',', $user->permit_type))) {

            return view('maintenance.all_car_under_maint');
        } else {

            return redirect()->route('home')->with( 'error', 'You dont have Permission');
        }
    }



    public function maint_car(Request $request){

        $car_id= $request->input('maint_id');
        $car= Car::where('id', $car_id)->first();

        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;


        if (!$car) {
            return response()->json(['error' => 'car not found'], 404);
        }

        $maint = new Maintgo();
        $maint->car_id = $car_id;
        $maint->maint_issue = $request->input('maint_name');
        $maint->issue_notes = $request->input('notes');
        $maint->start_date = $request->input('start_date');
        $maint->end_date = $request->input('end_date');
        $maint->status = 1;
        $maint->added_by = $user;
        $maint->user_id =  $user_id;
        $maint->save();


        $car_status= Car::where('id',   $car_id)->first();
        $car_status->status= 2;
        $car_status->start_date = $request->input('start_date');
        $car_status->end_date = $request->input('end_date');
        $car_status->save();

        // return response()->json([
        //     'maint_item' => $maint_item,
        // ]);


    }

    public function show_maint_car()
    {
        $sno=0;

        $view_car= Maintgo::all();
        if(count($view_car)>0)
        {
            foreach($view_car as $value)
            {
               $car= Car::with('color', 'size', 'brand', 'category')->where('id', $value->car_id)->first();
               $car_name= $car->car_name;
               $cat= $car->category->category_name;
               $clr= $car->color->color_name;
               $size= $car->size->size_name;
               $brnd= $car->brand->brand_name ?? 'null';
               if ($value->status == 1) {
                $modal = '<a class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#maint_complete_modal" onclick="comp_maint(' . $value->id . ')" title="Maintenance">
                            <i class="fa-solid fa-gears"></i> Finish Maintenance
                          </a>';
            } else {
                $modal = '<a class="btn btn-outline-secondary btn-sm" title="Maintenance Completed">
                            <i class="fa-solid fa-gears"></i> Completed
                          </a>';
            }



                // Conditional rendering based on car status

                $add_data=get_date_only($value->created_at);

                $sno++;
                $json[]= array(
                            $sno,
                            $car_name .'<br>'.  $cat .'<br>'. $clr .'<br>'. $size .'<br>'. $brnd,
                            $value->maint_issue,
                            $value->issue_notes,
                            $add_data .'<br>'. $value->added_by,

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


    public function maint_car_comp(Request $request){

        $maint_id= $request->input('maint_id');


        $maint= Maintgo::where('id',  $maint_id)->first();

        if (!$maint) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        $maint->maint_cost = $request->input('maint_cost');
        $maint->maint_comp_notes = $request->input('notes');
        $maint->status = 2;
        $maint->save();

        // update car
        $car_status= Car::where('id',   $maint->car_id)->first();
        $car_status->status= 1;
        $car_status->start_date = NULL;
        $car_status->end_date = NULL;
        $car_status->save();

        return response()->json([
            'success' => 'Maintenance completed successfully',
        ]);


    }

    public function car_profile($id){

        $car_data= Car::where('id', $id)->first();
        $category_data= Category::where('id', $car_data->category_name)->first();
        $color_data= Color::where('id', $car_data->color_name)->first();
        $size_data= Size::where('id', $car_data->size_name)->first();

        return view ('car.car_profile', compact('car_data','category_data','color_data','size_data'));


    }

    public function car_profile_data(Request $request)
    {

        $car = Car::find($request->car_id);
        if (!$car) {
            return response()->json(['message' => 'car not found'], 404);
        }
        $bookings = Booking::with([
            'bills',
            'payments',
            'car.brand',
            'car.category',
            'car.color',
            'car.size'
        ])->where('car_id', $car->id)->get();

        $upcoming_bookings = Booking::with([
            'bills',
            'payments',
            'car.brand',
            'car.category',
            'car.color',
            'car.size'
        ])
        ->where('car_id', $car->id)
        ->where('rent_date', '>', Carbon::now())
        ->get();

        $upcoming_bookings_count= $upcoming_bookings->count();
        $total_bookings= $bookings->count();
        $total_amount = 0;
        $total_panelty=0;
        foreach ($bookings as $booking) {

            foreach ($booking->payments as $payment) {
                $total_amount += $payment->paid_amount;
            }
        }

        foreach ($bookings as $booking) {
            foreach ($booking->bills as $payment) {
                echo $payment->total_panelty;
                $total_panelty += $payment->total_penalty;
            }
        }


        $currentBookings = Booking::with([
            'bills',
            'payments',
            'car.brand',
            'car.category',
            'car.color',
            'car.size'
        ])
        ->where('car_id', $car->id)
        ->whereDate('rent_date', '<=', Carbon::now())
        ->whereDate('return_date', '>=', Carbon::now())
        ->get();

        return response()->json([
            'bookings' => $bookings,
            'up_bookings'=> $upcoming_bookings,
            'total_amount'=>$total_amount,
            'total_bookings'=>$total_bookings,
            'upcoming_bookings_count'=>$upcoming_bookings_count,
            'total_panelty'=>$total_panelty,
            'current_bookings'=>$currentBookings


        ]);
    }

    // get models
    public function get_car_models(Request $request){
        $brand_id = $request->input('brand_id');
        $model_data = CarModel::where('brand_id', $brand_id)->get();
        $models="<option value=''>".trans('messages.choose_lang',[],session('locale'))."</option>";
        foreach ($model_data as $key => $value) {
            $models.='<option value="'.$value->id.'">'.$value->model_name.'</option>';
        }
        return response()->json(['models' => $models]);

    }

    // get years
    public function get_car_years(Request $request){
        $brand_id = $request->input('brand_id');
        $model_id = $request->input('model_id');
        $year_data = Year::where('brand_id', $brand_id)->where('model_id', $model_id)->get();
        $years="<option value=''>".trans('messages.choose_lang',[],session('locale'))."</option>";
        foreach ($year_data as $key => $value) {
            $years.='<option value="'.$value->id.'">'.$value->year_name.'</option>';
        }
        return response()->json(['years' => $years]);

    }

    // get price
    public function get_car_price(Request $request){
        $brand_id = $request->input('brand_id');
        $model_id = $request->input('model_id');
        $year_id = $request->input('year_id');
        $year_data = Year::where('brand_id', $brand_id)->where('model_id', $model_id)->where('id', $year_id)->first();
        $price=$year_data->price;

        return response()->json(['price' => $price]);

    }

}
