<script src="<?= base_url('plugins/formbuilder/form-builder.min.js?ver=' . $n_config['theme_version']) ?>"></script>
<script>

    var assign_label = $('#assign-label-wrapper'),
        reply_template = $('#reply-template-wrapper');

    function generate_select_box(
        options_array,
        name_attribute,
        multiple = false
    ) {
        var multi_select = multiple ? 'multiple' : '';
        var str = '';
        str += '<select class="form-control" name="' + name_attribute + '" id="' + name_attribute + '" ' + multi_select + '>';
        str += '<option value=""></option>';

        if (Array.isArray(options_array)) {
            options_array.forEach(option => {
                str += '<option value="' + option.value + '">' + option.text + '</option>';
            });
        }

        str += '</select>';

        return str;
    }

    function changePagehandlers() {

        var page_id = $("#page_table_id").val();

        // Hides select boxes primarily
        $(assign_label).add(reply_template).hide()

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            data: {page_id},
            url: '<?= site_url() ?>messenger_bot_connectivity/handle_select_boxes_data',
            success: function (data) {
                if (data.assign_labels && Array.isArray(data.assign_labels)) {
                    var select_assign_labels = generate_select_box(data.assign_labels, 'assign-label', true)
                    $('#select-assign-label').html(select_assign_labels);
                    $('#assign-label').select2({
                        width: '100%',
                        placeholder: '<?php echo $this->lang->line('Select label') ?>'
                    });

                    //push page id for label create
                    $("#create_label_webview").attr("page_id_for_label", page_id);


                    $(assign_label).show()
                }

                if (data.reply_template && Array.isArray(data.reply_template)) {
                    var select_reply_template = generate_select_box(data.reply_template, 'reply-template')
                    $('#select-reply-template').html(select_reply_template);
                    $('#reply-template').select2({
                        width: '100%',
                        placeholder: '<?php echo $this->lang->line('Select template') ?>'
                    })
                    $(reply_template).show();

                    // push page_id into add & refresh postback button
                    $(".add_template").attr("page_id_add_postback", page_id);
                    $(".ref_template").attr("page_id_refresh_postback", page_id);
                }
            }
        })
    }

    $(document).ready(function () {
        var base_url = "<?php echo site_url(); ?>";
        var select_page = $('#select-page');
        $(select_page).select2({
            width: '100%',
            placeholder: '<?php echo $this->lang->line('Select page') ?>'
        })

        $(document).on('change', '#select-page', function () {
            changePagehandlers();
        });

        var page_id = $("#page_table_id").val();

        if (page_id != '') changePagehandlers();

        // ===================== add & refresh postback section ====================

        // getting postback list and making iframe
        $('#add_template_modal').on('shown.bs.modal', function () {
            var page_id = $(".add_template").attr("page_id_add_postback");
            var iframe_link = "<?php echo base_url('messenger_bot/create_new_template/1/');?>" + page_id;
            $(this).find('iframe').attr('src', iframe_link);
        });
        // getting postback list and making iframe

        // add postback template modal
        $(document).on('click', '.add_template', function (e) {
            e.preventDefault();

            var page_id = $(this).attr("page_id_add_postback");
            if (page_id == "") {
                swal.fire('<?php echo $this->lang->line("Error"); ?>', "<?php echo $this->lang->line('Please select a page first')?>", 'error');
                return false;
            }
            $("#add_template_modal").modal();
        });

        $(document).on('click', '.ref_template', function (e) {
            e.preventDefault();
            var current_val = $("#reply-template").val();
            var page_id = $(this).attr("page_id_refresh_postback");
            var str = '';

            if (page_id == "") {
                swal.fire('<?php echo $this->lang->line("Error"); ?>', "<?php echo $this->lang->line('Please select a page first')?>", 'error');
                return false;
            }

            $.ajax({
                type: 'POST',
                url: base_url + "home/common_get_postback",
                data: {page_id: page_id},
                success: function (response) {
                    str += '<select class="form-control" id="reply-template" name="reply-template" style="width:100%;">' + response + '</select>';
                    $("#select-reply-template").html(str);
                    $("#reply-template").val(current_val);
                    $('#reply-template').select2({
                        width: '100%',
                        placeholder: '<?php echo $this->lang->line('Select Template') ?>'
                    });
                }
            });
        });

        $('#add_template_modal').on('hidden.bs.modal', function (e) {
            var current_val = $("#reply-template").val();
            var page_id = $(".add_template").attr("page_id_add_postback");
            var str = '';
            if (page_id == "") {
                swal.fire('<?php echo $this->lang->line("Error"); ?>', "<?php echo $this->lang->line('Please select a page first')?>", 'error');
                return false;
            }
            $.ajax({
                type: 'POST',
                url: base_url + "home/common_get_postback",
                data: {page_id: page_id},
                success: function (response) {
                    str += '<select class="form-control" id="reply-template" name="reply-template" style="width:100%;">' + response + '</select>';
                    $("#select-reply-template").html(str);
                    $('#reply-template').select2({
                        width: '100%'
                    });
                }
            });
        });

        // ============================ Add & refresh Postback Section ===============================

        // create an new label and put inside label list
        $(document).on('click', '#create_label_webview', function (e) {
            e.preventDefault();

            var page_id = $(this).attr('page_id_for_label');

            swal.fire({
                title: "<?php echo $this->lang->line('Label Name'); ?>",
                input: "text",
                confirmButtonText: "<?php echo $this->lang->line('Create'); ?>",
                cancelButtonText: "<?php echo $this->lang->line('Cancel'); ?>",
                showCancelButton: true,
            })
                .then((value) => {
                    if (value.isDenied || value.isDismissed) {
                        return;
                    }
                    var label_name = `${value.value}`;
                    if (label_name != "" && label_name != 'null') {
                        $("#save_changes").addClass("btn-progress");
                        $.ajax({
                            context: this,
                            type: 'POST',
                            dataType: 'JSON',
                            url: "<?php echo site_url();?>home/common_create_label_and_assign",
                            data: {page_id: page_id, label_name: label_name},
                            success: function (response) {

                                $("#save_changes").removeClass("btn-progress");

                                if (response.error) {
                                    var span = document.createElement("span");
                                    span.innerHTML = response.error;

                                    swal.fire({
                                        icon: 'error',
                                        title: '<?php echo $this->lang->line('Error'); ?>',
                                        html: span,
                                    });

                                } else {
                                    var newOption = new Option(response.text, response.id, true, true);
                                    $('#assign-label').append(newOption).trigger('change');
                                }
                            }
                        });
                    }
                });
        });

        var options = {
            // Makes fields to be used for one time only
            allowOneTimeFields: ['button'],

            // Decides whether controls should be draggable or not
            draggableControls: true,

            // Disables action button
            disabledActionButtons: ['data'], // save, data, clear

            // event to be used when saving data
            onSave: function (e, formData) {
                e.preventDefault()

                // Prepares data
                var parsed_form_data = JSON.parse(formData)

                // Shows error if button field doesn't exist
                if (Array.isArray(parsed_form_data)) {
                    var found = parsed_form_data.find((val) => {
                        if (val && val.hasOwnProperty('type')) {
                            return val.type === 'button'
                        }
                    })

                    if (!found) {
                        swal.fire('<?php echo $this->lang->line('Warning!') ?>', '<?php echo $this->lang->line('You forgot to choose a button field') ?>', 'warning')

                        return
                    }
                }

                // Starts loading state
                e.target.classList.remove('disabled', 'btn-progress')
                e.target.classList.add('disabled', 'btn-progress')

                // Prepares form data to be submitted
                var form_data = {
                    user_id: '<?= md5($user_id) ?>',
                    form_name: $('#form-name').val(),
                    form_title: $('#form-title').val(),
                    page_id: $('#select-page').val(),
                    assign_label: $('#assign-label').val() ? $('#assign-label').val().join() : '',
                    reply_template: $('#reply-template').val(),
                    form_data: formData
                }

                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('messenger_bot_connectivity/save_form_data') ?>',
                    dataType: 'JSON',
                    data: form_data || null,
                    success: function (response) {
                        if (response) {
                            if (response.success === true) {
                                // Shows success message
                                swal.fire({
                                    title: '<?php echo $this->lang->line('Success!'); ?>',
                                    text: response.message,
                                    icon: 'success'
                                })

                                // Changes loading state
                                e.target.classList.remove('disabled', 'btn-progress')

                                // Empties fields
                                if (parsed_form_data.length) {
                                    // Clears form name
                                    document.getElementById('form-name').value = ''
                                    document.getElementById('form-title').value = ''

                                    // Resets page selection
                                    $('#select-page').val(null).trigger("change")

                                    // Clears and hides other select boxes
                                    $('#assign-label-wrapper').add('#reply-template-wrapper').hide();

                                    $('#assign-label').val(null).trigger("change")
                                    $('#reply-template').val(null).trigger("change")

                                    // Clears form builders
                                    var clearAll = document.querySelector('.clear-all')
                                    $(clearAll).trigger('click')

                                    // Redirects to webview manager
                                    setTimeout(function () {
                                        window.location.replace('<?= base_url('messenger_bot_connectivity/webview_builder_manager/') . $page_id . '/1' ?>')
                                    }, 2000)
                                }

                            } else if (response.error === true) {
                                // Shows error message
                                swal.fire({
                                    title: '<?php echo $this->lang->line('Error!'); ?>',
                                    text: response.message,
                                    icon: 'error'
                                })

                                // Changes loading state
                                e.target.classList.remove('disabled', 'btn-progress')
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log('xhr: ', xhr)
                        console.log('status: ', status)

                        // Shows HTTP status error
                        swal.fire({
                            title: '<?php echo $this->lang->line('Error!'); ?>',
                            text: error,
                            icon: 'error'
                        })
                    }
                })
            },
        }

        $('#webview-builder').formBuilder(options)
    });
</script>