<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * SDv1 Process Controller
 * 
 * Controlls the Payment Process
 * 
 * This file is Part of SousrceDonatesv1
 * SousrceDonatesv1 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version. 
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * 
 * @package    SousrceDonatesv1
 * @author     Werner Maisl
 * @copyright  (c) 2012-2014 - Werner Maisl
 * @license    GNU AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 */

/* Status-IDs:
 * 0: Just Donated
 * 1: Approved
 * 2: Added Donator Rights
 * 3: Donation Expired (Removed Donator Rights)
 * 
 */


class Process extends CI_Controller{
    
    function index(){
        
        $data['lang'] = $this->config->item('site_language');
        
        $this->donate(); //redirect to the Donate Function
    }
    
    function donate(){
                
        $this->load->library('paypal_class');
        $this->load->library('encrypt');
        $this->load->helper('url');
        
        $p = new Paypal_class();
        
        $_this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
        
        //get the user-data from the post
        $steam_id = $this->input->post('steamid');
        $username = $this->input->post('nickname');
        $password = "no-pass";
        $forum_email = $this->input->post('email');
        $plan_id = $this->input->post('planid');
        $provider = $this->input->post('payment-provider');
        
        // check if the testmode is enabled
        if($this->config->item('testmode_enabled') === "1"){
            $provider = "testing";
        }

        
        
        //print_r($_POST);
        
        //echo "</br>Provider: '". $provider . "'<br/>";
        
        // for testing:
        // $provider = "paypal";

        //check if needed data is aviable
        if($steam_id === FALSE) redirect('/donate' , 'refresh');
        if($username === FALSE) redirect('/donate' , 'refresh');
        if($password === FALSE) redirect('/donate' , 'refresh');
        if($forum_email === FALSE) redirect('/donate' , 'refresh');
        if($plan_id === FALSE) redirect('/donate' , 'refresh');
        if($provider === FALSE) redirect('/donate' , 'refresh');

        
//        echo "</br>SteamID: ".$steam_id;
//        echo "</br>Username: ".$username;
//        echo "</br>Password: ".$password;
//        echo "</br>Forum-Email: ".$forum_email;
//        echo "</br>Plan_id: ".$plan_id;
/*        
        if($this->config->item('integration_forum_enabled') === "1" ){//Forum Integration enabled and User logged in            

            echo "if2";
            //Get Forum-Id from the DB
            
            $config_forum['hostname'] = $this->config->item('database_forum_host');
            $config_forum['username'] = $this->config->item('database_forum_user');
            $config_forum['password'] = $this->config->item('database_forum_password');
            $config_forum['database'] = $this->config->item('database_forum_db');
            $config_forum['dbdriver'] = "mysql";
            $config_forum['dbprefix'] = $this->config->item('database_forum_prefix');
            $config_forum['pconnect'] = FALSE;
            $config_forum['db_debug'] = TRUE;
            $config_forum['cache_on'] = FALSE;
            $config_forum['cachedir'] = "";
            $config_forum['char_set'] = "utf8";
            $config_forum['dbcollat'] = "utf8_general_ci";

            $DB_Forum = $this->load->database($config_forum,TRUE);

            switch ($this->config->item('integration_forum_system')){
                case 'mybb':
                    $DB_Forum->where('email', $forum_email);
                    $query_mail = $DB_Forum->get($this->config->item('integration_forum_usertable'));
                    $row_mail = $query_mail->row_array();
                    if($query_mail->num_rows >0)$forum_userid = $row_mail['uid']; 
                    break;
                case 'smf':
                    $DB_Forum->where('email', $forum_email); //change
                    $query_mail = $DB_Forum->get($this->config->item('integration_forum_usertable'));
                    $row_mail = $query_mail->row_array();
                    if($query_mail->num_rows >0)$forum_userid = $row_mail['email']; //change to smf uid row
                    break;
                case 'ipb':
                    $DB_Forum->where('email', $forum_email);
                    $query_mail = $DB_Forum->get($this->config->item('integration_forum_usertable'));
                    $row_mail = $query_mail->row_array();
                    if($query_mail->num_rows >0) $forum_userid = $row_mail['member_id'];
                    break;
                case 'vbulletin':
                    $DB_Forum->where('email', $forum_email); //change
                    $query_mail = $DB_Forum->get($this->config->item('integration_forum_usertable'));
                    $row_mail = $query_mail->row_array();
                    if($query_mail->num_rows >0)$forum_userid = $row_mail['email']; //change to vbulletin uid row
                    break;
                } 
        }elseif($this->config->item('integration_forum_enabled') === "0"){ //Forum Integration not enabled
            $forum_userid = 0;
        }
*/        
		$forum_userid = 0;
        //remove the ' ' in the Username
        $username = str_replace(" ", "", $username);
        
        //get the Plan data from the Main-DB
        $DB_Main = $this->load->database("default", TRUE);
        $DB_Main-> where("plan_id", $plan_id);
        $query_plan = $DB_Main->get("plans");
        $row_plan = $query_plan->row_array();
        
        $donation_reset_time = $row_plan["plan_time"];
        $donation_cost = $row_plan["plan_price"];

        //create the Userdata
        $userdata = $steam_id .",".$username.",".$password.",".$plan_id.",".$forum_userid;
        //$userdata = $this->encrypt->encode($userdata);
        
        echo "</br> Provider Selection";
        
        if ( $provider === "paypal"){ //paypal selected as provider
            echo "</br> Provider = PayPal";
            $business = $this->config->item("payment_pp_email");
            echo "business: ". $business . "</br><br/>";
            
            if($this->config->item('payment_pp_use_sandbox')=== '1'){
                $p->paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
                echo $p->paypal_url;
            }else{
                $p->paypal_url = "https://www.paypal.com/cgi-bin/webscr";
                echo $p->paypal_url;
            }
            
            
            
            $p->add_field('custom', $userdata);
            $p->add_field('no_shipping', '1');
            $p->add_field('business', $business);
            $p->add_field('return', site_url("process/success"));
            $p->add_field('cancel_return',  site_url("process/cancel"));
            $p->add_field('notify_url',  site_url("process/ipn_pp"));
            if($donation_reset_time === "0"){
                $p->add_field('item_name', "Reminder: Your donation is never reset");
            }else{
                $p->add_field('item_name', "Reminder: Your donation is reset after ". $donation_reset_time . " Days");
            }
            $p->add_field('amount', $donation_cost );
            $p->add_field('currency_code', $this->config->item("payment_pp_currency"));
            $p->add_field('rm','2'); // Return method = POST
            $p->add_field('cmd','_donations'); 

            $p->submit_paypal_post(); // submit the fields to paypal
            $p->dump_fields();      // for debugging, output a table of all the fields

        }elseif( $provider === "paygol"){ // paygol selected as provider //NOT Working

            $p->paypal_url = $pg_url;
            $p->add_field('pg_serviceid', $pg_serviceid);
            $p->add_field('pg_currency' , $pg_currency);
            $p->add_field('pg_name', $pg_name);
            $p->add_field('pg_custom' , $userdata);
            $p->add_field('pg_price', $donation_cost);
            $p->add_field('pg_cancel_url' , site_url("process/cancel") );
            $p->add_field('pg_return_url' , site_url("process/success") );
            $p->add_field('pg_notify_url' , site_url("process/ipn_pg"));
            $p->submit_paypal_post();


        }elseif($provider === "paysafe"){
            
            echo "</br>Provider: Paysafecard ";
            
            //PaySafe with Mollie.nl
            if($this->config->item('payment_mollie_use_paysafe') === '1'){
                
                echo "</br>Provider: Paysafecard (mollie)";
                
                $this->load->model('donate_functions');
                
                $partner_id = $this->config->item('payment_mollie_partnerid');
                $profile_key = $this->config->item('payment_mollie_profile_key');
                $customer_ref = 'klant'.$_SERVER['REMOTE_ADDR'];
                $amount = $donation_cost*100;
                $return_url = site_url('process/success');
                $report_url = site_url('process/ipn_mollie_psc');
                
                
                $profile_key = trim($profile_key);
                
                
//                echo "</br>partner_id ". $partner_id;
//                echo "</br>profile_key '". $profile_key."'";
//                echo "</br>customer_ref ". $customer_ref;
//                echo "</br>amount ". $amount;
//                echo "</br>return_url ". $report_url;
//                echo "</br>report_url ". $return_url;
                
                
                
                
                require_once './application/libraries/paysafecard.mollie.php';
                
                
                if (!in_array('ssl', stream_get_transports()))
                {
                    echo "<h1>Foutmelding</h1>";
                    echo "<p>Uw PHP installatie heeft geen SSL ondersteuning. SSL is nodig voor de communicatie met de Mollie Paysafecard API.</p>"; //This is a dutch error message that says the site doesnt support ssl and its needot to communicate twith mollie paysafe API
                    exit;	
                }

                $paysafecard = new PaySafeCard_Payment ($partner_id);

                if ($paysafecard->createPayment($amount, $profile_key, $customer_ref, $return_url, $report_url))
                {
                    
                    //echo "</br>profilekey 1".$paysafecard->getProfileKey();
                    
                    /*
                            here you can save the payments in your database. Ex: With the unique transaction id, you can request the transaction id by using $paysafecard->getTransactionId();.
                            After this the customer gets redirected to the chosen bank.
                    */
                            
                    $txn_id = $paysafecard->getTransactionId();
                    
                    echo "</br>TXN-ID:". $txn_id;
                    
                    $pending_id = $this->donate_functions->add_pending($txn_id, $forum_email, $steam_id, $username, $password, $plan_id, $forum_userid, $donation_cost);
                    
                    echo "</br>Pending-ID:". $pending_id;
                    redirect($paysafecard->getPaymentURL() , 'refresh');
                    //header("Location: " . $paysafecard->getPaymentURL());
                    exit;	
                }
                else 
                {
                    //echo "</br>profilekey 2: ".$paysafecard->getProfileKey();
                    /* Er is iets mis gegaan bij het aanmaken bij de betaling. U kunt meer informatie 
                    vinden over waarom het mis is gegaan door $paysafecard->getErrorMessage() en/of 
                    $paysafecard->getErrorCode() te gebruiken. */

                    /*
                    Something went wrong with creating the payment. U can find more info by using $paysafecard->getErrorMessage(); or $paysafecard->getErrorCode();
                    */

                    echo '<p>De betaling kon niet aangemaakt worden.</p>'; //The payment could not been made
                    echo '<p><strong>Foutmelding:</strong> ', $paysafecard->getErrorMessage(), '</p>'; //Error
                    exit;
                }
                
            }
            
            
            
            
        }else{
            
            echo "</br>else provider: $provider";
            
            
            if($this->config->item("payment_mollie_use_ideal")=== "1"){ 
                echo "</br>Provider: iDeal";

                //iDeal with Mollie.nl
                require_once './application/libraries/ideal.mollie.php';
                $partner_id = $this->config->item('payment_mollie_partnerid');
                $iDEAL = new Mollie_iDEAL_Payment ($partner_id);
                if($this->config->item('payment_mollie_ideal_testmode') === '1')$iDEAL->setTestMode();
                $bank_array = $iDEAL->getBanks();
                echo "</br>";
                //print_r($bank_array);
                $ideal_banks = array();
                
                foreach ($bank_array as $bank_id => $bank_name){
                    $ideal_banks[$bank_id] = $bank_id;
                }
                
                print_r($ideal_banks);
                
                if(in_array($provider , $ideal_banks)){
                    echo "</br>is bank";
                    
                    $this->load->model('donate_functions');

                    $partner_id = trim($this->config->item('payment_mollie_partnerid'));
                    $amount = $donation_cost*100;
                    $description = $row_plan['plan_description']; // Beschrijving die consument op zijn/haar afschrift ziet.
                    $return_url = site_url('process/return_ideal');
                    $report_url = site_url('process/ipn_mol_ideal');
                    
                    if (!in_array('ssl', stream_get_transports()))
                    {
                        echo "<h1>Foutmelding</h1>";
                        echo "<p>Uw PHP installatie heeft geen SSL ondersteuning. SSL is nodig voor de communicatie met de Mollie iDEAL API.</p>";
                        exit;	
                    }

                    
                    $iDEAL = new Mollie_iDEAL_Payment ($partner_id);
                    


                    if (isset($provider) and !empty($provider)) 
                    {
                        if ($iDEAL->createPayment($provider, $amount, $description, $return_url, $report_url)) 
                        {
                            /* Hier kunt u de aangemaakte betaling opslaan in uw database, bijv. met het unieke transactie_id
                               Het transactie_id kunt u aanvragen door $iDEAL->getTransactionId() te gebruiken. Hierna wordt 
                               de consument automatisch doorgestuurd naar de gekozen bank. */


                            $txn_id = $iDEAL->getTransactionId();

                            echo "</br>TXN-ID:". $txn_id;

                            $pending_id = $this->donate_functions->add_pending($txn_id, $forum_email, $steam_id, $username, $password, $plan_id, $forum_userid, $donation_cost);

                            echo "</br>Pending-ID:". $pending_id;
                            redirect($iDEAL->getBankURL() , 'refresh');
                            //header("Location: " . $iDEAL->getBankURL());
                            exit;	
                        }
                        else 
                        {
                            $partnerid = $iDEAL->getPartnerId();
                            echo "</br>partnerid: $partnerid";
                            /* Er is iets mis gegaan bij het aanmaken bij de betaling. U kunt meer informatie 
                               vinden over waarom het mis is gegaan door $iDEAL->getErrorMessage() en/of 
                               $iDEAL->getErrorCode() te gebruiken. */

                            echo '<p>De betaling kon niet aangemaakt worden.</p>';

                            echo '<p><strong>Foutmelding:</strong> ', htmlspecialchars($iDEAL->getErrorMessage()), '</p>';
                            exit;
                        }
                    }


                    /*
                      Hier worden de mogelijke banken opgehaald en getoont aan de consument.
                    */

                    //$bank_array = $iDEAL->getBanks();

                    if ($bank_array == false)
                    {
                            echo '<p>Er is een fout opgetreden bij het ophalen van de banklijst: ', $iDEAL->getErrorMessage(), '</p>';
                            exit;
                    }




                }    
            }
        }
    }
    
    
    function return_ideal(){
        require_once './application/libraries/ideal.mollie.php';
        
        $partner_id  = trim($this->config->item('payment_mollie_partnerid')); // your mollie partner ID
        
        if(isset($_GET['transaction_id'])){
            $DB_Main = $this->load->database('default', TRUE);
            
            $DB_Main->where('txn_id', $_GET['transaction_id']);
            $query_txn = $DB_Main->get('pending-orders');
            $num_rows = $query_txn->num_rows;
            
            if($num_rows === 0){
                redirect('process/success');
            }elseif($num_rows != 0){
                redirect('process/error');
            }
        }
    }
    
    
    function success(){ //Success of the Payment
        $data['forum_login'] = $this->session->userdata('forum_login');
        $data['currency'] = $this->config->item('community_currency');
        $data['community_name'] = $this->config->item('community_name');
        $data['community_logo'] = $this->config->item('community_logo');
        

        $this->load->view('parts/header_forum',$data);
        $this->load->view('status/success',$data);
        $this->load->view('parts/footer',$data);
    }
  
    function cancel(){ //Canceled the Payment
        $data['forum_login'] = $this->session->userdata('forum_login');
        $data['currency'] = $this->config->item('community_currency');
        $data['community_name'] = $this->config->item('community_name');
        $data['community_logo'] = $this->config->item('community_logo');
        

        $this->load->view('parts/header_forum',$data);
        $this->load->view('status/cancel',$data);
        $this->load->view('parts/footer',$data);
    }
    
    function error(){ //Error
        $data['forum_login'] = $this->session->userdata('forum_login');
        $data['currency'] = $this->config->item('community_currency');
        $data['community_name'] = $this->config->item('community_name');
        $data['community_logo'] = $this->config->item('community_logo');
        
        $this->load->view('parts/header_forum',$data);
        $this->load->view('status/error',$data);
        $this->load->view('parts/footer',$data);
    }
    
    
    function ipn_pg(){
        
        // check that the request comes from PayGol server
        if(!in_array($_SERVER['REMOTE_ADDR'],
            array('109.70.3.48', '109.70.3.146', '109.70.3.58'))) {
            header("HTTP/1.0 403 Forbidden");
            die("Error: Unknown IP");
        }

        // mail($inform_email, 'Valid PayGol IPN recieved' , 'You have recieved a Valid Paygol IPN'); // FIX-IT: Replace with Logging

        //Fraund Check
        $error = ""; //Initialize var for error msg

        // get the variables from PayGol system
        $message_id	= $_GET['message_id'];
        $service_id	= $_GET['service_id'];
        $shortcode	= $_GET['shortcode'];
        $keyword	= $_GET['keyword'];
        $message	= $_GET['message'];
        $sender         = $_GET['sender'];
        $operator	= $_GET['operator'];
        $country	= $_GET['country'];
        $points         = $_GET['points'];
        $price          = $_GET['price'];
        $currency	= $_GET['currency'];
        $userdata = explode (",",$_GET["custom"]);

        //FIX-IT: Enable Fraud Checks Again
        
        //        if($service_id != $pg_serviceid){
        //            $error .= 'Invalid Service ID \n';
        //        }

        //        if($price != $pg_price){
        //            $error .= 'Invalid Price \n';
        //        }
        //        
        //        if($currency != $pg_currency){
        //            $error .= 'Invalid Currency \n';
        //        }

        /* Should be fixed
        
        //FIX-IT: Update to the new DB-System 
        //$mysql_con_donation = mysql_connect($mysql_don_host, $mysql_don_user, $mysql_don_password) ;
        //mysql_select_db($mysql_don_database, $mysql_con_donation) ;

        //$txn_id = mysql_real_escape_string($_POST['txn_id']);
        //$sql = "SELECT COUNT(*) FROM orders WHERE txn_id = '$message_id'";
        //$r = mysql_query($sql, $mysql_con_donation);
        */
        
        $DB_Main = $this->load->database('default',TRUE);
        $r = $DB_Main->query("SELECT COUNT(*) FROM orders WHERE txn_id = '$message_id'"); //FIX-IT Update to new DB-System
        
         

        $exists = mysql_result($r, 0);
        mysql_free_result($r);

        if (!$r) {
            error_log(mysql_error());
            // mail($inform_email, 'MySQL_Error', "MySQL_Error"); //FIX-IT Replace E-Mail with Logging //FIXED
            log_message("error", "MySQL_Error");
            exit(0);
        }

        if ($exists) {
        $error .= "'txn_id' has already been processed: ".$_POST['message_id']."\n";
        log_message("info" , "message_id has already been Processed");
        //mail($inform_email, 'Payment already Processed', "Payment already Processed"); //FIX-IT: Replace E-Mail with Logging //FIXED
        }        


        // Fraud checks passed


        if($error === ""){

            //mail($inform_email, 'Fraud Check Passed', "Fraund check passed"); //FIX-IT: Replace E-Mail with Logging //FIXED
            log_message("info", "IPN_PG Fraud Check Passed");
            // Postprocess Donation


            // add this order to a table of completed orders

//            $txn_id = mysql_real_escape_string($message_id); //Transaktion id //Not Working
//            $payer_email = mysql_real_escape_string($userdata[3]); //E-Mail
//            $mc_gross = mysql_real_escape_string($price);
//            $steam_id = mysql_real_escape_string($userdata[0]);
//            $username = mysql_real_escape_string($userdata[1]);
//            $password = mysql_real_escape_string($userdata[2]);
//            $plan_id = mysql_real_escape_string($userdata[3]);
//            $forum_userid = mysql_real_escape_string($userdata[4]);
//            $timestamp = time();
//            $date = date("Y-m-d",$timestamp);
//            $provider = "paygol";


            $payment_data = array(
                "txn_id" => $txn_id,
                "payer_email" => $payer_email,
                "mc_gross" => $mc_gross,
                "steam_id" => $steam_id,
                "username" => $username,
                "password" => $password,
                "date" => $date,
                "provider" => $provider,
                "plan_id" => $plan_id,
                "forum_userid" => $forum_userid
                );

            global $payment_data;



            // donation_postprocess($payment_data); //FIX-IT: Use Models instead of include





        }else{
            // mail($inform_email, 'PayGol Fraud Warning' , $error); //FIX-IT: Replace E-Mail with Logging
            log_message("error", "PG IPN FRAUD WARNING");

        }        
        
    }
    
    function ipn_pp(){ //recieved the paypal IPN
        
        log_message('info', 'PP-IPN: Recieved');
        
        //$this->load->library('ipn_listener');
        $this->load->library('encrypt');
        
        $custom = $this->input->post('custom');
        log_message('info', 'PP-IPN: Custom Encrypted:'.$custom);
        //$custom = $this->encrypt->decode($custom);
        //log_message('info', 'PP-IPN: Custom:'.$custom);
        //$userdata = $steam_id .",".$username.",".$password.",".$plan_id.",".$forum_userid;
        $custom = explode(",", $custom);
        $steam_id = $custom[0];
        $username = $custom[1];
        $password = $custom[2];
        $plan_id = $custom[3];
        $forum_userid = $custom[4];
        
        $old = False;
        
        if ($old === true){
/* OLD SYSTEM */
        
//        $listener = new Ipn_listener();
//
//        // tell the IPN listener to use the PayPal test sandbox if enabled in the config
//        if($this->config->item('payment_pp_use_sandbox') === 0){
//            $listener->use_sandbox = FALSE;
//            log_message('info', 'use sandbox FALSE');
//        }else{
//            $listener->use_sandbox = TRUE;
//            log_message('info', 'use sandbox TRUE');
//        }
//        
//        log_message('info', 'line 381');
//
//        // try to process the IPN POST
//        try {
//            $listener->requirePostMethod();
//            $verified = $listener->processIpn();
//            mail("arrow768@sourcedonaates.com", "IPN", $listener->getTextReport());
//            log_message('info', $listener->getTextReport());
//        } catch (Exception $e) { //process failed
//            log_message('info', 'Error on line 385:'.$e->getMessage());
//            log_message('info', $listener->getTextReport());
//            mail("arrow768@sourcedonaates.com", "IPN", $listener->getTextReport());
//            exit(0); //exit application
//        }
//        
//        log_message('info', 'line 400');        
//        
//        // Send Status Mail
//
//        //        if ($verified) {
//        //        // TODO: Implement additional fraud checks and MySQL storage
//        //        mail($inform_email, 'Valid IPN', $listener->getTextReport());
//        //        } else {
//        //        // manually investigate the invalid IPN
//        //        mail($inform_email , 'Invalid IPN', $listener->getTextReport());
//        //        }
//
//
//        // Check if Payment is Valid (Amount, Reciever, Status, Currency)

//        if ($verified) {
//            
//            log_message("info", "Verify check passed");
//            
//            //Get the PLAN-ID:
//            $plan_id = mysql_real_escape_string($custom[3]);
//            
//            log_message('info', 'Plan_ID:'.$plan_id);
//            
//            //get the plan price from the db
//            $DB_Main = $this->load->database('default',TRUE);
//            $DB_Main->where("plan_id", $plan_id);
//            $query_plans = $DB_Main->get("plans");
//            $row_plans = $query_plans->row_array();
//            
//            $pp_amount = $row_plans["plan_price"];
//                        
//
//            $errmsg = '';   // stores errors from fraud checks
//
//            // 1. Make sure the payment status is "Completed" 
//            if ($_POST['payment_status'] != 'Completed') { 
//                // simply ignore any IPN that is not completed
//                mail($inform_email, 'Valid IPN', "Payment not complete");
//                exit(0);
//            }
//
//            // 2. Make sure seller email matches your primary account email.
//            if ($_POST['receiver_email'] != $this->config->item('payment_pp_email')) {
//                $errmsg .= "'receiver_email' does not match: ";
//                $errmsg .= $_POST['receiver_email']."\n";
//            }
//
//            // 3. Make sure the amount(s) paid match
//            if ($_POST['mc_gross'] != $pp_amount) { 
//                $errmsg .= "'mc_gross' does not match: ";
//                $errmsg .= $_POST['mc_gross']."\n";
//            }
//
//            // 4. Make sure the currency code matches
//            if ($_POST['mc_currency'] != $this->config->item('payment_pp_currency')) {
//                $errmsg .= "'mc_currency' does not match: ";
//                $errmsg .= $_POST['mc_currency']."\n";
//            }
//            
//            // FIX-IT: Update Database to new System
//            // 5. Ensure the transaction is not a duplicate. 
//            // $mysql_con_donation = mysql_connect($mysql_don_host, $mysql_don_user, $mysql_don_password) ;
//            // mysql_select_db($mysql_don_database, $mysql_con_donation) ;
//
//            // $txn_id = mysql_real_escape_string($_POST['txn_id']);
//            // $sql = "SELECT COUNT(*) FROM orders WHERE txn_id = '$txn_id'";
//            // $r = mysql_query($sql, $mysql_con_donation);
//            
//            
//            $r = $DB_Main->query("SELECT COUNT(*) FROM orders WHERE txn_id = '$txn_id'");
//
//            if (!$r) {
//                //error_log(mysql_error());
//                log_message("error", "MySQL_Error at PP IPN on line 468 in process.php");
//                exit(0);
//
//            }
//
//
//            $exists = mysql_result($r, 0);
//            mysql_free_result($r);
//
//            if ($exists) {
//                $errmsg .= "'txn_id' has already been processed: ".$_POST['txn_id']."\n";
//                log_message("info", "Payment already Processed");
//            }
//
//            if (!empty($errmsg)) {
//
//                // manually investigate errors from the fraud checking
//                $body = "IPN failed fraud checks: \n$errmsg\n\n";
//                $body .= $listener->getTextReport();
//                mail($this->config->item('community_email'), "IPN Fraud warning", $body, "SD");
//
//            }else {
//
//                mail($this->config->item('community_email'), "IPN Fraud passed", "IPN passed Fraud Check", "SD");
//                log_message("info", "Fraud check passed");
//
//                // Postprocess Donation
//                // $userdata = $steam_id .",".$username.",".$password.",".$plan_id.",".$forum_userid;
//                $txn_id = mysql_real_escape_string($_POST["txn_id"]);
//                $payer_email = mysql_real_escape_string($_POST['payer_email']);
//                $mc_gross = mysql_real_escape_string($_POST['mc_gross']);
//                $steam_id = mysql_real_escape_string($custom['0']);
//                $username = mysql_real_escape_string($custom['1']);
//                $password = mysql_real_escape_string($custom['2']);
//                $plan_id = mysql_real_escape_string($custom['3']);
//                $forum_userid = mysql_real_escape_string($custom['4']);
//                $timestamp = time();
//                $date = date("Y-m-d",$timestamp); //FIX-IT Date format
//                $provider = "paypal";
//                
//                
//                // add this order to a table of completed orders
//                
//                $payment_data = array(
//                    "txn_id" => $txn_id ,
//                    "payer_email" => $payer_email ,
//                    "mc_gross" => $mc_gross,
//                    "steam_id" => $steam_id,
//                    "username" => $username,
//                    "password" => $password,
//                    "date" => $date,
//                    "provider" => $provider,
//                    "plan_id" => $plan_id,
//                    "forum_userid" => $forum_userid
//                    );
//                
//                global $payment_data;
//
//                $this->Integration_features->donation_postprocess($payment_data);
//
//            }
//
//        } else {
//            // manually investigate the invalid IPN
//            mail($inform_email, 'Invalid IPN', $listener->getTextReport());
//        }
//
//        
            
        } //added the old into a if to be able to hide it
        
        /* NEW SYSTEM */
        
        log_message('info', 'Reached new IPN System');
        
        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

        // post back to PayPal system to validate

        $header = "POST /cgi-bin/webscr HTTP/1.1\r\n";

        
        if($this->config->item("payment_pp_use_sandbox") === "1"){
            $header .= "Host: www.sandbox.paypal.com:443\r\n";
            log_message('info', 'PP-IPN: PP Sandbox Header');
        }elseif($this->config->item("payment_pp_use_sandbox") === "0"){
            $header .= "Host: ipnpb.paypal.com:443\r\n";
            log_message('info', 'PP-IPN: PP Production Header');
        }
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

        
        if($this->config->item("payment_pp_use_sandbox") === "1"){
            $fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30); 
            log_message('info', 'PP-IPN: PP Sandbox URL');
        }elseif($this->config->item("payment_pp_use_sandbox") === "0"){
            $fp = fsockopen ('ssl://ipnpb.paypal.com', 443, $errno, $errstr, 30);
            log_message('info', 'PP-IPN: PP Production URL');
        }

        // assign posted variables to local variables
        $item_name = $_POST['item_name'];
        $item_number = $_POST['item_number'];
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];
        

        if (!$fp) {
            log_message('error', "IPN ver. HTTP Error");
            // HTTP ERROR
        } else {
            $done = false;
            fputs ($fp, $header . $req);
            
            while (!feof($fp)) {
                $res = fgets ($fp, 1024);
                log_message('info', 'Line 609'. $res);
                if (strpos($res,"VERIFIED")!==false) { //old: strcmp ($res, "VERIFIED") === 0
                    
                    log_message('info', 'PP-IPN: VALID IPN');
                    
                    //Fraud Checks
                    $error = "";
                    $error_count = 0;
                    
                    // check the payment_status is Completed
                    if ($payment_status != "Completed"){
                        log_message('error', 'PP-IPN: Payment Status not completed: '.$payment_status);
                        $error .= "Payment Status not completed: ".$payment_status." \n ";
                        $error_count +=1;
                    }
                    
                    // check that receiver_email is your Primary PayPal email
                    if($receiver_email != $this->config->item('payment_pp_email')){
                        log_message('error', 'PP-IPN: Payment Reciever Email doesnt match: '.$receiver_email);
                        $error .= "Payment Reciever Email doesnt match: ".$receiver_email." \n ";
                        $error_count +=1;
                    }
                    
                    // check that payment_amount/payment_currency are correct
                    $DB_Main = $this->load->database("default", TRUE);
                    $DB_Main-> where("plan_id", $plan_id);
                    $query_plan = $DB_Main->get("plans");
                    $row_plan = $query_plan->row_array();
                    
                    if($payment_amount != $row_plan['plan_price']){
                        log_message('error', 'PP-IPN: Payment Amount doesnt match: '.$payment_amount);
                        $error .= "Payment Amount doesnt match: ".$payment_amount." \n ";
                        $error_count +=1;
                    }
                    
                    if($payment_currency != $this->config->item('payment_pp_currency')){
                        log_message('error', 'PP-IPN: Payment Currency doesnt match: '.$payment_currency);
                        $error .= "Payment currency doesnt match: ".$payment_currency." \n ";
                        $error_count +=1;
                    }
                    
                    // check that txn_id has not been previously processed
                    $DB_Main = $this->load->database("default", TRUE);
                    $DB_Main-> where("txn_id", $txn_id);
                    $query_orders = $DB_Main->get("orders");
                    $num_orders = $query_orders->num_rows();
                    if($num_orders != 0 ){
                        log_message('error', 'PP-IPN: Payment Order Count is greater than 0, txn_id: '.$txn_id);
                        $error .= "Payment Order Count is greater than 0, txn_id: ".$txn_id." \n ";
                        $error_count +=1;
                        
                    }
                    
                    //Final Error Check:
                    if($error_count != 0){
                        log_message('error', 'PP-IPN: IPN ERRORS, see the lines above for details');
                        if($this->config->item('site_error_email') === "1"){
                            mail($this->config->item('community_email'),"IPN Errors",$error);
                        }
                        exit();
                        
                    }elseif($error_count === 0){ // process payment
                        log_message('debug', 'PP-IPN: Fraud Checks passed, processing');
                        
                        $timestamp = time();
                        $date = date("Y-m-d",$timestamp); //FIX-IT Date format
                        $provider = "paypal";
                        
                        $payment_data = array(
                            "txn_id" => $txn_id ,
                            "payer_email" => $payer_email ,
                            "mc_gross" => $payment_amount,
                            "steam_id" => $steam_id,
                            "username" => $username,
                            "password" => $password,
                            "date" => $date,
                            "provider" => $provider,
                            "plan_id" => $plan_id,
                            "forum_userid" => $forum_userid
                        );
                        
                        $this->Integration_features->donation_postprocess($payment_data);
                        echo "done";
                        break;

                    }
                    

                }else if (strcmp ($res, "INVALID") == 0) {
                    // log for manual investigation
                    log_message('error', 'PP-IPN: INVALID IPN POST');
                    
                    if($this->config->item('site_error_email') === "1"){
                        mail( $this->config->item('community_email'),"INVALID IPN POST" ,"INVALID IPN POST. The raw POST string is below.\n\n".$req);
                    }
                }
            }
            fclose ($fp);
        }
        
    }
    
    function ipn_mollie_psc(){
                
        require_once './application/libraries/paysafecard.mollie.php';
        log_message('debug', 'PS-IPN: IPN Recieved');
        
        $partner_id  = $this->config->item('payment_mollie_partnerid'); // your mollie partner ID
        
        $partner_id = trim($partner_id);

        if (isset($_GET['transaction_id'])) 
        {  
            echo "1";
            log_message('debug', 'PS-IPN: Got Transaction ID');
            
            $paysafecard = new PaySafeCard_Payment($partner_id);

            $paysafecard->checkPayment($_GET['transaction_id']);

            if ($paysafecard->getPaidStatus() == true) 
            {
                log_message('debug', 'PS-IPN: TXN is paid');
                echo "2";
                
                
                $txn_id = $_GET['transaction_id'];
                
                echo "</br>PS-IPN: TXN:" .$txn_id;
                log_message('debug', 'PS-IPN: TXN: '.$txn_id);
                
                $DB_Main = $this->load->database('default', TRUE);
                
                $DB_Main->where('txn_id', $txn_id);
                
                $query_pending = $DB_Main->get('pending-orders');
                
                $row_pending = $query_pending->row_array();
                
                
                log_message('debug', 'PS-IPN: Fraud Checks passed, processing');

                $timestamp = time();
                $date = date("Y-m-d",$timestamp);
                $provider = "paysafe";


                $payment_data = array(
                    "txn_id" => $txn_id ,
                    "payer_email" => $row_pending['user_email'] ,
                    "mc_gross" => $row_pending['amount'],
                    "steam_id" => $row_pending['steam_id'],
                    "username" => $row_pending['username'],
                    "password" => $row_pending['password'],
                    "date" => $date,
                    "provider" => $provider,
                    "plan_id" => $row_pending['plan_id'],
                    "forum_userid" => $row_pending['forum_userid'],
                );
                
                print_r($payment_data);

                $this->Integration_features->donation_postprocess($payment_data);
                
                $DB_Main->where('txn_id', $txn_id);
                
                $DB_Main->delete('pending-orders');
                
                log_message('debug', 'PS-IPN: done');

            }
        }
    }
    
    function ipn_mol_ideal(){
                
        require_once './application/libraries/ideal.mollie.php';
        log_message('debug', 'iDeal-IPN: IPN Recieved');
        
        $partner_id  = $this->config->item('payment_mollie_partnerid'); // your mollie partner ID
        
        $partner_id = trim($partner_id);

        if (isset($_GET['transaction_id'])) 
        {  
            echo "1";
            log_message('debug', 'iDeal-IPN: Got Transaction ID');
            
            $iDEAL = new Mollie_iDEAL_Payment ($partner_id);
            if( $this->config->item("payment_mollie_ideal_testmode") === "1") $iDEAL->setTestMode();
            
            
            $iDEAL->checkPayment($_GET['transaction_id']);

            if ($iDEAL->getPaidStatus() == true) 
            {
                echo "2";
                
                
                $txn_id = $_GET['transaction_id'];
                
                echo "</br>PS-IPN: TXN:" .$txn_id;
                log_message('debug', 'PS-IPN: TXN: '.$txn_id);
                
                $DB_Main = $this->load->database('default', TRUE);
                
                $DB_Main->where('txn_id', $txn_id);
                
                $query_pending = $DB_Main->get('pending-orders');
                
                $row_pending = $query_pending->row_array();
                
                
                log_message('debug', 'PS-IPN: Fraud Checks passed, processing');

                $timestamp = time();
                $date = date("Y-m-d",$timestamp);
                $provider = "ideal";


                $payment_data = array(
                    "txn_id" => $txn_id ,
                    "payer_email" => $row_pending['user_email'] ,
                    "mc_gross" => $row_pending['amount'],
                    "steam_id" => $row_pending['steam_id'],
                    "username" => $row_pending['username'],
                    "password" => $row_pending['password'],
                    "date" => $date,
                    "provider" => $provider,
                    "plan_id" => $row_pending['plan_id'],
                    "forum_userid" => $row_pending['forum_userid'],
                );
                
                print_r($payment_data);

                $this->Integration_features->donation_postprocess($payment_data);
                
                $DB_Main->where('txn_id', $txn_id);
                
                $DB_Main->delete('pending-orders');
                
                log_message('debug', 'PS-IPN: done');

            }

        }
    }
  
    
    
    function cron(){
        
        //Loading-Area
        $DB_Main = $this->load->database('default', TRUE);
        $date = time();
        
        
        $query_users = $DB_Main->get('users');
        
        foreach($query_users->result() as $row){
            //get vars
            $user_id = $row->user_id;
            $user_email = $row->email;
            $user_steamid = $row->steam_id;
            $plan_id = $row->plan_id;
            $status = $row->status;
            $time_now = time();
            $time_exp = strtotime($row->plan_exp_date);
            
            //echo status
            echo "</br>Start-Cron at User-ID:". $user_id ;
            
            if ($status === "1"){
                //Add Features
                echo "Add Donation-Features to User </br>";
                $this->Integration_features->donation_add($user_id);
                
                //Update Status
                $data= array(
                    'status' => '2'
                );
                $DB_Main->where('user_id',$user_id);
                $DB_Main->update('users',$data);
                
            }
            
            if( $time_exp < $time_now){
                if($status === "2"){
                    echo "Remove Donation-Features from User </br>";
                    echo "time_exp:".$time_exp."</br>";
                    echo "time_now:".$time_now."</br>";
                    $this->Integration_features->donation_remove($user_id);

                    //Update Status
                    $data= array('status' => '3');
                    $DB_Main->where('user_id',$user_id);
                    $DB_Main->update('users',$data);
                }
            }
            echo "</br>User done";
        }
    }
    
    function cron_light(){
        
        //Loading-Area
        $DB_Main = $this->load->database('default', TRUE);
        $date = time();
        
        
        $query_users = $DB_Main->get('users');
        
        foreach($query_users->result() as $row){
            //get vars
            $user_id = $row->user_id;
            $status = $row->status;
            
            //echo status
            echo "</br>Start-Cron-Light at User-ID:". $user_id ;
            
            if ($status === "1"){
                //Add Features
                echo "Add Donation-Features to User </br>";
                $this->Integration_features->donation_add($user_id);
                
                //Update Status
                $data= array(
                    'status' => '2'
                );
                $DB_Main->where('user_id',$user_id);
                $DB_Main->update('users',$data);
                
            }
        }
    }
    
}
?>
