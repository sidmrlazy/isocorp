<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img style="width: 40%; height: auto" src="assets/in3sync-logo-navbar.png" alt="" srcset="">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a style="font-size: 24px; margin-right:25px !important" class="nav-link active" aria-current="page" href="index.php"><ion-icon name="home-outline"></ion-icon></a>
        </li>
        <li class="nav-item">
          <a style="font-size: 24px; margin-right:25px !important" class="nav-link" aria-current="page" href="logout.php"><ion-icon name="power-outline"></ion-icon></a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">Disabled</a>
        </li> -->
      </ul>
      <form class="d-flex" role="search">

        <p>
          <?php
          $loggedInUser = $_SESSION['isms_user_name'] ?? 'unknown';  // fallback if not set
          echo $loggedInUser;
          ?>
        </p>
      </form>
    </div>
  </div>
</nav>