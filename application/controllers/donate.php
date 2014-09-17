<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * SDv1 Donate Controller
 * 
 * Provides the User Interface
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

class Donate extends CI_Controller{
    
    function __construct() {
        parent::__construct();

        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);        
        switch ($lang){
            case "en":
                $this->lang->load('donate', 'english');
                break;
            case "de":
                $this->lang->load('donate', 'english');
                break;
            case "nl":
                $this->lang->load('donate', 'dutch');
                break;     
            default:
                $this->lang->load('donate', $this->config->item("site_language"));
                break;
        }
        
    }
    
    function index(){ //Step 1: Overview about the aviable Plans    

        $DB_Main = $this->load->database('default', TRUE);

        //get the User-Data from the Forum
        if($this->config->item('integration_forum_enabled') === "1"){
        }


        $query_plans = $DB_Main->get('plans');

        $data['plans'] = $query_plans->result();

        $data['forum_login'] = $this->session->userdata('forum_login');
        $data['currency'] = $this->config->item('community_currency');
        $data['community_name'] = $this->config->item('community_name');
        $data['community_logo'] = $this->config->item('community_logo');



        $this->load->view('parts/header_forum_first',$data);
        $this->load->view('donate/plans',$data);
        $this->load->view('parts/footer',$data);

    }
    
    function details($slug){ //Step 2: Details about the Plans 
        
        if(!isset($slug)) redirect('/donate' , 'refresh');
        
        //get the selected Plan from the Plan-Table in the Main-DB
        
        $DB_Main = $this->load->database('default',TRUE);
        
        $DB_Main->where("plan_id", $slug);
        $query_plan = $DB_Main->get('plans');
        
        $query_categroy = $DB_Main->get('categories');
        
        $query_items = $DB_Main->get('items');
        
        if($query_plan->num_rows === 1){ //Correct plan found
            
            $data['plan'] = $query_plan->row();
            $data['category'] = $query_categroy->result();
            $data['items'] = $query_items->result();
            
            $data['forum_login'] = $this->session->userdata('forum_login');
            $data['forum_url'] = $this->config->item('integration_forum_url');
            $data['currency'] = $this->config->item('community_currency');
            $data['community_name'] = $this->config->item('community_name');
            $data['community_logo'] = $this->config->item('community_logo');
            
            $this->session->set_userdata('plan_id', $slug);
            
            
            $this->load->view('parts/header_forum',$data);
            $this->load->view('donate/package',$data);
            $this->load->view('parts/footer',$data);
            
        }else{ //NO plan found (not the correct plan
            $this->index();
        }
        
    }
       
    function selection($slug){
        $ip = $_SERVER["REMOTE_ADDR"];
        
        if(!isset($slug)) redirect("/donate", "refresh");
        $data["slug"] = $slug;
        
        //get the forum data
        if($this->config->item("integration_forum_enabled") === "1"){
            switch($this->config->item("integration_forum_system")){
                case "ipb":
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

                    $DB_Forum->where("ip_address",$ip);
                    $query_forum_users = $DB_Forum->get($this->config->item("integration_forum_usertable"));
                    $data["forum_enabled"] = "1";
                    $data["forum_users"] = $query_forum_users->result();
                    break;
            }
        }
        
        //get the steamids
		/*
        $config_server['hostname'] = $this->config->item('database_server_host');
        $config_server['username'] = $this->config->item('database_server_user');
        $config_server['password'] = $this->config->item('database_server_password');
        $config_server['database'] = $this->config->item('database_server_db');
        $config_server['dbdriver'] = "mysql";
        $config_server['dbprefix'] = $this->config->item('database_server_prefix');
        $config_server['pconnect'] = FALSE;
        $config_server['db_debug'] = TRUE;
        $config_server['cache_on'] = FALSE;
        $config_server['cachedir'] = "";
        $config_server['char_set'] = "utf8";
        $config_server['dbcollat'] = "utf8_general_ci";

        $DB_Server = $this->load->database($config_server,TRUE);
        $plugin_ip = ip2long($ip);
        $DB_Server->where('ip', $plugin_ip);
        $query_ip = $DB_Server->get('ci_donate_autofill');
        
        $data["steamids"] = $query_ip->result();
		*/
		$data['steamids'] = '';
        
        $data['forum_login'] = $this->session->userdata('forum_login');
        $data['forum_url'] = $this->config->item('integration_forum_url');
        $data['currency'] = $this->config->item('community_currency');
        $data['community_name'] = $this->config->item('community_name');
        $data['community_logo'] = $this->config->item('community_logo');

        $this->load->view('parts/header_forum',$data);
        $this->load->view('donate/selection',$data);
        $this->load->view('parts/footer',$data);
    }
    
    function payment($slug){
        
        if(!isset($slug)) redirect('/donate' , 'refresh');
        
        $this->load->model('Donate_functions');
        
        $DB_Main = $this->load->database('default',TRUE);
        
        $DB_Main->where("plan_id", $slug);
        $query_plan = $DB_Main->get('plans');
        $row_plan = $query_plan->row_array();
        
        if($query_plan->num_rows === 1){
            
            $ip = $_SERVER["REMOTE_ADDR"];
            
			/*
            if($this->config->item("integration_steamid_autofill")=== "1"){
                //get the ip to query the servers db for the steamid and the forum for the user/ip
                $steamid = $this->Donate_functions->get_steam_from_ip($ip);
                
                //if($steamid === TRUE) redirect("donate/selection/".$slug, "refresh");
                
            }else{
                $steamid = "";
            }
			*/
			$steamid = "";
            
            //check if comm is using hats
            if($this->config->item("community_hats_use") === "1"){
                $hats_id = $this->config->item("community_hats_id");
                $DB_Main->where("category_id", $hats_id);
                $query_hats = $DB_Main->get('items');
                $num_hats = 0;
                foreach ($query_hats->result() as $hat){
                    $cat_array = explode(",",$hat->plan_id);
                    if(in_array($slug, $cat_array)) $num_hats += 1;
                }
                
                
                if($num_hats != 1){
                    $data['hats_name'] = "hats";
                }else{
                    $data['hats_name'] = "hat";
                }
                
                $data['hats_use'] = $this->config->item("community_hats_use");
                $data['hats_num'] = $num_hats;
            }
            //check if comm is using skins
            if($this->config->item("community_skins_use") === "1"){
                $skins_id = $this->config->item("community_skins_id");
                $DB_Main->where("category_id", $skins_id);
                $query_skins = $DB_Main->get('items');
                $num_skins = 0;
                foreach ($query_skins->result() as $skin){
                    $cat_array = explode(",",$skin->plan_id);
                    if(in_array($slug, $cat_array)) $num_skins += 1;
                }
                
                
                
                if($num_skins != 1){
                    $data['skins_name'] = "skins";
                }else{
                    $data['skins_name'] = "skin";
                }
                
                $data['skins_use'] = $this->config->item("community_skins_use");
                $data['skins_num'] = $num_skins;
            }
            
            //check if iDeal is used
            if($this->config->item("payment_mollie_use_ideal")==="1"){
                require_once './application/libraries/ideal.mollie.php';
                $partner_id = $this->config->item('payment_mollie_partnerid');
                $iDEAL = new Mollie_iDEAL_Payment ($partner_id);
                if($this->config->item('payment_mollie_ideal_testmode') === '1')$iDEAL->setTestMode();
                $bank_array = $iDEAL->getBanks();
                $data['use_ideal'] = 1;
                $data['banks'] = $bank_array;
            }
            
            //get the username and the email from the forum
			/*
            if($this->config->item("integration_forum_enabled") === "1"){
                switch($this->config->item("integration_forum_system")){
                    case "ipb":
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
                        
                        $DB_Forum->where("ip_address",$ip);
                        $query_forum_users = $DB_Forum->get($this->config->item("integration_forum_usertable"));
                        $row_forum_users = $query_forum_users->row();
                        $num = $query_forum_users->num_rows();
                        
                        if($num === 1){
                            $data["got_user_data"] = "1";
                            $data["forum_username"] = $row_forum_users->name;
                            $data["forum_email"] = $row_forum_users->email;
                        }elseif($num > 1){
                            //redirect("/donate/selection/".$slug, "refresh");
                            $data["got_user_data"] = "0";
                        }else{
                            $data["got_user_data"] = "0";
                        }
                        
                        
                        break;
                }
            }else{
                $data["got_user_data"] = "0";
            }
			*/
			$data["got_user_data"] = "0";
			$data["forum_username"] = "";
			$data["forum_email"] = "";
			
            
            $data['plan'] = $query_plan->row();
            
            $data['steamid'] = $steamid;
            $data['forum_login'] = $this->session->userdata('forum_login');
            $data['forum_url'] = $this->config->item('integration_forum_url');
            $data['currency'] = $this->config->item('community_currency');
            $data['community_name'] = $this->config->item('community_name');
            $data['community_url'] = $this->config->item('community_url');
            $data['community_email'] = $this->config->item('community_email');
            $data['community_logo'] = $this->config->item('community_logo');
            $data['community_currency_long'] = $this->config->item('community_currency_long');
            $data['site_plan_suffix'] = $this->config->item('site_plan_suffix');
            $data['use_paypal'] = $this->config->item('payment_pp_enable');
            $data['use_paygol'] = $this->config->item('payment_pg_enable');
            $data['use_paysafe'] = $this->config->item('payment_mollie_use_paysafe');
            $data['slug'] = $slug;
            
            
            $this->load->view('parts/header_forum',$data);
            $this->load->view('donate/payment',$data);
            $this->load->view('parts/footer',$data);
            
            
        }else{
            $this->index();
        }

    }
    
    
    public function get_img(){
        
        $error_image = base_url('/img/sourcedonates.png');
        
        $steam_id = $this->input->post("sendValue");

        $friend_id = $this->GetFriendID($steam_id);
        
        if (!isset($friend_id)){
            echo json_encode(array("returnValue"=>''.$error_image.''));
            exit;
        }
        

        $url = "http://steamcommunity.com/profiles/".$friend_id."?xml=1";
        
        $player_url = "http://steamcommunity.com/profiles/".$friend_id."";

        $xml = simplexml_load_string(file_get_contents($url));

        $avatar_url =  $xml->avatarMedium;
        
        echo json_encode(array(
            "returnValue"=>''.$avatar_url.'',
            "player_url"=>''.$player_url.''
            ));

    }
    
    function GetFriendID($pszAuthID){
            
        $iServer = "0";
        $iAuthID = "0";

        $szAuthID = $pszAuthID;

        $szTmp = strtok($szAuthID, ":");

        while(($szTmp = strtok(":")) !== false){
            $szTmp2 = strtok(":");

            if($szTmp2 !== false){
                $iServer = $szTmp;
                $iAuthID = $szTmp2;
            }
        }
        if($iAuthID == "0")
            return "0";

        $i64friendID = bcmul($iAuthID, "2");

        //Friend ID's with even numbers are the 0 auth server.
        //Friend ID's with odd numbers are the 1 auth server.
        $i64friendID = bcadd($i64friendID, bcadd("76561197960265728", $iServer)); 

        return $i64friendID;
    }
}
?>
