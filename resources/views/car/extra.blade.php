
@extends('layouts.header')

@section('main')
@push('title')
<title> {{ trans('messages.menu_extra_lang',[],session('locale')) }}</title>
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
                        <h4 class="mb-sm-0 font-size-18">{{ trans('messages.menu_extra_lang',[],session('locale')) }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ trans('messages.pages_lang',[],session('locale')) }}</a></li>
                                <li class="breadcrumb-item active">{{ trans('messages.menu_extra_lang',[],session('locale')) }}</li>
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
                            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#add_extra_modal">
                                {{ trans('messages.add_data_lang',[],session('locale')) }}
                            </button>
                        </div>
                        <div class="card-body">

                            <table id="all_extra" class="table table-bordered dt-responsive  nowrap w-100">
                                <thead>
                                    <tr>
                                    <th>#</th>
                                    <th>{{ trans('messages.extra_name_lang',[],session('locale')) }}</th>
                                    <th>{{ trans('messages.cost_lang',[],session('locale')) }}</th>
                                    <th>{{ trans('messages.created_by_lang',[],session('locale')) }}</th>
                                    <th>{{ trans('messages.created_at_lang',[],session('locale')) }}</th>
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
    <div class="modal fade" id="add_extra_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">{{ trans('messages.add_data_lang',[],session('locale')) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" class="add_extra" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="extra_id" name="extra_id">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="extra_name" class="form-label">{{ trans('messages.extra_name_lang',[],session('locale')) }}</label>
                                            <input class="form-control extra_name" name="extra_name" type="text" id="extra_name">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="cost" class="form-label">{{ trans('messages.cost_lang',[],session('locale')) }}</label>
                                            <input class="form-control cost isnumber" name="cost" type="text" id="cost">
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
                                    <div class="col-md-12 text-center cursor-pointer " id="ad_cover_container">
                                        <img src="{{asset('custom_images/dummy/cover-image-icon.png') }}" id="ad_cover_preview" class="img-fluid">
                                    </div>
                                    <input type="file" name="extra_image" id="ad_cover" class="d-none extra_image">
                                </div>
                                <hr>

                            </div>

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ trans('messages.close_lang',[],session('locale')) }}</button>
                    <button type="submit" class="btn btn-primary ">{{ trans('messages.submit_lang',[],session('locale')) }}</button>
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

