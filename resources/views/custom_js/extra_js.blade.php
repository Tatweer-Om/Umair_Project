<script type="text/javascript">
    $(document).ready(function() {
        $('#add_extra_modal').on('hidden.bs.modal', function() {
            $(".add_extra")[0].reset();
            $('.extra_id').val('');
        });
        $('#all_extra').DataTable({
            "sAjaxSource": "{{ url('show_extra') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
        });

        $('.add_extra').off().on('submit', function(e){
            e.preventDefault();
            var formdatas = new FormData($('.add_extra')[0]);
            var title=$('.extra_name').val();
            var amount=$('.cost').val();

            var id=$('.extra_id').val();

            if(id!='')
            {
                if(title=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_extra_name_lang',[],session('locale')); ?>'); return false;
                }
                if(amount=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_amount_lang',[],session('locale')); ?>'); return false;
                }
                $('#global-loader').show();
                before_submit();
                var str = $(".add_extra").serialize();
                $.ajax({
                    type: "POST",
                    url: "{{ url('update_extra') }}",
                    data: formdatas,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#global-loader').hide();
                        after_submit();
                        // Clear previous error messages
                        $('.alert-danger').remove();

                        // If validation errors exist, display them
                        if (data.status === 422) {
                            var errors = data.responseJSON.errors;
                            var errorList = '<div class="alert alert-danger"><ul>';
                            $.each(errors, function(key, value) {
                                errorList += '<li>' + value[0] + '</li>'; // Show the first error message for each field
                            });
                            errorList += '</ul></div>';
                            $('#formErrors').html(errorList); // Assuming you have a div with id="formErrors" to display errors
                            return false;
                        }
                        show_notification('success','<?php echo trans('messages.data_update_success_lang',[],session('locale')); ?>');
                        $('#add_extra_modal').modal('hide');
                        $('#all_extra').DataTable().ajax.reload();
                        return false;
                    },
                    error: function(data)
                    {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('error','<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>');
                        $('#all_extra').DataTable().ajax.reload();
                        console.log(data);
                        return false;
                    }
                });
            }
            else if(id==''){


                if(title=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_extra_name_lang',[],session('locale')); ?>'); return false;
                }
                if(amount=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_amount_lang',[],session('locale')); ?>'); return false;
                }
                $('#global-loader').show();
                before_submit();
                var str = $(".add_extra").serialize();
                $.ajax({
                    type: "POST",
                    url: "{{ url('add_extra') }}",
                    data: formdatas,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#global-loader').hide();
                        after_submit();
                        // Clear previous error messages
                        $('.alert-danger').remove();

                        // If validation errors exist, display them
                        if (data.status === 422) {
                            var errors = data.responseJSON.errors;
                            var errorList = '<div class="alert alert-danger"><ul>';
                            $.each(errors, function(key, value) {
                                errorList += '<li>' + value[0] + '</li>'; // Show the first error message for each field
                            });
                            errorList += '</ul></div>';
                            $('#formErrors').html(errorList); // Assuming you have a div with id="formErrors" to display errors
                            return false;
                        }
                        $('#all_extra').DataTable().ajax.reload();
                        show_notification('success','<?php echo trans('messages.data_add_success_lang',[],session('locale')); ?>');
                        $('#add_extra_modal').modal('hide');
                        $(".add_extra")[0].reset();
                        return false;
                    },
                    error: function(data)
                    {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('error','<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>');
                        $('#all_extra').DataTable().ajax.reload();
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
            url : "{{ url('edit_extra') }}",
            method : "POST",
            data :   {id:id,_token: csrfToken},
            success: function(fetch) {
                $('#global-loader').hide();
                after_submit();
                if(fetch!=""){

                    $(".extra_name").val(fetch.extra_name);
                    $(".cost").val(fetch.cost);
                    $(".notes").val(fetch.notes);

                    var imagePath = '{{ asset('images/dummy_image/no_image.png') }}';
                    // Check if the category_image is present and not an empty string

                    if (fetch.extra_image != "") {
                        imagePath = '{{ asset('custom_images/extra_images/') }}/' + fetch.extra_image;
                    }
                    $('#ad_cover_preview').attr('src',imagePath);
                    $(".extra_id").val(fetch.extra_id);
                    $(".modal-title").html('<?php echo trans('messages.update_lang',[],session('locale')); ?>');
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

        Swal.fire({
            title:  '<?php echo trans('messages.sure_lang',[],session('locale')); ?>',
            text:  '<?php echo trans('messages.delete_lang',[],session('locale')); ?>',
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: '<?php echo trans('messages.delete_it_lang',[],session('locale')); ?>',
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1
        }).then(function (result) {
            if (result.value) {
                $('#global-loader').show();
                before_submit();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{ url('delete_extra') }}",
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
                        $('#all_extra').DataTable().ajax.reload();
                        show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_notification('success',  '<?php echo trans('messages.safe_lang',[],session('locale')); ?>' );
            }
        });
    }



    </script>
