<?php

namespace App\Http\Controllers;

use App\Models\InsuranceCompany;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class InsuranceCompanyController extends Controller
{
    public function index(){

        if (!Auth::check()) {

            return redirect()->route('login_page')->with('error', 'Please LogIn first()');
        }

        $user = Auth::user();

        if (in_array(2, explode(',', $user->permit_type))) {

            return view ('car.insurancecompany');
        } else {

            return redirect()->route('home')->with( 'error', 'You dont have Permission');
        }
    }
    public function show_insurance_company()
    {
        $sno=0;

        $view_insurance_company= InsuranceCompany::all();
        if(count($view_insurance_company)>0)
        {
            foreach($view_insurance_company as $value)
            {

                $modal='<a class="btn btn-outline-secondary btn-sm edit" data-bs-toggle="modal" data-bs-target="#add_insurance_company_modal" onclick=edit("'.$value->id.'") title="Edit">
                            <i class="fas fa-pencil-alt" title="Edit"></i>
                        </a>
                        <a class="btn btn-outline-secondary btn-sm edit" onclick=del("'.$value->id.'") title="Delete">
                            <i class="fas fa-trash" title="Edit"></i>
                        </a>';
                $add_data=get_date_only($value->created_at);

                $sno++;
                $json[]= array(
                            $sno,
                            $value->insurance_company,
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

    public function add_insurance_company(Request $request){

        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;


        $insurancecompany = new insurancecompany();


        $insurancecompany->insurance_company = $request['insurance_company'];
        $insurancecompany->added_by = $user;
        $insurancecompany->user_id = $user_id;
        $insurancecompany->save();
        return response()->json(['insurancecompany_id' => $insurancecompany->id]);

    }

    public function edit_insurance_company(Request $request){
        $insurancecompany = new insurancecompany();
        $insurancecompany_id = $request->input('id');

        // Use the Eloquent where method to retrieve the insurancecompany by column name
        $insurancecompany_data = InsuranceCompany::where('id', $insurancecompany_id)->first();
        // Add more attributes as needed
        $data = [
            'insurancecompany_id' => $insurancecompany_data->id,
            'insurance_company' => $insurancecompany_data->insurance_company,
            // Add more attributes as needed
        ];

        return response()->json($data);
    }

    public function update_insurance_company(Request $request){

        $user_id = Auth::id();
        $data= User::find( $user_id)->first();
        $user= $data->user_name;

        $insurancecompany_id = $request->input('insurance_company_id');
        $insurancecompany = InsuranceCompany::where('id', $insurancecompany_id)->first();
        $insurancecompany->insurance_company = $request->input('insurance_company');
        $insurancecompany->updated_by = $user;
        $insurancecompany->save();

    }

    public function delete_insurance_company(Request $request){
        $insurancecompany_id = $request->input('id');
        $insurancecompany = InsuranceCompany::where('id', $insurancecompany_id)->first();
        $insurancecompany->delete();
    }
}
