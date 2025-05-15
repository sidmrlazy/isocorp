<?php
include('includes/header.php');
include('includes/navbar.php');
include('includes/connection.php'); ?>
<div class="container" style="margin-top: 50px; margin-bottom: 30px;">
    <?php
    // Handle insert and update
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_or_update'])) {
        $ru_id = $_POST['ru_id'];
        $ru_name = $_POST['ru_name'];
        $ru_serv_prov = $_POST['ru_serv_prov'];
        $ru_modem = $_POST['ru_modem'];
        $ru_ipd = $_POST['ru_ipd'];
        $ru_dsp = $_POST['ru_dsp'];
        $ru_usp = $_POST['ru_usp'];
        $ru_sec_type = $_POST['ru_sec_type'];
        $ru_band = $_POST['ru_band'];

        $ru_image_path = "";
        if (!empty($_FILES["ru_image"]["name"])) {
            $target_dir = "assets/uploaded-image/";
            $ru_image_name = basename($_FILES["ru_image"]["name"]);
            $unique_name = time() . "_" . $ru_image_name;
            $target_file = $target_dir . $unique_name;

            if (move_uploaded_file($_FILES["ru_image"]["tmp_name"], $target_file)) {
                $ru_image_path = $unique_name;
            }
        }

        if (!empty($ru_id)) {
            // Update
            $update_sql = "UPDATE remote_user SET 
            ru_name='$ru_name',
            ru_serv_prov='$ru_serv_prov',
            ru_modem='$ru_modem',
            ru_ipd='$ru_ipd',
            ru_dsp='$ru_dsp',
            ru_usp='$ru_usp',
            ru_sec_type='$ru_sec_type',
            ru_band='$ru_band'";

            if (!empty($ru_image_path)) {
                $update_sql .= ", ru_image='$ru_image_path'";
            }

            $update_sql .= " WHERE ru_id='$ru_id'";
            mysqli_query($connection, $update_sql);
            echo "<div class='alert alert-success mb-3' role='alert'>User details updated successfully.</div>";
        } else {
            // Insert
            $insert_sql = "INSERT INTO remote_user (
            ru_name, ru_serv_prov, ru_modem, ru_ipd, 
            ru_dsp, ru_usp, ru_image, ru_sec_type, ru_band
        ) VALUES (
            '$ru_name', '$ru_serv_prov', '$ru_modem', '$ru_ipd',
            '$ru_dsp', '$ru_usp', '$ru_image_path', '$ru_sec_type', '$ru_band'
        )";
            mysqli_query($connection, $insert_sql);
            echo "<div class='alert alert-success mb-3' role='alert'>User details added successfully.</div>";
        }
    }
    ?>

    <!-- Modal for Add/Edit -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="remoteUserForm" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Remote User Details</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="ru_id" id="ru_id">

                        <div class="mb-3">
                            <label for="ru_name" class="form-label">Name</label>
                            <input name="ru_name" id="ru_name" type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="ru_serv_prov" class="form-label">Service Provider</label>
                            <input name="ru_serv_prov" id="ru_serv_prov" type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="ru_modem" class="form-label">Modem/Router</label>
                            <input name="ru_modem" id="ru_modem" type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="ru_ipd" class="form-label">IP Dynamic</label>
                            <input name="ru_ipd" id="ru_ipd" type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="ru_dsp" class="form-label">Download Speed</label>
                            <input name="ru_dsp" id="ru_dsp" type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="ru_usp" class="form-label">Upload Speed</label>
                            <input name="ru_usp" id="ru_usp" type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="ru_image" class="form-label">Image</label>
                            <input name="ru_image" id="ru_image" type="file" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="ru_sec_type" class="form-label">Security Type</label>
                            <input name="ru_sec_type" id="ru_sec_type" type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="ru_band" class="form-label">Bandwidth</label>
                            <input name="ru_band" id="ru_band" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="save_or_update" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add New Button -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="resetModal()">Add Remote User</button>

    <!-- Remote User Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Service Provider</th>
                <th>Modem</th>
                <th>Internet Plan</th>
                <th>Download Speed</th>
                <th>Upload Speed</th>
                <th>Image</th>
                <th>Security Type</th>
                <th>Bandwidth</th>
                <th>Action</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php

            if (isset($_POST['delete'])) {
                $fetched_ru_id = $_POST['fetched_ru_id'];
                $delete_query = "DELETE FROM remote_user WHERE ru_id = '$fetched_ru_id'";
                mysqli_query($connection, $delete_query);
            }


            $select_query = "SELECT * FROM remote_user";
            $result = mysqli_query($connection, $select_query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['ru_name']}</td>";
                echo "<td>{$row['ru_serv_prov']}</td>";
                echo "<td>{$row['ru_modem']}</td>";
                echo "<td>{$row['ru_ipd']}</td>";
                echo "<td>{$row['ru_dsp']}</td>";
                echo "<td>{$row['ru_usp']}</td>";
                echo "<td><img src='assets/uploaded-image/{$row['ru_image']}' width='50'></td>";
                echo "<td>{$row['ru_sec_type']}</td>";
                echo "<td>{$row['ru_band']}</td>";
                echo "<td><button class='btn btn-sm btn-primary' onclick='openEditModal(" . json_encode($row) . ")'>Edit</button></td>";
                echo "<td>
        <form method='POST' onsubmit=\"return confirm('Are you sure you want to delete this user?');\">
            <input type='hidden' name='fetched_ru_id' value='{$row['ru_id']}'>
            <button type='submit' name='delete' class='btn btn-sm btn-danger'>Delete</button>
        </form>
      </td>";

                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script>
    // Reset modal for "Add"
    function resetModal() {
        document.getElementById('remoteUserForm').reset();
        document.getElementById('ru_id').value = '';
    }

    // Fill modal for "Edit"
    function openEditModal(data) {
        document.getElementById('ru_id').value = data.ru_id;
        document.getElementById('ru_name').value = data.ru_name;
        document.getElementById('ru_serv_prov').value = data.ru_serv_prov;
        document.getElementById('ru_modem').value = data.ru_modem;
        document.getElementById('ru_ipd').value = data.ru_ipd;
        document.getElementById('ru_dsp').value = data.ru_dsp;
        document.getElementById('ru_usp').value = data.ru_usp;
        document.getElementById('ru_sec_type').value = data.ru_sec_type;
        document.getElementById('ru_band').value = data.ru_band;

        var modal = new bootstrap.Modal(document.getElementById('exampleModal'));
        modal.show();
    }
</script>

<?php include('includes/footer.php'); ?>