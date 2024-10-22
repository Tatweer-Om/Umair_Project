<script type="text/javascript">
    $(document).ready(function() {
        $('#add_attr_modal').on('hidden.bs.modal', function() {
            $(".add_attr")[0].reset();
            $('.attr_id').val('');
        });
        $('#all_attr').DataTable({
            "sAjaxSource": "{{ url('show_attr') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
        });

        $('.add_attr').off().on('submit', function(e){
            e.preventDefault();
            var formdatas = new FormData($('.add_attr')[0]);
            var title=$('.attr_name').val();
            var id=$('.attr_id').val();

            if(id!='')
            {
                if(title=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_attr_name_lang',[],session('locale')); ?>'); return false;
                }
                $('#global-loader').show();
                before_submit();
                var str = $(".add_attr").serialize();
                $.ajax({
                    type: "POST",
                    url: "{{ url('update_attr') }}",
                    data: formdatas,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('success','<?php echo trans('messages.data_updated_successful_lang',[],session('locale')); ?>');
                        $('#add_attr_modal').modal('hide');
                        $('#all_attr').DataTable().ajax.reload();
                        return false;
                    },
                    error: function(data)
                    {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('error','<?php echo trans('messages.data_updated_failed_lang',[],session('locale')); ?>');
                        $('#all_attr').DataTable().ajax.reload();
                        console.log(data);
                        return false;
                    }
                });
            }
            else if(id==''){


                if(title=="" )
                {
                    show_notification('error','<?php echo trans('messages.add_attr_name_lang',[],session('locale')); ?>'); return false;

                }

                $('#global-loader').show();
                before_submit();
                var str = $(".add_attr").serialize();
                $.ajax({
                    type: "POST",
                    url: "{{ url('add_attr') }}",
                    data: formdatas,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#global-loader').hide();
                        after_submit();
                        $('#all_attr').DataTable().ajax.reload();
                        show_notification('success','<?php echo trans('messages.data_added_successful_lang',[],session('locale')); ?>');
                        $('#add_attr_modal').modal('hide');
                        $(".add_attr")[0].reset();
                        return false;
                        },
                    error: function(data)
                    {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('error','<?php echo trans('messages.data_added_failed_lang',[],session('locale')); ?>');
                        $('#all_attr').DataTable().ajax.reload();
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
            url : "{{ url('edit_attr') }}",
            method : "POST",
            data :   {id:id,_token: csrfToken},
            success: function(fetch) {
                $('#global-loader').hide();
                after_submit();
                if(fetch!=""){
                    $(".attr_name").val(fetch.attr_name);
                    $(".attr_id").val(fetch.attr_id);
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
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{ url('delete_attr') }}",
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
                        $('#all_attr').DataTable().ajax.reload();
                        show_notification('success', '<?php echo trans('messages.data_deleted_successful_lang',[],session('locale')); ?>');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_notification('success', '<?php echo trans('messages.data_safe_lang',[],session('locale')); ?>');
            }
        });
    }

</script>
