<?php
/**
 * SDv1 Integration Features
 * 
 * Provides Integration Features into various systems
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

class Integration_features extends CI_Model{
    
    function __construct() {
        parent::__construct();
        $asc_str_grt = "84;104;105;115;32;68;111;110;97;116;105;111;110;32;105;115;32;112;111;119;101;114;101;100;32;98;121;32;115;111;117;114;99;101;100;111;110;97;116;101;115;46;99;111;109";
    }
    
    function bdi_add($steamid, $level){
        
        $data = array(
            'steamid' => $steamid,
            'level' => $level            
        );
        
        $DB_Main = $this->load->database('default',TRUE); //Load the Default Database
        $DB_Main->insert('donators', $data);
    }
    
    function bdi_remove($steamid){
        $DB_Main = $this->load->database('default',TRUE);
        $DB_Main->where('steamid', $steamid);
        $DB_Main->delete('donators');
    }
    
    
    function sm_mysql_admins_add($username, $steamid, $sm_groupid){
        log_message('info', 'PostPro-mysql-admin: reached');
        log_message('info', 'PostPro-mysql-admin: username:'.$username);
        log_message('info', 'PostPro-mysql-admin: steamid:'.$steamid);
        log_message('info', 'PostPro-mysql-admin: sm_groupid'.$sm_groupid);
        
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
        
        
        //check if already exits
        $DB_Server->where('identity',$steamid);
        $query_ident1 = $DB_Server->get('sm_admins');
        
        //print_r($query_ident1);
        //echo "</br> <br/>";
        
        if($query_ident1->num_rows === 0){
            log_message('info', 'PostPro-mysql-admin: user didn´t exixt -> added');
            //Insert the Userdata
            $user_data = array(
                'authtype' => 'steam',
                'identity' => $steamid,
                'flags' => '',
                'name' => $username,
                'immunity' => ''
            );
            $DB_Server->insert('sm_admins',$user_data);
            
        }elseif($query_ident1->num_rows >= 0){
            log_message('info', 'PostPro-mysql-admin: user already exist -> nothing to do');
        }
        
        
        echo "<br/>";
        //get the ID of the Insterted Userdata / the aviable User
        $DB_Server->where('identity',$steamid);
        $query_ident2 = $DB_Server->get('sm_admins');
        
        $row_ident2 = $query_ident2->row_array();
        $admin_id = $row_ident2['id'];
        
        log_message('info', 'PostPro-mysql-admin: admin_id:'.$admin_id);
        
        //chek if the user is already in sm_admins_groups
        $DB_Server->where('admin_id',$admin_id);
        $query_admins_groups = $DB_Server->get('sm_admins_groups'); 
        
        if($query_admins_groups->num_rows === 0){
            log_message('info', 'PostPro-mysql-admin: admins_groups didnt exits -> addded');
            //instert the admin_id
            $admin_data = array(
                'admin_id'=>$admin_id,
                'group_id'=>$sm_groupid,
                'inherit_order'=>'1'
            );
            
            $DB_Server->insert('sm_admins_groups', $admin_data);
            
        }elseif($query_admins_groups->num_rows >= 0){
            log_message('info', 'PostPro-mysql-admin: admins_groups already exist -> updated');
            
            //update the admin_id
            $admin_data = array(
                'admin_id'=>$admin_id,
                'group_id'=>$sm_groupid,
                'inherit_order'=>'1'
            );
            
            $DB_Server->where('admin_id',$admin_id);
            $DB_Server->update('sm_admins_groups', $admin_data);
            
        }
        
        
    }
    
    function sm_mysql_admins_remove($steamid){
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
        $DB_Server->where('identity', $steamid);
        $query_server = $DB_Server->get('sm_admins');
        $row_server = $query_server->row_array();
        
        $admin_id = $row_server['id'];
        
        echo "</br>removing adminid: $admin_id ";
        
        $DB_Server->where('admin_id',$admin_id);
        $DB_Server->delete('sm_admins_groups');
        
        $DB_Server->where('id',$admin_id);
        $DB_Server->delete('sm_admins');
    }
    
    
    function mybb_add($forum_userid, $forum_user_group, $forum_display_group, $donsys_uid){
        
        $DB_Main = $this->load->database('default',TRUE);
        
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
        
        //get the User_ID
        
        $DB_Forum->where('uid',$forum_userid);
        $query_forum_user = $DB_Forum->get($this->config->item('integration_forum_usertable'));
        $row_forum_user = $query_forum_user->row_array();
        $forum_old_addgroups = $row_forum_user['additionalgroups'];
        $forum_old_dispgroup = $row_forum_user['displaygroup'];
        
        if($query_forum_user->num_rows === 1){ //check if user is aviable
            
            // update the UserID in the Donation-Systems User table
            $data = array(
                'forum_oldusrgroup' => $forum_old_addgroups,
                'forum_olddispgroup' => $forum_old_dispgroup
            );

            $DB_Main->where('user_id', $donsys_uid);
            $DB_Main->update($this->config->item('integration_forum_usertable') , $data);


            // update the additionalgroups and the displaygroup

            $forum_new_addgroups = $forum_user_group . $forum_old_addgroups;

            $data = array(
                'additionalgroups'=> $forum_new_addgroups,
                'displaygroup' => $forum_display_group
            );

            $DB_Forum->where('uid',$forum_userid);
            $DB_Forum->update($this->config->item('integration_forum_usertable') , $data);
        }
    }
    
    function mybb_remove($forum_uid, $old_addgroup, $old_dispgroup){
        
        
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
        
        $data=array(
            'additionalgroups' => $old_forumgroup,
            'displaygroup' => $old_dispgroup
        );
        
        $DB_Forum->where('uid', $forum_uid);
        $DB_Forum->update($this->conif->item('integration_forum_usertable'), $data);
        
    }
    
    
    function ipb_add($forum_userid, $forum_group, $donsys_uid){
        
        $DB_Main = $this->load->database('default',TRUE);
        
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
        
        echo "adding_ipb";
        
        $DB_Forum = $this->load->database($config_forum,TRUE);
        
        //get the User_ID
        
        $DB_Forum->where('member_id',$forum_userid);
        $query_forum_user = $DB_Forum->get($this->config->item('integration_forum_usertable'));
        $row_forum_user = $query_forum_user->row_array();
        //old system
//        $forum_old_usergroup = $row_forum_user['member_group_id'];
//        $forum_old_additional_groups = $row_forum_user['mgroup_others'];
        
        if($query_forum_user->num_rows === 1){ //check if user is aviable
            $old_member_group_id = $row_forum_user["member_group_id"];
            $old_mgroup_others = $row_forum_user["mgroup_others"];
            
            $right_array = explode(",",$old_mgroup_others);
            $exception_array = explode(",",$this->config->item("integration_forum_exception_usergroups"));
            
            $right_array = $this->remove_null_array($right_array);
            
            if(in_array($old_member_group_id,$exception_array)){
                //DON OVERWRITE THE MEMBER GROUP ID
                if(!in_array($forum_group,$right_array)) $right_array[] = $forum_group;
                $new_mgroup_others = implode(",", $right_array);
                $new_mgroup_others = ",".$new_mgroup_others.",";
                $new_member_group_id = $old_member_group_id;
            }else{
                //SET MEMBER GROUP ID TO DONATOR GROUP ID
                if(!in_array($old_member_group_id,$right_array) && $old_member_group_id != $forum_group)$right_array[] = $old_member_group_id;
                $new_mgroup_others = implode(",", $right_array);
                $new_mgroup_others = ",".$new_mgroup_others.",";
                $new_member_group_id = $forum_group;
            }
            
            echo "</br>new_mgroup_others = $new_mgroup_others";
            echo "</br>new_member_group_id = $new_member_group_id";
            
            $data=array(
                "member_group_id" =>$new_member_group_id,
                "mgroup_others" =>$new_mgroup_others
            );
            
            $DB_Forum->where("member_id",$forum_userid);
            $DB_Forum->update($this->config->item("integration_forum_usertable"),$data);
        }
    }
    
    function ipb_remove($donsys_userid ,$forum_userid, $plan_id){ 
        
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
        
        echo "</br>removing ipb";

        $DB_Forum = $this->load->database($config_forum,TRUE);
        $DB_Main = $this->load->database("default",TRUE);
        
        
        $DB_Forum->where("member_id",$forum_userid);
        $query_forum_user = $DB_Forum->get($this->config->item("integration_forum_usertable"));
        
        if($query_forum_user->num_rows === 1){
            $row_forum_user = $query_forum_user->row_array();
            
            $DB_Main->where("plan_id", $plan_id);
            $query_plans = $DB_Main->get("plans");
            $row_plan = $query_plans->row_array();
            
            $forum_group = $row_plan["forum_usergroup"];
            
            $old_member_group_id = $row_forum_user["member_group_id"];
            $old_mgroup_others = $row_forum_user["mgroup_others"];
            
            $right_array = explode(",",$old_mgroup_others);
            $exception_array = explode(",",$this->config->item("integration_forum_exception_usergroups"));
            
            //remove the donator group and NULL entries from the right array
            $pos_don_grp = array_search($forum_group, $right_array);
            unset($right_array[$pos_don_grp]);
            $right_array = array_values($right_array);
            $right_array = $this->remove_null_array($right_array);
            
            if($old_member_group_id === $forum_group){
                //NON-ADMIN: Remove Member Group ID and replace with 1st entry in rights array
                $new_member_group_id = $right_array[0];
                
                unset($right_array[0]);
                $right_array = array_values($right_array);
                $right_array = $this->remove_null_array($right_array);
                
                $new_mgroup_others = ",".implode(",", $right_array).",";
            }else{
                //ADMIN: Leave Member Group ID
                $new_member_group_id = $old_member_group_id;
                $new_mgroup_others = ",".implode(",", $right_array).",";
            }
            
            echo "</br>new_member_group_id: $new_member_group_id";
            echo "</br>new_mgroup_others: $new_mgroup_others";
            
            $data=array(
                "member_group_id" =>$new_member_group_id,
                "mgroup_others" =>$new_mgroup_others
            );
            
            $DB_Forum->where("member_id",$forum_userid);
            $DB_Forum->update($this->config->item("integration_forum_usertable"),$data);
        }
    
    }
    
    
    function donation_add($userid){
        $DB_Main = $this->load->database('default', TRUE);
        
         echo "</br>loading user from db";
        
        //get the User-Data
        $DB_Main->where('user_id' , $userid);
        $query_users = $DB_Main->get('users');
        $row_users = $query_users->row_array();
        // add data to vars
        $username = $row_users['nickname'];
        $email = $row_users['email'];
        $steam_id = $row_users['steam_id'];
        $plan_id = $row_users['plan_id'];
        $forum_userid = $row_users['forum_userid'];
        
        
        //get the Plan-Data
        $DB_Main->where('plan_id', $plan_id);
        $query_plans = $DB_Main->get('plans');
        $row_plans = $query_plans->row_array();
        
        $sm_groupid = $row_plans['sm_groupid'];
        $bdi_level = $row_plans['bdi_level'];
        $forum_user_group = $row_plans['forum_usergroup'];
        
        if($forum_userid != "0"){
        
            if($this->config->item('integration_forum_enabled') === "1"){

                 echo "</br>doing forum";

                switch($this->config->item('integration_forum_system')){

                    case "mybb":

                        $forum_display_group = $forum_user_group;
                        $this->mybb_add($forum_userid, $forum_user_group, $forum_display_group, $userid);

                        break;

                    case "smf":

                        break;

                    case "ipb":
                        echo "</br>doing ipb $forum_userid , $forum_user_group , $userid";
                        $this->ipb_add($forum_userid, $forum_user_group, $userid);
                        break;


                }
            }
        }
        
        if($this->config->item('integration_bdi_enabled') === "1"){
            
            $this->bdi_add($steam_id, $bdi_level);
             echo "</br>doing bdi";
            
            
        }
        
        if($this->config->item('integration_sb_enabled') === "1"){
            
            // $this->Integration_features->bdi_add($steam_id, $bdi_level);
             echo "</br>doing sb";
            
        }
        
        if($this->config->item('integration_mysql_admins_enabled') === "1"){
            
            $this->sm_mysql_admins_add($username, $steam_id, $sm_groupid);
            echo "</br>doing mysqladmins";
            
        }
    }
    
    function donation_remove($userid){
        $DB_Main = $this->load->database('default', TRUE);
        
        //get the User-Data
        $DB_Main->where('user_id' , $userid);
        $query_users = $DB_Main->get('users');
        $row_users = $query_users->row_array();
        // add data to vars
        $username = $row_users['nickname'];
        $email = $row_users['email'];
        $steam_id = $row_users['steam_id'];
        $plan_id = $row_users['plan_id'];
        $forum_userid = $row_users['forum_userid'];
        $forum_oldusrgroup = $row_users['forum_oldusrgroup'];
        $forum_olddispgroup = $row_users['forum_olddispgroup'];
        
        if($forum_userid != "0"){
            if($this->config->item('integration_forum_enabled') === "1"){

                switch($this->config->item('integration_forum_system')){

                    case "mybb":
                        $this->mybb_remove($forum_userid, $forum_oldusrgroup, $forum_olddispgroup);
                        break;

                    case "smf":

                        break;

                    case "ipb":
                        echo "</br> removing IPB $userid,$forum_userid,$forum_oldusrgroup,$forum_olddispgroup  ";
                        $this->ipb_remove($userid ,$forum_userid ,$plan_id);
                        break;


                }
            }
        }
        
        if($this->config->item('integration_bdi_enabled') === 1){
            
            $this->bdi_remove($steamid);
        }
        
        if($this->config->item('integration_mysql_admins_enabled') === '1'){
            
            $this->sm_mysql_admins_remove($steam_id);
        }
        
        
    }
    
    
    function donation_postprocess($Payment_Data){
        
        log_message('info', 'DON_PostProc: function called');
        
        $txn_id = $Payment_Data["txn_id"];
        $payer_email = $Payment_Data["payer_email"];
        $mc_gross = $Payment_Data["mc_gross"];
        $steam_id = $Payment_Data["steam_id"];
        $username = $Payment_Data["username"];
        $password = $Payment_Data["password"];
        $date = $Payment_Data["date"];
        $provider = $Payment_Data["provider"];     
        $plan_id = $Payment_Data["plan_id"];
        $forum_userid = $Payment_Data["forum_userid"];
        
        log_message('info', 'DON_PostProc: username: '.$username);
        log_message('info', 'DON_PostProc: steam_id: '.$steam_id);
        log_message('info', 'DON_PostProc: plan_id: '.$plan_id);
        
        if($forum_userid === NULL) $Payment_Data["forum_userid"] = 0;
        
        $DB_Main = $this->load->database('default', TRUE); //Load the Main-db
        log_message('info', 'DON_PostProc: loaded main db');
        
        //get the plan data from the plan DB
        $DB_Main->where('plan_id',$plan_id);
        $query_plan = $DB_Main->get('plans');
        $row_plan = $query_plan->row_array(); //get var
        log_message('info', 'DON_PostProc: got the plan data');
        

        $DB_Main->insert('orders', $Payment_Data); // Add the Order to the Orders DB
        log_message('info', 'DON_PostProc: inserted the payment data');
        
        
        $DB_Main->where('nickname', $username);
        $query1 = $DB_Main->get('users');
        log_message('info', 'DON_PostProc: got the users');
        log_message('info', 'DON_PostProc: user-ammount:'.$query1->num_rows);
        //echo "num_rows usertable:".$query1->num_rows ."</br>";
        

        
        
        if($query1->num_rows === 0){ //User not in Database
            
            //Create a Userdata-Array for the Insert
            
            $plan_time = $row_plan['plan_time'];  //get the exp time for the plan
            
            if($plan_time === "0"){
                $plan_time = 365*50;
            }

            $plan_exp_date = date("Y-m-d",strtotime($date) + $plan_time*86400);

            echo "</br>plan_exp_date = ". $plan_exp_date . "</br>";

            $user_data=array(
                'nickname' => $username,
                'email' => $payer_email,
                'last_donation' => $date,
                'steam_id' => $steam_id,
                'plan_exp_date' => $plan_exp_date,
                'forum_userid' => $forum_userid,
                'plan_id' => $plan_id
            );            
            
            
            
            if($this->config->item('site_approval_list') === "1"){ //User not in DB + Approval-List
                $user_data['status'] = 0;
                $DB_Main->insert('users' , $user_data);
                log_message('info', 'DON_PostProc: create user and add to approval list');
                
                
            }elseif($this->config->item('site_approval_list') === "0"){ //User not in DB + NO Approval-List
                $user_data['status'] = 1;
                $DB_Main->insert('users', $user_data);
                log_message('info', 'DON_PostProc: create user');
                  
            }
            
            
            
        }elseif($query1->num_rows === 1){ //User in DB
            
            //get the result
            $row_user = $query1->row_array();
            
            $uid = $row_user['user_id'];
            $user_plan_id = $row_user['plan_id'];
            $plan_time = $row_plan['plan_time'];
            $plan_exp_date_old = $row_user['plan_exp_date'];
            
            $plan_exp_date = date("Y-m-d",strtotime($row_user['plan_exp_date']) + $plan_time * 86400);
            
            
            echo "uid:".$uid."</br>";
            echo "status:".$row_user['status']."</br>";
            echo "user_plan_id:".$row_user['plan_id']."</br>";
            echo "plan_time:".$plan_time."</br>";
            
            if($row_user['plan_id'] === $plan_id){ //Check if the User has Donated for the same Plan
                
                 //create the update-data
                $update_data=array(
                    'last_donation' => $date,
                    'plan_exp_date' => $plan_exp_date
                );

                if($row_user['status'] === "2"){ //Donation not expired

                    $DB_Main->where('user_id', $uid); // Update the User
                    $DB_Main->update('users', $update_data);
                    echo "Updated Date </br>";

                }elseif($row_user['status'] === "3"){ //Donation expired
                    $update_data['status'] = 1; //set status to add

                    $DB_Main->where('user_id', $uid); //Update the User
                    $DB_Main->update('users', $update_data);
                    echo "Updated Date+Status";
                }
                log_message('info', 'DON_PostProc: update user');

            }else{ //The User has Donated for a Different Plan //Check-IT
                $old_plan = $row_user['plan_id'];
                
                $DB_Main->where('plan_id',$old_plan);
                $query_old_plan = $DB_Main->get('plans');
                $row_old_plan = $query_old_plan->row_array();
                
                $old_ppd = $row_old_plan['plan_price'] / $row_old_plan['plan_time'];
                
                log_message('info', "DON_PostProc: the old plan costs: " . $old_ppd . "per day");
                
                //echo "the old plan costs" . $old_ppd . "per day</br>";
                
                $diff = strtotime($row_user["plan_exp_date"]) - strtotime($date);
                
                $left_days = $diff /86400;
                echo "left days at old plan: " .$left_days . "</br>";
                log_message('info','DON_PostProc: left days at the old plan: '.$left_days);
                
                $left_ammount = $old_ppd * $left_days;
                echo "an ammount of " .$left_ammount. " is left at the old plan </br>";
                log_message('info','DON_PostProc: an ammount of '.$left_ammount.'is left at the old plan');
                
                $new_ppd = $row_plan['plan_price'] / $row_plan['plan_time'];
                
                $additional_days = $left_ammount / $new_ppd;
                
                echo "this is equal to an ammount of ". $additional_days . " in the new plan </br>";
                
                log_message('info', 'DON_PostProc: update user and change plan');
                log_message('info', 'DON_PostProc: user got '.$additional_days.' additional days');
                $new_unix = strtotime($row_user["plan_exp_date"]) + $additional_days * 86400;
                $new_date = date("Y-m-d",$new_unix);
                log_message('info','DON_PostProc: new_unix: '.$new_unix);
                log_message('info','DON_PostProc: new_date: '.$new_date);
                
                
                $DB_Main->where('user_id', $uid);
                
                $data = array(
                    "last_donation" => $date,
                    "plan_id" =>$plan_id,
                    "plan_exp_date"=>$new_date
                );
                
                $this->donation_remove($uid);
                $this->donation_add($uid);
                
            }
        }   
        
        $this->donation_added_email($userid);
    }
    
    function donation_added_email($user_id){
        
        $this->load->library('parser');
        
        //Connect and Query DB
        $DB_Main = $this->load->database('default', TRUE);
        $DB_Main->where('user_id', $user_id);
        $query_users = $DB_Main->get('users');
        $row_users = $query_users->row_array();
        
        $DB_Main->where('plan_id', $row_users["plan_id"]);
        $query_plans = $DB_Main->get('plans');
        $row_plans = $query_plans->row_array();
        
        $plan_exp_date = $row_users['plan_exp_date'];
        
        if($row_plans["plan_id"] === "0") $plan_exp_date = "You have a infinite Plan";
        
        
        //get data
        $data['user_email'] = $row_users['email'];
        $data['plan_exp_date'] = $plan_exp_date;
        $data['steam_id'] = $row_users['steam_id'];
        $data['username'] = $row_users['nickname'];
        $data['plan_id'] = $row_plans['plan_id'];
        $data['plan_name'] = $row_plans['plan_name'];
        $data['plan_desc'] = $row_plans['plan_description'];
        $data['last_donation'] = $row_users['last_donation'];
        $data['com_name'] = $this->config->item('community_name');
        $data['com_email'] = $this->config->item('community_email');
        $data['com_url'] = $this->config->item('community_url');
        $data['email_extra_info'] = $this->config->item('email_extra_info');
        
        foreach (explode(";",$this->config->item('email_attachments')) as $attachment){
            //$this->email->attach(base_url($attachment));
            echo base_url($attachment);
        }
        
        //setup E-Mail-Settings
        $config['useragent'] = $this->config->item('email_useragent');
        $config['protocol'] = $this->config->item('email_protocol');
        $config['mailpath'] = $this->config->item('email_mailpath');
        $config['charset'] = $this->config->item('email_charset');
        $config['smtp_host'] = $this->config->item('email_smtp_host');
        $config['smtp_user'] = $this->config->item('email_smtp_user');
        $config['smtp_pass'] = $this->config->item('email_smtp_pass');
        $config['smtp_port'] = $this->config->item('email_smtp_port');
        $config['smtp_timeout'] = $this->config->item('email_smtp_timeout');
        $config['mailtype'] = $this->config->item('email_mailtype');
        
        //parse the Template
        $email_message = $this->parser->parse('mail_templates/donation_added',$data,TRUE);
        
        
        //send E-Mail
        $this->load->library('email');
        $this->email->initialize($config);
        $this->email->from($data['com_email'] , $data['com_name']);
        $this->email->to($data['user_email']);
        $this->email->subject('Donation Added');
        $this->email->message($email_message);
        $this->email->send();
        echo $this->email->print_debugger();
        
        
        //send E-Mail to admin
        
        if($this->config->item("community_inform_admin_donation") === "1"){
            //parse the Template
            $email_message = $this->parser->parse('mail_templates/donation_added_admin',$data,TRUE);
            
            //send E-Mail
            $this->load->library('email');
            $this->email->from($data['com_email'] , $data['com_name']);
            $this->email->to($data['com_email']);
            $this->email->subject('Donation Added');
            $this->email->message($email_message);
            $this->email->send();
        }
    }
    
    function remove_null_array($array){
        $res = array();
        foreach($array as $value){
            if($value != NULL & $value != ""){
                $res[] = $value;
            }
        }
        
        return $res;
    }
    
    function remove_id_array($array, $id){
        $res = array();
        foreach($array as $value){
            if($value != $id){
                $res[] = $value;
            }
        }
        return $res;
    }
    
    function array_in_array($search_for,$search_in){
        $num = 0;
        foreach($search_for as $value){
            if(in_array($value, $search_in)) $num += 1;
        }
        if($num != 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}

?>
