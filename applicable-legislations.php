<?php
include('includes/header.php');
include('includes/navbar.php');
include('includes/connection.php');
include('includes/auth_check.php');
?>
<div class="dashboard-container">

    <!-- ================ MODAL BUTTON ================ -->
    <div class="d-flex justify-content-end
     align-items-center mb-3">
        <button type="button" style="font-size: 12px !important" data-bs-toggle="modal" data-bs-target="#alModal" class="btn btn-sm btn-outline-success">Add</button>
    </div>

    <?php
    if (isset($_POST['del-al'])) {
        $al_id = $_POST['al_id'];
        $delete = "DELETE FROM `app_leg` WHERE `al_id` = '$al_id'";
        $delete_r = mysqli_query($connection, $delete);
        if ($delete_r) {
            echo '<div id="alertBox" class="alert alert-success" role="alert" style="font-size: 12px">Applicable Legislation Deleted Successfully!</div>';
        } else {
            echo '<div id="alertBox" class="alert alert-danger" role="alert" style="font-size: 12px">Error Deleting Applicable Legislation!</div>';
        }
    }

    if (isset($_POST['update-al'])) {
        $al_id = $_POST['al_id'];
        $al_jurisdiction = $_POST['al_jurisdiction'];
        $al_law_regulation = $_POST['al_law_regulation'];
        $al_description = $_POST['al_description'];
        $al_applicable_clauses_arr = $_POST['al_applicable_clauses'] ?? [];
        $al_applicable_clauses = implode(',', $al_applicable_clauses_arr);
        $al_compliance_status = $_POST['al_compliance_status'];
        $al_review_date = $_POST['al_review_date'];

        $update = "UPDATE `app_leg` SET 
        `al_jurisdiction` = '$al_jurisdiction',
        `al_law_regulation` = '$al_law_regulation',
        `al_description` = '$al_description',
        `al_applicable_clauses` = '$al_applicable_clauses',
        `al_compliance_status` = '$al_compliance_status',
        `al_review_date` = '$al_review_date'
        WHERE `al_id` = '$al_id'";

        $update_r = mysqli_query($connection, $update);
        if ($update_r) {
            echo '<div class="alert alert-success" style="font-size: 12px">Applicable Legislation Updated Successfully!</div>';
        } else {
            echo '<div class="alert alert-danger" style="font-size: 12px">Error Updating Applicable Legislation!</div>';
        }
    }


    if (isset($_POST['add-al'])) {
        $al_jurisdiction = $_POST['al_jurisdiction'];
        $al_law_regulation = $_POST['al_law_regulation'];
        $al_description = $_POST['al_description'];
        $al_applicable_clauses_arr = $_POST['al_applicable_clauses'] ?? [];
        $al_applicable_clauses = implode(',', $al_applicable_clauses_arr);  // Store as comma-separated string
        $al_compliance_status = $_POST['al_compliance_status'];
        $al_review_date = $_POST['al_review_date'];

        $insert = "INSERT INTO `app_leg` (
        `al_jurisdiction`, 
        `al_law_regulation`, 
        `al_description`, 
        `al_applicable_clauses`, 
        `al_compliance_status`, 
        `al_review_date`) VALUES (
        '$al_jurisdiction', 
        '$al_law_regulation', 
        '$al_description', 
        '$al_applicable_clauses', 
        '$al_compliance_status', 
        '$al_review_date')";
        $insert_r = mysqli_query($connection, $insert);
    }
    ?>

    <!-- ================ APPLICABLE LEGISLATIONS MODAL ================ -->
    <div class="modal fade" id="alModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="" method="POST" class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Applicable Legislation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Jurisdiction</label>
                        <input type="text" name="al_jurisdiction" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>

                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Law | Regulation</label>
                        <input type="text" name="al_law_regulation" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>

                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Description</label>
                        <input type="text" name="al_description" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>

                    <?php
                    $clause_options = [];
                    $clause_query = "
                        SELECT 'policy' AS type, policy_id AS id, CONCAT(policy_clause, ' ', policy_name) AS name FROM policy
                        UNION
                        SELECT 'sub_control_policy', sub_control_policy_id, CONCAT(p.policy_clause, ' ', p.policy_name, ' > ', s.sub_control_policy_number, ' ', s.sub_control_policy_heading)
                        FROM sub_control_policy s
                        JOIN policy p ON p.policy_id = s.main_control_policy_id
                        UNION
                        SELECT 'linked_control_policy', l.linked_control_policy_id,
                        CONCAT(p.policy_clause, ' ', p.policy_name, ' > ', s.sub_control_policy_number, ' ', s.sub_control_policy_heading, ' > ', l.linked_control_policy_number, ' ', l.linked_control_policy_heading)
                        FROM linked_control_policy l
                        JOIN sub_control_policy s ON s.sub_control_policy_id = l.sub_control_policy_id
                        JOIN policy p ON p.policy_id = s.main_control_policy_id
                    ";

                    $res = mysqli_query($connection, $clause_query);
                    while ($row = mysqli_fetch_assoc($res)) {
                        $clause_options[] = $row;
                    }
                    ?>

                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Applicable Clauses</label>
                        <select multiple style="font-size: 12px !important;" name="al_applicable_clauses[]" class="form-select" aria-label="Applicable Clauses" size="10">
                            <?php foreach ($clause_options as $clause): ?>
                                <option value="<?= $clause['type'] . ':' . $clause['id'] ?>">
                                    <?= htmlspecialchars($clause['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Compliance Status</label>
                        <select style="font-size: 12px !important;" name="al_compliance_status" class="form-select" aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <option value="Compliant">Compliant</option>
                            <option value="Partially Compliant">Partially Compliant</option>
                            <option value="Not Compliant">Not Compliant</option>
                            <option value="Not Applicable (N/A)">Not Applicable (N/A)</option>
                            <option value="Under Review">Under Review</option>
                            <option value="Planned for Compliance">Planned for Compliance</option>
                            <option value="Obsolete / No Longer Applicable">Obsolete / No Longer Applicable</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Review Date</label>
                        <input type="date" name="al_review_date" style="font-size: 12px !important" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    </div>
                    <input type="hidden" name="al_id" id="al_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add-al" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
    <?php

    $get = "SELECT * FROM `app_leg`";
    $get_r = mysqli_query($connection, $get);
    $get_count = mysqli_num_rows($get_r);
    if ($get_count == 0) {
        echo '<div class="alert alert-warning" role="alert" style="font-size: 12px">No Applicable Legislation Found!</div>';
    } else {
    ?>
        <div class="card p-3 table-responsive mt-3">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th style="font-size: 12px !important;" scope="col">Jurisdication</th>
                        <th style="font-size: 12px !important;" scope="col">Law/Regulation</th>
                        <th style="font-size: 12px !important;" scope="col">Description</th>
                        <th style="font-size: 12px !important;" scope="col">Applicable Clauses</th>
                        <th style="font-size: 12px !important;" scope="col">Compliance Status</th>
                        <th style="font-size: 12px !important;" scope="col">Review Date</th>
                        <th style="font-size: 12px !important;" scope="col">Action</th>
                        <th style="font-size: 12px !important;" scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($get_r)) {
                        $al_id = $row['al_id'];
                        $al_jurisdiction = $row['al_jurisdiction'];
                        $al_law_regulation = $row['al_law_regulation'];
                        $al_description = $row['al_description'];
                        $al_applicable_clauses = $row['al_applicable_clauses'];
                        $al_compliance_status = $row['al_compliance_status'];
                        $al_review_date = $row['al_review_date'];
                    ?>
                        <tr>
                            <td style="font-size: 12px !important;"><?php echo $al_jurisdiction; ?></td>
                            <td style="font-size: 12px !important;"><?php echo $al_law_regulation; ?></td>
                            <td style="font-size: 12px !important;"><?php echo $al_description; ?></td>
                            <td style="font-size: 12px !important;">
                                <?php
                                $clause_display = [];
                                $clause_refs = explode(',', $al_applicable_clauses);

                                foreach ($clause_refs as $ref) {
                                    list($type, $id) = explode(':', $ref);

                                    if ($type === 'policy') {
                                        $q = "SELECT CONCAT(policy_clause, ' ', policy_name) AS name FROM policy WHERE policy_id = '$id'";
                                    } elseif ($type === 'sub_control_policy') {
                                        $q = "SELECT CONCAT(p.policy_clause, ' ', p.policy_name, ' > ', s.sub_control_policy_number, ' ', s.sub_control_policy_heading) AS name 
                  FROM sub_control_policy s 
                  JOIN policy p ON p.policy_id = s.main_control_policy_id 
                  WHERE s.sub_control_policy_id = '$id'";
                                    } elseif ($type === 'linked_control_policy') {
                                        $q = "SELECT CONCAT(p.policy_clause, ' ', p.policy_name, ' > ', s.sub_control_policy_number, ' ', s.sub_control_policy_heading, ' > ', l.linked_control_policy_number, ' ', l.linked_control_policy_heading) AS name 
                  FROM linked_control_policy l 
                  JOIN sub_control_policy s ON s.sub_control_policy_id = l.sub_control_policy_id 
                  JOIN policy p ON p.policy_id = s.main_control_policy_id 
                  WHERE l.linked_control_policy_id = '$id'";
                                    } else {
                                        continue;
                                    }

                                    $result = mysqli_query($connection, $q);
                                    if ($r = mysqli_fetch_assoc($result)) {
                                        $clause_display[] = htmlspecialchars($r['name']);
                                    }
                                }

                                echo implode('<br>', $clause_display);
                                ?>
                            </td>

                            <td style="font-size: 12px !important;"><?php echo $al_compliance_status; ?></td>
                            <td style="font-size: 12px !important;"><?php echo $al_review_date; ?></td>
                            <td style="font-size: 12px !important;">
                                <button type="button"
                                    class="btn btn-sm btn-outline-dark edit-btn"
                                    style="font-size: 12px !important;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#alModal"
                                    data-id="<?= $al_id ?>"
                                    data-jurisdiction="<?= htmlspecialchars($al_jurisdiction, ENT_QUOTES) ?>"
                                    data-law="<?= htmlspecialchars($al_law_regulation, ENT_QUOTES) ?>"
                                    data-description="<?= htmlspecialchars($al_description, ENT_QUOTES) ?>"
                                    data-clauses="<?= htmlspecialchars($al_applicable_clauses, ENT_QUOTES) ?>"
                                    data-status="<?= $al_compliance_status ?>"
                                    data-date="<?= $al_review_date ?>">
                                    Edit
                                </button>

                            </td>
                            <td style="font-size: 12px !important;">
                                <form action="" method="POST">
                                    <input type="text" name="al_id" value="<?php echo $al_id; ?>" hidden>
                                    <button type="submit" name="del-al" style="font-size: 12px !important;" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById('alModal');
        const jurisdictionInput = modal.querySelector('input[name="al_jurisdiction"]');
        const lawInput = modal.querySelector('input[name="al_law_regulation"]');
        const descriptionInput = modal.querySelector('input[name="al_description"]');
        const clausesSelect = modal.querySelector('select[name="al_applicable_clauses[]"]');
        const statusSelect = modal.querySelector('select[name="al_compliance_status"]');
        const reviewDateInput = modal.querySelector('input[name="al_review_date"]');
        const idInput = modal.querySelector('input[name="al_id"]');

        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                jurisdictionInput.value = btn.dataset.jurisdiction;
                lawInput.value = btn.dataset.law;
                descriptionInput.value = btn.dataset.description;
                statusSelect.value = btn.dataset.status;
                reviewDateInput.value = btn.dataset.date;
                idInput.value = btn.dataset.id;

                // Reset all options first
                Array.from(clausesSelect.options).forEach(opt => opt.selected = false);

                // Select matching options
                const selectedClauses = btn.dataset.clauses.split(',');
                selectedClauses.forEach(val => {
                    const opt = Array.from(clausesSelect.options).find(o => o.value === val);
                    if (opt) opt.selected = true;
                });

                // Update modal title and button
                modal.querySelector('.modal-title').textContent = 'Edit Applicable Legislation';
                modal.querySelector('button[name="add-al"]').setAttribute('name', 'update-al');
                modal.querySelector('button[name="update-al"]').textContent = 'Update';
            });
        });
    });
</script>

<?php include('includes/footer.php'); ?>