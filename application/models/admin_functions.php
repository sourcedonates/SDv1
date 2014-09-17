<?php
/**
 * SDv1 Admin Functions
 * 
 * Provides Functions for the Admin Menu
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

class Admin_functions extends CI_Model{
    
    function get_data_from_openid($oid_identity){
        $DB_Main = $this->load->database('default', TRUE);
        $DB_Main->where('openid_identity',$oid_identity);
        $query_admin_users = $DB_Main->get('admin_users');
        if($query_admin_users->num_rows === 1){
            return $query_admin_users->row();
        }else{
            return FALSE;
        }
    }
    
    
    function get_plans(){
        $DB_Main = $this->load->database('default', TRUE);
        $DB_Main->order_by("plan_id","asc");
        $query_plans = $DB_Main->get('plans');
        return $query_plans;
    }
    
    function get_categories(){
        $DB_Main = $this->load->database('default', TRUE);
        $DB_Main->order_by("category_id","asc");
        $query_categories = $DB_Main->get('categories');
        return $query_categories;
    }
    
    function get_items(){
        $DB_Main = $this->load->database('default', TRUE);
        $DB_Main->order_by("item_id","asc");
        $query_items = $DB_Main->get('items');
        return $query_items;
    }
    
    
    function get_donators($status){
        $DB_Main = $this->load->database('default', TRUE);
        $DB_Main->order_by("user_id","desc");
        $DB_Main->where('status',$status);
        $query_donators = $DB_Main->get('users');
        return $query_donators;
    }
    
    function get_settings(){
        $DB_Main = $this->load->database('default', TRUE);
        $DB_Main->order_by("id","asc");
        $query_settings = $DB_Main->get('settings');
        return $query_settings;
    }
    
    function get_admins(){
        $DB_Main = $this->load->database('default', TRUE);
        $DB_Main->order_by("id","asc");
        $query_admins = $DB_Main->get('admin_users');
        return $query_admins;
    }
    
    
    function get_forum_groups(){
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
        
        $query_groups = $DB_Forum->get($this->config->item("integration_forum_grouptable"));
        
        return $query_groups;
    }
}
?>
