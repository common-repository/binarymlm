<?php
defined( 'ABSPATH' ) || exit; 
class Letscms_MLM_Email_Settings{
  use letscms_mlmfunction;
  public function mlmEmailTemplates(){
	  $this->letscms_check_first_user();
	if (isset($_POST['MLMMemberAction'])) {

        foreach ($_POST AS $key => $value) {
            update_option($key, $value);
        }

        if (isset($_POST['letscms_mlm_payout_mail'])) {
            update_option('letscms_mlm_payout_mail', 1);
        }
        else {
            update_option('letscms_mlm_payout_mail', 0);
        }

        if (isset($_POST['letscms_mlm_network_mail'])) {
            update_option('letscms_mlm_network_mail', 1);
        }
        else {
            update_option('letscms_mlm_network_mail', 0);
        }

        if (isset($_POST['letscms_mlm_withdrawal_mail'])) {
            update_option('letscms_mlm_withdrawal_mail', 1);
        }
        else {
            update_option('letscms_mlm_withdrawal_mail', 0);
        }

        if (isset($_POST['letscms_mlm_process_withdrawal_mail'])) {
            update_option('letscms_mlm_process_withdrawal_mail', 1);
        }
        else {
            update_option('letscms_mlm_process_withdrawal_mail', 0);
        }
        $msg = "<div class='notibar msgsuccess'><p>Your Email settings have been successfully updated.</p></div>";
        echo $msg;
    }
    ?>
<style type="text/css">
        body {
            /*Tahoma, Verdana, Arial, Helvetica;*/
            font-size: 12px;
        }
        #nav, #nav ul { /* all lists */
            padding: 0;
            margin: 0;
            list-style: none;
            line-height: 1;
        }
        #nav a {
            width: 14em;
            text-decoration:none;

        }
        #nav li ul li a {
            text-decoration:none;
            color: #000000;
            padding-left:10px;
            padding-top:10px;

            vertical-align: middle; /* | top | bottom */
        }
        #nav li { /* all list items */
            float: left;
            width: 14em; /* width needed or else Opera goes nuts */
            height:20px;
        }
        #nav li ul { /* second-level lists */
            position: absolute;
            /*background: #EBEAEB;*/
            padding-left:10px;
            padding-top:10px;
            width: 14em;
            left: -999em; /* using left instead of display to hide menus because display: none isn't read by screen readers */
        }
        #nav li ul li:hover {
            background: #F5F5F5;
        }
        #nav li:hover ul, #nav li.sfhover ul { /* lists nested under hovered list items */
            left: auto;
            background: #EBEAEB;
        }
        
        .table-hover { margin-top:20px; }
    </style>

    <script type="text/javascript">
        sfHover = function() {
            var sfEls = document.getElementById("nav").getElementsByTagName("LI");
            for (var i = 0; i < sfEls.length; i++) {
                sfEls[i].onmouseover = function() {
                    this.className += " sfhover";
                }
                sfEls[i].onmouseout = function() {
                    this.className = this.className.replace(new RegExp(" sfhover\\b"), "");
                }
            }
        }
        if (window.attachEvent)
            window.attachEvent("onload", sfHover);

        function mlm_insertHTML(html, field) { 
            var o = document.getElementById(field);
            try { 
                if (o.selectionStart || o.selectionStart === 0) {
                    o.focus();
                    var os = o.selectionStart;
                    var oe = o.selectionEnd;
                    var np = os + html.length;
                    o.value = o.value.substring(0, os) + html + o.value.substring(oe, o.value.length);
                    o.setSelectionRange(np, np);
                } else if (document.selection) {
                    o.focus();
                    sel = document.selection.createRange();
                    sel.text = html;
                } else {
                    o.value += html;
                }
            } catch (e) {
            }
        }
    </script>


<!-- Email Settings -->

    <form method="post">
        <div class="container1" >
        <div class="box1" ><h4 align="center">Payout Received Mail</h4></div>
        <table class="table table-hover" cellpadding="10" style="background-color: lemonchiffon; margin-top: 20px;">

            <tr valign="top">
                <th scope="row" style="border:none" class="WLRequired"><?php _e('Subject', 'mlm'); ?></th>
                <td style="border:none"><input type="text" name="letscms_mlm_runpayout_email_subject" value="<?php echo get_option('letscms_mlm_runpayout_email_subject', true); ?>" size="40" /></td>
            </tr>
            <tr valign="top">
                <th scope="row" class="WLRequired"><?php _e('Body', 'mlm'); ?></th>
                <td>
                    <textarea name="letscms_mlm_runpayout_email_message" id="<?php echo 'letscms_mlm_runpayout_email_message'; ?>" cols="40" rows="10" style="float:left;margin-right:10px"><?php echo get_option($x = 'letscms_mlm_runpayout_email_message'); ?></textarea>
                    <ul id="nav">
                        <li><a href="javascript:return false;"> &nbsp; Merge codes </a>
                            <ul>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[firstname]', '<?php echo $x; ?>')"><?php _e('Insert First Name', 'mlm'); ?></a> </li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[lastname]', '<?php echo $x; ?>')"><?php _e('Insert Last Name', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[email]', '<?php echo $x; ?>')"><?php _e('Insert Email', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[username]', '<?php echo $x; ?>')"><?php _e('Insert Username', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[amount]', '<?php echo $x; ?>')"><?php _e('Insert Amount', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[sitename]', '<?php echo $x; ?>')"><?php _e('Insert Sitename', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[payoutid]', '<?php echo $x; ?>')"><?php _e('Insert Payout Id', 'mlm'); ?></a></li>
                            </ul>
                        </li>
                    </ul>
                    <br clear="all" />
                    <?php _e('If you would like to insert info for the individual member<br />please click the merge field codes on the right.', 'mlm'); ?>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row" style="border:none" class="WLRequired">&nbsp;</th>
                <td style="border:none"><input type="checkbox" name="letscms_mlm_payout_mail" value="1" <?php if ($x = get_option('letscms_mlm_payout_mail', true) == 1) echo "checked"; ?>/>Enabled this Mail functionality</td>
            </tr>
        </table>  </div> <br><br>

        <div class="container1" >
        <div class="box1" ><h4 align="center">Network Growing Mail</h4></div>
        <table class="table table-hover" style="background-color: goldenrod;">

            <tr valign="top">
                <th scope="row" style="border:none" class="WLRequired"><?php _e('Subject', 'mlm'); ?></th>
                <td style="border:none"><input type="text" name="letscms_mlm_networkgrowing_email_subject" value="<?php echo get_option('letscms_mlm_networkgrowing_email_subject', true); ?>" size="40" /></td>
            </tr>
            <tr valign="top">
                <th scope="row" class="WLRequired"><?php _e('Body', 'mlm'); ?></th>
                <td>
                    <textarea name="letscms_mlm_networkgrowing_email_message" id="<?php echo 'letscms_mlm_networkgrowing_email_message'; ?>" cols="40" rows="10" style="float:left;margin-right:10px"><?php echo get_option($x = 'letscms_mlm_networkgrowing_email_message'); ?></textarea>
                    <ul id="nav">
                        <li><a href="javascript:return false;"> &nbsp; Merge codes </a>
                            <ul>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[firstname]', '<?php echo $x; ?>')"><?php _e('Insert First Name', 'mlm'); ?></a> </li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[lastname]', '<?php echo $x; ?>')"><?php _e('Insert Last Name', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[email]', '<?php echo $x; ?>')"><?php _e('Insert Email', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[username]', '<?php echo $x; ?>')"><?php _e('Insert Username', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[sponsor]', '<?php echo $x; ?>')"><?php _e('Insert Sponsor', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[parent]', '<?php echo $x; ?>')"><?php _e('Insert Parent', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[sitename]', '<?php echo $x; ?>')"><?php _e('Insert Sitename', 'mlm'); ?></a></li>

                            </ul>
                        </li>
                    </ul>
                    <br clear="all" />
                    <?php _e('If you would like to insert info for the individual member<br />please click the merge field codes on the right.', 'mlm'); ?>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row" style="border:none" class="WLRequired">&nbsp;</th>
                <td style="border:none"><input type="checkbox" name="letscms_mlm_network_mail" value="1" <?php if ($w = get_option('letscms_mlm_network_mail', true) == 1) echo "checked"; ?> />Enabled this Mail functionality</td>
            </tr>		
        </table>    </div> <br><br>

<div class="container1" >
        <div class="box1" ><h4 align="center">Withdrawal Initiated Mail</h4></div>
      
        <table class="table table-hover" cellpadding="10" style="background-color: lemonchiffon;">

            <tr valign="top">
                <th scope="row" style="border:none" class="WLRequired"><?php _e('Subject', 'mlm'); ?></th>
                <td style="border:none"><input type="text" name="letscms_mlm_withdrawalInitiate_email_subject" value="<?php echo get_option('letscms_mlm_withdrawalInitiate_email_subject', true); ?>" size="40" /></td>
            </tr>
            <tr valign="top">
                <th scope="row" class="WLRequired"><?php _e('Body', 'mlm'); ?></th>
                <td>
                    <textarea name="letscms_mlm_withdrawalInitiate_email_message" id="<?php echo 'letscms_mlm_withdrawalInitiate_email_message'; ?>" cols="40" rows="10" style="float:left;margin-right:10px"><?php echo get_option($x = 'letscms_mlm_withdrawalInitiate_email_message'); ?></textarea>
                    <ul id="nav">
                        <li><a href="javascript:return false;"> &nbsp; Merge codes </a>
                            <ul>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[firstname]', '<?php echo $x; ?>')"><?php _e('Insert First Name', 'mlm'); ?></a> </li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[lastname]', '<?php echo $x; ?>')"><?php _e('Insert Last Name', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[email]', '<?php echo $x; ?>')"><?php _e('Insert Email', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[username]', '<?php echo $x; ?>')"><?php _e('Insert Username', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[password]', '<?php echo $x; ?>')"><?php _e('Insert Password', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[loginurl]', '<?php echo $x; ?>')"><?php _e('Insert Login URL', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[memberlevel]', '<?php echo $x; ?>')"><?php _e('Insert Membership Level', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[amount]', '<?php echo $x; ?>')"><?php _e('Insert Amount', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[comment]', '<?php echo $x; ?>')"><?php _e('Insert Comment', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[payoutid]', '<?php echo $x; ?>')"><?php _e('Insert Payout Id', 'mlm'); ?></a></li>
                            </ul>
                        </li>
                    </ul>
                    <br clear="all" />
                    <?php _e('If you would like to insert info for the individual member<br />please click the merge field codes on the right.', 'mlm'); ?>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row" style="border:none" class="WLRequired">&nbsp;</th>
                <td style="border:none"><input type="checkbox" name="letscms_mlm_withdrawal_mail" value="1" <?php if ($v = get_option('letscms_mlm_withdrawal_mail', true) == 1) echo "checked"; ?> />Enabled this Mail functionality</td>
            </tr>		

        </table>   </div><br><br>

<div class="container1" >
        <div class="box1" ><h4 align="center">Withdrawal Processed Mail</h4></div>

         <table class="table table-hover" style="background-color: goldenrod;">

            <tr valign="top">
                <th scope="row" style="border:none" class="WLRequired"><?php _e('Subject', 'mlm'); ?></th>
                <td style="border:none"><input type="text" name="letscms_mlm_withdrawalProcess_email_subject" value="<?php echo get_option('letscms_mlm_withdrawalProcess_email_subject', true); ?>" size="40" /></td>
            </tr>
            <tr valign="top">
                <th scope="row" class="WLRequired"><?php _e('Body', 'mlm'); ?></th>
                <td>
                    <textarea name="letscms_mlm_withdrawalProcess_email_message" id="<?php echo 'letscms_mlm_withdrawalProcess_email_message'; ?>" cols="40" rows="10" style="float:left;margin-right:10px"><?php echo get_option($x = 'letscms_mlm_withdrawalProcess_email_message'); ?></textarea>
                    <ul id="nav">
                        <li><a href="javascript:return false;"> &nbsp; Merge codes </a>
                            <ul>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[firstname]', '<?php echo $x; ?>')"><?php _e('Insert First Name', 'mlm'); ?></a> </li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[lastname]', '<?php echo $x; ?>')"><?php _e('Insert Last Name', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[email]', '<?php echo $x; ?>')"><?php _e('Insert Email', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[username]', '<?php echo $x; ?>')"><?php _e('Insert Username', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[amount]', '<?php echo $x; ?>')"><?php _e('Insert Amount', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[withdrawalmode]', '<?php echo $x; ?>')"><?php _e('Insert Withdrawal Mode', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[payoutid]', '<?php echo $x; ?>')"><?php _e('Insert Payout Id', 'mlm'); ?></a></li>
                                <li><a href="javascript:;" onclick="mlm_insertHTML('[sitename]', '<?php echo $x; ?>')"><?php _e('Insert Sitename', 'mlm'); ?></a></li>
                            </ul>
                        </li>
                    </ul>
                    <br clear="all" />
                    <?php _e('If you would like to insert info for the individual member<br />please click the merge field codes on the right.', 'mlm'); ?>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row" style="border:none" class="WLRequired">&nbsp;</th>
                <td style="border:none"><input type="checkbox" name="letscms_mlm_process_withdrawal_mail" value="1"  <?php if ($u = get_option('letscms_mlm_process_withdrawal_mail', true) == 1) echo "checked"; ?>/>Enabled this Mail functionality</td>
            </tr>		

        </table>    </div>           
<br>
<div class="col-md-6">
  <div class="form-group">
    <button type="submit" name="MLMMemberAction" id="mlm_eligibility_settings" class="btn btn-primary btn-default" onclick="needToConfirm = false;">Save Settings &raquo;</button>
  </div>
  </div>

    </form>

<?php
  }//end function mlmEmailTemplate()
}// end of class
