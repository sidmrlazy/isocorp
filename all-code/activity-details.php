<?php
include('includes/header.php');
include('includes/navbar.php');
include('includes/connection.php');
?>

<div class="dashboard-container">
    <?php
    if (isset($_POST['save'])) {
        $mrb_act_id = intval($_POST['mrb_act_id']);
        $editor_text = $_POST['editor_content'];

        $editor_blob = mysqli_real_escape_string($connection, $editor_text);
        $update_query = "UPDATE `mrb_activities` SET `mrb_act_details` = '$editor_blob'  WHERE mrb_act_id = $mrb_act_id";
        $update_res = mysqli_query($connection, $update_query);
    }
    
    if (isset($_POST['submit'])) {
        $mrb_act_id = intval($_POST['mrb_act_id']);
        $editor_text = $_POST['editor_content'];
        $mrb_act_status = "2";
        $editor_blob = mysqli_real_escape_string($connection, $editor_text);
        $update_query = "UPDATE `mrb_activities` SET `mrb_act_details` = '$editor_blob' , `mrb_act_status` = $mrb_act_status WHERE mrb_act_id = $mrb_act_id";
        $update_res = mysqli_query($connection, $update_query);
    }

    if (isset($_GET['act_id'])) {
        $act_id = intval($_GET['act_id']);

        $get_mrb_activity_details = "SELECT * FROM `mrb_activities` WHERE `mrb_act_id` = $act_id";
        $get_mrb_activity_res = mysqli_query($connection, $get_mrb_activity_details);

        if ($row = mysqli_fetch_assoc($get_mrb_activity_res)) {
            $new_id = htmlspecialchars($row['mrb_act_id']);
            $new_details = htmlspecialchars($row['mrb_act_details']);
            $new_status = htmlspecialchars($row['mrb_act_status']); ?>
<!-- <div class="data-container">
    <p><?php echo htmlspecialchars_decode($new_details) ?></p>
</div> -->

<?php
        } 
    } ?>

    <form action="" method="POST" class="text-editor-container">
        <div class="WYSIWYG-editor">
            <textarea id="editorNew" name="editor_content"><?php echo $new_details; ?></textarea>
        </div>
        <input type="hidden" value="<?php echo $new_id; ?>" name="mrb_act_id">
        <?php 
        if($new_status == "1") { ?>
        <button type="submit" name="save" class="mt-3 btn btn-primary">Save Details</button>
        <button type="submit" name="submit" class="mt-3 btn btn-success">Submit Details</button>
        <?php } elseif($new_status == "2") { ?>
            <button type="submit" name="save" class="mt-3 d-none btn btn-primary">Save Details</button>
            <button type="submit" name="submit" class="mt-3 d-none btn btn-success">Submit Details</button>
        <?php } ?>
    </form>
</div>

<?php include('includes/footer.php'); ?>
