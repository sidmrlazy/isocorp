<div class="mt-3">
    <div class="table-responsive">
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Policy Name</th>
                    <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Sub Policy Name</th>
                    <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Linked Policy</th>
                    <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Inner Linked Policy</th>
                    <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Status</th>
                    <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'includes/connection.php';
                $get_data = "SELECT * FROM controls";
                $get_data_r = mysqli_query($connection, $get_data);
                while ($row = mysqli_fetch_assoc($get_data_r)) {
                    $control_id = $row['control_id'];
                    $control_name = $row['control_name'];
                    $control_linked_1 = $row['control_linked_1'];
                    $control_linked_2 = $row['control_linked_2'];
                    $control_linked_3 = $row['control_linked_3'];
                    $control_status = $row['control_status'];
                ?>
                    <tr>
                        <th style="font-size: 12px !important; font-weight: bold !important;" scope="row"><?php echo $control_name ?></th>
                        <td style="font-size: 12px;"><?php echo $control_linked_1 ?></td>
                        <td style="font-size: 12px;"><?php echo $control_linked_2 ?></td>
                        <td style="font-size: 12px;"><?php echo $control_linked_3 ?></td>
                        <td style="font-size: 12px;"><?php echo $control_status ?></td>
                        <td>
                            <a href="edit-policy-details.php?control_id=<?php echo $control_id; ?>" class="btn btn-sm btn-outline-success" style="font-size: 12px !important;">
                                View Policy Document
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

