<?php
defined( 'ABSPATH' ) || exit;
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Withdrawals_List_Table extends WP_List_Table {
    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'username':
            case 'useremail':
            case 'payoutid':
            case 'payoutdate':
            case 'withdrawaldate':
            case 'withdrawalamount':
            case 'paymentmode':
            case 'paymentdetails':
                return $item[$column_name];
            default:
                return print_r($item, true);
        }
    }

    function get_columns() {
        $columns = array(
            'username' => __('User Name', 'mlm'),
            'useremail' => __('User Email', 'mlm'),
            'payoutid' => __('Payout Id', 'mlm'),
            'payoutdate' => __('Payout Date', 'mlm'),
            'withdrawaldate' => __('Withdrawal Date', 'mlm'),
            'withdrawalamount' => __('Withdrawal Amount', 'mlm'),
            'paymentmode' => __('Payment Mode', 'mlm'),
            'paymentdetails' => __('Payment Details', 'mlm')
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'username' => array('username', false),
            'useremail' => array('useremail', false)
        );
        return $sortable_columns;
    }

    function prepare_items() {
        global $wpdb;
        global $date_format;
        $per_page = 10;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $sql = "SELECT * FROM {$wpdb->prefix}binarymlm_payout WHERE withdrawal_initiated= 1 AND `payment_processed`= 1";
        $i = 0;
        $ID = 1;
        $listArr = array();
        $rs = $wpdb->get_results($sql, ARRAY_A);
        $num = $wpdb->num_rows;
        if ($num > 0) {
            foreach ($rs as $row) {
                $sql1 = "SELECT {$wpdb->prefix}binarymlm_users.username AS uname , {$wpdb->prefix}users.user_email AS uemail FROM {$wpdb->prefix}users,{$wpdb->prefix}binarymlm_users WHERE {$wpdb->prefix}binarymlm_users.username = {$wpdb->prefix}users.user_login AND {$wpdb->prefix}binarymlm_users.id = '" . $row['user_id'] . "'";

                $row1 = $wpdb->get_row($sql1, ARRAY_A);

                $payoutDetail['memberId'] = $row['user_id'];
                $withdrawalamount = number_format($row['capped_amt'], 2);
                $paymentmode = $row['payment_mode'];
                $date = date_create($row['payment_processed_date']);
                $paymentdate = date_format($date, $date_format);

                /*                 * ******************* Cheque Info ****************************** */
                $cheque_no = $row['cheque_no'];
                $chdate = date_create($row['cheque_date']);
                $cheque_date = date_format($chdate, $date_format);
                $bank_name = $row['bank_name'];

                /*                 * ******************** Bank Transfer Info *********************** */
                $beneficiary = $row['beneficiary'];
                $ubank_name = $row['user_bank_name'];
                $ub_account_no = $row['user_bank_account_no'];
                $bt_code = $row['banktransfer_code'];

                /*                 * ************************* Other Info **************************** */
                $other = $row['other_comments'];

                if ($paymentmode == 'cheque') {
                    $paymentdetail = 'Cheque No: ' . $cheque_no . '<br/>Cheque Date: ' . $cheque_date . '<br/>Bank Name: ' . $bank_name .
                            '<br/>Date: ' . $paymentdate . '<br/>';
                }
                elseif ($paymentmode == 'bank-transfer') {
                    $paymentdetail = 'Benificiary: ' . $beneficiary . '<br/>Bank Name: ' . $ubank_name . '<br/>Account No: '
                            . $ub_account_no . '<br/>Banktransfer Code: ' . $bt_code . '<br/>Date: ' . $paymentdate . '<br/>';
                }
                elseif ($paymentmode == 'other') {
                    $paymentdetail = 'Date: ' . $paymentdate . '<br/>' . $other . '<br/>';
                }
                else {
                    $paymentdetail = 'Date: ' . $paymentdate . '<br/>';
                }

                if ($paymentmode == 'cheque') {
                    $payment_mode = "Cheque";
                }
                elseif ($paymentmode == 'bank-transfer') {
                    $payment_mode = "Bank Transfer";
                }
                elseif ($paymentmode == 'cash') {
                    $payment_mode = "Cash";
                }
                else {
                    $payment_mode = "Other";
                }

                $listArr[$i]['username'] = $row1['uname'];
                $listArr[$i]['useremail'] = $row1['uemail'];
                $listArr[$i]['payoutid'] = $row['payout_id'];
                $pdate = date_create($row['date']);
                $listArr[$i]['payoutdate'] = date_format($pdate, $date_format);
                $widate = date_create($row['withdrawal_initiated_date']);
                $listArr[$i]['withdrawaldate'] = date_format($widate, $date_format);
                $listArr[$i]['withdrawalamount'] = $withdrawalamount;
                $listArr[$i]['paymentmode'] = $payment_mode;
                $listArr[$i]['paymentdetails'] = str_replace('<br/>', '', $paymentdetail);
                $listArr[$i]['paymentdate'] = $paymentdate;
                $i++;
            }
        }

        $data = $listArr;
        $listArrtitle['username'] = __('User Name', 'mlm');
        $listArrtitle['useremail'] = __('User Email', 'mlm');
        $listArrtitle['payoutid'] = __('Payout Id', 'mlm');
        $listArrtitle['payoutdate'] = __('Payout Date', 'mlm');
        $listArrtitle['withdrawaldate'] = __('Withdrawal Date', 'mlm');
        $listArrtitle['withdrawalamount'] = __('Withdrawal Amount', 'mlm');
        $listArrtitle['paymentmode'] = __('Payment Mode', 'mlm');
        $listArrtitle['paymentdetails'] = __('Payment Details', 'mlm');
        array_unshift($listArr, $listArrtitle);
        update_option('letscms_mlm_sucessed_withdrawal_list', $listArr);

        function usort_reorder($a, $b) {
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'paymentdate';
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc';
            $result = strcmp($a[$orderby], $b[$orderby]);
            return ($order === 'asc') ? $result : -$result;
        }

        usort($data, 'usort_reorder');

        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);
        $this->items = $data;
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }
}
