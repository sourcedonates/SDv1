	<section id="main" class="column">
            <?php if($mod_status === "success"):?>
            <h4 class="alert_success">Edit Successful</h4>
            <?php elseif($mod_status === "no_permission"): ?>
            <h4 class="alert_error">You do not have the Permission to do this</h4>
            <?php endif; ?>
            <?php if(in_array('plans_view',$permissions)):?>
		<article class="module width_full">
		<header><h3 class="tabs_involved">Item List</h3>
		<ul class="tabs">
                <!--<li><a href="#tab1">Active</a></li>-->
		</ul>
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr>
    				<th>Plan ID</th> 
    				<th>Plan Name</th>
    				<th>Plan Description</th>
                                <th>Plan Price</th>
                                <th>Plan Time</th>
                                <th>Plan Color</th>
                                <th>SM Group ID</th>
                                <th>BDI Level</th>
                                <th>Forum Usergroup ID</th>
				</tr> 
			</thead> 
			<tbody>
                            <?php if($plans->num_rows > 0): ?>
                            <?php foreach($plans->result() as $plan): ?>
                            <form action="<?php echo base_url("index.php/admin/process")?>" method="post">
                                <input type="hidden" name="page" value="plans">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="plan_id" value="<?=$plan->plan_id?>">
				<tr>
    				<td><input name="planid" value="<?=$plan->plan_id?>" disabled/></td> 
    				<td><input name="plan_name" value="<?=$plan->plan_name?>" /></td>
                                <td><input name="plan_description" value="<?=$plan->plan_description?>" /></td>
                                <td><input name="plan_price" value="<?=$plan->plan_price?>" /></td>
                                <td><input name="plan_time" value="<?=$plan->plan_time?>" /></td>
                                <td><input name="plan_color" value="<?=$plan->plan_color?>" /></td>
                                <td><input name="sm_groupid" value="<?=$plan->sm_groupid?>" /></td>
                                <td><input name="bdi_level" value="<?=$plan->bdi_level?>" /></td>
                                <td><input name="forum_usergroup" value="<?=$plan->forum_usergroup?>" /></td>
                                <td><input type="image" src="<?php echo base_url('img/images/icn_edit.png')?>" title="Edit"></td> 
				</tr>
                            </form>
                            <?php endforeach;?>  
                            <?php endif; ?>
			</tbody> 
			</table>
			</div>
			
			
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
				
		<div class="clear"></div>
		
		<div class="spacer"></div>
            <?php else: ?>
                You do not have the permission to view this page
            <?php endif;?>
	</section>