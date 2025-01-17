<div class="container vh-100">
<div class="col-md-6 img-block bg-img" style="background-image:url('<?php echo xit_load_images('images/login-or-signup.jpg'); ?>');min-height: 100%;"></div>
<div class="col-md-5 p-y-md m-x">
        <div class="" id="recovery_form">
          <form method="POST" class="form-white">
		  <p class="text-center"><a href="<?php echo base_url();?>"><img src="<?php echo base_url();?>assets/img/logo.png" alt="<?php echo $this->config->item('product_name');?>" width="200"></a></p>
		  <h5 class="text-center"><?php echo $this->lang->line("Password Recovery"); ?></h5>
          <p class="text-muted"><?php echo $this->lang->line("We will send you a email containing steps to reset password"); ?></p>
            <div class="form-group">
              <label for="email"><?php echo $this->lang->line("email"); ?> *</label>
              <div class="invalid-feedback"><?php echo $this->lang->line("Please enter your email"); ?></div>
              <input id="email" type="email" class="form-control" id="email" name="email" tabindex="1" autofocus>
            </div>

            <div class="form-group">
              <button type="submit" id="submit" class="btn btn-blue text-uppercase" tabindex="4">
               <?php echo $this->lang->line("Send Reset Link"); ?>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>



<script type="text/javascript">
$('document').ready(function(){
  $("#submit").click(function(e){
    e.preventDefault();


    var email=$("#email").val();
    if(email=='')
    {
        $("#email").addClass('is-invalid');
        return false;
    }
    else
    {
        $("#email").removeClass('is-invalid');
    }
    $(this).addClass('btn-progress');
    $.ajax({
      context: this,
      type:'POST',
      url: "<?php echo site_url();?>home/code_genaration",
      data:{email:email},
      success:function(response){
        $(this).removeClass('btn-progress');
        if(response=='0')
        {
          swal('<?php echo $this->lang->line("Error") ?>', '<?php echo $this->lang->line("Invalid email or it is not associated with any user") ?>', 'error');
        }
        else
        {
          var string='<div class="alert alert-primary alert-has-icon"><div class="alert-icon"><i class="far fa-paper-plane"></i></div><div class="alert-body"><div class="alert-title"><?php echo $this->lang->line("Sent") ?></div><?php echo $this->lang->line("A email containing password reset steps has been sent to your email."); ?></div></div>';
          $("#recovery_form").slideUp();
          $("#recovery_form").html(string);
          $("#recovery_form").slideDown();
        }
    }
    });
    
  });
});
</script>