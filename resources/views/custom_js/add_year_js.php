<script type="text/javascript">
    $(document).ready(function() {
        $('#add_year_modal').on('hidden.bs.modal', function() {
            $(".add_year")[0].reset();
            $('.year_id').val('');
        });
        $('#all_year').DataTable({
            "sAjaxSource": "<?php  echo url('show_year'); ?>",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
        });

        $('.add_year').off().on('submit', function(e){
            e.preventDefault();
            var formdatas = new FormData($('.add_year')[0]);
            var title=$('.year_name').val();
            var brand=$('.brand_id').val();
            var model=$('.model_id').val();
            var price=$('.price').val();
            var id=$('.year_id').val();

            if(id!='')
            {
                if(title=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_year_name_lang',[],session('locale')); ?>'); return false;
                }
                if(brand=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_brand_name_lang',[],session('locale')); ?>'); return false;
                }
                if(model=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_model_name_lang',[],session('locale')); ?>'); return false;
                }
                if(price=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_price_lang',[],session('locale')); ?>'); return false;
                }
                $('#global-loader').show();
                before_submit();
                var str = $(".add_year").serialize();
                $.ajax({
                    type: "POST",
                    url: "<?php  echo url('update_year') ?>",
                    data: formdatas,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('success','<?php echo trans('messages.data_updated_successful_lang',[],session('locale')); ?>');
                        $('#add_year_modal').modal('hide');
                        $('#all_year').DataTable().ajax.reload();
                        return false;
                    },
                    error: function(data)
                    {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('error','<?php echo trans('messages.data_updated_failed_lang',[],session('locale')); ?>');
                        $('#all_year').DataTable().ajax.reload();
                        console.log(data);
                        return false;
                    }
                });
            }
            else if(id==''){

                if(title=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_year_name_lang',[],session('locale')); ?>'); return false;

                }
                if(brand=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_brand_name_lang',[],session('locale')); ?>'); return false;
                }
                if(model=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_model_name_lang',[],session('locale')); ?>'); return false;
                }
                if(price=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_price_lang',[],session('locale')); ?>'); return false;
                }

                $('#global-loader').show();
                before_submit();
                var str = $(".add_year").serialize();
                $.ajax({
                    type: "POST",
                    url: "<?php  echo url('add_year') ?>",
                    data: formdatas,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#global-loader').hide();
                        after_submit();
                        $('#all_year').DataTable().ajax.reload();
                        show_notification('success','<?php echo trans('messages.data_added_successful_lang',[],session('locale')); ?>');
                        $('#add_year_modal').modal('hide');
                        $(".add_year")[0].reset();
                        return false;
                        },
                    error: function(data)
                    {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('error','<?php echo trans('messages.data_added_failed_lang',[],session('locale')); ?>');
                        $('#all_year').DataTable().ajax.reload();
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
            url : "<?php  echo url('edit_year') ?>",
            method : "POST",
            data :   {id:id,_token: csrfToken},
            success: function(fetch) {
                $('#global-loader').hide();
                after_submit();
                if(fetch!=""){

                    $(".year_name").val(fetch.year_name);
                    $(".brand_id").val(fetch.brand_id);
                    $(".model_id").html(fetch.model_id);
                    $(".price").val(fetch.price);
                    $(".year_id").val(fetch.year_id);
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

    function del(id) {
        Swal.fire({
            title:  '<?php echo trans('messages.sure_lang',[],session('locale')); ?>',
            text:  '<?php echo trans('messages.wanna_delete_lang',[],session('locale')); ?>',
            type: "warning",
            showCancelButton: !0,
            confirmButtonyear: "#3085d6",
            cancelButtonyear: "#d33",
            confirmButtonText:  '<?php echo trans('messages.delete_lang',[],session('locale')); ?>',
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1
        }).then(function (result) {
            if (result.value) {
                $('#global-loader').show();
                before_submit();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "<?php  echo url('delete_year') ?>",
                    type: 'POST',
                    data: {id: id,_token: csrfToken},
                    error: function () {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('error', '<?php echo trans('messages.data_delete_failed_lang',[],session('locale')); ?>');
                    },
                    success: function (data) {
                        $('#global-loader').hide();
                        after_submit();
                        $('#all_year').DataTable().ajax.reload();
                        show_notification('success', '<?php echo trans('messages.data_deleted_successful_lang',[],session('locale')); ?>');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_notification('success', '<?php echo trans('messages.data_safe_lang',[],session('locale')); ?>');
            }
        });
    }

    // get models
    $(document).on('change','.brand_id',function(e){
        e.preventDefault();
        var brand_id = $(this).val();
        var form_data = new FormData();
        form_data.append('brand_id',brand_id);
        $.ajax({
            url:"<?php  echo url('get_models') ?>",
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
                $('.model_id').html(response.models);
            }
        });
    });



</script>
