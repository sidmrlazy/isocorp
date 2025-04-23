<?php
include 'includes/config.php';
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
            <li><a class="dropdown-item" href="security-incident-management.php"><ion-icon
                  name="lock-closed-outline"></ion-icon> Security Incident Management</a></li>
            <li>
              <a class="dropdown-item" href="mrb.php">
                <ion-icon name="book-outline"></ion-icon> Management Review Board
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="asset-inventory.php">
                <ion-icon name="desktop-outline"></ion-icon> Asset Inventory
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="risks-treatments.php">
                <ion-icon name="flask-outline"></ion-icon> Risks & Treatments
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="corrective-actions.php">
                <ion-icon name="construct-outline"></ion-icon> Corrective Actions & Improvements
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="audit-program.php">
                <ion-icon name="clipboard-outline"></ion-icon> Audit Programme
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

              <li><a class="dropdown-item" href="main-controls.php">Add Control</a></li>
              <li><a class="dropdown-item" href="sub-controls.php">Add Sub-Control</a></li>
              <li><a class="dropdown-item" href="linked-sub-controls.php">Add linked Sub-Control</a></li>
              <li><a class="dropdown-item" href="inner-linked-control-policy-form.php">Add Inner linked Sub-Control</a></li>
              <hr>
              <li><a class="dropdown-item" href="audit-program-setup.php">Audit Programme Setup</a></li>
              <hr>
              <li><a class="dropdown-item" href="user-setup.php">User Setup</a></li>
            </ul>
          </li>
        <?php } elseif ($user_role == '2') { ?>
          <li class="nav-item dropdown d-none">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Settings
            </a>
            <ul class="dropdown-menu">

              <li><a class="dropdown-item" href="main-controls.php">Add Control</a></li>
              <li><a class="dropdown-item" href="sub-controls.php">Add Sub-Control</a></li>
              <li><a class="dropdown-item" href="linked-sub-controls.php">Add linked Sub-Control</a></li>
              <li><a class="dropdown-item" href="inner-linked-control-policy-form.php">Add Inner linked Sub-Control</a></li>
              <hr>
              <li><a class="dropdown-item" href="add-mrb-topic.php">Management Review Board Setup</a></li>
              <hr>
              <li><a class="dropdown-item" href="user-setup.php">User Setup</a></li>
            </ul>
          </li>
        <?php } ?>
      </ul>
      <form class="d-flex" role="search">
        <a class="nav-link" aria-current="page" href="logout.php"><ion-icon name="log-out-outline"></ion-icon>
          Logout</a>
      </form>
    </div>
  </div>
</nav>