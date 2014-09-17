	<section id="main" class="column">
            <?php if($mod_status === "success"):?>
            <h4 class="alert_success">Edit Successful</h4>
            <?php elseif($mod_status === "no_permission"): ?>
            <h4 class="alert_error">You do not have the Permission to do this</h4>
            <?php endif; ?>
            <?php if(in_array('categories_view',$permissions)):?>
		<article class="module width_full">
		<header><h3 class="tabs_involved">Category List</h3>
		<ul class="tabs">
                <!--<li><a href="#tab1">Active</a></li>-->
		</ul>
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr>
    				<th>CAT ID</th> 
    				<th>Category</th>
    				<th>Actions</th> 
				</tr> 
			</thead> 
			<tbody>
                            <?php if($categories->num_rows > 0): ?>
                            <?php foreach($categories->result() as $category_row): ?>
                            <form action="<?php echo base_url("index.php/admin/process")?>" method="post">
                                <input type="hidden" name="page" value="categories">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="category_id" value="<?=$category_row->category_id?>">
				<tr>
    				<td><input name="categorie_id" value="<?=$category_row->category_id?>" disabled/></td> 
    				<td><input name="category_name" value="<?=$category_row->category_name?>" /></td>
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