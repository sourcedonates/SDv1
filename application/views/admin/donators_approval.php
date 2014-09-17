	<section id="main" class="column">
            <?php if($mod_status === "success"):?>
            <h4 class="alert_success">Edit Successful</h4>
            <?php elseif($mod_status === "no_permission"): ?>
            <h4 class="alert_error">You do not have the Permission to do this</h4>
            <?php endif; ?>
            <?php if(in_array('donators_approval_view',$permissions)):?>
		<article class="module width_full">
		<header><h3 class="tabs_involved">Donator List</h3>
		<ul class="tabs">
                <li><a href="#tab1">Waiting for Approval</a></li>
		</ul>
		</header>
                    
		<div class="tab_container">
                    
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
                                <th></th> 
    				<th>Username</th> 
    				<th>Email</th>
				<th>SteamID</th>
    				<th>Expire Date</th>
    				<th>Actions</th> 
				</tr> 
			</thead> 
			<tbody>
                            <?php if($donators_approval->num_rows > 0): ?>
                            <?php foreach($donators_approval->result() as $donator): ?>
                                <tr> 
   				<td><input type="checkbox"></td> 
    				<td><?=$donator->nickname?></td> 
    				<td><?=$donator->email?></td>
				<td><?=$donator->steam_id?></td>
    				<td><?=$donator->plan_exp_date?></td> 
                                <td><input type="image" src="<?php echo base_url('img/images/icn_edit.png')?>" title="Edit"><input type="image" src="<?php echo base_url('img/images/icn_trash.png')?>" title="Trash"></td> 
				</tr> 
                            <?php endforeach; ?>
                            <?php endif; ?>
                                
			</tbody> 
			</table>
                        </div><!-- end of #tab1 -->
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
				
		<div class="clear"></div>
		
		<div class="spacer"></div>
            <?php else:?>
                You do not have the permission to view this page
            <?php endif; ?>
	</section>