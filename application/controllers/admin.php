<?php
/**
 * SDv1 Admin Controller
 * 
 * Provides the Admin Interface
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
class admin extends CI_Controller{
    
    var $admin_data;
    var $admin_permissions;
    
    function __construct() {
        parent::__construct();
        //setting the right header
        header('Content-Type: text/html; charset=utf-8');
        
        
        //setting the global admin data var
        $this->admin_data = "";
        $this->admin_permissions = "";
        $this->load->model('admin_functions');
        $oid_identity = $this->session->userdata('oid_identity');
        if($oid_identity != ""){
            $this->admin_data = $this->admin_functions->get_data_from_openid($oid_identity);
            $this->admin_permissions = explode(",", $this->admin_data->permissions);
        }
        
    }
    
    function index(){
        
        if($this->admin_data != ""){
            $data['admin'] = $this->admin_data;
            $data['permissions'] = $this->admin_permissions;
            
            $this->load->view('parts/admin_head',$data);
            $this->load->view('parts/admin_sidebar',$data);
            $this->load->view('parts/admin_footer',$data);
        }else{
            redirect(base_url("index.php/admin/login/"));
        }
    }
    
     
    function login(){
        $oid_identity = $this->session->userdata('oid_identity');
        echo $oid_identity;
        if($this->admin_data != ""){
            redirect('admin/index');
        }else{
            $data['forum_login'] = $this->session->userdata('forum_login');
            $data['currency'] = $this->config->item('community_currency');
            $data['community_name'] = $this->config->item('community_name');
            $data['community_logo'] = $this->config->item('community_logo');

            $this->load->view('admin/login_openid',$data);
        }
    }
    
    function logout(){
        $this->session->sess_destroy();
        redirect('admin/login');
    }
    
    
    function plans(){
        if($this->admin_data === ""){
            redirect('admin/index');
        }else{
            $data["mod_status"] = $this->session->userdata('mod_status');
            $this->session->unset_userdata("mod_status");
            
            $plans = $this->admin_functions->get_plans();

            $data['admin'] = $this->admin_data;
            $data['permissions'] = $this->admin_permissions;
            $data['plans'] = $plans;
            
            $this->load->view('parts/admin_head',$data);
            $this->load->view('parts/admin_sidebar',$data);
            $this->load->view('admin/plans',$data);
            $this->load->view('parts/admin_footer',$data);
        }
    }
    
    function categories(){
        if($this->admin_data === ""){
            redirect('admin/index');
        }else{
            $data["mod_status"] = $this->session->userdata('mod_status');
            $this->session->unset_userdata("mod_status");
            
            $categories = $this->admin_functions->get_categories();

            $data['admin'] = $this->admin_data;
            $data['permissions'] = $this->admin_permissions;
            $data['categories'] = $categories;


            $this->load->view('parts/admin_head',$data);
            $this->load->view('parts/admin_sidebar',$data);
            $this->load->view('admin/categories',$data);
            $this->load->view('parts/admin_footer',$data);
        }
    }
    
    function items(){
        if($this->admin_data === ""){
            redirect('admin/index');
        }else{
            $data["mod_status"] = $this->session->userdata('mod_status');
            $this->session->unset_userdata("mod_status");
            
            $items = $this->admin_functions->get_items();

            $data['admin'] = $this->admin_data;
            $data['permissions'] = $this->admin_permissions;
            $data['items'] = $items;


            $this->load->view('parts/admin_head',$data);
            $this->load->view('parts/admin_sidebar',$data);
            $this->load->view('admin/items',$data);
            $this->load->view('parts/admin_footer',$data);
        }
    }
    
    
    function donators_list(){
        if($this->admin_data === ""){
            redirect('admin/index');
        }else{
            $data["mod_status"] = $this->session->userdata('mod_status');
            $this->session->unset_userdata("mod_status");
            
            $donators_waiting = $this->admin_functions->get_donators("1");
            $donators_added = $this->admin_functions->get_donators("2");
            $donators_expired = $this->admin_functions->get_donators("3");

            $data['admin'] = $this->admin_data;
            $data['permissions'] = $this->admin_permissions;
            $data['donators_waiting'] = $donators_waiting;
            $data['donators_added'] = $donators_added;
            $data['donators_expired'] = $donators_expired;


            $this->load->view('parts/admin_head',$data);
            $this->load->view('parts/admin_sidebar',$data);
            $this->load->view('admin/donators_list',$data);
            $this->load->view('parts/admin_footer',$data);
        }
    }
    
    function donators_approval(){
        if($this->admin_data === ""){
            redirect('admin/index');
        }else{
            $data["mod_status"] = $this->session->userdata('mod_status');
            $this->session->unset_userdata("mod_status");
            
            $donators_approval = $this->admin_functions->get_donators("0");

            $data['admin'] = $this->admin_data;
            $data['permissions'] = $this->admin_permissions;
            $data['donators_approval'] = $donators_approval;


            $this->load->view('parts/admin_head',$data);
            $this->load->view('parts/admin_sidebar',$data);
            $this->load->view('admin/donators_approval',$data);
            $this->load->view('parts/admin_footer',$data);
        }
    }
    
    
    function settings_sd(){
        if($this->admin_data === ""){
            redirect('admin/index');
        }else{
            $data["mod_status"] = $this->session->userdata('mod_status');
            $this->session->unset_userdata("mod_status");
            
            $data['admin'] = $this->admin_data;
            $data['permissions'] = $this->admin_permissions;
            $data['settings'] = $this->admin_functions->get_settings();

            $this->load->view('parts/admin_head',$data);
            $this->load->view('parts/admin_sidebar',$data);
            $this->load->view('admin/settings_sd',$data);
            $this->load->view('parts/admin_footer',$data);
        }
    }
    
    function settings_admins(){
        if($this->admin_data === ""){
            redirect('admin/index');
        }else{
            $data["mod_status"] = $this->session->userdata('mod_status');
            $this->session->unset_userdata("mod_status");
            
            $data['admin'] = $this->admin_data;
            $data['permissions'] = $this->admin_permissions;
            $data['admins'] = $this->admin_functions->get_admins();

            $this->load->view('parts/admin_head',$data);
            $this->load->view('parts/admin_sidebar',$data);
            $this->load->view('admin/settings_admins',$data);
            $this->load->view('parts/admin_footer',$data);
        }
    }
    
    function settings_forumgroups(){
        if($this->admin_data === ""){
            redirect('admin/index');
        }else{
            $data["mod_status"] = $this->session->userdata('mod_status');
            $this->session->unset_userdata("mod_status");
            
            $forum_groups = $this->admin_functions->get_forum_groups();

            $data['admin'] = $this->admin_data;
            $data['permissions'] = $this->admin_permissions;
            $data['forum_groups'] = $forum_groups;
            $data['exception_groups'] = explode(",",$this->config->item("integration_forum_exception_usergroups"));


            $this->load->view('parts/admin_head',$data);
            $this->load->view('parts/admin_sidebar',$data);
            $this->load->view('admin/settings_forumgroup',$data);
            $this->load->view('parts/admin_footer',$data);
        }
    }   
    
    
    function process_openid(){
        require_once './application/libraries/openid.php';
        
        try {
            # Change 'localhost' to your domain name.
            $openid = new OpenID(substr(base_url(),7));
            if(!$openid->mode) {
                if(isset($_POST['openid_identifier'])) {
                    $openid->identity = $_POST['openid_identifier'];
                    # The following two lines request email, full name, and a nickname
                    # from the provider. Remove them if you don't need that data.
                    $openid->required = array('contact/email');
                    $openid->optional = array('namePerson', 'namePerson/friendly');
                    header('Location: ' . $openid->authUrl());
                }
            } elseif($openid->mode == 'cancel') {
                echo 'User has canceled authentication!';
            } else {
                //echo 'User ' . ($openid->validate() ? $openid->identity . ' has ' : 'has not ') . 'logged in.';
                if($openid->validate() === 1){
                    $oid_loggedin = TRUE;
                    $oid_identity = $openid->identity;
                    echo "</br>User: $oid_identity has logged in";
                    $this->session->set_userdata('oid_identity', $oid_identity);
                    redirect(base_url('index.php/admin/login'));
                }else{
                    echo "</br>Not Logged in";
                    $oid_loggedin = FALSE;
                }
                

            }
        } catch(ErrorException $e) {
            echo $e->getMessage();
        }
        
    }
    
    function process(){
        $post = $this->input->post();
        print_r($post);
        echo "</br>Page:".$post['page'];
        switch ($post['page']){
            case "plans":
                if(in_array('plans_edit',$this->admin_permissions)){
                    echo "<br/>";
                    $DB_Main = $this->load->database('default',TRUE);
                    if($post["action"] === "add"){
                        
                    }elseif($post["action"] === "edit"){
                        $data = array(
                            "plan_name" => $post["plan_name"],
                            "plan_description" => $post["plan_description"],
                            "plan_price" => $post["plan_price"],
                            "plan_time" => $post["plan_color"],
                            "sm_groupid" => $post["sm_groupid"],
                            "bdi_level" => $post["bdi_level"],
                            "forum_usergroup" => $post["forum_usergroup"]
                        );
                        $DB_Main->where("plan_id",$post["plan_id"]);
                        $DB_Main->update("plans",$data);
                    }elseif($post["action"] === "del"){
                        
                    }else{
                        
                    }
                    $this->session->set_userdata('mod_status', 'success');
                    redirect('admin/plans');
                }else{
                    echo "You do not have the Permission to edit the Plans";
                    $this->session->set_userdata('mod_status', 'no_permission');
                    redirect('admin/plans');
                }
                break;

            case "categories":
                if(in_array('categories_edit',$this->admin_permissions)){
                    echo "<br/>";
                    $DB_Main = $this->load->database('default',TRUE);
                    if($post["action"] === "add"){
                        
                    }elseif($post["action"] === "edit"){
                        $data = array(
                            "category_name" => $post["category_name"],
                        );
                        $DB_Main->where("category_id",$post["category_id"]);
                        $DB_Main->update("categories",$data);
                    }elseif($post["action"] === "del"){
                        
                    }else{
                        
                    }
                    $this->session->set_userdata('mod_status', 'success');
                    redirect('admin/categories');
                }else{
                    echo "You do not have the Permission to edit the Plans";
                    $this->session->set_userdata('mod_status', 'no_permission');
                    redirect('admin/categories');
                }
                break;
                
            case "settings_sd":
                if(in_array('settings_sd_edit',$this->admin_permissions)){
                    $DB_Main = $this->load->database('default',TRUE);
                    
                    $query_settings = $this->admin_functions->get_settings();
                    foreach($query_settings->result() as $setting){
                        if($setting->value != $post[$setting->id]){
                            echo "<br/>'$setting->id' is different";
                            echo "<br/>old_value: '$setting->value'";
                            echo "<br/>new_value: '".$post[$setting->id]. "'";
                            $data = "";
                            $data["value"] = $post[$setting->id];
                            $DB_Main->where("id",$setting->id);
                            $DB_Main->update("settings",$data);
                            $data = "";
                            
                        }
                    }
                    echo "<br/>";
                    $this->session->set_userdata('mod_status', 'success');
                    redirect('admin/settings_sd');
                }else{
                    echo "You do not have the Permission to edit the Database";
                    $this->session->set_userdata('mod_status', 'no_permission');
                    redirect('admin/settings_sd');
                }
                break;
            case "settings_admins":
                if(in_array('settings_admins_edit',$this->admin_permissions)){
                    echo "</br>in case settings_sd";
                    
                    $DB_Main = $this->load->database('default',TRUE);
                    
                    $data=array(
                        "username" => $post['username'],
                        "openid_identity" => $post['oid_identity'],
                        "email" => $post['email'],
                        "permissions" => $post['permissions']
                    );
                    
                    $DB_Main->where("openid_identity", $post['oid_identity']);
                    $DB_Main->update('admin_users', $data);
                    redirect('admin/settings_admins');
                    $this->session->set_userdata('mod_status', 'success');
                    
                }else{
                    echo "You do not have the Permission to edit the admins";
                    $this->session->set_userdata('mod_status', 'no_permission');
                    redirect('admin/settings_admins');
                }
                break;
            case "settings_forumgroups":
                if(in_array('settings_forumgroups_edit',$this->admin_permissions)){
                    $DB_Main = $this->load->database("default", TRUE);
                    $post = $this->input->post();
                    $integration_forum_exception_usergroups = implode(",", $post["group"]);
                    
                    echo "</br>$integration_forum_exception_usergroups";
                    
                    $data= array( "value" => $integration_forum_exception_usergroups);
                    
                    $DB_Main->where("id", "integration_forum_exception_usergroups");
                    $DB_Main->update("settings", $data);
                    $this->session->set_userdata('mod_status', 'success');
                    redirect("admin/settings_forumgroups");
                    
                }else{
                    echo "You do not have the Permission to edit the forumgroups";
                    $this->session->set_userdata('mod_status', 'no_permission');
                    redirect('admin/settings_forumgroups');
                }
                break;
        
        }
    }
}

?>
