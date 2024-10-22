<script type="text/javascript">
    $(document).ready(function() {
        $('#add_service_modal').on('hidden.bs.modal', function() {
            $(".add_service")[0].reset();
            $('.service_id').val('');
        });
        $('#services').DataTable({
            "sAjaxSource": "{{ url('show_service') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
        });


        $('.add_service').off().on('submit', function(e){
            e.preventDefault();
            var formdatas = new FormData($('.add_service')[0]);
            var service_name=$('.service_name').val();
            var id=$('.service_id').val();

            if(id!='')
            {
                if(service_name=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_service_name_lang',[],session('locale')); ?>'); return false;
                }
                $('#global-loader').show();
                before_submit();
                var str = $(".add_service").serialize();
                $.ajax({
                    type: "POST",
                    url: "{{ url('update_service') }}",
                    data: formdatas,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('success','<?php echo trans('messages.data_update_success_lang',[],session('locale')); ?>');
                        $('#add_service_modal').modal('hide');
                        $('#services').DataTable().ajax.reload();
                        return false;
                    },
                    error: function(data)
                    {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('error','<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>');
                        $('#services').DataTable().ajax.reload();
                        console.log(data);
                        return false;
                    }
                });
            }
            else if(id==''){


                if(service_name=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_service_name_lang',[],session('locale')); ?>'); return false;
                }

                $('#global-loader').show();
                before_submit();
                var str = $(".add_service").serialize();
                $.ajax({
                    type: "POST",
                    url: "{{ url('add_service') }}",
                    data: formdatas,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#global-loader').hide();
                        after_submit();
                        $('#services').DataTable().ajax.reload();
                        show_notification('success','<?php echo trans('messages.data_add_success_lang',[],session('locale')); ?>');
                        $('#add_service_modal').modal('hide');
                        $(".add_service")[0].reset();
                        return false;
                        },
                    error: function(data)
                    {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('error','<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>');
                        $('#services').DataTable().ajax.reload();
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
            url : "{{ url('edit_service') }}",
            method : "POST",
            data :   {id:id,_token: csrfToken},
            success: function(fetch) {
                $('#global-loader').hide();
                after_submit();
                if(fetch!=""){

                    $(".search_car").val(fetch.search_car);
                    $(".current_km").val(fetch.current_km);
                    $(".service_duration").val(fetch.service_duration);
                    $(".service_expense").val(fetch.service_expense);
                    $(".service_date").val(fetch.service_date);
                    $(".next_service_date").val(fetch.next_service_date);
                    $(".notes").val(fetch.notes);
                    $(".service_id").val(fetch.service_id);
                    $(".modal-title").html('Update');
                }
            },
            error: function(html)
            {
                $('#global-loader').hide();
                after_submit();
                show_notification('error','<?php echo trans('messages.edit_failed_lang',[],session('locale')); ?>');
                console.log(html);
                return false;
            }
        });
    }


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
                    url: "{{ url('delete_service') }}",
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
                        $('#services').DataTable().ajax.reload();
                        show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_notification('success', '<?php echo trans('messages.data_is_safe_lang',[],session('locale')); ?>');
            }
        });
    }


    //search_car
    // $(document).ready(function() {
    // $(".search_car").autocomplete({
    //     source: function(request, response) {
    //         $.ajax({
    //             url: "{{ url('search_car') }}",
    //             type: "POST",
    //             dataType: "json",
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             },
    //             data: {
    //                 term: request.term
    //             },
    //             success: function(data) {
    //                 // Send data to the autocomplete dropdown
    //                 response(data);
    //             },
    //             error: function(xhr, status, error) {
    //                 console.error('Error fetching car data:', xhr.responseText);
    //                 alert('Error fetching car data.');
    //             }
    //         });
    //     },
    //     select: function(event, ui) {
    //         // When a car is selected, set the value in the input
    //         $(".search_car").val(ui.item.value);
    //         console.log('Selected Car ID:', ui.item.car_id);
    //         return false; // Prevents default behavior of replacing the input value
    //     },
    //     appendTo: "modal",  // Ensures the dropdown is appended to the body, avoiding modal overflow issues
    //     open: function() {
    //         $(".ui-autocomplete").css({
    //             'z-index': 1051,  // Ensures the dropdown appears on top of the Bootstrap modal
    //             'position': 'absolute' // Ensures the dropdown is properly positioned
    //         });
    //     },
    //     minLength: 2  // Start searching after 2 characters
    // });







    </script>
