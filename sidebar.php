
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-white elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="assets/logo.jpg" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Tiyo Pau's</span>
    </a>

    <div class="sidebar">

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-flat nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php if ($_SESSION["user_type"] == 'Client') { ?>

                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?php echo $title == 'Dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Service Offer</p>
                    </a>
                </li>
				
				<?php } else { ?>
				  <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?php echo $title == 'Dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Dashboard</p>
                    </a>
                </li>
				<?php }?>

                <?php if ($_SESSION["user_type"] == 'Superadmin') { ?>

                    <li class="nav-item">
                        <a href="events.php" class="nav-link <?php echo $title == 'Events' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-calendar"></i>
                            <p> Events</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="clients.php" class="nav-link <?php echo $title == 'Clients' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p> Clients</p>
                        </a>
                    </li>
                    
                    <!-- <li class="nav-item menu-open">
                        <a href="#" class="nav-link <?php echo 
                            $title == 'Events' || 
                            $title == 'Types' || 
                            $title == 'Reservation' || 
                            $title == 'Reviews' ||
                            $title == 'Pending' || 
                            $title == 'Quoted' || 
                            $title == 'Approved' || 
                            $title == 'Accepted' || 
                            $title == 'Reserved' || 
                            $title == 'Processing' || 
                            $title == 'Declined' || 
                            $title == 'Cancelled' || 
                            $title == 'Completed' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-calendar-day"></i>
                            <p>
                                Events
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview"> -->

                            <!-- <li class="nav-item">
                                <a href="reservation.php" class="nav-link <?php echo $title == 'Reservation' ? 'active' : '' ?>">
                                    <i class="far fa-calendar-check nav-icon"></i>
                                    <p>Reservation List</p>
                                </a>
                            </li> -->
                    


                            <!-- <li class="nav-item">
                                <a href="reviews.php" class="nav-link <?php echo $title == 'Reviews' ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-star"></i>
                                    <p> Reviews</p>
                                </a>
                            </li> -->

                            <!-- <li class="nav-item">
                                <a href="notifications.php" class="nav-link <?php echo $title == 'Notifications' ? 'active' : '' ?>">
                                    <i class="nav-icon fa fa-bell"></i>
                                    <p>
                                        Notifications
                                        <span class="right badge badge-danger notifications">0</span>
                                    </p>
                                </a>
                            </li> -->


                        <!-- </ul>
                    </li> -->

                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link <?php echo 
                            $title == 'Pending' || 
                            $title == 'Quoted' || 
                            $title == 'Approved' || 
                            $title == 'Accepted' || 
                            $title == 'Reserved' || 
                            $title == 'Processing' || 
                            $title == 'Declined' || 
                            $title == 'Cancelled' || 
                            $title == 'Completed' ? 
                            'active' : '' ?>">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                            Reservation
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="pending.php" class="nav-link <?php echo $title == 'Pending' ? 'active' : '' ?>">
                                    <i class="fa fa-calendar-plus nav-icon"></i>
                                    <p>Pending</p>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a href="approved.php" class="nav-link <?php echo $title == 'Approved' ? 'active' : '' ?>">
                                    <i class="fa fa-thumbs-up nav-icon"></i>
                                    <p>Approved</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="accepted.php" class="nav-link <?php echo $title == 'Accepted' ? 'active' : '' ?>">
                                    <i class="fa fa-calendar-day nav-icon"></i>
                                    <p>Accepted</p>
                                </a>
                            </li> -->
                            <li class="nav-item">
                                <a href="reserved.php" class="nav-link <?php echo $title == 'Reserved' ? 'active' : '' ?>">
                                    <i class="fa fa-calendar-week nav-icon"></i>
                                    <p>Reserved</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="quoted.php" class="nav-link <?php echo $title == 'Quoted' ? 'active' : '' ?>">
                                    <i class="fa fa-clipboard-list nav-icon"></i>
                                    <p>Quoted</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="payment.php" class="nav-link <?php echo $title == 'Payment' ? 'active' : '' ?>">
                                    <i class="fa fa-recycle nav-icon"></i>
                                    <p>Payment</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="completed.php" class="nav-link <?php echo $title == 'Completed' ? 'active' : '' ?>">
                                    <i class="fa fa-calendar-check nav-icon"></i>
                                    <p>Completed</p>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a href="cancelled.php" class="nav-link <?php echo $title == 'Cancelled' ? 'active' : '' ?>">
                                    <i class="fa fa-calendar-times nav-icon"></i>
                                    <p>Cancelled</p>
                                </a>
                            </li> -->
                            <li class="nav-item">
                                <a href="declined.php" class="nav-link <?php echo $title == 'Declined' ? 'active' : '' ?>">
                                    <i class="fa fa-thumbs-down nav-icon"></i>
                                    <p>Declined</p>
                                </a>
                            </li>
							
							  <li class="nav-item">
                                <a href="meeting.php" class="nav-link <?php echo $title == 'Meeting' ? 'active' : '' ?>">
                                    <i class="fa fa-calendar-minus nav-icon"></i>
                                    <p>Meeting</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="reviews.php" class="nav-link <?php echo $title == 'Reviews' ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-star"></i>
                                    <p> Reviews</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="equipment_list.php" class="nav-link <?php echo $title == 'Equipment List' ? 'active' : '' ?>">
                            <i class="far fa-list-alt nav-icon"></i>
                            <p>Equipment List</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="report.php" class="nav-link <?php echo $title == 'Report' ? 'active' : '' ?>">
                            <i class="nav-icon fa fa-list-ol"></i>
                            <p> Report</p>
                        </a>
                    </li>

                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link <?php echo $title == 'Products' || $title == 'Category' || $title == 'Settings' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                            Settings
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="category.php" class="nav-link <?php echo $title == 'Category' ? 'active' : '' ?>">
                                    <i class="fa fa-th nav-icon"></i>
                                    <p>Category</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="products.php" class="nav-link <?php echo $title == 'Products' ? 'active' : '' ?>">
                                    <i class="fa fa-list-ul nav-icon"></i>
                                    <p>Products</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="event_types.php" class="nav-link <?php echo $title == 'Event Types' ? 'active' : '' ?>">
                                    <i class="fa fa-calendar-day nav-icon"></i>
                                    <p>Event Types</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="settings.php" class="nav-link <?php echo $title == 'Settings' ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p> Service Offers</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- <li class="nav-item">
                        <a href="notifications.php" class="nav-link <?php echo $title == 'Notifications' ? 'active' : '' ?>">
                            <i class="nav-icon fa fa-bell"></i>
                            <p>
                                Notifications
                                <span class="right badge badge-danger ">0</span>
                            </p>
                        </a>
                    </li> -->

                <?php } else {?>

                    <!-- <li class="nav-item">
                        <a href="menu_list.php" class="nav-link <?php echo $title == 'Menu List' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-th"></i>
                            <p> Menu List</p>
                        </a>
                    </li> -->

                    <li class="nav-item">
                        <a href="reservation.php?data=Pending" class="nav-link <?php echo $title == 'Reservation' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-calendar-check"></i>
                            <p> Reservation</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="reviews.php" class="nav-link <?php echo $title == 'Reviews' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-star"></i>
                            <p> Reviews</p>
                        </a>
                    </li>
					
					 <li class="nav-item">
                        <a href="reports.php" class="nav-link <?php echo $title == 'Report' ? 'active' : '' ?>">
                            <i class="nav-icon fa fa-list-ol"></i>
                            <p> Report</p>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="#" class="nav-link btn_profile" data-toggle="modal" data-target="#profileModal">
                            <i class="nav-icon fas fa-user-circle"></i>
                            <p> Profile</p>
                        </a>
                    </li>

                    <!-- <li class="nav-item">
                        <a href="settings.php" class="nav-link <?php echo $title == 'Settings' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-cog"></i>
                            <p> Settings</p>
                        </a>
                    </li> -->

                    <!-- <li class="nav-item">
                        <a href="notifications.php" class="nav-link <?php echo $title == 'Notifications' ? 'active' : '' ?>">
                            <i class="nav-icon fa fa-bell"></i>
                            <p>
                                Notifications
                                <span class="right badge badge-danger notifications">0</span>
                            </p>
                        </a>
                    </li> -->

                    <li class="nav-item">
                        <a href="logout.php" class="nav-link">
                            <i class="nav-icon fa fa-power-off"></i>
                            <p> Logout</p>
                        </a>
                    </li>

                    <!-- <li class="nav-header">INFORMATION</li>
                    <li class="nav-item">
                        <a  class="nav-link">
                            <i class="nav-icon fas fa-street-view"></i>
                            <p> Biñan, Philippines</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link">
                            <i class="nav-icon fas fa-phone"></i>
                            <p> 0915 063 6457</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link">
                            <i class="nav-icon fas fa-envelope"></i>
                            <p> tiyopaus@gmail.com</p>
                        </a>
                    </li> -->
                
                <?php } ?>

            </ul>
        </nav>

    </div>

  </aside>