<?php
/**
 * SDv1 Donate Functions
 * 
 * Provides donation functions
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
class Donate_functions extends CI_Model{
    
    function get_steam_from_ip($ip){
        //get the Steam_ID from the Server-SB
        
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
        
        
        //get steam_id from plugin-table
        $DB_Server->where('ip', $plugin_ip);
        $query_ip = $DB_Server->get('sd_donate_autofill');
        $row_ip = $query_ip->row_array();
        $num_ip = $query_ip->num_rows;
        
        if($num_ip === 1){
            return $row_ip['steamid'];
        }elseif($num_ip > 1){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    
    function get_forum_data($ip){
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
    }
    
    
    function add_pending($txn_id, $user_email, $steam_id, $username, $password, $plan_id, $forum_userid, $amount){
        
        $DB_Main = $this->load->database('default',TRUE);
        
        $data=array(
            'txn_id'=>$txn_id,
            'user_email'=>$user_email,
            'steam_id'=>$steam_id,
            'username'=>$username,
            'password'=>$password,
            'plan_id'=>$plan_id,
            'forum_userid'=>$forum_userid,
            'amount'=>$amount
        );
        
        $DB_Main->insert('pending-orders', $data);
        
        return $DB_Main->insert_id();
        
    }
    
}
?>
