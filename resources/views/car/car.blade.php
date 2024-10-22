@extends('layouts.header')

@section('main')
@push('title')
<title> {{ trans('messages.menu_car_lang',[],session('locale')) }}</title>
@endpush
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ trans('messages.menu_car_lang',[],session('locale')) }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('messages.pages_lang',[],session('locale')) }}</a></li>
                                <li class="breadcrumb-item active">{{ trans('messages.menu_car_lang',[],session('locale')) }}</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#add_car_modal">
                                {{ trans('messages.add_data_lang',[],session('locale')) }}
                            </button>
                        </div>
                        <div class="card-body">

                            <table id="all_car" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ trans('messages.chassis_no_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.car_name_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.per_day_price_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.add_date_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.added_by_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.actions_lang',[],session('locale')) }}</th>
                                    </tr>
                                </thead>


                                <tbody>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <!-- Static Backdrop Modal -->
    <div class="modal fade" id="add_car_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ trans('messages.add_data_lang',[],session('locale')) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('add_car') }}" class="add_car" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="car_id" name="car_id">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="brand_name" class="form-label">{{ trans('messages.brand_name_lang',[],session('locale')) }}</label>
                                            <select class="form-control brand_name" name="brand_name">
                                                <option value="">{{ trans('messages.choose_lang',[],session('locale')) }}</option>
                                                @foreach ($view_brand as $brand) {
                                                    <option value="{{$brand->id}}">{{$brand->brand_name}}</option>';
                                                }
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="model_name" class="form-label">{{ trans('messages.model_name_lang',[],session('locale')) }}</label>
                                            <select class="form-control model_name" name="model_name">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="year_name" class="form-label">{{ trans('messages.year_name_lang',[],session('locale')) }}</label>
                                            <select class="form-control year_name" name="year_name">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="color_name" class="form-label">{{ trans('messages.color_name_lang',[],session('locale')) }}</label>
                                            <select class="form-control color_name" name="color_name">
                                                <option value="">{{ trans('messages.choose_lang',[],session('locale')) }}</option>
                                                @foreach ($view_color as $color) {
                                                    <option value="{{$color->id}}">{{$color->color_name}}</option>';
                                                }
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="insurance_company_name" class="form-label">{{ trans('messages.insurance_company_name_lang',[],session('locale')) }}</label>
                                            <select class="form-control insurance_company_name" name="insurance_company">
                                                <option value="">{{ trans('messages.choose_lang',[],session('locale')) }}</option>
                                                @foreach ($view_insurance_company as $insurance_company) {
                                                    <option value="{{$insurance_company->id}}">{{$insurance_company->insurance_company}}</option>';
                                                }
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="insurance_expiry_date" class="form-label">{{ trans('messages.insurance_expiry_date_lang',[],session('locale')) }}</label>
                                            <input class="form-control insurance_expiry_date datepick" value="<?php echo date('Y-m-d'); ?>" readonly name="insurance_expiry_date" type="text" id="insurance_expiry_date">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="plate_no" class="form-label">{{ trans('messages.plate_no_lang',[],session('locale')) }}</label>
                                            <input class="form-control plate_no" name="plate_no" type="text" id="plate_no">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="chassis_no" class="form-label">{{ trans('messages.chassis_no_lang',[],session('locale')) }}</label>
                                            <input class="form-control chassis_no" name="chassis_no" type="text" id="chassis_no">
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="per_day_price" class="form-label">{{ trans('messages.per_day_per_day_price_lang',[],session('locale')) }}</label>
                                            <input class="form-control per_day_price isnumber" name="per_day_price" type="text" id="per_day_price">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="per_week_price" class="form-label">{{ trans('messages.per_week_price_lang',[],session('locale')) }}</label>
                                            <input class="form-control per_week_price isnumber" name="per_week_price" type="text" id="per_week_price">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="per_month_price" class="form-label">{{ trans('messages.per_month_price_lang',[],session('locale')) }}</label>
                                            <input class="form-control per_month_price isnumber" name="per_month_price" type="text" id="per_month_price">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="mulkia_expiry_date" class="form-label">{{ trans('messages.mulkia_expiry_date_lang',[],session('locale')) }}</label>
                                            <input class="form-control mulkia_expiry_date datepick" value="<?php echo date('Y-m-d'); ?>" readonly name="mulkia_expiry_date" type="text" id="insurance_expiry_date">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="trans_min_expiry" class="form-label">{{ trans('messages.trans_min_expiry_lang',[],session('locale')) }}</label>
                                            <input class="form-control trans_min_expiry datepick" value="<?php echo date('Y-m-d'); ?>" readonly name="trans_min_expiry" type="text" id="insurance_expiry_date">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="vms_expiry" class="form-label">{{ trans('messages.vms_expiry_lang',[],session('locale')) }}</label>
                                            <input class="form-control vms_expiry datepick" value="<?php echo date('Y-m-d'); ?>" readonly name="vms_expiry" type="text" id="insurance_expiry_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="notes" class="form-label">{{ trans('messages.notes_lang',[],session('locale')) }}</label>
                                            <textarea class="form-control notes" name="notes" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12 text-center cursor-pointer" id="ad_cover_container">
                                        <img src="{{asset('custom_images/dummy/cover-image-icon.png') }}" id="ad_cover_preview" class="img-fluid">
                                    </div>
                                    <input type="file" name="car_image" id="ad_cover" class="d-none">
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <button type="button" class="btn btn-danger btn-block" id="btn-ad-images"><i class="fas fa-folder-open"></i> {{ trans('messages.more_images_lang',[],session('locale')) }}</button>
                                        <input type="file" multiple name="ad_images" id="ad_images" class="d-none">
                                    </div>
                                    <div class="col-md-12">
                                        <div  class="row" id="attachment-holder"></div>
                                    </div>
                                </div>
                            </div>

                        </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ trans('messages.close_lang',[],session('locale')) }}</button>
                    <button type="submit" class="btn btn-primary submit_form">{{ trans('messages.submit_lang',[],session('locale')) }}</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    @include('layouts.footer_content')
</div>
<!-- end main content-->

</div>

<div class="modal fade" id="return_maint_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="returnMaintLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnMaintLabel">{{ trans('messages.add_data_lang',[],session('locale')) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" class="add_maint" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" class="maint_id" name="maint_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="maint_name" class="form-label">{{ trans('messages.maint_name_lang',[],session('locale')) }}</label>
                                <input class="form-control" name="maint_name" type="text" id="maint_name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">{{ trans('messages.start_date_lang',[],session('locale')) }}</label>
                                <input class="form-control" name="start_date" type="text" id="start_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">{{ trans('messages.end_date_lang',[],session('locale')) }}</label>
                                <input class="form-control" name="end_date" type="text" id="end_date">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="notes" class="form-label">{{ trans('messages.notes_lang',[],session('locale')) }}</label>
                                <textarea class="form-control notes" name="notes" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ trans('messages.close_lang',[],session('locale')) }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('messages.submit_lang',[],session('locale')) }}</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- END layout-wrapper -->
@include('layouts.footer')
@endsection

