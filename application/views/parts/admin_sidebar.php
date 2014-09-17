<body>

	<header id="header">
		<hgroup>
			<h1 class="site_title"><a href="index.html">SourceDonates Admin</a></h1>
                        <h2 class="section_title">Dashboard</h2><div class="btn_view_site"><a href="<?php echo base_url() ?>">View Site</a></div>
		</hgroup>
	</header> <!-- end of header bar -->
	
	<section id="secondary_bar">
		<div class="user">
			<p><?=$admin->username?> (<a href="<?php echo base_url('admin/messages') ?>">3 Messages</a>)</p>
			<a class="logout_user" href="<?php echo base_url('index.php/admin/logout') ?>" title="Logout">Logout</a>
		</div>
		<div class="breadcrumbs_container">
			<article class="breadcrumbs"><a href="<?php echo base_url('index.php/admin/index') ?>">SourceDonates Admin</a> <div class="breadcrumb_divider"></div> <a class="current">Dashboard</a></article>
		</div>
	</section><!-- end of secondary bar -->
	
	<aside id="sidebar" class="column">
		<h3>Plans, Categories and Items</h3>
		<ul class="toggle">
			<li class="icn_new_article"><a href="<?php echo base_url('index.php/admin/plans') ?>">Plans</a></li>
			<li class="icn_edit_article"><a href="<?php echo base_url('index.php/admin/categories') ?>">Categories</a></li>
			<li class="icn_categories"><a href="<?php echo base_url('index.php/admin/items') ?>">Items</a></li>
			<!-- <li class="icn_tags"><a href="#">Tags</a></li> -->
		</ul>
		<h3>Stats</h3>
		<ul class="toggle">
			<li class="icn_new_article"><a href="<?php echo base_url('index.php/admin/charts/chart_donation') ?>">Donation Charts</a></li>
			<li class="icn_edit_article"><a href="<?php echo base_url('index.php/admin/charts/cgart_plan') ?>">Plan Charts</a></li>
			<li class="icn_categories"><a href="<?php echo base_url('index.php/admin/charts/chart_payment') ?>">Payment Charts</a></li>
			<!-- <li class="icn_tags"><a href="#">Tags</a></li> -->
		</ul>
		<h3>Donators</h3>
		<ul class="toggle">
			<li class="icn_add_user"><a href="<?php echo base_url('index.php/admin/donators_list') ?>">Donator list</a></li>
			<li class="icn_view_users"><a href="<?php echo base_url('index.php/admin/donators_approval') ?>">Approval list</a></li>
		</ul>
		<h3>Settings</h3>
		<ul class="toggle">
			<li class="icn_folder"><a href="<?php echo base_url('index.php/admin/settings_sd') ?>">SourceDonate Settings</a></li>
                        <?php if($this->config->item("integration_forum_enabled") === "1"): ?><li class="icn_folder"><a href="<?php echo base_url('index.php/admin/settings_forumgroups') ?>">Forum Group Settings</a></li> <?php endif; ?>
			<li class="icn_photo"><a href="<?php echo base_url('index.php/admin/settings_admins') ?>">Admins</a></li>
			<li class="icn_audio"><a href="<?php echo base_url('index.php/admin/wizards') ?>">Wizards</a></li>
		</ul>
		
		<footer>
			<hr />
			<p><strong>Copyright &copy; 2012 Sourcedonates</strong></p>
			<p>Theme by <a href="http://www.medialoot.com">MediaLoot</a></p>
		</footer>
	</aside><!-- end of sidebar -->