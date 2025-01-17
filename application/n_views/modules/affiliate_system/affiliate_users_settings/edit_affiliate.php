<?php
$general_signup_commission = isset($info['signup_commission']) ? $info['signup_commission'] : 0;
$general_sign_up_amount = isset($info['sign_up_amount']) ? $info['sign_up_amount'] : '';
$general_payment_commission = isset($info['payment_commission']) ? $info['payment_commission'] : 0;
$general_payment_type = isset($info['payment_commission']) ? $info['payment_type'] : '';
$general_payment_percentage = isset($info['percentage']) ? $info['percentage'] : '';
$general_payment_fixed_amount = isset($info['fixed_amount']) ? $info['fixed_amount'] : '';
$general_is_recurring = isset($info['is_recurring']) ? $info['is_recurring'] : 0;

$is_fixed = false;
$is_percentage = false;
$fixed_amount = "";
$percentage_amount = "";

$individual_payment_type = isset($xdata['payment_type']) ? $xdata['payment_type'] : "";
$individual_fixed_amount = isset($xdata['fixed_amount']) ? $xdata['fixed_amount'] : "";
$individual_percentage_amount = isset($xdata['percentage_amount']) ? $xdata['percentage_amount'] : "";

if ($individual_payment_type == 'fixed' && $general_payment_type == 'fixed') $is_fixed = true;
else if ($individual_payment_type == 'fixed' && $general_payment_type == 'percentage') $is_fixed = true;
else if ($individual_payment_type == 'fixed' && $general_payment_type == '') $is_fixed = true;
else if ($individual_payment_type == '' && $general_payment_type == 'fixed') $is_fixed = true;

if ($individual_payment_type == 'percentage' && $general_payment_type == 'percentage') $is_percentage = true;
else if ($individual_payment_type == 'percentage' && $general_payment_type == 'fixed') $is_percentage = true;
else if ($individual_payment_type == 'percentage' && $general_payment_type == '') $is_percentage = true;
else if ($individual_payment_type == '' && $general_payment_type == 'percentage') $is_percentage = true;

?>
<div class="content-header row">
    <div class="content-header-left col-12 mb-2 mt-1">
        <div class="breadcrumbs-top">
            <h5 class="content-header-title float-left pr-1 mb-0"><?php echo $page_title; ?></h5>
            <div class="breadcrumb-wrapper d-none d-sm-block">
                <ol class="breadcrumb p-0 mb-0 pl-1">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard"><i
                                    class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a
                                href="<?php echo base_url('affiliate_system/affiliate_users'); ?>"><?php echo $this->lang->line("Affiliate Users"); ?></a>
                    </li>
                    <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/theme/message'); ?>

<div class="row">
    <div class="col-12">

        <form class="form-horizontal" action="<?php echo site_url() . 'affiliate_system/edit_affiliate_action'; ?>"
              method="POST">
            <input type="hidden" name="csrf_token" id="csrf_token"
                   value="<?php echo $this->session->userdata('csrf_token_session'); ?>">
            <input type="hidden" name="affiliate_id" id="affiliate_id" value="<?php echo $xdata['id']; ?>">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name"> <?php echo $this->lang->line("Full Name") ?> </label>
                                <input name="name" value="<?php echo $xdata['name']; ?>" class="form-control"
                                       type="text">
                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="username"> <?php echo $this->lang->line("username") ?> *</label>
                                <input name="username" value="<?php echo $xdata['username']; ?>" class="form-control"
                                       type="text">
                                <span class="text-danger"><?php echo form_error('username'); ?></span>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="email"> <?php echo $this->lang->line("Email") ?> *</label>
                                <input name="email" value="<?php echo $xdata['email']; ?>" class="form-control"
                                       type="email">
                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="mobile"><?php echo $this->lang->line("Mobile") ?></label>
                                <input name="mobile" value="<?php echo $xdata['mobile']; ?>" class="form-control"
                                       type="text">
                                <span class="text-danger"><?php echo form_error('mobile'); ?></span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="address"> <?php echo $this->lang->line("Address") ?></label>
                                <textarea name="address"
                                          class="form-control"><?php echo $xdata['address']; ?></textarea>
                                <span class="text-danger"><?php echo form_error('address'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="status"> <?php echo $this->lang->line('Status'); ?></label><br>
                                <label class="custom-switch mt-2">
                                    <input type="checkbox" name="status" value="1"
                                           class="custom-switch-input" <?php if ($xdata['status'] == '1') echo "checked"; ?>>
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description"><?php echo $this->lang->line('Active'); ?></span>
                                    <span class="text-danger"><?php echo form_error('status'); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="status"><?php echo $this->lang->line('Set Custom Commission'); ?>
                                    <a href="#" data-placement="top" data-trigger="focus" data-toggle="popover"
                                       title="<?php echo $this->lang->line("Commission Settings"); ?>"
                                       data-content="<?php echo $this->lang->line("If you want to set special commission for this affiliate on signup/payment then enable it to start the procedure. If you set special commission, this affiliate will get commissions based on this settings instead of Generic payment setttings for affiliate."); ?>"><i
                                                class='bx bx-info-circle'></i> </a>
                                </label><br>
                                <label class="custom-switch mt-2">
                                    <input type="checkbox" name="is_overwritten" id="is_overwritten" value="1"
                                           class="custom-switch-input" <?php if ($xdata['is_overwritten'] == '1') echo "checked"; ?>>
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description"><?php echo $this->lang->line('Yes'); ?></span>
                                    <span class="text-danger"><?php echo form_error('is_overwritten'); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row"
                         id="commission_section" <?php if ($xdata['is_overwritten'] == '1') echo 'style="display:block"'; else echo 'style="display: none;"'; ?>>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header" style="border:1px solid #e4e6fc;border-bottom:none;">
                                    <h4><i class="bx bx-gift"></i> <?php echo $this->lang->line('Affiliate Payment'); ?>
                                    </h4>
                                </div>

                                <div class="card-body" style="border:1px solid #e4e6fc">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group mt-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="1" id="by_signup"
                                                           name="signup_commission"
                                                           class="custom-control-input" <?php if ($xdata['is_signup_commission'] == '1' || $general_signup_commission == '1') echo "checked"; ?>>
                                                    <label class="custom-control-label pointer"
                                                           for="by_signup"><?php echo $this->lang->line("Signup Commission"); ?>
                                                        <a href="#" data-placement="top" data-trigger="focus"
                                                           data-toggle="popover"
                                                           title="<?php echo $this->lang->line("Signup Commission"); ?>"
                                                           data-content="<?php echo $this->lang->line("Affiliate will get commission on every user signup who have come through the affiliate link."); ?>"><i
                                                                    class='bx bx-info-circle'></i> </a>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group"
                                                 id="signup_sec_div" <?php if ($xdata['is_signup_commission'] == '1' || $general_signup_commission == '1') echo 'style="display:block"'; else echo 'style="display: none;"'; ?>>
                                                <label for="signup_amount"><i
                                                            class="bx bx-briefcase"></i> <?php echo $this->lang->line('Amount'); ?>
                                                </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend"
                                                         title="<?php echo $this->lang->line('Currency'); ?>">
                                                        <div class="input-group-text">
                                                            <?php echo $curency_icon; ?>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" name="signup_amount"
                                                           id="signup_amount"
                                                           value="<?php if ($xdata['signup_amount'] != '') echo $xdata['signup_amount']; else echo $general_sign_up_amount; ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group mt-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="1" id="by_payment" name="is_payment"
                                                           class="custom-control-input" <?php if ($xdata['is_payment'] == '1' || $general_payment_commission == '1') echo "checked"; ?>>
                                                    <label class="custom-control-label pointer"
                                                           for="by_payment"><?php echo $this->lang->line("Payment Commission"); ?>
                                                        <a href="#" data-placement="top" data-trigger="focus"
                                                           data-toggle="popover"
                                                           title="<?php echo $this->lang->line("Payment Commission"); ?>"
                                                           data-content="<?php echo $this->lang->line("Affiliate will get commission on every package buying payment who have registered with the affiliate link."); ?>"><i
                                                                    class='bx bx-info-circle'></i> </a>
                                                    </label>
                                                </div>
                                            </div>

                                            <div id="payment_sec_div" <?php if ($xdata['is_payment'] == '1' || $general_payment_commission == '1') echo 'style="display:block"'; else echo 'style="display: none;"'; ?>>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for=""><i
                                                                        class="bx bx-arch"></i> <?php echo $this->lang->line('Fixed'); ?>
                                                            </label><br>
                                                            <label class="custom-switch mt-2">
                                                                <input type="radio" name="payment_type"
                                                                       id="payment_type" value="fixed"
                                                                       class="custom-switch-input" <?php if ($is_fixed) echo "checked"; ?>>
                                                                <span class="custom-switch-indicator"></span>
                                                                <span class="custom-switch-description"><?php echo $this->lang->line('Enable'); ?></span>
                                                                <span class="text-danger"><?php echo form_error('payment_type'); ?></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for=""><i
                                                                        class="bx bx-trending-up"></i> <?php echo $this->lang->line('Percentage'); ?>
                                                            </label>
                                                            <br>
                                                            <label class="custom-switch mt-2">
                                                                <input type="radio" name="payment_type"
                                                                       id="payment_type" value="percentage"
                                                                       class="custom-switch-input" <?php if ($is_percentage) echo "checked"; ?>>
                                                                <span class="custom-switch-indicator"></span>
                                                                <span class="custom-switch-description"><?php echo $this->lang->line('Enable'); ?></span>
                                                                <span class="text-danger"><?php echo form_error('payment_type'); ?></span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label for=""><i
                                                                        class="bx bx-dollar-circle"></i> <?php echo $this->lang->line('Recurring'); ?>
                                                            </label>
                                                            <br>
                                                            <label class="custom-switch mt-2">
                                                                <input type="checkbox" name="is_recurring"
                                                                       id="is_recurring" value="1"
                                                                       class="custom-switch-input" <?php if ($xdata["is_recurring"] == '1' || $general_is_recurring == '1') echo "checked"; ?>>
                                                                <span class="custom-switch-indicator"></span>
                                                                <span class="custom-switch-description"><?php echo $this->lang->line('Enable'); ?></span>
                                                                <span class="text-danger"><?php echo form_error('is_recurring'); ?></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" id="fixed_amount_div"
                                                    <?php
                                                    if ($is_fixed)
                                                        echo 'style="display:block"';
                                                    else
                                                        echo 'style="display: none;"'; ?>
                                                >
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"
                                                             title="<?php echo $this->lang->line('Currency'); ?>">
                                                            <div class="input-group-text">
                                                                <?php echo $curency_icon; ?>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="fixed_amount"
                                                               id="fixed_amount"
                                                               value="<?php if ($xdata['fixed_amount'] != '') echo $xdata['fixed_amount']; else echo $general_payment_fixed_amount; ?>">
                                                        <span class="text-danger"><?php echo form_error('fixed_amount'); ?></span>
                                                    </div>
                                                </div>

                                                <div class="form-group"
                                                     id="percentage_div" <?php if ($is_percentage) echo 'style="display:block"'; else echo 'style="display: none;"'; ?>>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bx bx-trending-up"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="percent_amount"
                                                               id="percent_amount"
                                                               value="<?php if ($xdata['percentage_amount'] != '') echo $xdata['percentage_amount']; else echo $general_payment_percentage; ?>">
                                                        <span class="text-danger"><?php echo form_error('percent_amount'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-whitesmoke">
                    <button name="submit" type="submit" class="btn btn-primary"><i class="bx bx-save"></i> <span
                                class="align-middle ml-25"><?php echo $this->lang->line("save"); ?></span></button>
                    <button type="button" class="btn btn-secondary float-right"
                            onclick='goBack("affiliate_system/affiliate_users",0)'><i class="bx bx-trash"></i> <span
                                class="align-middle ml-25"><?php echo $this->lang->line("cancel"); ?></span></button>
                </div>
            </div>
        </form>
    </div>
</div>


