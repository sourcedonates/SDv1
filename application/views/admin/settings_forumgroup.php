            <section id="main" class="column">      
            <?php if($mod_status === "success"):?>
            <h4 class="alert_success">Edit Successful</h4>
            <?php elseif($mod_status === "no_permission"): ?>
            <h4 class="alert_error">You do not have the Permission to do this</h4>
            <?php endif; ?>
                <?php if(in_array('donators_approval_view',$permissions)):?>
                <form action="<?php echo base_url("index.php/admin/process")?>" method="post">
                <button class="save float-right inline-block" type="submit">Save all changes</button>
		<article class="module width_full">
		<header><h3 class="tabs_involved">Select the groups where 'donator-group' should NOT be set as the Displaygroup</h3>
		<ul class="tabs">
   		<li><a href="#tab_groups">Groups</a></li>
		</ul>
		</header>

		<div class="tab_container">
			<div id="tab_groups" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   				<th></th> 
    				<th>Group ID</th> 
    				<th>Group Name</th>
				</tr> 
			</thead> 
			<tbody>
                            <input type="hidden" name="page" value="settings_forumgroups">
                            <?php foreach($forum_groups->result() as $group):?>
                            <?php if($this->config->item("integration_forum_system") === "ipb"){ $group_id = $group->g_id; $group_name = $group->g_title;}?>
                            
				<tr> 
   				<td><input type="checkbox" name="group[]" value="<?=$group_id?>" <?php if(in_array($group_id,$exception_groups)) echo "checked" ;?>></td> 
    				<td><?=$group_id?></td> 
    				<td><?=$group_name?></td>
				</tr>
                            <?php endforeach;?>
                        
			</tbody>
			</table>
			</div>
			
		</div>
		
		</article><!-- end of content manager article -->
                <button class="save float-right inline-block" type="submit">Save all changes</button>
                </form>
		<div class="clear"></div>
		
		<div class="spacer"></div>
            <?php else:?>
                You do not have the permission to view this page
            <?php endif;?>
	</section>