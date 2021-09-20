<div id="navigation">
	<div class="container-fluid">
		<a href="#" id="brand"><?php echo $patchworks->customerName; ?></a>
		<a href="#" class="toggle-nav" rel="tooltip" data-placement="bottom" title="Toggle navigation"><i class="icon-reorder"></i></a>
		<ul class='main-nav'>
			
			<?php if ( strpos( $_SESSION['s_accstr'], 'website' ) !== false ) { ?>
			<li>
				<a href="#" data-toggle="dropdown" class='dropdown-toggle'>
					<i class="icon-edit"></i>
					<span>Website</span>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					
					<?php if (in_array('website:sitemap', $_SESSION['s_usracc'])) { ?>
					<li>
						<a href="website/sitemap.php">Sitemap</a>
					</li>
					<?php } ?>

                    <li class="dropdown-submenu">
                        <a href="#">Articles</a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="website/articles.php">Articles</a>
                            </li>
                            <li>
                                <a href="website/article-category.php">Article Categories</a>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown-submenu">
                        <a href="#">Website Content</a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="custom/testimonials/testimonials.php">Testimonials</a>
                            </li>
                            <li>
                                <a href="gallery/galleries.php">Galleries</a>
                            </li>
                            <li>
                                <a href="gallery/globalgallery.php">Global Gallery</a>
                            </li>
                            <li>
                                <a href="gallery/optimiseimages.php">Optimise Images</a>
                            </li>
                            <li>
                                <a href="downloads/libraries.php">Downloads</a>
                            </li>
                            <li>
                                <a href="website/forms.php">Forms</a>
                            </li>
                            <li>
                                <a href="website/generic-content.php">Generic Content</a>
                            </li>
                            <li>
                                <a href="website/templates.php">Templates</a>
                            </li>
                            <li>
                                <a href="website/copycontent.php">Copy Content</a>
                            </li>
                        </ul>
                    </li>


                    <li class="dropdown-submenu">
                        <a href="#">Extras</a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="locations/locations.php">Locations</a>
                            </li>
                            <li>
                                <a href="employees/employees.php">Employees</a>
                            </li>
                            <li>
                                <a href="reviews/reviews.php">Reviews</a>
                            </li>
                            <li>
                                <a href="hotspots/hotspots.php">Hot Spots</a>
                            </li>
                            <li>
                                <a href="tempobjects/tempobjects.php">Objects</a>
                            </li>
                        </ul>
                    </li>



				</ul>
			</li>
			<?php } ?>

            <?php if (1==2) { ?>


            <li>
                <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                    <i class="icon-tasks"></i>
                    <span>Custom</span>
                    <span class="caret"></span>
                </a>


                <ul class="dropdown-menu">

                    <li>
                        <a href="custom/services.php">History</a>
                    </li>

<!--                    <li class="dropdown-submenu">-->
<!--                        <a href="#">Product Details</a>-->
<!--                        <ul class="dropdown-menu">-->
<!--                            <li>-->
<!--                                <a href="products/structure.php">Shop Structure</a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href="products/productgroups.php">Product Groups</a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href="products/product-category.php">Product Categories</a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </li>-->

                </ul>

            </li>

            <?php } ?>


            <li>
                <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                    <i class="icon-shopping-cart"></i>
                    <span>Products</span>
                    <span class="caret"></span>
                </a>

                <ul class="dropdown-menu">

                    <li>
                        <a href="products/producttypes.php">Products</a>
                    </li>

                    <li class="dropdown-submenu">
                        <a href="#">Product Details</a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="products/structure.php">Shop Structure</a>
                            </li>
                            <li>
                                <a href="products/productgroups.php">Product Groups</a>
                            </li>
                            <li>
                                <a href="products/product-category.php">Product Categories</a>
                            </li>
                        </ul>
                    </li>

                </ul>

            </li>





<!--            <li>-->
<!--                <a href="#" data-toggle="dropdown" class="dropdown-toggle">-->
<!--                    <i class="icon-calendar"></i>-->
<!--                    <span>Booking System</span>-->
<!--                    <span class="caret"></span>-->
<!--                </a>-->
<!--                <ul class="dropdown-menu">-->
<!---->
<!--                    <li>-->
<!--                        <a href="events/calendar.php">Calendar</a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a href="events/venues.php">Venues</a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a href="events/events.php">Events</a>-->
<!--                    </li>-->
<!---->
<!--                </ul>-->
<!--            </li>-->

			
			<?php if ( strpos( $_SESSION['s_accstr'], 'system' ) !== false ) { ?>
			<li>
				<a href="#" data-toggle="dropdown" class='dropdown-toggle'>
					<i class="icon-cogs"></i>
					<span>System</span>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<?php if (in_array('system:users', $_SESSION['s_usracc'])) { ?>
					<li>
						<a href="system/users.php">Users</a>
					</li>
					<?php } ?>
					<?php if (in_array('system:categories', $_SESSION['s_usracc'])) { ?>
					<li>
						<a href="system/categories.php">Categories</a>
					</li>
					<?php } ?>


					<?php if (in_array('system:filetree', $_SESSION['s_usracc'])) { ?>
					<li>
						<a href="system/filetree.php">Filetree</a>
					</li>
					<?php } ?>


                    <li>
                        <a href="gallery/rebuildgalleries.php" target="_blank">Rebuild Galleries</a>
                    </li>

				</ul>
			</li>
			<?php } ?>
			
		</ul>
		<div class="user">
			<ul class="icon-nav">
			
				<?php
				if (false && is_array($messages) && count($messages) > 0) {
				
				require_once("../attributes/classes/attrgroups.cls.php");
				require_once("../attributes/classes/attrvalues.cls.php");
				
				$TmpAtr = new AtrDAO();
				$TmpAtv = new AtvDAO();
				
				?>
			
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-envelope-alt"></i><span class="label label-lightred"><?php echo count($messages); ?></span></a>
					<ul class="dropdown-menu pull-right message-ul">
					
						<?php
						for ($m=0;$m<count($messages);$m++) {
						
						$resultSet = $TmpAtv->selectValueSet($messages[$m]['atr_id'], $messages[$m]['tblnam'], $messages[$m]['tbl_id'], NULL, NULL, false);
						$attrGroupRec = $TmpAtr->select($messages[$m]['atr_id'], NULL, NULL, NULL, true);
						
						?>
					
						<li>
							<a href="website/webcontacts.php">
								<!--<img src="img/demo/user-1.jpg" alt="">-->
								<div class="details">
									<div class="name">Web Contact</div>
									<div class="message">
										<?php echo ($attrGroupRec) ? $attrGroupRec->atrnam : $messages[$m]['msgtxt']; ?>
									</div>
								</div>
							</a>
						</li>
						
						<?php
						}
						?>
						
						<li>
							<a href="website/webcontacts.php" class="more-messages">Go to Message center <i class="icon-arrow-right"></i></a>
						</li>
					</ul>
				</li>
				
				<?php 
				}
				?>
				
<!--				<li class="dropdown sett">-->
<!--					<a href="#" class='dropdown-toggle' data-toggle="dropdown"><i class="icon-cog"></i></a>-->
<!--					<ul class="dropdown-menu pull-right theme-settings">-->
<!--						<li>-->
<!--							<span>Layout-width</span>-->
<!--							<div class="version-toggle">-->
<!--								<a href="#" class='set-fixed'>Fixed</a>-->
<!--								<a href="#" class="active set-fluid">Fluid</a>-->
<!--							</div>-->
<!--						</li>-->
<!--						<li>-->
<!--							<span>Topbar</span>-->
<!--							<div class="topbar-toggle">-->
<!--								<a href="#" class='set-topbar-fixed'>Fixed</a>-->
<!--								<a href="#" class="active set-topbar-default">Default</a>-->
<!--							</div>-->
<!--						</li>-->
<!--						<li>-->
<!--							<span>Sidebar</span>-->
<!--							<div class="sidebar-toggle">-->
<!--								<a href="#" class='set-sidebar-fixed'>Fixed</a>-->
<!--								<a href="#" class="active set-sidebar-default">Default</a>-->
<!--							</div>-->
<!--						</li>-->
<!--					</ul>-->
<!--				</li>-->
<!--				<li class='dropdown colo'>-->
<!--					<a href="#" class='dropdown-toggle' data-toggle="dropdown"><i class="icon-tint"></i></a>-->
<!--					<ul class="dropdown-menu pull-right theme-colors">-->
<!--						<li class="subtitle"> Predefined colors </li>-->
<!--						<li>-->
<!--							<span class='red'></span>-->
<!--							<span class='orange'></span>-->
<!--							<span class='green'></span>-->
<!--							<span class="brown"></span>-->
<!--							<span class="blue"></span>-->
<!--							<span class='lime'></span>-->
<!--							<span class="teal"></span>-->
<!--							<span class="purple"></span>-->
<!--							<span class="pink"></span>-->
<!--							<span class="magenta"></span>-->
<!--							<span class="grey"></span>-->
<!--							<span class="darkblue"></span>-->
<!--							<span class="lightred"></span>-->
<!--							<span class="lightgrey"></span>-->
<!--							<span class="satblue"></span>-->
<!--							<span class="satgreen"></span>-->
<!--						</li>-->
<!--					</ul>-->
<!--				</li>-->
<!--				<li>-->
<!--					<a href="more-locked.html" class='lock-screen' rel='tooltip' title="Lock screen" data-placement="bottom"><i class="icon-lock"></i></a>-->
<!--				</li>-->
				
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i></a>
					<ul class="dropdown-menu pull-right">
						<li>
							<a href="system/user-account.php">User account</a>
						</li>
						<li>
							<a href="logout.php">Sign out</a>
						</li>
					</ul>
				</li>
				
			</ul>
		</div>
	</div>
</div>
