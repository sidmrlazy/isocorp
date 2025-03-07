<?php
include 'includes/connection.php';

$type = isset($_GET['type']) ? $_GET['type'] : '';
$parent_id = isset($_GET['parent_id']) ? intval($_GET['parent_id']) : 0;

$clauses = [];

if ($type == "policy") {
    $result = $connection->query("SELECT policy_id AS id, CONCAT(policy_clause, ' - ', policy_name) AS name FROM POLICY");
} elseif ($type == "sub_control_policy" && $parent_id) {
    $result = $connection->prepare("SELECT sub_control_policy_id AS id, sub_control_policy_number AS number, sub_control_policy_heading AS name FROM SUB_CONTROL_POLICY WHERE main_control_policy_id = ?");
    $result->bind_param("i", $parent_id);
    $result->execute();
    $result = $result->get_result();
} elseif ($type == "linked_control_policy" && $parent_id) {
    $result = $connection->prepare("SELECT linked_control_policy_id AS id, linked_control_policy_number AS number, linked_control_policy_heading AS name FROM LINKED_CONTROL_POLICY WHERE sub_control_policy_id = ?");
    $result->bind_param("i", $parent_id);
    $result->execute();
    $result = $result->get_result();
} elseif ($type == "inner_linked_control_policy" && $parent_id) {
    $result = $connection->prepare("SELECT inner_linked_control_policy_id AS id, inner_linked_control_policy_number AS number, inner_linked_control_policy_heading AS name FROM INNER_LINKED_CONTROL_POLICY WHERE linked_control_policy_id = ?");
    $result->bind_param("i", $parent_id);
    $result->execute();
    $result = $result->get_result();
}

while ($row = $result->fetch_assoc()) {
    $clauses[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'number' => isset($row['number']) ? $row['number'] : ''
    ];
}

echo json_encode($clauses);
?>
