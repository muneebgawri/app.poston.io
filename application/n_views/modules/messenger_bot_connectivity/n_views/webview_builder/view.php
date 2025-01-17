<div class="row">
    <div class="webview-form col-12 d-flex justify-content-center" style="margin-top: 30px;">
        <div class="col-12 col-sm-12 col-md-8 col-lg-6">
            <div class="card">
                <form id="webview-form">
                    <div class="card-header">
                        <h4><?= htmlspecialchars($form['form_title']) ?></h4>
                    </div>

                    <input type="hidden" name="subscriber_id" value="<?php echo $subscriber_id; ?>">
                    <input type="hidden" name="webview_form_id" value="<?php echo $form_id; ?>">

                    <div class="card-body">
                        <!-- renders form -->
                        <?= $form_data ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        var base_url = "<?php echo base_url(); ?>";

        $('.datepicker_x').datetimepicker({
            theme: 'light',
            format: 'Y-m-d',
            formatDate: 'Y-m-d',
            timepicker: false,
        });

        $('.timepicker_x').datetimepicker({
            datepicker: false,
            format: 'H:i:s'
        });


//PSID variable comes from bare-them.php file. 

        $('.select2').select2({width: '100%'});

        $("form").on('submit', function (event) {
            event.preventDefault();

            // Checks whether radio and checkbox are checked if they exist
            const areRadiosAndCheckboxesCheckedArray = [];
            $('.is_required_group').each(function () {
                const isCheckedArray = $(this).find(':input')
                    .map((index, element) => $(element).prop('checked'))
                    .get()
                    .filter(el => true === el);

                areRadiosAndCheckboxesCheckedArray.push(isCheckedArray)
            });

            for (const arr of areRadiosAndCheckboxesCheckedArray) {
                if (!Array.isArray(arr) || !arr.length) {
                    swal.fire({
                        title: '<?php echo $this->lang->line("Warning!"); ?>',
                        text: '<?php echo $this->lang->line("Fill in all the fields"); ?>',
                        icon: 'warning'
                    });

                    return false;
                }
            }

            var psid_from_url = $("input[name='subscriber_id']").val();
            if (psid_from_url == '')
                $("input[name='subscriber_id']").val(PSID);


            var currnet_class = $("#webview_submit_button").attr('class');
            $("#webview_submit_button").attr("class", " btn btn-primary btn-progress");

            var form_data = $(this).serialize();
            $.ajax({
                url: base_url + "messenger_bot_connectivity/form_submit",
                method: "POST",
                data: form_data,
                dataType: 'JSON',
                success: function (response) {

                    $("#webview_submit_button").attr("class", currnet_class);

                    if (response.error == '1') {
                        alert(response.error_message);
                    } else {

                        if (PSID === undefined) {

                            $('#webview-form .card-body').html('<h3><?php echo $this->lang->line("Submitted Successfully"); ?></h3><p><?php echo $this->lang->line("You can close this window."); ?></p>');


                            // alert();
                        }
                    }

                    window.setTimeout(function () {
                            MessengerExtensions.requestCloseBrowser(function success() {
                            }, function error(err) {
                            });
                        }
                        , 1500, true);


                    // alert(response.error);


                }

            });

        });

    });
</script>

