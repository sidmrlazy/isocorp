<?php
include 'includes/connection.php';
function getVersionControlInfo($connection, $data_id)
{
    $query = "SELECT vc_assigned_to, vc_status FROM version_control 
              WHERE vc_data_id = $data_id AND vc_screen_name = 'Policy Details' 
              ORDER BY vc_updated_on DESC LIMIT 1";
    $result = mysqli_query($connection, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return ['vc_assigned_to' => 'N/A', 'vc_status' => 'N/A'];
}

// ✅ NEW FUNCTION using risk_policies
function getRiskCount($connection, $clause_type, $clause_id)
{
    $query = "SELECT COUNT(*) as count FROM risk_policies 
              WHERE clause_type = '$clause_type' AND clause_id = $clause_id";
    $result = mysqli_query($connection, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['count'];
    }
    return 0;
}
?>

<div class="table-responsive ">
    <table class="table table-bordered table-striped table-hover" id="policyTable">
        <thead class="table-dark">
            <tr>
                <th style="font-size: 12px !important;">Main Policy</th>
                <th style="font-size: 12px !important;">Sub Policy</th>
                <th style="font-size: 12px !important;">Linked Policy</th>
                <th style="font-size: 12px !important;">Inner Linked Policy</th>
                <th style="font-size: 12px !important;">Risks Assigned</th>
                <th style="font-size: 12px !important;">Details</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $policy_query = "SELECT * FROM `policy`";
            $policy_result = mysqli_query($connection, $policy_query);

            if ($policy_result && mysqli_num_rows($policy_result) > 0) {
                while ($policy = mysqli_fetch_assoc($policy_result)) {
                    $policy_id = $policy['policy_id'];
                    $main_policy = htmlspecialchars($policy['policy_clause'] . " " . $policy['policy_name']);

                    $sub_policy_query = "SELECT * FROM `sub_control_policy` WHERE `main_control_policy_id` = $policy_id";
                    $sub_policy_result = mysqli_query($connection, $sub_policy_query);

                    if (mysqli_num_rows($sub_policy_result) > 0) {
                        while ($sub_policy = mysqli_fetch_assoc($sub_policy_result)) {
                            $sub_policy_id = $sub_policy['sub_control_policy_id'];
                            $sub_policy_text = htmlspecialchars($sub_policy['sub_control_policy_number'] . " " . $sub_policy['sub_control_policy_heading']);

                            $linked_policy_query = "SELECT * FROM `linked_control_policy` WHERE `sub_control_policy_id` = $sub_policy_id";
                            $linked_policy_result = mysqli_query($connection, $linked_policy_query);

                            if (mysqli_num_rows($linked_policy_result) > 0) {
                                while ($linked_policy = mysqli_fetch_assoc($linked_policy_result)) {
                                    $linked_policy_id = $linked_policy['linked_control_policy_id'];
                                    $linked_policy_text = htmlspecialchars($linked_policy['linked_control_policy_number'] . " - " . $linked_policy['linked_control_policy_heading']);

                                    $inner_linked_policy_query = "SELECT * FROM `inner_linked_control_policy` WHERE `linked_control_policy_id` = $linked_policy_id";
                                    $inner_linked_policy_result = mysqli_query($connection, $inner_linked_policy_query);

                                    if (mysqli_num_rows($inner_linked_policy_result) > 0) {
                                        while ($inner = mysqli_fetch_assoc($inner_linked_policy_result)) {
                                            $inner_id = $inner['inner_linked_control_policy_id'];
                                            $inner_text = htmlspecialchars($inner['inner_linked_control_policy_number'] . " - " . $inner['inner_linked_control_policy_heading']);

                                            $riskCount = getRiskCount($connection, 'inner_linked_control_policy', $inner_id);
            ?>
                                            <tr>
                                                <td style='font-size: 12px !important;'><?= $main_policy ?></td>
                                                <td style='font-size: 12px !important;'><?= $sub_policy_text ?></td>
                                                <td style='font-size: 12px !important;'><?= $linked_policy_text ?></td>
                                                <td style='font-size: 12px !important;'><?= $inner_text ?></td>
                                                <td style='color: <?= ($riskCount == 0 ? "red" : "inherit") ?>;'><?= $riskCount ?></td>

                                                <td>
                                                    <form action='policy-details.php' target='_blank' method='GET'>
                                                        <input type='hidden' name='inner_policy_id' value='<?= $inner_id ?>'>
                                                        <button class='btn btn-sm btn-outline-success'>View</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else {
                                        $riskCount = getRiskCount($connection, 'linked_control_policy', $linked_policy_id);
                                        ?>
                                        <tr>
                                            <td style='font-size: 12px !important;'><?= $main_policy ?></td>
                                            <td style='font-size: 12px !important;'><?= $sub_policy_text ?></td>
                                            <td style='font-size: 12px !important;'><?= $linked_policy_text ?></td>
                                            <td></td>
                                            <td style='color: <?= ($riskCount == 0 ? "red" : "inherit") ?>;'><?= $riskCount ?></td>

                                            <td>
                                                <form action='policy-details.php' target='_blank' method='GET'>
                                                    <input type='hidden' name='linked_policy_id' value='<?= $linked_policy_id ?>'>
                                                    <button class='btn btn-sm btn-outline-success'>View</button>
                                                </form>
                                            </td>
                                        </tr>
                                <?php }
                                }
                            } else {
                                $riskCount = getRiskCount($connection, 'sub_control_policy', $sub_policy_id);
                                ?>
                                <tr>
                                    <td style='font-size: 12px !important;'><?= $main_policy ?></td>
                                    <td style='font-size: 12px !important;'><?= $sub_policy_text ?></td>
                                    <td></td>
                                    <td></td>
                                    <td style='color: <?= ($riskCount == 0 ? "red" : "inherit") ?>;'><?= $riskCount ?></td>

                                    <td>
                                        <form action='policy-details.php' target='_blank' method='GET'>
                                            <input type='hidden' name='policy_id' value='<?= $sub_policy_id ?>'>
                                            <button class='btn btn-sm btn-outline-success'>View</button>
                                        </form>
                                    </td>
                                </tr>
                        <?php }
                        }
                    } else {
                        $riskCount = getRiskCount($connection, 'policy', $policy_id);
                        ?>
                        <tr>
                            <td style='font-size: 12px !important;'><?= $main_policy ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style='color: <?= ($riskCount == 0 ? "red" : "inherit") ?>;'><?= $riskCount ?></td>

                            <td>
                                <form action='policy-details.php' target='_blank' method='GET'>
                                    <input type='hidden' name='policy_id' value='<?= $policy_id ?>'>
                                    <button class='btn btn-sm btn-outline-success'>View</button>
                                </form>
                            </td>
                        </tr>
            <?php }
                }
            }
            ?>
        </tbody>
    </table>
</div>