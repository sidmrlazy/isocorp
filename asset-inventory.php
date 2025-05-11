<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';
include 'includes/config.php';
?>
<div class="dashboard-container">
    <div class="screen-name-container">
        <h1>ASSET INVENTORY</h1>
        <h2><a href="dashboard.php">Dashboard</a> > Asset Inventory</h2>
    </div>

    <?php
    if (isset($_POST['delete-asset'])) {
        $asset_id = $_POST['asset_id'];
        $del = "DELETE FROM asset WHERE asset_id = '$asset_id'";
        $del_r = mysqli_query($connection, $del);
    }

    if (isset($_POST['add-item'])) {
        $asset_name = mysqli_real_escape_string($connection, $_POST['asset_name']);
        $insert = "INSERT INTO `asset`(`asset_name`, `asset_created_by`) VALUES ('$asset_name', '$user_name')";
        $insert_r = mysqli_query($connection, $insert);
    }
    ?>

    <!-- =========== IF USER IS ADMIN =========== -->
    <?php if ($user_role == '1') { ?>
        <div class="d-flex justify-content-end align-items-center">
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addNewItem">
                <ion-icon name="add-circle-outline"></ion-icon> New Item
            </button>
        </div>
        <!-- =========== IF USER IS READ ONLY =========== -->
    <?php } elseif ($user_role == '2') { ?>
        <div class="d-none justify-content-end align-items-center">
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addNewItem">
                <ion-icon name="add-circle-outline"></ion-icon> New Item
            </button>
        </div>
    <?php } ?>
    <!-- ============ ADD NEW ITEM MODAL ============ -->
    <div class="modal fade" id="addNewItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="" method="POST" class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">New Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" name="asset_name" class="form-control" id="floatingInput" placeholder="">
                        <label for="floatingInput">Item name</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add-item" class="btn btn-outline-success btn-sm">Add</button>
                </div>
            </form>
        </div>
    </div>
    <?php
    $fetch = "SELECT * FROM asset";
    $fetch_r = mysqli_query($connection, $fetch);
    $count = mysqli_num_rows($fetch_r);
    if ($count > 0) {
    ?>
        <div class="table-responsive table-container mt-3 mb-5">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="asset-table-heading" style="font-weight: 600 !important; font-size: 12px !important;" scope="col">ID</th>
                        <th class="asset-table-heading" style="font-weight: 600 !important; font-size: 12px !important;" scope="col">Name</th>
                        <th class="asset-table-heading" style="font-weight: 600 !important; font-size: 12px !important;" scope="col">Status</th>
                        <th class="asset-table-heading" style="font-weight: 600 !important; font-size: 12px !important;" scope="col">Financial Value</th>
                        <th class="asset-table-heading" style="font-weight: 600 !important; font-size: 12px !important;" scope="col">Type</th>
                        <th class="asset-table-heading" style="font-weight: 600 !important; font-size: 12px !important;" scope="col">Classification</th>
                        <th class="asset-table-heading" style="font-weight: 600 !important; font-size: 12px !important;" scope="col">Location</th>
                        <th class="asset-table-heading" style="font-weight: 600 !important; font-size: 12px !important;" scope="col">Legal Owner</th>
                        <th class="asset-table-heading" style="font-weight: 600 !important; font-size: 12px !important;" scope="col">Owner/Lead</th>
                        <th class="asset-table-heading" style="font-weight: 600 !important; font-size: 12px !important;" scope="col">Assigned to </th>
                        <th class="asset-table-heading" style="font-weight: 600 !important; font-size: 12px !important;" scope="col">Created on</th>
                        <!-- <th class="asset-table-heading" style="font-weight: 600 !important; font-size: 12px !important;" scope="col">Created by</th> -->
                        <th class="asset-table-heading" style="font-weight: 600 !important; font-size: 12px !important;" scope="col">Review Date</th>
                        <th class="asset-table-heading" style="font-weight: 600 !important; font-size: 12px !important;" scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($fetch_r)) {
                        $asset_id = $row['asset_id'];
                        $asset_name = $row['asset_name'];
                        $asset_status = $row['asset_status'];
                        $asset_value = $row['asset_value'];
                        $asset_type = $row['asset_type'];
                        $asset_classification = $row['asset_classification'];
                        $asset_location = $row['asset_location'];
                        $asset_owner_legal = $row['asset_owner_legal'];
                        $asset_owner = $row['asset_owner'];
                        $asset_assigned_to = $row['asset_assigned_to'];
                        $asset_created_date = $row['asset_created_date'];
                        $asset_review_date = $row['asset_review_date'];
                        $asset_created_by = $row['asset_created_by'];
                    ?>
                        <tr>
                            <th style="font-size: 12px !important;" class="asset-data" scope="row"><?php echo $asset_id ?></th>
                            <td class="asset-data">
                                <a href="asset-details.php?id=<?php echo $asset_id; ?>"><?php echo $asset_name; ?></a>
                            </td>
                            <td class="asset-data"><?php echo $asset_status ?></td>
                            <td class="asset-data"><?php echo $asset_value ?></td>
                            <td class="asset-data"><?php echo $asset_type ?></td>
                            <td class="asset-data"><?php echo $asset_classification ?></td>
                            <td class="asset-data"><?php echo $asset_location ?></td>
                            <td class="asset-data"><?php echo $asset_owner_legal ?></td>
                            <td class="asset-data"><?php echo $asset_owner ?></td>
                            <td class="asset-data"><?php echo $asset_assigned_to ?></td>
                            <td class="asset-data"><?php echo $asset_created_date ?></td>
                            <!-- <td class="asset-data"><?php echo $asset_created_by ?></td> -->
                            <td class="asset-data"><?php echo $asset_review_date ?></td>
                            <td class="text-center">
                                <form action="" method="POST">
                                    <input type="text" name="asset_id" value="<?php echo $asset_id ?>" hidden>
                                    <button type="submit" name="delete-asset" class="btn btn-sm btn-outline-dark">
                                        <ion-icon name="close-circle-outline"></ion-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-danger mt-3 mb-3" role="alert">
            No item has been added!
        </div>

    <?php } ?>
</div>

<?php include 'includes/footer.php' ?>