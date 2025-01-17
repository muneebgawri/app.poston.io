<script>
    $(document).ready(function ($) {
//todo: $('.datepicker_x').datetimepicker({
        // var today = new Date();
        $('.datepicker_x').datetimepicker({
            theme: 'light',
            format: 'Y-m-d H:i:s',
            formatDate: 'Y-m-d H:i:s',
            // minDate: today
        });


        // $('.visualeditor').summernote({
        //   minHeight: 200,
        //     maxHeight: 500,
        //   toolbar: [
        //       ['style', ['style']],
        //       ['font', ['bold', 'underline']],
        //       // ['fontname', ['fontname']],
        //       // ['color', ['color']],
        //       // ['para', ['ul', 'ol', 'paragraph']],
        //       // ['table', ['table']],
        //       ['insert', ['link', 'picture']],
        //       ['view', ['codeview']]
        //   ]
        // });

        // $(".xscroll1").mCustomScrollbar({
        // autoHideScrollbar:true,
        // theme:"light-thick",
        // axis: "x"
        // });
        //todo: mCustomScrollbar


        $(document).on('click', '#variables', function (e) {
            e.preventDefault();

            var success_message = '{{first_name}}<br/>{{last_name}}<br/>{{email}}<br/>{{mobile}}<br/>{{cart_url}}<br/>{{checkout_url}}';
            var span = document.createElement("span");
            span.innerHTML = success_message;
            swal.fire({title: '<?php echo $this->lang->line("Variables"); ?>', html: span, icon: 'info'});

        });


        $(document).on('click', '.download', function (event) {
            event.preventDefault();
            var base_url = '<?php echo site_url();?>';
            $(this).addClass('btn-primary btn-progress');
            $(this).removeClass('btn-outline-primary');
            var that = $(this);
            var campaign_id = $(this).attr('campaign_id');

            $.ajax({
                context: this,
                type: 'POST',
                url: "<?php echo site_url();?>woocommerce_abandoned_cart/download_woocommerce_plugin_modal",
                dataType: 'json',
                data: {campaign_id: campaign_id},
                success: function (response) {

                    $(that).removeClass('btn-primary btn-progress');
                    $(this).addClass('btn-outline-primary');

                    if (response.status == '1') {
                        $("#download_modal .modal-body").html(response.message);
                        $("#download_modal").modal();
                    } else swal.fire('<?php echo $this->lang->line("Error"); ?>', response.message, 'error');
                }
            });
        });
    });
</script>

<div class="modal fade" id="download_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i
                            class="bx bx-cloud-download"></i> <?php echo $this->lang->line("Download Plugin"); ?></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bx bx-time"></i> <span
                            class="align-middle ml-25"><?php echo $this->lang->line("close"); ?></span></button>
            </div>
        </div>
    </div>
</div>