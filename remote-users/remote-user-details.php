<?php
include 'includes/header.php';
include 'includes/connection.php';
?>
<div class="container">
    <div class="mt-5">
        <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="submit" name="add-details" class="btn btn-sm btn-success">Add Details</button>
    </div>

    <!-- ======== ADD DETAIL MODAL ======== -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="font-size: 16px !important; font-weight: 600 !important">Add remote user details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $ru_name = $_POST['ru_name'];
                        $ru_serv_prov = $_POST['ru_serv_prov'];
                        $ru_modem = $_POST['ru_modem'];
                        $ru_ipd = $_POST['ru_ipd']; // Text input for internet plan
                        $ru_dsp = $_POST['ru_dsp'];
                        $ru_usp = $_POST['ru_usp'];
                        $ru_sec_type = $_POST['ru_sec_type'];
                        $ru_band = $_POST['ru_band'];

                        // ======== IMAGE UPLOAD ========
                        $target_dir = "assets/uploaded-image/";
                        $ru_image_name = basename($_FILES["ru_image"]["name"]);
                        $target_file = $target_dir . time() . "_" . $ru_image_name;

                        if (move_uploaded_file($_FILES["ru_image"]["tmp_name"], $target_file)) {
                            $ru_image_path = $target_file;
                        } else {
                            echo "<script>alert('Error uploading image.');</script>";
                            $ru_image_path = "";
                        }

                        // ======== INSERT QUERY ========
                        $sql = "INSERT INTO remote_user (
                            ru_name, ru_serv_prov, ru_modem, ru_ipd, 
                            ru_dsp, ru_usp, ru_image, ru_sec_type, ru_band
                        ) VALUES (
                            '$ru_name', '$ru_serv_prov', '$ru_modem', '$ru_ipd',
                            '$ru_dsp', '$ru_usp', '$ru_image_path', '$ru_sec_type', '$ru_band'
                        )";

                        if (mysqli_query($connection, $sql)) {
                            echo "<script>alert('Remote user added successfully');</script>";
                        } else {
                            echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
                        }
                    }
                    ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="ru_name" class="form-label" style="font-size: 12px;">Name</label>
                            <input name="ru_name" type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="ru_serv_prov" class="form-label" style="font-size: 12px;">Service Provider</label>
                            <input name="ru_serv_prov" type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="ru_modem" class="form-label" style="font-size: 12px;">Modem</label>
                            <input name="ru_modem" type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="ru_ipd" class="form-label" style="font-size: 12px;">Internet Plan Details</label>
                            <input name="ru_ipd" type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="ru_dsp" class="form-label" style="font-size: 12px;">Download Speed</label>
                            <input name="ru_dsp" type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="ru_usp" class="form-label" style="font-size: 12px;">Upload Speed</label>
                            <input name="ru_usp" type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="ru_image" class="form-label" style="font-size: 12px;">Device Image</label>
                            <input name="ru_image" type="file" class="form-control" style="font-size: 12px;" required>
                        </div>
                        <div class="mb-3">
                            <label for="ru_sec_type" class="form-label" style="font-size: 12px;">Security Type</label>
                            <select name="ru_sec_type" class="form-select" required>
                                <option selected disabled>Open this select menu</option>
                                <option value="WEP">WEP</option>
                                <option value="WPA">WPA</option>
                                <option value="WPA2">WPA2</option>
                                <option value="WPA3">WPA3</option>
                                <option value="OPEN">OPEN</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ru_band" class="form-label" style="font-size: 12px;">WiFi Band</label>
                            <select name="ru_band" class="form-select" required>
                                <option selected disabled>Open this select menu</option>
                                <option value="Dual Band (2.4Ghz/5Ghz)">Dual Band (2.4Ghz/5Ghz)</option>
                                <option value="2.4Ghz">2.4Ghz</option>
                                <option value="5Ghz">5Ghz</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ======== DISPLAY TABLE ======== -->
    <div class="table-container mt-4">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="font-size: 12PX;">S.NO</th>
                        <th style="font-size: 12PX;">NAME</th>
                        <th style="font-size: 12PX;">SERVICE PROVIDER</th>
                        <th style="font-size: 12PX;">MODEM</th>
                        <th style="font-size: 12PX;">INTERNET PLAN</th>
                        <th style="font-size: 12PX;">DOWNLOAD SPEED</th>
                        <th style="font-size: 12PX;">UPLOAD SPEED</th>
                        <th style="font-size: 12PX;">DEVICE IMAGE</th>
                        <th style="font-size: 12PX;">SECURITY TYPE</th>
                        <th style="font-size: 12PX;">WI-FI BAND</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $fetch = "SELECT * FROM remote_user";
                    $fetch_r = mysqli_query($connection, $fetch);
                    $count = 1;
                    while($row = mysqli_fetch_assoc($fetch_r)) {
                        echo "<tr>
                            <td>{$count}</td>
                            <td>{$row['ru_name']}</td>
                            <td>{$row['ru_serv_prov']}</td>
                            <td>{$row['ru_modem']}</td>
                            <td>{$row['ru_ipd']}</td>
                            <td>{$row['ru_dsp']}</td>
                            <td>{$row['ru_usp']}</td>
                            <td><img src='{$row['ru_image']}' width='60' alt='Device Image'></td>
                            <td>{$row['ru_sec_type']}</td>
                            <td>{$row['ru_band']}</td>
                        </tr>";
                        $count++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
