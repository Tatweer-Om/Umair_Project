
@extends('layouts.header')

@section('main')
@push('title')
<title> {{ trans('messages.menu_service_lang',[],session('locale')) }}</title>
@endpush


<style>
 .ui-autocomplete {
    z-index: 1051 !important; /* Ensure the dropdown is above the modal */
    max-height: 200px; /* Limit dropdown height */
    overflow-y: auto; /* Enable scrolling if there are too many results */
    background-color: white; /* Dropdown background color */
}
</style>
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ trans('messages.menu_service_lang',[],session('locale')) }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('messages.pages_lang',[],session('locale')) }}</a></li>
                                <li class="breadcrumb-item active">{{ trans('messages.menu_service_lang',[],session('locale')) }}</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#add_service_modal">
                                {{ trans('messages.add_data_lang',[],session('locale')) }}
                            </button>
                        </div>

                        <div class="card-body">

                            <table id="services" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>{{ trans('messages.sr_no_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.car_detail_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.current_km_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.service_dates_expense_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.notes_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.created_by_lang',[],session('locale')) }}</th>
                                        <th>{{ trans('messages.action_lang', [], session('locale')) }}</th>
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
    <div class="modal fade"  id="add_service_modal"   tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered"  role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ trans('messages.add_data_lang',[],session('locale')) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" >
                    <form action="#" class="add_service" method="POST" >
                        @csrf
                        <input type="hidden" class="service_id" name="service_id">
                        <div class="row">
                            <div class="col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <label for="example-text-input" class="form-label">{{ trans('messages.search_car_lang',[],session('locale')) }}</label>

                                    <select class="form-control search_car searchable" data-trigger name="search_car" id="search_car" onchange="get_dress_detail()">
                                        <option value="">{{ trans('messages.choose_lang', [], session('locale')) }}</option>
                                        @foreach ($view_car as $car)
                                            <option value="{{ $car['id'] }}">{{ $car['brand'] . ' - ' . $car['plate_no'] . ' (' . $car['chassis_no'] . ')' }}</option>
                                        @endforeach
                                    </select>


                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4">
                                <div class="mb-3" >
                                    <label for="example-text-input" class="form-label">{{ trans('messages.current_km_lang',[],session('locale')) }}</label>
                                    <input class="form-control current_km isnumber" name="current_km" type="text" id="example-text-input">
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <label for="example-text-input" class="form-label">{{ trans('messages.service_duration_lang',[],session('locale')) }}</label>
                                    <input class="form-control service_duration isnumber" name="service_duration" type="text" id="example-text-input">
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <label for="example-text-input" class="form-label">{{ trans('messages.service_expense',[],session('locale')) }}</label>
                                    <input class="form-control service_expense isnumber" name="service_expense" type="text" id="example-text-input">
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <label for="example-text-input" class="form-label">{{ trans('messages.service_date_lang',[],session('locale')) }}</label>
                                    <input class="form-control service_date datepick" name="service_date" type="text" id="example-text-input">
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4">
                                <div class="mb-3">
                                    <label for="example-text-input" class="form-label">{{ trans('messages.next_service_date_lang',[],session('locale')) }}</label>
                                    <input class="form-control next_service_date datepick" name="next_service_date" type="text" id="example-text-input">
                                </div>
                            </div>


                            <div class="col-md-12 col-lg-12">
                                <div class="mb-3 form-group">
                                    <label>{{ trans('messages.notes_lang', [], session('locale')) }}</label>
                                    <textarea  class="form-control notes" rows="3" name="notes"></textarea>
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
<!-- END layout-wrapper -->
@include('layouts.footer')
@endsection

