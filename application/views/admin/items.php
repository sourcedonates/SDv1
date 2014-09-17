	<section id="main" class="column">
            <?php if($mod_status === "success"):?>
            <h4 class="alert_success">Edit Successful</h4>
            <?php elseif($mod_status === "no_permission"): ?>
            <h4 class="alert_error">You do not have the Permission to do this</h4>
            <?php endif; ?>
            <?php if(in_array('items_view',$permissions)):?>
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
    				<th>Item ID</th> 
    				<th>Plan IDs</th>
    				<th>Category ID</th>
                                <th>Item Name</th>
                                <th>Item Picture</th>
				</tr> 
			</thead> 
			<tbody>
                            <?php if($items->num_rows > 0): ?>
                            <?php foreach($items->result() as $item): ?>
                            <form action="<?php echo base_url("index.php/admin/process")?>" method="post">
                                <input type="hidden" name="page" value="items">
				<tr>
    				<td><input name="item_id" value="<?=$item->item_id?>" disabled/></td> 
    				<td><input name="plan_id" value="<?=$item->plan_id?>" /></td>
                                <td><input name="category_id" value="<?=$item->category_id?>" /></td>
                                <td><input name="item_name" value="<?=$item->item_name?>" /></td>
                                <td><input name="item_picture" value="<?=$item->item_picture?>" /></td>
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