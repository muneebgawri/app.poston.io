<?php
$content_generator = file_exists(APPPATH.'modules/n_generator/include/modal_message_universal.php');
?>

<link rel="stylesheet"
      href="<?php echo base_url(); ?>n_assets/plugins/summernote/summernote-bs4.css?ver=<?php echo $n_config['theme_version']; ?>">
<link href="<?php echo base_url(); ?>plugins/datetimepickerjquery/jquery.datetimepicker.css?ver=<?php echo $n_config['theme_version']; ?>"
      rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css"
      href="<?php echo base_url(); ?>n_assets/app-assets/css/plugins/forms/wizard.min.css?ver=<?php echo $n_config['theme_version']; ?>">
<style>
    /* multicolumn layout */
    .multi_layout {
        margin: 0;
        background: #fff;
        border: .5px solid #ececec !important;
        border-top: none !important;
    }

    .multi_layout .card {
        border-radius: 0;
    }

    .multi_layout p, .multi_layout ul:not(.list-unstyled), .multi_layout ol {
        line-height: 15px;
    }

    .multi_layout .list-group li {
        padding: 15px 10px 12px 25px;
    }

    .multi_layout .collef, .multi_layout .colmid, .multi_layout .colrig {
        padding-left: 0px;
        padding-right: 0px;
    }

    .multi_layout .collef {
        border-right: 1px solid #ececec;
    }

    .multi_layout .colmid { /*border-right: .5px solid #dee2e6;*/
    }

    .multi_layout .main_card {
        min-height: 300px;
        box-shadow: none;
    }

    .multi_layout .collef .makeScroll_1 {
        max-height: 790px;
        overflow: auto;
    }

    .multi_layout .list-group {
        padding-top: 6px;
    }

    .multi_layout .list-group .list-group-item {
        border-radius: 0;
        border: .5px solid #dee2e6;
        border-left: none;
        border-right: none;
        cursor: pointer;
        z-index: 0;
    }

    .multi_layout .list-group .list-group-item:first-child {
        border-top: none;
    }

    .multi_layout .list-group .list-group-item:last-child {
        border-bottom: none;
    }

    .multi_layout .list-group .list-group-item.active {
        border: .5px solid #6777EF;
    }

    .multi_layout .mCSB_inside > .mCSB_container {
        margin-right: 0;
    }

    .multi_layout .card-statistic-1 {
        border-radius: 0;
    }

    .multi_layout h6.page_name {
        font-size: 14px;
    }

    .multi_layout .card .card-header input {
        max-width: 100% !important;
    }

    .multi_layout .waiting, .modal_waiting {
        height: 100%;
        width: 100%;
        display: table;
    }

    .multi_layout .waiting i, .modal_waiting i {
        font-size: 60px;
        display: table-cell;
        vertical-align: middle;
        padding: 30px 0;
    }

    .multi_layout .card .card-header h4 a {
        font-weight: 700 !important;
    }

    /* force active class */
    .list-unstyled .force_active {
        color: #fff;
        background: #6777EF;
    }

    .list-unstyled .force_active .media-body .media-title {
        color: #fff;
        background: #6777EF;
    }


    .makeScroll_1 {
        height: 280px;
        overflow: hidden;
    }

    .caret_font_size {
        font-size: 24px;
    }

    .page_list_heading {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        color: #34395e;
    }

    .page_list_media_title {
        font-size: 12px !important;
    }

    /*#search_page_list{display: none;}*/

    .facebook_page_search i {
        background: #6777ef;
        padding: 5px;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;
        border: solid 1px #eee;
        font-size: 11px !important;
    }

    .account_div_height {
        min-height: 280px;
        height: 100%;
    }


    .dropzone {
        min-height: 0px !important;
    }

    .dz-message {
        margin: 65px !important;
    }

    .note-editor .btn i, .note-editor .btn i {
        top: 0;
    }

    .note-toolbar {
        background: #eee !important;
    }
</style>