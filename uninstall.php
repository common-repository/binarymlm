<?php

//this check makes sure that this file is called manually.
if (!defined("WP_UNINSTALL_PLUGIN")) 
    exit();

//put plugin uninstall code here

include_once dirname( __FILE__ ).'/includes/class-mlm-install.php';

$install_class=new Letscms_MLM_Install();
$install_class->uninstall();
