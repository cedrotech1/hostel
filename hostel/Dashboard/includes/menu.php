<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <!-- View Statistics -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#icons-nav0" data-bs-toggle="collapse" href="#">
                <i class="bi bi-bar-chart-line"></i><span>View Statistics</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="icons-nav0" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a class="nav-link" href="index.php">
                        <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link collapsed" href="statistics.php">
                        <i class="bi bi-file-earmark-bar-graph"></i><span>Statistics Documentation</span>
                    </a>
                </li>

                <li>
                    <a class="nav-link collapsed" href="uploadFiles.php">
                        <i class="bi bi-file-earmark-bar-graph"></i><span>Shared documents</span>
                    </a>
                </li>
            </ul>
        </li> -->

        <!-- Manage Data (Admin Only) -->
        <?php if ($_SESSION['role'] == 'admin') { ?>
            <li class="nav-item">
                <a class="nav-link collapsed" href="add_user.php">
                    <i class="bi bi-person"></i><span>Manage Users</span>
                </a>
            </li>

            <!-- Settings -->
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#icons-nav10" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-gear"></i><span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="icons-nav10" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                    <li>
                        <a class="nav-link collapsed" href="system.php">
                            <i class="bi bi-tools"></i><span>System settings</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link collapsed" href="users-profile.php">
                            <i class="bi bi-person"></i><span>Profile</span>
                        </a>
                    </li>

                    <li>
                        <a class="nav-link collapsed" href="download.php">
                            <i class="bi bi-person"></i><span>backup data file</span>
                        </a>
                    </li>
                </ul>
            </li>
        <?php } ?>


        <?php if ($_SESSION['role'] == 'information_modifier') { ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-folder"></i><span>Manage Data</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="icons-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a class="nav-link collapsed" href="add_data.php">
                            <i class="bi bi-person-plus"></i><span>Add Student Info</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link collapsed" href="add_hostel.php">
                            <i class="bi bi-person-plus"></i><span>upload hostels</span>
                        </a>
                    </li>

                    <li>
                        <a class="nav-link collapsed" href="hostelslist.php">
                            <i class="bi bi-person-plus"></i><span>list of  hostels</span>
                        </a>
                    </li>
                   
                    <li>
                        <a class="nav-link collapsed" href="updateinfo.php">
                            <i class="bi bi-pencil-square"></i><span>Update Info</span>
                        </a>
                    </li>

                    <li>
                        <a class="nav-link collapsed" href="allstudents.php">
                            <i class="bi bi-card-heading"></i><span>All students</span>
                        </a>
                    </li>

                    <li>
                        <a class="nav-link collapsed text-danger" href="cleardata.php">
                            <i class="bi bi-trash"></i><span class="text-danger">Delete All System Data</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Manage Student Cards -->
          



            <!-- Settings -->
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#icons-nav10" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-gear"></i><span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="icons-nav10" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                
                    <li>
                        <a class="nav-link collapsed" href="users-profile.php">
                            <i class="bi bi-person"></i><span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link collapsed" href="setexcel.php">
                            <i class="bi bi-card-list"></i><span>Set Excel</span>
                        </a>
                    </li>
                </ul>
            </li>
        <?php } ?>


        <?php if ($_SESSION['role'] == 'cards_manager') { ?>

            <!-- Manage Student Cards -->
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#icons-nav3" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-person-badge"></i><span>Manage Student Cards</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="icons-nav3" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a class="nav-link collapsed" href="view_cards.php">
                            <i class="bi bi-eye"></i><span>View Cards with Pictures</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link collapsed" href="others.php">
                            <i class="bi bi-eye"></i><span>Others(Masters,....)</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link collapsed" href="allcards.php">
                            <i class="bi bi-card-heading"></i><span>All Cards</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link collapsed" href="get_card_by.php">
                            <i class="bi bi-search"></i><span>Search by</span>
                        </a>
                    </li>

                    <li>
                        <a class="nav-link collapsed" href="searchimages.php">
                            <i class="bi bi-search"></i><span>Search images</span>
                        </a>
                    </li>

                    <li>
                        <a class="nav-link collapsed" href="get_specific_cards.php">
                            <i class="bi bi-search"></i><span>Get specific card/s</span>
                        </a>
                    </li>

                    <li>
                        <a class="nav-link collapsed" href="huye.php">
                            <i class="bi bi-geo-alt"></i><span>Huye Registrar Back</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Manage Exam Cards -->
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#icons-nav1" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-journal"></i><span>Manage Exam Cards</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="icons-nav1" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a class="nav-link collapsed" href="exam_card.php">
                            <i class="bi bi-file-text"></i><span>All Exam Cards</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link collapsed" href="get_exam_card_by.php">
                            <i class="bi bi-search"></i><span>Get Exam Cards By</span>
                        </a>
                    </li>

                    <li>
                        <a class="nav-link collapsed" href="get_specific_Exam_cards.php">
                            <i class="bi bi-search"></i><span>Get specific card/s</span>
                        </a>
                    </li>



                </ul>
            </li>

            <!-- Manage Rejected Cards -->
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#icons-nav2" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-x-circle"></i><span>Manage Rejected Cards</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="icons-nav2" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a class="nav-link collapsed" href="rejected.php">
                            <i class="bi bi-eye-slash"></i><span>View Rejected Cards</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link collapsed" href="accepted.php">
                            <i class="bi bi-check-circle"></i><span>View Re-Accepted Cards</span>
                        </a>
                    </li>

                    <li>
                        <a class="nav-link collapsed" href="rejecting.php">
                            <i class="bi bi-x-circle"></i><span>Rejecting/accepting</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Settings -->
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#icons-nav10" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-gear"></i><span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="icons-nav10" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                    <li>
                        <a class="nav-link collapsed" href="users-profile.php">
                            <i class="bi bi-person"></i><span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link collapsed" href="messages.php">
                            <i class="bi bi-envelope"></i><span>Messages</span>
                        </a>
                    </li>
                    <!-- settings -->
                   


                </ul>
            </li>
        <?php } ?>
    </ul>
</aside>