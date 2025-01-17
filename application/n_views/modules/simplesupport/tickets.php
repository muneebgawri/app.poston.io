<?php

//todo: 00000 before realease

?>

<div class="content-header row">
    <div class="content-header-left col-12 mb-2 mt-1">
        <div class="breadcrumbs-top">
            <h5 class="content-header-title float-left pr-1 mb-0"><?php echo $page_title; ?></h5>
            <div class="breadcrumb-wrapper d-none d-sm-block">
                <ol class="breadcrumb p-0 mb-0 pl-1">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard"><i
                                    class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/theme/message'); ?>
<?php
// if($this->session->flashdata('mark_seen_success')!='')
// echo "<div class='alert alert-primary text-center'><i class='bx bx-check-circle'></i> ".$this->session->flashdata('mark_seen_success')."</div>";
?>


<div class="row">
    <div class="col-12 col-md-7">
        <div class="input-group mb-3" id="searchbox">
            <div class="input-group-prepend">
                <select class="select2 form-control" id="ticket_status">
                    <option value="1"><?php echo $this->lang->line("Open"); ?></option>
                    <option value="3"><?php echo $this->lang->line("Resolved"); ?></option>
                    <option value="2"><?php echo $this->lang->line("Closed"); ?></option>
                    <?php if ($this->session->userdata("user_type") == "Admin") { ?>
                        <option value="hidden"><?php echo $this->lang->line("Hidden"); ?></option>
                    <?php } ?>
                    <option value=""><?php echo $this->lang->line("Everything"); ?></option>
                </select>
            </div>
            <input type="text" class="form-control" id="search" autofocus
                   placeholder="<?php echo $this->lang->line('Search...'); ?>" aria-label=""
                   aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" id="search_submit" type="button"><i
                            class="bx bx-search"></i> <?php echo $this->lang->line('Search'); ?></button>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-5">

        <?php if ($this->session->userdata("user_type") == "Admin") { ?>
            <a class="btn btn-outline-primary float-right"
               href="<?php echo base_url('simplesupport/support_category_manager'); ?>"><i
                        class="bx bx-layer"></i> <?php echo $this->lang->line("Manage Category"); ?></a>
            <?php
        } else { ?>
            <a class="btn btn-outline-primary float-right" href="<?php echo site_url('simplesupport/open_ticket'); ?>">
                <i class="bx bx-plus-circle"></i> <?php echo $this->lang->line("New Ticket"); ?>
            </a>
            <?php
        } ?>


    </div>
</div>

<div class="activities">
    <div id="load_data" style="width: 100%;"></div>
</div>


<div class="text-center" id="waiting" style="width: 100%;margin: 30px 0;">
    <i class="bx bx-loader-alt bx-spin blue" style="font-size:60px;"></i>
</div>

<div class="card" id="nodata" style="display: none">
    <div class="card-body">
        <div class="empty-state">
            <img class="img-fluid" style="height: 300px"
                 src="<?php echo base_url('assets/img/drawkit/drawkit-nature-man-colour.svg'); ?>" alt="image">
            <h2 class="mt-0"><?php echo $this->lang->line("We could not find any data.") ?></h2>
        </div>
    </div>
</div>


<button class="btn btn-outline-primary float-right" style="display: none;" id="load_more" data-limit="10"
        data-start="0"><i class="bx bx-book-reader"></i> <?php echo $this->lang->line("Load More"); ?></button>
      




