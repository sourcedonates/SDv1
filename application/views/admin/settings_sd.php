	<section id="main" class="column">
            <?php if($mod_status === "success"):?>
            <h4 class="alert_success">Edit Successful</h4>
            <?php elseif($mod_status === "no_permission"): ?>
            <h4 class="alert_error">You do not have the Permission to do this</h4>
            <?php endif; ?>
            <?php if(in_array('settings_sd_view',$permissions)):?>
            <form action="<?php echo base_url('index.php/admin/process') ?>" method="post">
                <input type="hidden" name="page" value="settings_sd">
                <button class="save float-right inline-block" type="submit">Save all changes</button>
		<article class="module width_full">
		<header><h3 class="tabs_involved">Settings</h3>

		<ul class="tabs">
   		<li><a href="#tab_community">Community</a></li>
                <li><a href="#tab_database">Database</a></li>
    		<li><a href="#tab_email">Email</a></li>
		<li><a href="#tab_integration">Integration</a></li>
		<li><a href="#tab_payment">Payment</a></li>
		<li><a href="#tab_site">Site</a></li>
		<li><a href="#tab_testmode">Testmode</a></li>
		</ul>

		</header>

		<div class="tab_container">
			<div id="tab_community" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
                            <tr> 
                                <th>ID</th>
                                <th>Value</th>
                                <th>Description</th>
                            </tr> 
			</thead> 
			<tbody>
                            <?php foreach($settings->result() as $setting):?>
                            <?php if(strpos($setting->id,"community_") !== false):?>
				<tr> 
    				<td><?=$setting->id?></td> 
    				<td><input name="<?=$setting->id?>" value="<?=$setting->value?>"/></td>
                                <td><?=$setting->description?></td>
				</tr>
                            <?php endif;?>
                            <?php endforeach;?>
			</tbody> 
			</table>
			</div>
                    
			<div id="tab_database" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
                            <tr> 
                                <th>ID</th>
                                <th>Value</th>
                                <th>Description</th>
                            </tr> 
			</thead> 
			<tbody>
                            <?php foreach($settings->result() as $setting):?>
                            <?php if(strpos($setting->id,"database_") !== false):?>
				<tr> 
    				<td><?=$setting->id?></td> 
    				<td><input name="<?=$setting->id?>" value="<?=$setting->value?>"/></td>
                                <td><?=$setting->description?></td>
				</tr>
                            <?php endif;?>
                            <?php endforeach;?>
			</tbody> 
			</table>
			</div>
			
			<div id="tab_email" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th>ID</th>
    				<th>Value</th>
                                <th>Description</th>
				</tr> 
			</thead> 
			<tbody> 
                            <?php foreach($settings->result() as $setting):?>
                            <?php if(strpos($setting->id,"email_") !== false):?>
				<tr> 
    				<td><?=$setting->id?></td> 
    				<td><input name="<?=$setting->id?>" value="<?=$setting->value?>"/></td>
                                <td><?=$setting->description?></td>
				</tr>
                            <?php endif;?>
                            <?php endforeach;?>
			</tbody> 
			</table>
			</div>
			
			<div id="tab_integration" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th>ID</th>
    				<th>Value</th>
                                <th>Description</th>
				</tr> 
			</thead> 
			<tbody> 
                            <?php foreach($settings->result() as $setting):?>
                            <?php if(strpos($setting->id,"integration_") !== false):?>
				<tr> 
    				<td><?=$setting->id?></td> 
    				<td><input name="<?=$setting->id?>" value="<?=$setting->value?>"/></td>
                                <td><?=$setting->description?></td>
				</tr>
                            <?php endif;?>
                            <?php endforeach;?>
			</tbody> 
			</table>
			</div>
		
			<div id="tab_payment" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th>ID</th>
    				<th>Value</th>
                                <th>Description</th>
				</tr> 
			</thead> 
			<tbody> 
                            <?php foreach($settings->result() as $setting):?>
                            <?php if(strpos($setting->id,"payment_") !== false):?>
				<tr> 
    				<td><?=$setting->id?></td> 
    				<td><input name="<?=$setting->id?>" value="<?=$setting->value?>"/></td>
                                <td><?=$setting->description?></td>
				</tr>
                            <?php endif;?>
                            <?php endforeach;?>
			</tbody> 
			</table>
			</div>
		
			<div id="tab_site" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th>ID</th>
    				<th>Value</th>
                                <th>Description</th>
				</tr> 
			</thead> 
			<tbody> 
                            <?php foreach($settings->result() as $setting):?>
                            <?php if(strpos($setting->id,"site_") !== false):?>
				<tr> 
    				<td><?=$setting->id?></td> 
    				<td><input name="<?=$setting->id?>" value="<?=$setting->value?>"/></td>
                                <td><?=$setting->description?></td>
				</tr>
                            <?php endif;?>
                            <?php endforeach;?>
			</tbody> 
			</table>
			</div>
		
			<div id="tab_testmode" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th>ID</th>
    				<th>Value</th>
                                <th>Description</th>
				</tr> 
			</thead> 
			<tbody> 
                            <?php foreach($settings->result() as $setting):?>
                            <?php if(strpos($setting->id,"testmode_") !== false):?>
				<tr> 
    				<td><?=$setting->id?></td> 
    				<td><input name="<?=$setting->id?>" value="<?=$setting->value?>"/></td>
                                <td><?=$setting->description?></td>
				</tr>
                            <?php endif;?>
                            <?php endforeach;?>
			</tbody> 
			</table>
			</div>
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		<button class="save float-right inline-block" type="submit">Save all changes</button>
                </form>
		<?php else:?>
                    You do not have the permission to view this page
                <?php endif;?>
		<div class="spacer"></div>
	</section>