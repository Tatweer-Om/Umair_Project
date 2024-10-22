@extends('layouts.header')

@section('main')
@push('title')
<title> {{ trans('messages.menu_brand_lang',[],session('locale')) }}</title>
@endpush

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ trans('messages.enrollment',[],session('locale')) }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('messages.enrollment',[],session('locale')) }}</a></li>
                                <li class="breadcrumb-item active">{{ trans('messages.enrollment_list',[],session('locale')) }}</li>
                            </ol>
                        </div>

                    </div>
                </div>

            </div>
<form action="" class="enroll_list">
    @csrf
    <div class="card" style="padding: 20px">
        <div class="row">
            <div class="col-lg-12">
                <h3>{{ trans('messages.booking_detail_lang',[],session('locale')) }}</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <label for="customer_id" class="form-label">{{ trans('messages.customer_name_lang',[],session('locale')) }}</label>
                <input type="text" class="form-control customer_name" id="customer_name">
                <input type="hidden" class="form-control" id="customer_id">
            </div>
            <div class="col-lg-3">
                <label for="start_time" class="form-label">{{ trans('messages.start_date_lang',[],session('locale')) }}</label>
                <input type="text" class="form-control start_time" name="start_time" id="start_time">
            </div>
            <div class="col-lg-3">
                <label for="end_time" class="form-label">{{ trans('messages.end_date_lang',[],session('locale')) }}</label>
                <input type="text" class="form-control end_time" name="end_time" id="end_time">
            </div>
            <div class="col-lg-3">
                <label for="duration" class="form-label">{{ trans('messages.duration_lang',[],session('locale')) }}</label>
                <input type="text" class="form-control duration" readonly name="duration" id="duration">
            </div>
        </div><br>
        <div class="row">
            <div class="col-lg-3">
                <label for="car_name" class="form-label">{{ trans('messages.car_name_lang',[],session('locale')) }}</label>
                <input type="text" class="form-control car_name" name="car_name" id="car_name">
            </div>
            <div class="col-lg-3">
                <label for="price" class="form-label">{{ trans('messages.price_lang',[],session('locale')) }}</label>
                <input type="text" class="form-control price" name="price" id="price">
            </div>
            <div class="col-lg-3">
                <label for="discount" class="form-label">{{ trans('messages.discount_lang',[],session('locale')) }}</label>
                <input type="text" class="form-control discount" name="discount" id="discount">
            </div>
            <div class="col-lg-3">
                <label for="total_price" class="form-label">{{ trans('messages.total_price_lang',[],session('locale')) }}</label>
                <input type="text" class="form-control total_price" readonly name="total_price" id="total_price">
            </div>

        </div><br>
        <div class="row">

            <div class="col-lg-3">
                <label for="booking_date" class="form-label">{{ trans('messages.booking_date_lang',[],session('locale')) }}</label>
                <input type="text" class="form-control booking_date datepick" readonly value="<?php echo date('Y-m-d'); ?>" id="booking_date">
            </div>
            <div class="col-lg-3">
                <label for="pickup" class="form-label">{{ trans('messages.pickup_lang',[],session('locale')) }}</label>
                <select class="form-control pickup" name="pickup">
                    <option value="">{{ trans('messages.choose_lang',[],session('locale')) }}</option>
                    @foreach ($view_location as $pickup)
                        <option value="{{ $pickup->id }}">{{ $pickup->location_name.' ('.$pickup->location_cost.')' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label for="dropoff" class="form-label">{{ trans('messages.dropoff_lang',[],session('locale')) }}</label>
                <select class="form-control dropoff" name="dropoff">
                    <option value="">{{ trans('messages.choose_lang',[],session('locale')) }}</option>
                    @foreach ($view_location as $dropoff)
                        <option value="{{ $dropoff->id }}">{{ $dropoff->location_name.' ('.$dropoff->location_cost.')' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label for="attributes" class="form-label">{{ trans('messages.attributes_lang',[],session('locale')) }}</label>
                <select class="form-control attributes" name="attributes[]"
                id="attributes" multiple>
                    <option value="">{{ trans('messages.choose_lang',[],session('locale')) }}</option>
                    @foreach ($view_attributes as $attributes)
                        <option value="{{ $attributes->id }}">{{ $attributes->attr_name }}</option>
                    @endforeach
                </select>
            </div>
        </div><br>
        <div class="row">
            <div class="col-lg-12">
                <h3>{{ trans('messages.service_detail_lang',[],session('locale')) }}</h3>
            </div>
            <div class="col-md-12">
                @foreach ($view_extras as $extra)
                    <div class="col-lg-3">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="{{ $extra->extra_name }}" id="formCheck{{ $extra->id }}">
                            <label class="form-check-label" for="formCheck{{ $extra->id }}">
                                {{ $extra->extra_name.' ('.$extra->cost.')' }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div><br>
        <div class="row">
            <div class="col-lg-3">
                <label for="notes" class="form-label">{{ trans('messages.notes_lang',[],session('locale')) }}</label>
                <textarea class="form-control notes" name="notes" id="notes" rows="5"></textarea>
            </div>
        </div>



        <!-- New Row for the Second Card Body -->

    </div>

</form>



        </div> <!-- container-fluid -->
    </div>




    @include('layouts.footer')
    @endsection


