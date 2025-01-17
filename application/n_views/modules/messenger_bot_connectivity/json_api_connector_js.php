<script src="<?php echo base_url(); ?>n_assets/app-assets/vendors/js/forms/select/select2.full.min.js?ver=<?php echo $n_config['theme_version']; ?>"></script>
<script src="<?php echo base_url(); ?>n_assets/app-assets/vendors/js/extensions/moment.min.js?ver=<?php echo $n_config['theme_version']; ?>"></script>
<script type="text/javascript"
        src="<?php echo base_url(); ?>plugins/datetimepickerjquery/jquery.datetimepicker.js?ver=<?php echo $n_config['theme_version']; ?>"></script>
<script src="<?php echo base_url(); ?>n_assets/app-assets/vendors/js/pickers/daterange/daterangepicker.js?ver=<?php echo $n_config['theme_version']; ?>"></script>
<script src="<?php echo base_url(); ?>n_assets/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js?ver=<?php echo $n_config['theme_version']; ?>"></script>
<script src="<?php echo base_url(); ?>n_assets/app-assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js?ver=<?php echo $n_config['theme_version']; ?>"></script>
<script src="<?php echo base_url(); ?>n_assets/app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js?ver=<?php echo $n_config['theme_version']; ?>"></script>
<script src="<?php echo base_url(); ?>n_assets/app-assets/vendors/js/tables/datatable/buttons.html5.min.js?ver=<?php echo $n_config['theme_version']; ?>"></script>
<script src="<?php echo base_url(); ?>n_assets/app-assets/vendors/js/tables/datatable/buttons.print.min.js?ver=<?php echo $n_config['theme_version']; ?>"></script>
<script src="<?php echo base_url(); ?>n_assets/app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js?ver=<?php echo $n_config['theme_version']; ?>"></script>
<script src="<?php echo base_url(); ?>n_assets/app-assets/vendors/js/tables/datatable/pdfmake.min.js?ver=<?php echo $n_config['theme_version']; ?>"></script>
<script src="<?php echo base_url(); ?>n_assets/app-assets/vendors/js/tables/datatable/vfs_fonts.js?ver=<?php echo $n_config['theme_version']; ?>"></script>
<script src="<?php echo base_url(); ?>n_assets/plugins/summernote/summernote-bs4.js?ver=<?php echo $n_config['theme_version']; ?>"></script>

<script type="text/javascript">
    $("document").ready(function () {

        var areWeUsingScroll = false;
        //todo: areWeUsingScroll
        $(document).ready(function () {
            'use strict';

            $(".select2").select2({
                dropdownAutoWidth: true,
                width: '100%',

            });
        });

        var base_url = "<?php echo site_url(); ?>";

        $('[data-toggle="popover"]').popover();
        $('[data-toggle="popover"]').on('click', function (e) {
            e.preventDefault();
            return true;
        });


        $("#postbackid").prop("disabled", true);
        $("#formdata").prop("disabled", true);
        $("#user_input_flow_campaign").prop("disabled", true);

        setTimeout(function () {
            $('#post_date_range').daterangepicker({
                locale: daterange_locale,
                ranges: {
                    '<?php echo $this->lang->line("Last 30 Days");?>': [moment().subtract(29, 'days'), moment()],
                    '<?php echo $this->lang->line("This Month");?>': [moment().startOf('month'), moment().endOf('month')],
                    '<?php echo $this->lang->line("Last Month");?>': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            }, function (start, end) {
                $('#post_date_range_val').val(start.format('YYYY-M-D') + '|' + end.format('YYYY-M-D')).change();
            });
        }, 2000);

        // datatable section started
        var perscroll;
        var table = $("#mytable").DataTable({
            serverSide: true,
            processing: true,
            bFilter: false,
            order: [[1, "desc"]],
            pageLength: 10,
            ajax:
                {
                    "url": base_url + 'messenger_bot_connectivity/json_api_connector_dashbaord_data',
                    "type": 'POST',
                    data: function (d) {
                        d.searching = $('#searching').val();
                        d.post_date_range = $('#post_date_range_val').val();
                    }
                },
            language:
                {
                    url: "<?php echo base_url('n_assets/plugins/datatables_language/' . $this->language . '.json'); ?>"
                },
            dom: '<"top"f>rt<"bottom"lip><"clear">',
            columnDefs: [
                {
                    targets: [1],
                    visible: false
                },
                {
                    targets: [0, 1, 4, 6, 7],
                    className: 'text-center'
                },
                {
                    targets: [0, 1, 2, 3, 4, 5],
                    sortable: false
                },
                {
                    targets: [4],
                    "render": function (data, type, row) {
                        data = data.replaceAll('fas fa-user-slash', 'bx bxs-user-x');
                        data = data.replaceAll('fas fa-comment-slash', 'bx bx-comment-x');
                        data = data.replaceAll('fas fa-map', 'bx bx-map');
                        data = data.replaceAll('fas fa-birthday-cake', 'bx bx-cake');
                        data = data.replaceAll('fas fa-headset', 'bx bx-headphone');
                        data = data.replaceAll('fas fa-phone', 'bx bx-phone');
                        data = data.replaceAll('fas fa-robot', 'bx bx-bot');
                        data = data.replaceAll('fas fa-envelope', 'bx bx-envelope');
                        data = data.replaceAll('fas fa-code', 'bx bx-code');
                        data = data.replaceAll('fas fa-edit', 'bx bx-edit');
                        data = data.replaceAll('fa fa-edit', 'bx bx-edit');
                        data = data.replaceAll('fa  fa-edit', 'bx bx-edit');
                        data = data.replaceAll('far fa-copy', 'bx bx-copy');
                        data = data.replaceAll('fa fa-trash', 'bx bx-trash');
                        data = data.replaceAll('fas fa-trash', 'bx bx-trash');
                        data = data.replaceAll('fa fa-eye', 'bx bxs-show');
                        data = data.replaceAll('fas fa-eye', 'bx bxs-show');
                        data = data.replaceAll('fas fa-trash-alt', 'bx bx-trash');
                        data = data.replaceAll('fa fa-wordpress', 'bx bxl-wordpress');
                        data = data.replaceAll('fa fa-briefcase', 'bx bx-briefcase');
                        data = data.replaceAll('fab fa-wpforms', 'bx bx-news');
                        data = data.replaceAll('fas fa-file-export', 'bx bx-export');
                        data = data.replaceAll('fa fa-comment', 'bx bx-comment');
                        data = data.replaceAll('fa fa-user', 'bx bx-user');
                        data = data.replaceAll('fa fa-refresh', 'bx bx-refresh');
                        data = data.replaceAll('fa fa-plus-circle', 'bx bx-plus-circle');
                        data = data.replaceAll('fas fa-comments', 'bx bx-comment');
                        data = data.replaceAll('fa fa-hand-o-right', 'bx bx-link-external');
                        data = data.replaceAll('fab fa-facebook-square', 'bx bxl-facebook-square');
                        data = data.replaceAll('fas fa-exchange-alt', 'bx bx-repost');
                        data = data.replaceAll('fa fa-sync-alt', 'bx bx-sync');
                        data = data.replaceAll('fas fa-key', 'bx bx-key');
                        data = data.replaceAll('fas fa-bolt', 'bx bxs-bolt');
                        data = data.replaceAll('fas fa-clone', 'bx bxs-copy-alt');
                        data = data.replaceAll('fas fa-receipt', 'bx bx-receipt');
                        data = data.replaceAll('fa fa-paper-plane', 'bx bx-paper-plane');
                        data = data.replaceAll('fa fa-send', 'bx bx-send');
                        data = data.replaceAll('fas fa-hand-point-right', 'bx bx-news');
                        data = data.replaceAll('fa fa-code', 'bx bx-code');
                        data = data.replaceAll('fa fa-clone', 'bx bx-duplicate');
                        data = data.replaceAll('fas fa-pause', 'bx bx-pause');
                        data = data.replaceAll('fas fa-play', 'bx bx-play');
                        data = data.replaceAll('fa fa-cog', 'bx bx-cog');
                        data = data.replaceAll('fa fa-check-circle', 'bx bx-check-circle');
                        data = data.replaceAll('fas fa-comment', 'bx bx-comment');
                        data = data.replaceAll('swal(', 'swal.fire(');
                        data = data.replaceAll('rounded-circle', 'rounded-circle');
                        data = data.replaceAll('fas fa-check-circle', 'bx bx-check-circle');
                        data = data.replaceAll('fas fa-plug', 'bx bx-plug');
                        data = data.replaceAll('fas fa-times-circle', 'bx bx-time');
                        data = data.replaceAll('fas fa-chart-bar', 'bx bx-chart');
                        data = data.replaceAll('fas fa-cloud-download-alt', 'bx bxs-cloud-download');
                        data = data.replaceAll('padding-10', 'p-10');
                        data = data.replaceAll('padding-left-10', 'pl-10');
                        data = data.replaceAll('h4', 'h5 class="card-title font-medium-1"');
                        data = data.replaceAll('fas fa-heart', 'bx bx-heart');
                        data = data.replaceAll('fas fa-road', 'bx bx-location-plus');
                        data = data.replaceAll('fas fa-city', 'bx bxs-city');
                        data = data.replaceAll('fas fa-globe-americas', 'bx bx-globe');
                        data = data.replaceAll('fas fa-at', 'bx bx-at');
                        data = data.replaceAll('fas fa-mobile-alt', 'bx bx-mobile-alt');
                        data = data.replaceAll('<div class="dropdown-title"><?php echo $this->lang->line('Options'); ?></div>', '<h6 class="dropdown-header"><?php echo $this->lang->line('Options'); ?></h6>');
                        data = data.replaceAll('fas fa-file-signature', 'bx bxs-user-badge');
                        data = data.replaceAll('fas fa-user-circle', 'bx bxs-user');
                        data = data.replaceAll('fas fa-toggle-on', 'bx bx-toggle-right');
                        data = data.replaceAll('fas fa-toggle-off', 'bx bx-toggle-left');
                        data = data.replaceAll('width:161px', 'width:220px');


                        return data;
                    }
                },
            ],
            fnInitComplete: function () { // when initialization is completed then apply scroll plugin
                if (areWeUsingScroll) {
                    if (perscroll) perscroll.destroy();
                    perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
                }
            },
            scrollX: 'auto',
            fnDrawCallback: function (oSettings) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again
                if (areWeUsingScroll) {
                    if (perscroll) perscroll.destroy();
                    perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
                }
            },
            "drawCallback": function (settings) {
                $('table [data-toggle="tooltip"]').tooltip('dispose');
                $('table [data-toggle="tooltip"]').tooltip(
                    {
                        placement: 'left',
                        container: 'body',
                        html: true,
                        template: '<div class="tooltip tooltip_pd" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                    }
                );
            }
        });

        $(document).on('change', '#post_date_range_val', function (event) {
            event.preventDefault();
            table.draw();
        });

        $(document).on('keyup', '#searching', function (event) {
            event.preventDefault();
            table.draw();
        });
        // End of datatable section

        var table1 = '';
        var perscroll1;
        $(document).on('click', '.view_connector', function (event) {
            event.preventDefault();
            $("#view_connector_info_modal").modal();

            var table_id = $(this).attr('table_id');
            $("#view_connector_info_modal").modal();

            $.ajax({
                type: 'POST',
                url: "<?php echo site_url();?>messenger_bot_connectivity/ajax_view_connector_info",
                data: {table_id: table_id},
                success: function (data) {
                    if (data) {
                        $("#info_modal").html(data);
                    } else {
                        var loading = '<div class="text-center waiting"><i class="bx bx-loader-alt bx-spin blue text-center" style="font-size:40px"></i></div>';
                        $(".modal-body").html(loading);
                    }
                }
            });

            // loading campaign's last 10 activities data
            $("#put_row_id").val(table_id);
            setTimeout(function () {
                if (table1 == '') {
                    table1 = $("#mytable1").DataTable({
                        serverSide: true,
                        processing: true,
                        bFilter: false,
                        order: [[1, "desc"]],
                        pageLength: 10,
                        ajax: {
                            url: base_url + 'messenger_bot_connectivity/ajax_get_connector_last_activities',
                            type: 'POST',
                            data: function (d) {
                                d.table_id = $("#put_row_id").val();
                            }
                        },
                        language:
                            {
                                url: "<?php echo base_url('n_assets/plugins/datatables_language/' . $this->language . '.json'); ?>"
                            },
                        dom: '<"top"f>rt<"bottom"lip><"clear">',
                        columnDefs: [
                            {
                                targets: [1],
                                visible: false
                            },
                            {
                                targets: [0, 1, 2, 4, 5],
                                className: 'text-center'
                            },
                            {
                                targets: '',
                                sortable: false
                            },
                            {
                                targets: [4],
                                "render": function (data, type, row) {
                                    data = data.replaceAll('fas fa-user-slash', 'bx bxs-user-x');
                                    data = data.replaceAll('fas fa-comment-slash', 'bx bx-comment-x');
                                    data = data.replaceAll('fas fa-map', 'bx bx-map');
                                    data = data.replaceAll('fas fa-birthday-cake', 'bx bx-cake');
                                    data = data.replaceAll('fas fa-headset', 'bx bx-headphone');
                                    data = data.replaceAll('fas fa-phone', 'bx bx-phone');
                                    data = data.replaceAll('fas fa-robot', 'bx bx-bot');
                                    data = data.replaceAll('fas fa-envelope', 'bx bx-envelope');
                                    data = data.replaceAll('fas fa-code', 'bx bx-code');
                                    data = data.replaceAll('fas fa-edit', 'bx bx-edit');
                                    data = data.replaceAll('fa fa-edit', 'bx bx-edit');
                                    data = data.replaceAll('fa  fa-edit', 'bx bx-edit');
                                    data = data.replaceAll('far fa-copy', 'bx bx-copy');
                                    data = data.replaceAll('fa fa-trash', 'bx bx-trash');
                                    data = data.replaceAll('fas fa-trash', 'bx bx-trash');
                                    data = data.replaceAll('fa fa-eye', 'bx bxs-show');
                                    data = data.replaceAll('fas fa-eye', 'bx bxs-show');
                                    data = data.replaceAll('fas fa-trash-alt', 'bx bx-trash');
                                    data = data.replaceAll('fa fa-wordpress', 'bx bxl-wordpress');
                                    data = data.replaceAll('fa fa-briefcase', 'bx bx-briefcase');
                                    data = data.replaceAll('fab fa-wpforms', 'bx bx-news');
                                    data = data.replaceAll('fas fa-file-export', 'bx bx-export');
                                    data = data.replaceAll('fa fa-comment', 'bx bx-comment');
                                    data = data.replaceAll('fa fa-user', 'bx bx-user');
                                    data = data.replaceAll('fa fa-refresh', 'bx bx-refresh');
                                    data = data.replaceAll('fa fa-plus-circle', 'bx bx-plus-circle');
                                    data = data.replaceAll('fas fa-comments', 'bx bx-comment');
                                    data = data.replaceAll('fa fa-hand-o-right', 'bx bx-link-external');
                                    data = data.replaceAll('fab fa-facebook-square', 'bx bxl-facebook-square');
                                    data = data.replaceAll('fas fa-exchange-alt', 'bx bx-repost');
                                    data = data.replaceAll('fa fa-sync-alt', 'bx bx-sync');
                                    data = data.replaceAll('fas fa-key', 'bx bx-key');
                                    data = data.replaceAll('fas fa-bolt', 'bx bxs-bolt');
                                    data = data.replaceAll('fas fa-clone', 'bx bxs-copy-alt');
                                    data = data.replaceAll('fas fa-receipt', 'bx bx-receipt');
                                    data = data.replaceAll('fa fa-paper-plane', 'bx bx-paper-plane');
                                    data = data.replaceAll('fa fa-send', 'bx bx-send');
                                    data = data.replaceAll('fas fa-hand-point-right', 'bx bx-news');
                                    data = data.replaceAll('fa fa-code', 'bx bx-code');
                                    data = data.replaceAll('fa fa-clone', 'bx bx-duplicate');
                                    data = data.replaceAll('fas fa-pause', 'bx bx-pause');
                                    data = data.replaceAll('fas fa-play', 'bx bx-play');
                                    data = data.replaceAll('fa fa-cog', 'bx bx-cog');
                                    data = data.replaceAll('fa fa-check-circle', 'bx bx-check-circle');
                                    data = data.replaceAll('fas fa-comment', 'bx bx-comment');
                                    data = data.replaceAll('swal(', 'swal.fire(');
                                    data = data.replaceAll('rounded-circle', 'rounded-circle');
                                    data = data.replaceAll('fas fa-check-circle', 'bx bx-check-circle');
                                    data = data.replaceAll('fas fa-plug', 'bx bx-plug');
                                    data = data.replaceAll('fas fa-times-circle', 'bx bx-time');
                                    data = data.replaceAll('fas fa-chart-bar', 'bx bx-chart');
                                    data = data.replaceAll('fas fa-cloud-download-alt', 'bx bxs-cloud-download');
                                    data = data.replaceAll('padding-10', 'p-10');
                                    data = data.replaceAll('padding-left-10', 'pl-10');
                                    data = data.replaceAll('h4', 'h5 class="card-title font-medium-1"');
                                    data = data.replaceAll('fas fa-heart', 'bx bx-heart');
                                    data = data.replaceAll('fas fa-road', 'bx bx-location-plus');
                                    data = data.replaceAll('fas fa-city', 'bx bxs-city');
                                    data = data.replaceAll('fas fa-globe-americas', 'bx bx-globe');
                                    data = data.replaceAll('fas fa-at', 'bx bx-at');
                                    data = data.replaceAll('fas fa-mobile-alt', 'bx bx-mobile-alt');
                                    data = data.replaceAll('<div class="dropdown-title"><?php echo $this->lang->line('Options'); ?></div>', '<h6 class="dropdown-header"><?php echo $this->lang->line('Options'); ?></h6>');
                                    data = data.replaceAll('fas fa-file-signature', 'bx bxs-user-badge');
                                    data = data.replaceAll('fas fa-user-circle', 'bx bxs-user');
                                    data = data.replaceAll('fas fa-toggle-on', 'bx bx-toggle-right');
                                    data = data.replaceAll('fas fa-toggle-off', 'bx bx-toggle-left');
                                    data = data.replaceAll('fas fa-info-circle', 'bx bx-info-circle');
                                    data = data.replaceAll('fa fa-info-circle', 'bx bx-info-circle');
                                    data = data.replaceAll('fas fa-id-card', 'bx bxs-id-card');
                                    data = data.replaceAll('fas fa-mars', 'bx bx-male-sign');
                                    data = data.replaceAll('fas fa-language', 'bx bx-flag');
                                    data = data.replaceAll('fas fa-globe', 'bx bx-globe');
                                    data = data.replaceAll('far fa-clock', 'bx bx-time');
                                    data = data.replaceAll('fas fa-ellipsis-v', 'bx bx-dots-vertical-rounded');
                                    data = data.replaceAll('far fa-hand-point-right', 'bx bxs-hand-right');
                                    data = data.replaceAll('fas fa-cogs', 'bx bx-cog');
                                    data = data.replaceAll('far fa-pause-circle', 'bx bx-pause-circle');
                                    data = data.replaceAll('fas fa-retweet', 'bx bxs-share');
                                    data = data.replaceAll('fas fa-sync-alt', 'bx bx-sync');
                                    data = data.replaceAll('fas fa-sync', 'bx bx-sync');
                                    data = data.replaceAll('fas fa-briefcase', 'bx bx-briefcase');
                                    data = data.replaceAll('far fa-stop-circle', 'bx bx-stop-circle');
                                    data = data.replaceAll('far fa-play-circle', 'bx bx-play-circle');
                                    data = data.replaceAll('fa fa-bug', 'bx bx-bug');

                                    return data;
                                }
                            },
                        ],
                        fnInitComplete: function () {  // when initialization is completed then apply scroll plugin
                            if (areWeUsingScroll) {
                                if (perscroll1) perscroll1.destroy();
                                perscroll1 = new PerfectScrollbar('#mytable1_wrapper .dataTables_scrollBody');
                            }
                        },
                        scrollX: 'auto',
                        fnDrawCallback: function (oSettings) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again
                            if (areWeUsingScroll) {
                                if (perscroll1) perscroll1.destroy();
                                perscroll1 = new PerfectScrollbar('#mytable1_wrapper .dataTables_scrollBody');
                            }
                        },
                        "drawCallback": function (settings) {
                            $('table [data-toggle="tooltip"]').tooltip('dispose');
                            $('table [data-toggle="tooltip"]').tooltip(
                                {
                                    placement: 'left',
                                    container: 'body',
                                    html: true,
                                    template: '<div class="tooltip tooltip_pd" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                                }
                            );
                        }
                    });
                } else table1.draw();
            }, 1000);

        });

        $(document).on('click', '.view_post_data', function (event) {
            event.preventDefault();

            $("#view_post_data_modal").modal();

            var postVal = $(this).attr('post_data');

            if (postVal != "" || postVal != null || typeof (postVal) != 'undefined') {
                var parseData = JSON.parse(postVal);
                var parseData2 = Object.assign({}, parseData);
                delete parseData2['user_input_data'];
                var i = 1;
                var appendTable = '<table class="table table-bordered table-hover text-center"><thead><tr><th>#</th><th>' + '<?php echo $this->lang->line('Field Name'); ?>' + '</th><th>' + '<?php echo $this->lang->line('Value'); ?>' + '</th></tr></thead><tbody>';

                jQuery.each(parseData2, (index, item) => {
                    if (index.indexOf('_') != -1) index = index.replace(/[_\W]+/g, " ")
                    appendTable += '<tr><th scope="row">' + i + '</th><td scope="col">' + index.charAt(0).toUpperCase() + index.substr(1).toLowerCase() + '</td>';
                    appendTable += '<td scope="col">' + item + '</td></tr>';
                    i++;
                });
                appendTable += '</table></tbody>';

                $(".user_input_flows_card").css('display', 'none');

                var user_input_data = parseData && parseData['user_input_data'] ? parseData['user_input_data'] : [];

                if (user_input_data != '') {
                    var j = 1;
                    var appendTable2 = '<table class="table table-bordered table-hover text-center"><thead><tr><th>#</th><th>' + '<?php echo $this->lang->line('Question'); ?>' + '</th><th>' + '<?php echo $this->lang->line('Answer'); ?>' + '</th></tr></thead><tbody>';

                    user_input_data.forEach((item, index) => {
                        var question = item.question;
                        var answer = item.answer;

                        if (answer.indexOf('_') != -1) answer = answer.replace(/[_\W]+/g, " ");
                        if (question.indexOf('_') != -1) question = question.replace(/[_\W]+/g, " ");
                        appendTable2 += '<tr><th scope="row">' + j + '</th><td scope="col">' + question + '</td>';
                        appendTable2 += '<td scope="col">' + answer + '</td></tr>';
                        j++;
                    });

                    appendTable2 += '</table></tbody>';
                    $(".user_input_flows_card").css('display', 'block');
                    $(".user_input_flows").html(appendTable2);

                }

                $("#json_formate_data").html(postVal);
                $(".infos").html(appendTable);

            } else {

                $("#json_formate_data").html("<p>" + '<?php echo $this->lang->line('No Data Available to show.'); ?>' + "</p>");
                $(".infos").html("");
                $(".user_input_flows").html('');
                $(".user_input_flows_card").css('display', 'none');
            }

        });


        $('#view_post_data_modal').on("hidden.bs.modal", function (e) {
            if ($('.modal:visible').length) {
                $('body').addClass('modal-open');
            }

            $(".infos").html('');
            $("#json_formate_data").html('');
            table1.draw();
        });

        $(document).on('click', '.add_connector', function (event) {
            event.preventDefault();

            $("#add_new_connector_modal").modal();

        });


        $(document).on('click', '#save_added_connector_infos', function (event) {
            event.preventDefault();

            var name = $("#name").val();
            var webhook_url = $("#webhook_url").val();
            var page_table_id = $("#page_table_id").val();
            var triggered_webhook = $('input[name="field[]"]:checked').length;
            var sending_data = $('input[name="variable_post[]"]:checked').length;

            var checkorNot1 = false;
            var checkorNot2 = false;
            var checkorNot3 = false;
            if ($("#trigger_postbackid").prop('checked') == true) {
                var postbackids = $('#postback').val();
                checkorNot1 = true;
            }

            if ($("#trigger_webview").prop('checked') == true) {
                var webviewids = $('#webview').val();
                checkorNot2 = true;
            }

            if ($("#trigger_user_input").prop('checked') == true) {
                var input_campaigns = $('#input_flow').val();
                checkorNot3 = true;
            }


            if (name == '') {
                var error = '<?php echo $this->lang->line("Name is Required."); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');

            } else if (page_table_id == '') {
                var error = '<?php echo $this->lang->line("Please select a page."); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');

            } else if (webhook_url == '') {
                var error = '<?php echo $this->lang->line("Webhook URL is Required."); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');

            } else if (checkorNot1 == true && (postbackids == null || postbackids == '' || typeof (postbackids) == "undefined")) {
                var error = '<?php echo $this->lang->line("Please select at least one Postback ID."); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');

            } else if (checkorNot2 == true && (webviewids == null || webviewids == '' || typeof (webviewids) == "undefined")) {
                var error = '<?php echo $this->lang->line("Please select at least one Webview Data."); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');

            } else if (checkorNot3 == true && (input_campaigns == null || input_campaigns == '' || typeof (input_campaigns) == "undefined")) {
                var error = '<?php echo $this->lang->line("Please select at least one Input Flow Campaign."); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');

            } else if (triggered_webhook == 0) {
                var error = '<?php echo $this->lang->line("Please select at least one field from Trigger Webhook Section."); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');

            } else if (sending_data == 0) {
                var error = '<?php echo $this->lang->line("Please select at least one field from Which Data You Want To Send Section."); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');
            } else {
                $(this).addClass('btn-progress')
                var that = $(this);

                var alldatas = new FormData($("#json_api_connector_form")[0]);

                $.ajax({
                    url: base_url + 'messenger_bot_connectivity/ajax_connector_info_saving',
                    type: 'POST',
                    dataType: 'JSON',
                    data: alldatas,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $(that).removeClass('btn-progress');

                        if (response.result == '1') {
                            iziToast.success({title: '', message: response.msg, position: 'bottomRight'});
                            $("#add_new_connector_modal").modal('hide');
                            table.draw();


                        } else {
                            iziToast.errr({title: '', message: response.msg, position: 'bottomRight'});

                        }
                    }
                });
            }
        });

        $(document).on('click', '.edit_connector', function (event) {
            event.preventDefault();

            var table_id = $(this).attr('table_id');

            var loading = '<img src="' + base_url + 'assets/pre-loader/Fading squares2.gif" class="center-block">';
            $('#response_status').html(loading);

            $.ajax({
                url: base_url + 'messenger_bot_connectivity/ajax_get_update_connector_info',
                type: 'POST',
                data: {table_id: table_id},
                success: function (response) {
                    if (response) {
                        $("#update_connector_modal").modal();
                        $("#updateConnectorForm").html(response);
                        if ($("#trigger_postbackid_updated").prop('checked') == true) {
                            $("#updated_postback_div").css('display', 'block');
                            $("#postbackid_updated").prop("disabled", false);

                        } else {
                            $("#updated_postback_div").css('display', 'none');
                            $("#postbackid_updated").prop({
                                disabled: true,
                                checked: false
                            });
                        }

                        if ($("#trigger_webview_updated").prop('checked') == true) {
                            $("#updated_webview_div").css('display', 'block');
                            $("#formdata_updated").prop("disabled", false);

                        } else {
                            $("#updated_webview_div").css('display', 'none');
                            $("#formdata_updated").prop({
                                disabled: true,
                                checked: false
                            });
                        }

                        if ($("#trigger_user_input_updated").prop('checked') == true) {
                            $("#updated_input_flow_div").css('display', 'block');
                            $("#user_input_flow_campaign_updated").prop("disabled", false);

                        } else {

                            $("#updated_input_flow_div").css('display', 'none');
                            $("#user_input_flow_campaign_updated").prop({
                                disabled: true,
                                checked: false
                            });
                        }
                    }
                }
            });

        });


        $(document).on('click', '#save_updated_connector_infos', function (event) {
            event.preventDefault();

            var name = $("#connector_name").val();
            var webhook_url = $("#updated_webhook_url").val();
            var page_table_id = $("#updated_page_table_id").val();
            var triggered_webhook = $('input[name="updated_field[]"]:checked').length;
            var sending_data = $('input[name="updated_variable_post[]"]:checked').length;

            var checkedornot1 = false;
            var checkorNot2 = false;
            var checkorNot3 = false;
            if ($("#trigger_postbackid_updated").prop('checked') == true) {
                var postbackids = $('#postback_updated').val();
                checkedornot1 = true;
            }

            var checkedornot2 = false;
            if ($("#trigger_webview_updated").prop('checked') == true) {
                var webviewsdata = $('#webview_updated').val();
                checkedornot2 = true;
            }

            var checkedornot3 = false;
            if ($("#trigger_user_input_updated").prop('checked') == true) {
                var user_input_data = $('#input_flow_updated').val();
                checkedornot3 = true;
            }


            if (name == '') {
                var error = '<?php echo $this->lang->line("Name is Required."); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');

            } else if (page_table_id == '') {
                var error = '<?php echo $this->lang->line("Please select a page."); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');

            } else if (webhook_url == '') {
                var error = '<?php echo $this->lang->line("Webhook URL is Required."); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');

            } else if (triggered_webhook == 0) {
                var error = '<?php echo $this->lang->line("Please select at least one field from Trigger Webhook Section."); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');

            } else if (checkedornot1 == true && (postbackids == null || postbackids == '' || typeof (postbackids) == "undefined")) {
                var error = '<?php echo $this->lang->line("Please select at least one Postback ID."); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');

            } else if (checkedornot2 == true && (webviewsdata == null || webviewsdata == '' || typeof (webviewsdata) == "undefined")) {
                var error = '<?php echo $this->lang->line("Please select at least one Webview Data."); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');

            } else if (checkedornot3 == true && (user_input_data == null || user_input_data == '' || typeof (user_input_data) == "undefined")) {
                var error = '<?php echo $this->lang->line("Please select at least one Webview Data."); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');

            } else if (sending_data == 0) {
                var error = '<?php echo $this->lang->line("Please select at least one field from Which Data You Want To Send Section."); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');

            } else {
                $(this).addClass('btn-progress')
                var that = $(this);

                var alldatas = new FormData($("#json_api_connector_update_form")[0]);

                $.ajax({
                    url: base_url + 'messenger_bot_connectivity/ajax_connector_info_updating',
                    type: 'POST',
                    dataType: 'JSON',
                    data: alldatas,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $(that).removeClass('btn-progress');

                        if (response.result == '1') {
                            iziToast.success({title: '', message: response.msg, position: 'bottomRight'});
                            $("#update_connector_modal").modal('hide');
                            table.draw();

                        } else {
                            iziToast.error({title: '', message: response.msg, position: 'bottomRight'});
                        }
                    }
                });
            }
        });

        var Doyouwanttodeletethisrecordfromdatabase = "<?php echo $this->lang->line('Do you want to detete this record?'); ?>";
        $(document).on('click', '.delete_connector', function (e) {
            e.preventDefault();
            swal.fire({
                title: '<?php echo $this->lang->line("Are you sure?"); ?>',
                text: Doyouwanttodeletethisrecordfromdatabase,
                icon: 'warning',
                confirmButtonText: "<?php echo $this->lang->line('Yes'); ?>",
                cancelButtonText: "<?php echo $this->lang->line('No'); ?>",
                showCancelButton: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete.isConfirmed) {
                        var table_id = $(this).attr('table_id');

                        $.ajax({
                            context: this,
                            type: 'POST',
                            url: "<?php echo base_url('messenger_bot_connectivity/ajax_delete_connector_info')?>",
                            data: {table_id: table_id},
                            success: function (response) {

                                if (response == '1') {
                                    iziToast.success({
                                        title: '',
                                        message: '<?php echo $this->lang->line("Campaign has been deleted successfully."); ?>',
                                        position: 'bottomRight'
                                    });
                                    table.draw();
                                } else {
                                    iziToast.error({
                                        title: '',
                                        message: '<?php echo $this->lang->line("Something Went Wrong, please try again."); ?>',
                                        position: 'bottomRight'
                                    });
                                }
                            }
                        });
                    }
                });

        });

        $(document).on('change', '#trigger_postbackid', function (event) {
            event.preventDefault();

            if ($("#page_table_id").val() == '') {
                $(this).prop('checked', false);
                var error = '<?php echo $this->lang->line("Please select at least one page"); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');
                return;
            }

            if ($(this).prop('checked') == true) {
                $("#postback_div").css('display', 'block');
                $("#postbackid").prop("disabled", false);

            } else {
                $("#postback_div").css('display', 'none');
                $("#postbackid").prop({
                    disabled: true,
                    checked: false
                });
            }


        });

        $(document).on('change', '#trigger_webview', function (event) {
            event.preventDefault();

            if ($("#page_table_id").val() == '') {
                $(this).prop('checked', false);
                var error = '<?php echo $this->lang->line("Please select at least one page"); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');
                return;

            }

            if ($(this).prop('checked') == true) {
                $("#webview_div").css('display', 'block');
                $("#formdata").prop("disabled", false);

            } else {
                $("#webview_div").css('display', 'none');
                $("#formdata").prop({
                    disabled: true,
                    checked: false
                });
            }

        });

        $(document).on('change', '#trigger_user_input', function (event) {
            event.preventDefault();

            if ($("#page_table_id").val() == '') {
                $(this).prop('checked', false);
                var error = '<?php echo $this->lang->line("Please select at least one page"); ?>';
                swal.fire('<?php echo $this->lang->line("Warning"); ?>', error, 'warning');
                return;

            }

            if ($(this).prop('checked') == true) {
                $("#input_flow_div").css('display', 'block');
                $("#user_input_flow_campaign").prop("disabled", false);

            } else {
                $("#input_flow_div").css('display', 'none');
                $("#user_input_flow_campaign").prop({
                    disabled: true,
                    checked: false
                });
            }

        });


        $(document).on('change', '#page_table_id', function (event) {
            event.preventDefault();

            var page_id = $(this).val();

            if ($(this).val() == '') {
                $("#trigger_postbackid").prop("checked", "");
                $("#trigger_webview").prop("checked", "");
                $("#postback_div").css("display", "none");
                $("#webview_div").css("display", "none");
            }

            $.ajax({
                url: base_url + 'messenger_bot_connectivity/find_page_postback',
                type: 'POST',
                dataType: 'JSON',
                data: {page_id: page_id},
                success: function (response) {
                    $("#postback_div").html(response.html1);
                    $("#webview_div").html(response.html2);
                    $("#input_flow_div").html(response.html3);
                }
            });

        });


        $(document).on('change', '#updated_page_table_id', function (event) {
            event.preventDefault();

            var page_id = $(this).val();
            var table_id = $('#table_id').val();

            $.ajax({
                url: base_url + 'messenger_bot_connectivity/find_page_update_postback',
                type: 'POST',
                dataType: 'json',
                data: {page_id: page_id, table_id: table_id},
                success: function (response) {
                    $("#updated_postback_div").html(response.html1);
                    $("#updated_webview_div").html(response.html2);
                    $("#updated_input_flow_div").html(response.html3);
                }
            })

        });


        $(document).on('change', '#trigger_postbackid_updated', function (event) {
            event.preventDefault();

            if ($(this).prop('checked') == true) {
                $("#updated_postback_div").css('display', 'block');
                $("#postbackid_updated").prop("disabled", false);


            } else {
                $("#updated_postback_div").css('display', 'none');
                $("#postbackid_updated").prop({
                    disabled: true,
                    checked: false,
                });
            }

        });


        $(document).on('change', '#trigger_webview_updated', function (event) {
            event.preventDefault();

            if ($(this).prop('checked') == true) {
                $("#updated_webview_div").css('display', 'block');
                $("#formdata_updated").prop("disabled", false);

            } else {
                $("#updated_webview_div").css('display', 'none');
                $("#formdata_updated").prop({
                    disabled: true,
                    checked: false
                });
            }

        });


        $(document).on('change', '#trigger_user_input_updated', function (event) {
            event.preventDefault();

            if ($(this).prop('checked') == true) {
                $("#updated_input_flow_div").css('display', 'block');
                $("#user_input_flow_campaign_updated").prop("disabled", false);

            } else {
                $("#updated_input_flow_div").css('display', 'none');
                $("#user_input_flow_campaign_updated").prop({
                    disabled: true,
                    checked: false
                });
            }

        });


        $(document).on('click', '#cancel', function (event) {
            event.preventDefault();
            table.draw();
        });


        $("#view_connector_info_modal").on('hidden.bs.modal', function () {
            $("#put_row_id").val('');
            table.draw();
        });


        $("#add_new_connector_modal").on('hidden.bs.modal', function () {
            $("#json_api_connector_form").trigger('reset');
            $("#page_table_id").val('').change();
            table.draw();
        });

        $("#update_connector_modal").on('hidden.bs.modal', function () {
            table.draw();
        });


    });

</script>