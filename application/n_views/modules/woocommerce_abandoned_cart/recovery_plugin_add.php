<link href="<?php echo base_url(); ?>plugins/datetimepickerjquery/jquery.datetimepicker.css?ver=<?php echo $n_config['theme_version']; ?>"
      rel="stylesheet" type="text/css"/>
<style type="text/css">
    div.tooltip{top:0px!important;}
</style>
<div id="put_script"></div>


<div class="content-header row">
    <div class="content-header-left col-12 mb-2 mt-1">
        <div class="breadcrumbs-top">
            <h5 class="content-header-title float-left pr-1 mb-0"><?php echo $page_title; ?></h5>
            <div class="breadcrumb-wrapper d-none d-sm-block">
                <ol class="breadcrumb p-0 mb-0 pl-1">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard"><i
                                    class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a
                                href="<?php echo base_url('integration'); ?>"><?php echo $this->lang->line("Integration"); ?></a>
                    </li>
                    <li class="breadcrumb-item"><a
                                href="<?php echo base_url("woocommerce_abandoned_cart/recovery_plugin_list"); ?>"><?php echo $this->lang->line("WooCommerce Abandoned Cart"); ?></a>
                    </li>
                    <li class="breadcrumb-item active"><?php echo $this->lang->line("Add Plugin"); ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="section-body">
    <form action="#" enctype="multipart/form-data" id="plugin_form">
        <div class="row">
            <div class="col-12 col-lg-4 order-2 order-lg-1">
                <div class="card main_card">
                    <div class="card-header"><h4><i
                                    class="bx bx-check-circle"></i> <?php echo $this->lang->line("Checkbox OPT-IN"); ?>
                        </h4></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <label>
                                    <?php echo $this->lang->line("select page"); ?> *
                                    <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus"
                                       title="<?php echo $this->lang->line("select page") ?>"
                                       data-content='<?php echo $this->lang->line("Select your Facebook page for which you want to generate the plugin.") ?>'><i
                                                class='bx bxs-help-circle'></i> </a>
                                </label>
                                <?php $page_info[''] = $this->lang->line("select page"); ?>
                                <?php echo form_dropdown('page', $page_info, '', 'class="select2 form-control" id="page" style="width:100%;"'); ?>
                            </div>
                            <div class="form-group col-12">
                                <label>
                                    <?php echo $this->lang->line("Woocommerce Site URL"); ?> *
                                    <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus"
                                       title="<?php echo $this->lang->line("domain") ?>"
                                       data-content='<?php echo $this->lang->line("Website URL where you want to embed this plugin. Website must have https.") ?>'><i
                                                class='bx bxs-help-circle'></i> </a>
                                </label>
                                <input type="text" name="domain_name" autocomplete="off" id="domain_name"
                                       class="form-control" placeholder="https://example.com">
                            </div>
                            <div class="form-group col-12">
                                <label>
                                    <?php echo $this->lang->line("language"); ?> *
                                    <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus"
                                       title="<?php echo $this->lang->line("language") ?>"
                                       data-content='<?php echo $this->lang->line("Facebook SDK will be loaded in this language.") ?>'><i
                                                class='bx bxs-help-circle'></i> </a>
                                </label>
                                <?php echo form_dropdown('language', $sdk_list, 'en_US', 'class="select2 form-control" id="language" style="width:100%;"'); ?>
                            </div>

                            <div class="form-group col-12">
                                <label>
                                    <?php echo $this->lang->line("reference"); ?> *
                                    <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus"
                                       title="<?php echo $this->lang->line("reference") ?>"
                                       data-content='<?php echo $this->lang->line("put a unique reference to track this plugin later.") ?>'><i
                                                class='bx bx-info-circle'></i> </a>
                                </label>
                                <input type="text" name="reference" id="reference" class="form-control" value="">
                            </div>

                            <div class="form-group col-12">
                                <label>
                                    <?php echo $this->lang->line("Plugin skin"); ?> *
                                    <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus"
                                       title="<?php echo $this->lang->line("Plugin skin") ?>"
                                       data-content='<?php echo $this->lang->line("light skin is suitable for pages with dark background and dark skin is suitable for pages with light background.") ?>'><i
                                                class='bx bxs-help-circle'></i> </a>
                                </label>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="selectgroup w-100">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="skin" value="dark" id="dark"
                                                       class="selectgroup-input">
                                                <span class="selectgroup-button"> <?php echo $this->lang->line("Light") ?></span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="skin" value="light" id="light"
                                                       class="selectgroup-input" checked>
                                                <span class="selectgroup-button"> <?php echo $this->lang->line("Dark") ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-12">
                                <label>
                                    <?php echo $this->lang->line("Plugin size"); ?> *
                                    <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus"
                                       title="<?php echo $this->lang->line("plugin size") ?>"
                                       data-content='<?php echo $this->lang->line("overall plugin size.") ?>'><i
                                                class='bx bx-info-circle'></i> </a>
                                </label>
                                <div class="selectgroup selectgroup-pills">
                                    <?php
                                    $i = 0;
                                    foreach ($btn_sizes as $key => $value) {
                                        $i++;
                                        $checked = $selected = '';
                                        if ($value == 'medium') {
                                            $selected = 'default-label';
                                            $checked = 'checked';
                                        }
                                        $val_print = $value;
                                        $title = $value;
                                        if ($val_print == "small") $val_print = "S";
                                        if ($val_print == "medium") $val_print = "M";
                                        if ($val_print == "large") $val_print = "L";
                                        if ($val_print == "xlarge") {
                                            $val_print = "XL";
                                            $title = "Extra Large";
                                        }
                                        echo '
							  		<label class="selectgroup-item">
		  			                    <input type="radio" name="btn_size" value="' . $value . '" id="btn_size' . $i . '" ' . $checked . ' class="selectgroup-input">
		  			                    <span class="selectgroup-button" data-toggle="tooltip" title="' . $this->lang->line($title) . '">' . $this->lang->line($val_print) . '</span>
		  			                </label>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="form-group col-12">
                                <label>
                                    <?php echo $this->lang->line("OPT-IN success message"); ?>
                                    <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus"
                                       title="<?php echo $this->lang->line("OPT-IN success message") ?>"
                                       data-content='<?php echo $this->lang->line("this message will be displayed after successful OPT-IN.") ?> <?php echo $this->lang->line('Keep it blank if you do not want.'); ?>'><i
                                                class='bx bx-info-circle'></i> </a>
                                </label>
                                <textarea class="form-control" name="button_click_success_message"
                                          id="button_click_success_message" style="width: 100%;"></textarea>
                            </div>


                            <div class="form-group col-12">
                                <label>
                                    <?php echo $this->lang->line("select label"); ?>
                                    <a href="#" data-placement="top" data-toggle="popover" data-trigger="focus"
                                       title="<?php echo $this->lang->line("select label") ?>"
                                       data-content='<?php echo $this->lang->line("subscriber obtained from this plugin will be enrolled in these labels.") ?> <?php echo $this->lang->line("You must select page to fill this list with data."); ?>'><i
                                                class='bx bx-info-circle'></i> </a>
                                </label>
                                <?php echo form_dropdown('label_ids[]', array(), '', 'style="height:45px;overflow:hidden;width:100%;" multiple="multiple" class="select2 form-control" id="label_ids"'); ?>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="col-12 col-lg-8 order-1 order-lg-2">
                <div class="card main_card">
                    <div class="card-header"><h4 class="full_width"><i
                                    class="bx bx-bell"></i> <?php echo $this->lang->line("Reminder Message"); ?> <a
                                    id="variables" class="float-right text-warning pointer"><i
                                        class="bx bx-circle"></i> <?php echo $this->lang->line("Variables"); ?></a></h4>
                    </div>
                    <div class="card-body" style="min-height: 825px;">
                        <ul class="nav nav-tabs" id="sequence_tab" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link active" id="messenger_link" data-toggle="tab" href="#messenger_tab"
                                   role="tab" aria-controls="profile" aria-selected="false"><i
                                            class="bx bxl-messenger"></i> <?php echo $this->lang->line("Messenger"); ?>
                                </a>
                            </li>

                            <?php if ($this->session->userdata('user_type') == 'Admin' || in_array(264, $this->module_access)) : ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="sms_link" data-toggle="tab" href="#sms_tab" role="tab"
                                       aria-controls="profile" aria-selected="false"><i
                                                class="bx bxs-chat"></i> <?php echo $this->lang->line("SMS"); ?></a>
                                </li>
                            <?php endif; ?>

                            <?php
                            if ($this->basic->is_exist("modules", array("id" => 263))) :
                                if ($this->session->userdata('user_type') == 'Admin' || in_array(263, $this->module_access)) : ?>
                                    <li class="nav-item">
                                        <a class="nav-link" id="email_link" data-toggle="tab" href="#email_tab"
                                           role="tab" aria-controls="profile" aria-selected="false"><i
                                                    class="bx bx-envelope"></i> <?php echo $this->lang->line("Email"); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                        </ul>
                        <div class="tab-content tab-bordered">

                            <div class="tab-pane fade show active" id="messenger_tab" role="tabpanel"
                                 aria-labelledby="messenger_link">

                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-5">
                                        <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                            aria-orientation="vertical">
                                            <?php
                                            for ($i = 1; $i <= $how_many_reminder; $i++) { ?>
                                                <li class="nav-item">
                                                    <a href="#msg_reminder<?php echo $i; ?>"
                                                       id="msg_reminder_link<?php echo $i; ?>"
                                                       class="nav-link <?php if ($i == 1) echo 'active'; ?>"
                                                       data-toggle="pill" role="tab"
                                                       aria-controls="msg_reminder<?php echo $i; ?>"
                                                       aria-selected="true"><i
                                                                class="bx bx-bell"></i> <?php echo $this->lang->line("Messenger Reminder"); ?>
                                                        #<?php echo $i; ?></a>

                                                    <?php
                                                    $tmp_name = 'msg_reminder_time[]';
                                                    $tmp_id = 'msg_reminder_time_' . $i;
                                                    $tmp_select = ($i == 1) ? '1' : '';
                                                    echo form_dropdown($tmp_name, $hours, $tmp_select, 'id="' . $tmp_id . '" class="form-control" style="width: 100% !important;"');
                                                    ?>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                            <li class="nav-item">
                                                <a href="#msg_checkout" id="msg_checkout_link" class="nav-link nav_cart"
                                                   data-toggle="pill" role="tab" aria-controls="msg_checkout"
                                                   aria-selected="true"><i
                                                            class="bx bx-shopping-bag"></i> <?php echo $this->lang->line("Messenger Checkout Confirmation"); ?>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-7">
                                        <?php echo $this->lang->line("Messenger Content"); ?>
                                        <div class="tab-content" id="MsgReminderTabContent">
                                            <?php
                                            for ($i = 1; $i <= $how_many_reminder; $i++) { ?>
                                                <div class="reminder_badge_warpper tab-pane fade <?php if ($i == 1) echo 'active show'; ?>"
                                                     id="msg_reminder<?php echo $i; ?>" role="tabpanel"
                                                     aria-labelledby="msg_reminder_link>">
                                                    <span class="reminder_badge" data-toggle="tooltip"
                                                          title="<?php echo $this->lang->line('Messenger Reminder') . ' #' . $i; ?>"><i
                                                                class="bx bx-bell"></i> <?php echo $i; ?></span>
                                                    <div class="reminder_block">
						                         	<span class="block1">
						                         		<textarea autofocus data-toggle="tooltip"
                                                                  title="<?php echo $this->lang->line('Intro message will be displayed here, click to edit text. Clean text if you do not want this.'); ?>"
                                                                  name="msg_reminder_text[]"
                                                                  id="msg_reminder_text<?php echo $i; ?>">Hi {{last_name}}, have you forgot something special?</textarea>
						                         	</span>
                                                        <span class="block2 ">
						                         		<img data-toggle="tooltip"
                                                             title="<?php echo $this->lang->line('Item preview will be displayed here.'); ?>"
                                                             src="<?php echo base_url('assets/img/products/product-6.jpg') ?>">
						                         	</span>
                                                        <span class="block3">
						                         		<h6 data-toggle="tooltip"
                                                            title="<?php echo $this->lang->line('Item name will be displayed here.'); ?>"><?php echo "Cart item title"; ?></h6>
						                         		<span data-toggle="tooltip"
                                                              title="<?php echo $this->lang->line('Item quantity & price will be displayed here.'); ?>"
                                                              class="text-muted"><?php echo "Quantity & price"; ?> </span>
						                         		<span class="text-muted website" data-toggle="tooltip"
                                                              title="<?php echo $this->lang->line('Woocommerce store address will be displayed here.'); ?>">example.com</span>
						                         		<p>
						                         		<input data-toggle="tooltip"
                                                               title="<?php echo $this->lang->line('Item link will be embedded here, click to edit button name.'); ?>"
                                                               value="View Details" class="btn btn-block bg-white"
                                                               name="msg_reminder_btn_details[]"
                                                               id="msg_reminder_btn_details<?php echo $i; ?>"/>
						                         		</p>
						                         	</span>
                                                        <span class="block4">
						                         		<textarea data-toggle="tooltip"
                                                                  title="<?php echo $this->lang->line('Additonal information like coupon can be displayed here, click to edit text.'); ?>"
                                                                  name="msg_reminder_text_checkout[]"
                                                                  id="msg_reminder_text_checkout<?php echo $i; ?>">Stock limited, complete your order before it is out of stock.</textarea>
						                         		<p>
						                         		<input data-toggle="tooltip"
                                                               title="<?php echo $this->lang->line('Checkout link will be embedded here, click to edit button name.'); ?>"
                                                               value="Checkout Now" class="btn btn-block bg-white"
                                                               name="msg_reminder_btn_checkout[]"
                                                               id="msg_reminder_btn_checkout<?php echo $i; ?>"/>
						                         		</p>
						                         	</span>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>

                                            <div class="reminder_badge_warpper tab-pane fade" id="msg_checkout"
                                                 role="tabpanel" aria-labelledby="msg_checkout_link>">
                                                <span class="reminder_badge" data-toggle="tooltip"
                                                      title="<?php echo $this->lang->line('Messenger Checkout'); ?>"><i
                                                            class="bx bx-shopping-bag"></i></span>
                                                <div class="reminder_block">
					                         	<span class="block1">
					                         		<textarea autofocus data-toggle="tooltip"
                                                              title="<?php echo $this->lang->line('Intro message will be displayed here, click to edit text. Clean text if you do not want this.'); ?>"
                                                              name="msg_checkout_text" id="msg_checkout_text">Congratulations {{last_name}}!&#13;&#10;Thanks for shopping from our store. You made the right choice. If you need any information, just leave us a message here.</textarea>
					                         	</span>
                                                    <span class="block5">

					                         		<ul class="list-group list-group-flush">
													  <li class="list-group-item"><span class="text-muted">Order confirmation</span></li>

													  <li class="list-group-item">
													  	<div class="media">
													  	  <img class="align-self-start mr-3"
                                                               src="<?php echo base_url('assets/img/products/product-6.jpg') ?>">
													  	  <div class="media-body">
													  	    <h6 class="mt-0">Cart item title</h6>
													  	    <p class="text-muted">Price : XX</p>
													  	    <p class="text-muted">Qty : XX</p>
													  	  </div>
													  	</div>
													  </li>

													  <li class="list-group-item payment_info">
													  	<p class="text-muted">Paid with</p>
													  	<h6>Payment method</h6>
													  	<br>
													  	<p class="text-muted">Deliver to</p>
													  	<h6>Delivery address...</h6>
													  </li>

													  <li class="list-group-item">
													  	<span class="text-muted float-left"><?php echo $this->lang->line("Total"); ?></span>
													  	<b class="float-right">$xx.xx</b>
													  </li>
													</ul>
					                         	</span>
                                                    <span class="block4">
					                         		<textarea data-toggle="tooltip"
                                                              title="<?php echo $this->lang->line('Additonal information about next purchase like coupon can be displayed here, click to edit text.'); ?>"
                                                              name="msg_reminder_text_checkout_next"
                                                              id="msg_reminder_text_checkout_next">There are many other exciting products you may like as well. Let's see what's there !</textarea>
					                         		<p>
					                         		<input data-toggle="tooltip"
                                                           title="<?php echo $this->lang->line('Website link will be embedded here, click to edit button name. Clean text if you do not want this.'); ?>"
                                                           value="Visit Shop" class="btn btn-block bg-white"
                                                           name="msg_checkout_btn_website"
                                                           id="msg_checkout_btn_website"/>
					                         		</p>
					                         	</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>


                            <?php if ($this->session->userdata('user_type') == 'Admin' || in_array(264, $this->module_access)) : ?>
                                <div class="tab-pane fade" id="sms_tab" role="tabpanel" aria-labelledby="sms_link">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-5">
                                            <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                                aria-orientation="vertical">
                                                <?php
                                                for ($i = 1; $i <= $how_many_reminder; $i++) { ?>
                                                    <li class="nav-item">
                                                        <a href="#sms_reminder<?php echo $i; ?>"
                                                           id="sms_reminder_link<?php echo $i; ?>"
                                                           class="nav-link <?php if ($i == 1) echo 'active'; ?>"
                                                           data-toggle="pill" role="tab"
                                                           aria-controls="sms_reminder<?php echo $i; ?>"
                                                           aria-selected="true"><i
                                                                    class="bx bx-bell"></i> <?php echo $this->lang->line("SMS Reminder"); ?>
                                                            #<?php echo $i; ?></a>

                                                        <?php
                                                        $tmp_name = 'sms_reminder_time[]';
                                                        $tmp_id = 'sms_reminder_time_' . $i;
                                                        $tmp_select = '';
                                                        echo form_dropdown($tmp_name, $hours, $tmp_select, 'id="' . $tmp_id . '" class="form-control" style="width: 100% !important;"');
                                                        ?>
                                                    </li>
                                                    <?php
                                                }
                                                ?>
                                                <li class="nav-item">
                                                    <a href="#sms_checkout" id="sms_checkout_link"
                                                       class="nav-link nav_cart" data-toggle="pill" role="tab"
                                                       aria-controls="msg_checkout" aria-selected="true"><i
                                                                class="bx bx-shopping-bag"></i> <?php echo $this->lang->line("SMS Checkout Confirmation"); ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-7">
                                            <?php echo $this->lang->line("SMS Content"); ?>
                                            <div class="tab-content" id="SmsReminderTabContent">
                                                <?php
                                                for ($i = 1; $i <= $how_many_reminder; $i++) { ?>
                                                    <div class="reminder_badge_warpper tab-pane fade <?php if ($i == 1) echo 'active show'; ?>"
                                                         id="sms_reminder<?php echo $i; ?>" role="tabpanel"
                                                         aria-labelledby="sms_reminder_link>">
                                                        <span class="reminder_badge" data-toggle="tooltip"
                                                              title="<?php echo $this->lang->line('SMS Reminder') . ' #' . $i; ?>"><i
                                                                    class="bx bx-bell"></i> <?php echo $i; ?></span>
                                                        <div class="reminder_block">
						                         	<span class="block4">
						                         		<textarea data-toggle="tooltip"
                                                                  title="<?php echo $this->lang->line('SMS content goes here.'); ?>"
                                                                  name="sms_reminder_text_checkout[]"
                                                                  id="sms_reminder_text_checkout<?php echo $i; ?>">Hi, have you forgot something special? Stock limited, complete your order before it is out of stock : {{cart_url}}</textarea>
						                         	</span>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                                <div class="reminder_badge_warpper tab-pane fade" id="sms_checkout"
                                                     role="tabpanel" aria-labelledby="sms_checkout_link>">
                                                    <span class="reminder_badge" data-toggle="tooltip"
                                                          title="<?php echo $this->lang->line('SMS Checkout'); ?>"><i
                                                                class="bx bx-shopping-bag"></i></span>
                                                    <div class="reminder_block">
					                         	<span class="block4">
					                         		<textarea data-toggle="tooltip"
                                                              title="<?php echo $this->lang->line('SMS content goes here.'); ?>"
                                                              name="sms_reminder_text_checkout_next"
                                                              id="sms_reminder_text_checkout_next">Congratulations, thanks for shopping from our store. You made the right choice.</textarea>
					                         	</span>
                                                    </div>
                                                </div>

                                            </div>

                                            <br>
                                            <div class="form-group">
                                                <div class="label"><?php echo $this->lang->line("SMS Sender"); ?></div>
                                                <?php echo form_dropdown('sms_api_id', $sms_option, '', 'class="select2 form-control" id="sms_api_id" style="width:100%"'); ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>



                            <?php if ($this->session->userdata('user_type') == 'Admin' || in_array(263, $this->module_access)) : ?>
                                <div class="tab-pane fade" id="email_tab" role="tabpanel" aria-labelledby="email_link">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-5">
                                            <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                                aria-orientation="vertical">
                                                <?php
                                                for ($i = 1; $i <= $how_many_reminder; $i++) { ?>
                                                    <li class="nav-item">
                                                        <a href="#email_reminder<?php echo $i; ?>"
                                                           id="email_reminder_link<?php echo $i; ?>"
                                                           class="nav-link <?php if ($i == 1) echo 'active'; ?>"
                                                           data-toggle="pill" role="tab"
                                                           aria-controls="email_reminder<?php echo $i; ?>"
                                                           aria-selected="true"><i
                                                                    class="bx bx-bell"></i> <?php echo $this->lang->line("Email Reminder"); ?>
                                                            #<?php echo $i; ?></a>

                                                        <?php
                                                        $tmp_name = 'email_reminder_time[]';
                                                        $tmp_id = 'email_reminder_time_' . $i;
                                                        $tmp_select = '';
                                                        echo form_dropdown($tmp_name, $hours, $tmp_select, 'id="' . $tmp_id . '" class="form-control" style="width: 100% !important;"');
                                                        ?>
                                                    </li>
                                                    <?php
                                                }
                                                ?>
                                                <li class="nav-item">
                                                    <a href="#email_checkout" id="email_checkout_link"
                                                       class="nav-link nav_cart" data-toggle="pill" role="tab"
                                                       aria-controls="msg_checkout" aria-selected="true"><i
                                                                class="bx bx-shopping-bag"></i> <?php echo $this->lang->line("Email Checkout Confirmation"); ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-7">
                                            <?php echo $this->lang->line("Email Content"); ?>
                                            <div class="tab-content" id="EmailReminderTabContent">
                                                <?php
                                                for ($i = 1; $i <= $how_many_reminder; $i++) { ?>
                                                    <div class="reminder_badge_warpper tab-pane fade <?php if ($i == 1) echo 'active show'; ?> "
                                                         style="border:none;padding: 0"
                                                         id="email_reminder<?php echo $i; ?>" role="tabpanel"
                                                         aria-labelledby="email_reminder_link>">
                                                        <span class="reminder_badge" data-toggle="tooltip"
                                                              title="<?php echo $this->lang->line('Email Reminder') . ' #' . $i; ?>"><i
                                                                    class="bx bx-bell"></i> <?php echo $i; ?></span>
                                                        <textarea class="visualeditor" data-toggle="tooltip"
                                                                  title="<?php echo $this->lang->line('Email content goes here.'); ?>"
                                                                  name="email_reminder_text_checkout[]"
                                                                  id="email_reminder_text_checkout<?php echo $i; ?>">Hi {{last_name}},<br>Have you forgot something special? Stock limited, complete your order before it is out of stock : <a href="{{checkout_url}}" target="_blank">Checkout here</a></a><br>Happy shopping :)</textarea>
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                                <div class="reminder_badge_warpper tab-pane fade"
                                                     style="border:none;padding: 0" id="email_checkout" role="tabpanel"
                                                     aria-labelledby="email_checkout_link>">
                                                    <span class="reminder_badge" data-toggle="tooltip"
                                                          title="<?php echo $this->lang->line('Email Checkout'); ?>"><i
                                                                class="bx bx-shopping-bag"></i></span>
                                                    <textarea class="visualeditor" data-toggle="tooltip"
                                                              title="<?php echo $this->lang->line('Email content goes here.'); ?>"
                                                              name="email_reminder_text_checkout_next"
                                                              id="email_reminder_text_checkout_next">Congratulations {{last_name}}!<br>Thanks for shopping from our store. You made the right choice. If you need any information, just leave us a message here.<br>Have a nice day :)</textarea>
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <div class="label"><?php echo $this->lang->line("Email Sender"); ?></div>
                                                <?php echo form_dropdown('email_api_id', $email_option, '', 'class="select2 form-control" id="email_api_id" style="width:100%"'); ?>
                                            </div>
                                            <div class="form-group">
                                                <div class="label"><?php echo $this->lang->line("Email Subject"); ?></div>
                                                <input name="email_subject" id="email_subject" class="form-control"
                                                       value="Abandoned Cart">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-footer">
                        <button class="btn btn-primary" id="get_button" name="get_button" type="button"><i
                                    class="bx bx-code"></i> <?php echo $this->lang->line("Generate Plugin"); ?></button>
                        <button class="btn btn-light float-right"
                                onclick="goBack('woocommerce_abandoned_cart/recovery_plugin_list')" type="button"><i
                                    class="bx bx-time"></i> <span
                                    class="align-middle ml-25"><?php echo $this->lang->line("cancel"); ?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>


<a href="" class="download"></a>

