<?php
$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Guest');
$user_name = isset($_COOKIE['user_name']) ? $_COOKIE['user_name'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest');
$user_role = isset($_COOKIE['user_role']) ? $_COOKIE['user_role'] : (isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Guest');
include 'includes/connection.php';
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary custom-navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img src="assets/logo/isocorp_logo_white.png" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Work Area
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="security-incident-management.php">
                <ion-icon style="font-size: 20px !important;" name="lock-closed-outline"></ion-icon> Security Incident Management</a></li>
            <li>
              <a class="dropdown-item" href="mrb.php">
                <ion-icon style="font-size: 20px !important;" name="book-outline"></ion-icon> Management Review Board
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="asset-inventory.php">
                <ion-icon style="font-size: 20px !important;" name="desktop-outline"></ion-icon> Asset Inventory
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="risks-treatments.php">
                <ion-icon style="font-size: 20px !important;" name="flask-outline"></ion-icon> Risks & Treatments
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="corrective-actions.php">
                <ion-icon style="font-size: 20px !important;" name="construct-outline"></ion-icon> Corrective Actions & Improvements
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="staff-communications.php">
                <ion-icon style="font-size: 20px !important;" name="megaphone-outline"></ion-icon> Staff Communications
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="audit-program.php">
                <ion-icon style="font-size: 20px !important;" name="glasses-outline"></ion-icon> Audit Programme
              </a>
            </li>

            <li>
              <a class="dropdown-item" href="#">
                <ion-icon style="font-size: 20px !important;" name="shield-half-outline"></ion-icon> Applicable Legislations
              </a>
            </li>

          </ul>
        </li>
        <?php if ($user_role == '1') { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Settings
            </a>
            <ul class="dropdown-menu">
              <!-- <li><a class="dropdown-item" href="main-controls.php">Add Control</a></li>
              <li><a class="dropdown-item" href="sub-controls.php">Add Sub-Control</a></li>
              <li><a class="dropdown-item" href="linked-sub-controls.php">Add linked Sub-Control</a></li>
              <li><a class="dropdown-item" href="inner-linked-control-policy-form.php">Add Inner linked Sub-Control</a></li>
              <hr> -->
              <li><a class="dropdown-item" href="audit-program-setup.php">Audit Programme Setup</a></li>
              <hr>
              <li><a class="dropdown-item" href="user-setup.php">User Setup</a></li>
              <hr>
              <li><a class="dropdown-item" href="policy-setup.php">Policy Section Setup</a></li>
            </ul>
          </li>
        <?php } ?>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <ion-icon name="person-circle-outline"></ion-icon> <?php echo htmlspecialchars($user_name); ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="#"><ion-icon name="person-outline"></ion-icon> Profile</a></li>
            <li><a class="dropdown-item" href="logout.php"><ion-icon name="log-out-outline"></ion-icon> Logout</a></li>
          </ul>
        </li>
      </ul>

      <!-- <form class="d-flex" role="search">
        <a class="nav-link" aria-current="page" href="logout.php"><ion-icon name="log-out-outline"></ion-icon>
          Logout</a>
      </form> -->
    </div>
  </div>
</nav>