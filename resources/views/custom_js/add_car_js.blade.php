<script type="text/javascript">
const rentDatePicker = flatpickr("#start_date", {
    defaultDate: new Date(),
    onChange: function(selectedDates, dateStr, instance) {
      // When rent_date changes, update return_date to ensure it's always greater
      returnDatePicker.set('minDate', dateStr);

    }
  });

  const returnDatePicker = flatpickr("#end_date", {
    defaultDate: new Date(),
    onChange: function() {

    }
  });
function maint(id) {
    $('#return_maint_modal .maint_id').val(id);

    }
    function comp_maint(id) {
    $('#maint_complete_modal .maint_id').val(id);

    }
    $(document).ready(function() {

    $('.add_maint').on('submit', function(event) {
        event.preventDefault();
        $('#global-loader').show();
        var formData = new FormData(this);
        var title=$('#maint_name').val();
        if(title=="" )
        {
            show_notification('error','<?php echo trans('messages.add_maint_name_lang',[],session('locale')); ?>'); return false;
        }
        before_submit();
        $.ajax({
            url: "{{ url('maint_car') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            error: function() {
                $('#global-loader').hide();
                show_notification('error', '<?php echo trans('messages.maintenance_failed_lang',[],session('locale')); ?>');
            },
            success: function(data) {
                after_submit();
                $('#global-loader').hide();
                show_notification('success', '<?php echo trans('messages.send_to_maintenance_lang',[],session('locale')); ?>');
                $('#all_car').DataTable().ajax.reload();
                $('#return_maint_modal').modal('hide'); // Close the modal
            }
        });
    });

    $('#all_maint').DataTable({
            "sAjaxSource": "{{ url('show_maint_car') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
        });

    $('.maint_comp').on('submit', function(event) {
        event.preventDefault();
        $('#global-loader').show();
        var formData = new FormData(this);
        var cost=$('#maint_cost').val();
        if(cost=="" )
        {
            show_notification('error','<?php echo trans('messages.add_maint_cost_lang',[],session('locale')); ?>'); return false;
        }
        before_submit();
        $.ajax({
            url: "{{ url('maint_car_comp') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            error: function() {
                $('#global-loader').hide();
                show_notification('error', '<?php echo trans('messages.maintenance_finish_failed_lang',[],session('locale')); ?>');
                $('#all_maint').DataTable().ajax.reload();
            },
            success: function(data) {
                after_submit();
                $('#global-loader').hide();
                show_notification('success', '<?php echo trans('messages.maintenance_finish_success_lang',[],session('locale')); ?>');
                $('#maint_complete_modal').modal('hide'); // Close the modal
                $('#all_maint').DataTable().ajax.reload();
            }
        });
    });




        $('#add_car_modal').on('hidden.bs.modal', function() {
            $(".add_car")[0].reset();
            $('.car_id').val('');
            var imagePath = '{{ asset('custom_images/dummy_image/cover-image-icon.png') }}';
            $('#ad_cover_preview').attr('src',imagePath);
            $('#attachment-holder').html('');
            $('#all_attribute').html('');
        });
        $('#all_car').DataTable({
            "sAjaxSource": "{{ url('show_car') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
        });
        $('#ad_cover_container').on('click',function(e){
            $('#ad_cover').trigger('click');
        });

        $('#ad_cover').on('change',function(e){
            $('#ad_cover_preview').attr('src', window.URL.createObjectURL(this.files[0]));
        });


        $('#btn-ad-images').on('click',function(e){
            $('#ad_images').trigger('click');
        });

        // upload atttachments
        $('#ad_images').on('change',function(e){
            var attachments= $(this)[0].files.length;
            var car_id     = $('.car_id').val();
            var form_data = new FormData();
            form_data.append('car_id',car_id);
            if(attachments>0)
            {
                for (var x = 0; x <attachments; x++)
                {
                    form_data.append('attachments[]',$(this)[0].files[x]);
                }
            }
            $.ajax({
                url:"{{ url('upload_attachments') }}",
                type:'POST',
                processData:false,
                contentType: false,
                data:form_data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(response)
                {
                    $('#attachment-holder').html(response.images).fadeIn('slow');
                },
            });
        });

        // remove attachments
        $(document).on('click','.rmv-attachment',function(e){
            e.preventDefault();
            var img = $(this).closest('div').find('img').attr('src');
            var form_data = new FormData();
            form_data.append('img',img);
            $.ajax({
                url:"{{ url('remove_attachments') }}",
                type:'POST',
                processData:false,
                contentType: false,
                data:form_data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                context:this,
                success:function(response)
                {
                    // alert(response);
                    $(this).parent().parent().remove();
                }
            });
        });
        // remove edit attachments
        $(document).on('click','.e-rmv-attachment',function(e){
            e.preventDefault();
            var image_id     = $(this).attr('id');
            var car_id     = $('.car_id').val();
            var img = $(this).closest('div').find('img').attr('src');
            var form_data = new FormData();
            form_data.append('img',img);
            form_data.append('image_id',image_id);
            form_data.append('car_id',car_id);
            $.ajax({
                url:"{{ url('e_remove_attachments') }}",
                type:'POST',
                processData:false,
                contentType: false,
                data:form_data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                context:this,
                success:function(response)
                {
                    $(this).parent().parent().remove();
                    $('#attachment-holder').html(response.images).fadeIn('slow');
                }
            });
        });



        $('.add_car').off().on('submit', function(e){
            e.preventDefault();
            var formdatas = new FormData($('.add_car')[0]);
            var brand=$('.brand_name').val();
            var model=$('.model_name').val();
            var year=$('.year_name').val();
            var price=$('.price').val();
            var plate_no=$('.plate_no').val();
            var chassis_no=$('.chassis_no').val();
            var id=$('.car_id').val();

            if(id!='')
            {
                if(brand=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_brand_name_lang',[],session('locale')); ?>'); return false;
                }
                if(model=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_model_name_lang',[],session('locale')); ?>'); return false;
                }
                if(year=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_year_name_lang',[],session('locale')); ?>'); return false;
                }
                if(plate_no=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_plate_no_lang',[],session('locale')); ?>'); return false;
                }
                if(chassis_no=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_chassis_no_lang',[],session('locale')); ?>'); return false;
                }
                if(price=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_price_lang',[],session('locale')); ?>'); return false;
                }



                $('#global-loader').show();
                before_submit();
                var str = $(".add_car").serialize();
                $.ajax({
                    type: "POST",
                    url: "{{ url('update_car') }}",
                    data: formdatas,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('success','<?php echo trans('messages.data_updated_successful_lang',[],session('locale')); ?>');
                        $('#add_car_modal').modal('hide');
                        $('#all_car').DataTable().ajax.reload();
                        return false;
                    },
                    error: function(data)
                    {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('error','<?php echo trans('messages.data_updated_failed_lang',[],session('locale')); ?>');
                        $('#all_car').DataTable().ajax.reload();
                        console.log(data);
                        return false;
                    }
                });
            }
            else if(id==''){


                if(brand=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_brand_name_lang',[],session('locale')); ?>'); return false;
                }
                if(model=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_model_name_lang',[],session('locale')); ?>'); return false;
                }
                if(year=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_year_name_lang',[],session('locale')); ?>'); return false;
                }
                if(plate_no=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_plate_no_lang',[],session('locale')); ?>'); return false;
                }
                if(chassis_no=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_chassis_no_lang',[],session('locale')); ?>'); return false;
                }
                if(price=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_price_lang',[],session('locale')); ?>'); return false;
                }

                $('#global-loader').show();
                before_submit();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var formdatas = new FormData($(".add_car")[0]); // Create FormData
                formdatas.append('_token', csrfToken);
                var str = $(".add_car").serialize();
                $.ajax({
                    type: "POST",
                    url: "{{ url('add_car') }}",
                    data: formdatas,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#global-loader').hide();
                        after_submit();
                        $('#all_car').DataTable().ajax.reload();
                        show_notification('success','<?php echo trans('messages.data_added_successful_lang',[],session('locale')); ?>');
                        $('#add_car_modal').modal('hide');
                        $(".add_car")[0].reset();
                        return false;
                        },
                    error: function(data)
                    {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('error','<?php echo trans('messages.data_added_failed_lang',[],session('locale')); ?>');
                        $('#all_car').DataTable().ajax.reload();
                        console.log(data);
                        return false;
                    }
                });

            }

        });
    });
    function edit(id){
        $('#global-loader').show();
        before_submit();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax ({
            dataType:'JSON',
            url : "{{ url('edit_car') }}",
            method : "POST",
            data :   {id:id,_token: csrfToken},
            success: function(fetch) {
                $('#global-loader').hide();
                after_submit();
                if(fetch!=""){

                    // / Define a variable for the image path
                    var imagePath = '{{ asset('custom_images/dummy_image/cover-image-icon.png') }}';

                    // Check if the category_image is present and not an empty string
                    if (fetch.car_image && fetch.car_image !== "") {
                        imagePath = '{{ asset('custom_images/car_image/') }}/' + fetch.car_image;
                    }
                    $('#ad_cover_preview').attr('src',imagePath);
                    $("#attachment-holder").html(fetch.all_images);
                    $(".brand_name").val(fetch.brand_name);
                    $(".model_name").html(fetch.model_name);
                    $(".year_name").html(fetch.year_name);
                    $(".insurance_company_name").val(fetch.insurance_company);
                    $(".insurance_expiry_date").val(fetch.insurance_expiry_date);
                    $(".color_name").val(fetch.color_name);
                    $(".plate_no").val(fetch.plate_no);
                    $(".chassis_no").val(fetch.chassis_no);
                    $(".mulkia_expiry_date").val(fetch.mulkia_expiry_date);
                    $(".vms_expiry").val(fetch.vms_expiry);
                    $(".trans_min_expiry").val(fetch.trans_min_expiry);
                    $(".per_day_price").val(fetch.per_day_price);
                    $(".per_week_price").val(fetch.per_week_price);
                    $(".per_month_price").val(fetch.per_month_price);
                    $(".notes").val(fetch.notes);
                    $(".car_id").val(fetch.car_id);
                    $(".modal-title").html('<?php echo trans('messages.update_data_lang',[],session('locale')); ?>');
                }
            },
            error: function(html)
            {
                $('#global-loader').hide();
                after_submit();
                show_notification('error','<?php echo trans('messages.data_edit_failed_lang',[],session('locale')); ?>');
                console.log(html);
                return false;
            }
        });
    }


    // Example: Call the function when the page is ready or a customer is selected
    let carId = $('#car_id').val();

    // Replace with the actual car ID dynamically
    loadcarProfileData(carId);

    function loadcarProfileData(carId) {
    $.ajax({
        url: "{{ url('car_profile_data') }}",
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            car_id: carId
        },
        success: function(response) {
            let bookingsTable = $('#all_profile_docs_1 tbody');
            let upcomingTable = $('#all_profile_docs_2 tbody');
            let currentBookings = response.current_bookings;
            let bookingList = $('#current_bookings');
            $('#booking_count').text(response.total_bookings || 0);
            $('#total_payment').text(response.total_amount || 0);
            $('#total_panelty').text(response.total_panelty || 0);
            $('#up_booking').text(response.upcoming_bookings_count || 0);

            bookingsTable.empty();
            upcomingTable.empty();
            bookingList.empty();


            // Loop through bookings and append to the table
            $.each(response.bookings, function(index, booking) {
                let bill = Array.isArray(booking.bills) && booking.bills.length > 0 ? booking.bills[0] : null;
                let bookingRow = `
                    <tr>
                        <td style="text-align:center; width:5%;">${index + 1}</td>
                        <td style="text-align:center;width:25%;">
                            <span>${'{{ trans("messages.car_name_lang") }}: '}${booking.car ? booking.car.car_name : '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.brand_name_lang") }}: '}${booking.car && booking.car.brand ? booking.car.brand.brand_name : '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.category_name_lang") }}: '}${booking.car && booking.car.category ? booking.car.category.category_name : '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.color_lang") }}: '}${booking.car && booking.car.color ? booking.car.color.color_name : '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.size_lang") }}: '}${booking.car && booking.car.size ? booking.car.size.size_name : '{{ trans("messages.na_lang") }}'}</span>
                        </td>
                        <td style="text-align:center; width:25%;">
                            <span>${'{{ trans("messages.booking_no_lang") }}: '}${booking.booking_no || '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.booking_date_lang") }}: '}${booking.booking_date || '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.return_date_lang") }}: '}${booking.return_date || '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.rent_date_lang") }}: '}${booking.rent_date || '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.duration_lang") }}: '}${booking.duration || '{{ trans("messages.na_lang") }}'} days</span>
                        </td>
                        <td style="text-align:center; width:25%;">
                            <span>${'{{ trans("messages.rent_price_lang") }}: '}${bill ? bill.total_price : '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.discount_lang") }}: '}${bill ? bill.total_discount : '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.total_penalty_lang") }}: '}${bill ? bill.total_penalty : '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.grand_total_lang") }}: '}${bill ? bill.grand_total : '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.remaining_lang") }}: '}${bill ? bill.total_remaining : '{{ trans("messages.na_lang") }}'}</span>
                        </td>
                        <td style="text-align:center; width:20%;">
                            <span>${'{{ trans("messages.added_by_lang") }}: '}${booking.added_by || '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.created_at_lang") }}: '}${booking.created_at ? get_date_only(booking.created_at) : '{{ trans("messages.na_lang") }}'}</span>
                        </td>
                    </tr>
                `;
                bookingsTable.append(bookingRow);
            });

            $('#all_profile_docs_1').DataTable({
        destroy: true,  // Allows re-initializing table multiple times
        responsive: true
    });

            // Loop through upcoming bookings if needed
            $.each(response.up_bookings, function(index, booking) {
                let bill = Array.isArray(booking.bills) && booking.bills.length > 0 ? booking.bills[0] : null;

                let upcomingRow = `
                    <tr>
                        <td style="text-align:center;">${index + 1}</td>
                        <td style="text-align:center;width:25%;">
                            <span>${'{{ trans("messages.car_name_lang") }}: '}${booking.car ? booking.car.car_name : '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.brand_name_lang") }}: '}${booking.car && booking.car.brand ? booking.car.brand.brand_name : '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.category_name_lang") }}: '}${booking.car && booking.car.category ? booking.car.category.category_name : '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.color_lang") }}: '}${booking.car && booking.car.color ? booking.car.color.color_name : '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.size_lang") }}: '}${booking.car && booking.car.size ? booking.car.size.size_name : '{{ trans("messages.na_lang") }}'}</span>
                        </td>
                        <td style="text-align:center; width:25%;">
                            <span>${'{{ trans("messages.booking_no_lang") }}: '}${booking.booking_no || '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.booking_date_lang") }}: '}${booking.booking_date || '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.return_date_lang") }}: '}${booking.return_date || '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.rent_date_lang") }}: '}${booking.rent_date || '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.duration_lang") }}: '}${booking.duration || '{{ trans("messages.na_lang") }}'} days</span>
                        </td>
                        <td style="text-align:center; width:25%;">
                            <span>${'{{ trans("messages.rent_price_lang") }}: '}${bill ? bill.total_price : '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.discount_lang") }}: '}${bill ? bill.total_discount : '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.total_penalty_lang") }}: '}${bill ? bill.total_penalty : '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.grand_total_lang") }}: '}${bill ? bill.grand_total : '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.remaining_lang") }}: '}${bill ? bill.total_remaining : '{{ trans("messages.na_lang") }}'}</span>
                        </td>
                        <td style="text-align:center; width:20%;">
                            <span>${'{{ trans("messages.added_by_lang") }}: '}${booking.added_by || '{{ trans("messages.na_lang") }}'}</span><br>
                            <span>${'{{ trans("messages.created_at_lang") }}: '}${booking.created_at ? get_date_only(booking.created_at) : '{{ trans("messages.na_lang") }}'}</span>
                        </td>
                    </tr>
                `;
                upcomingTable.append(upcomingRow);
            });

            if (currentBookings.length > 0) {
                $.each(currentBookings, function(index, booking) {
                    let bookingItem = `
                        <a href="javascript: void(0);" class="list-group-item text-muted pb-3 pt-0 px-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h3 class="font-size-20 text-truncate">${booking.car.car_name || 'N/A'}</h3>
                                      <p class="text-danger">${'{{ trans("messages.duration_lang") }}: '} ${booking.duration || 'N/A'} days  </p>
                                    <p class="text-danger">${'{{ trans("messages.rent_date_lang") }}: '} ${booking.booking_date || 'N/A'}  </p>
                                      <p class="text-danger">${'{{ trans("messages.return_date_lang") }}: '} ${booking.return_date || 'N/A'}  </p>
                                </div>
                                <div class="fs-1">
                                    <i class="mdi mdi-calendar"></i>
                                </div>
                            </div>
                        </a>
                    `;
                    bookingList.append(bookingItem);
                });
            } else {
                bookingList.append('<a href="javascript: void(0);" class="list-group-item text-muted pb-3 pt-0 px-2">{{ trans("messages.no_booking_lang") }}</a>');
            }
        },

        error: function(xhr) {
            console.error('Error loading car profile data: ', xhr.responseText);
        }

    });
}


// get models
    $(document).on('change','.brand_name',function(e){
        e.preventDefault();
        var brand_id = $(this).val();
        var form_data = new FormData();
        form_data.append('brand_id',brand_id);
        $.ajax({
            url:"<?php  echo url('get_car_models') ?>",
            type:'POST',
            processData:false,
            contentType: false,
            data:form_data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            context:this,
            success:function(response)
            {
                $('.model_name').html(response.models);
            }
        });
    });

    // get models
    $(document).on('change','.model_name',function(e){
        e.preventDefault();
        var brand_id = $('.brand_name').val();
        var model_id = $(this).val();
        var form_data = new FormData();
        form_data.append('model_id',model_id);
        form_data.append('brand_id',brand_id);
        $.ajax({
            url:"<?php  echo url('get_car_years') ?>",
            type:'POST',
            processData:false,
            contentType: false,
            data:form_data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            context:this,
            success:function(response)
            {
                $('.year_name').html(response.years);
            }
        });
    });

    // get models
    $(document).on('change','.year_name',function(e){
        e.preventDefault();
        var brand_id = $('.brand_name').val();
        var model_id = $('.model_name').val();
        var year_id = $(this).val();
        var form_data = new FormData();
        form_data.append('year_id',year_id);
        form_data.append('brand_id',brand_id);
        form_data.append('model_id',model_id);
        $.ajax({
            url:"<?php  echo url('get_car_price') ?>",
            type:'POST',
            processData:false,
            contentType: false,
            data:form_data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            context:this,
            success:function(response)
            {
                $('.price').val(response.price);
            }
        });
    });


    function del(id) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        Swal.fire({
            title:  '<?php echo trans('messages.sure_lang',[],session('locale')); ?>',
            text:  '<?php echo trans('messages.wanna_delete_lang',[],session('locale')); ?>',
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText:  '<?php echo trans('messages.delete_lang',[],session('locale')); ?>',
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1
        }).then(function (result) {
            if (result.value) {
                $('#global-loader').show();
                before_submit();
                $.ajax({
                    url: "{{ url('delete_car') }}",
                    type: 'POST',
                    data: {id: id,_token: csrfToken},
                    error: function () {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('error', '<?php echo trans('messages.delete_failed_lang',[],session('locale')); ?>');
                    },
                    success: function (data) {
                        $('#global-loader').hide();
                        after_submit();
                        $('#all_car').DataTable().ajax.reload();
                        show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_notification('success', '<?php echo trans('messages.data_is_safe_lang',[],session('locale')); ?>');
            }
        });
    }


</script>
