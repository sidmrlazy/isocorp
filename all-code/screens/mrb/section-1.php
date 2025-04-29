<div class="dashboard-container">

    <!-- ============ SCREEN NAME ============ -->
    <div class="screen-name-container">
        <h1>MANAGEMENT REVIEW BOARD</h1>
        <h2><a href="dashboard.php">Dashboard</a> > Management Review Board</h2>
    </div>

    <div class="center-aligned-container">
        <div class="policies-control-container">
            <h3>Overview</h3>
            <p>A work environment for the ISMS Management Review Board to undertake management reviews and monitor business
                objectives as well as document the implementation of the ISMS.</p>


            <?php
            include 'includes/connection.php';

            $mrb_query = "SELECT * FROM mrb";
            $mrb_result = mysqli_query($connection, $mrb_query);

            if ($mrb_result && mysqli_num_rows($mrb_result) > 0) {
            ?>
                <div class="mt-5">
                    <div class="accordion" id="mrbAccordion">
                        <?php while ($mrb = mysqli_fetch_assoc($mrb_result)) {
                            $mrb_id = $mrb['mrb_id'];
                            $mrb_topic = htmlspecialchars($mrb['mrb_topic']);
                        ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingMRB<?= $mrb_id ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseMRB<?= $mrb_id ?>" aria-expanded="false"
                                        aria-controls="collapseMRB<?= $mrb_id ?>">
                                        <?= $mrb_topic ?>
                                    </button>
                                </h2>
                                <div id="collapseMRB<?= $mrb_id ?>" class="accordion-collapse collapse" data-bs-parent="#mrbAccordion">
                                    <div class="accordion-body">
                                        <div class="accordion" id="deliverableAccordion<?= $mrb_id ?>">
                                            <?php
                                            $deliverable_query = "SELECT * FROM mrb_deliverables WHERE mrb_del_board_id = $mrb_id";
                                            $deliverable_result = mysqli_query($connection, $deliverable_query);
                                            if (mysqli_num_rows($deliverable_result) > 0) {
                                                while ($deliverable = mysqli_fetch_assoc($deliverable_result)) {
                                                    $del_id = $deliverable['mrb_del_id'];
                                                    $del_name = htmlspecialchars($deliverable['mrb_del_deliverable']);
                                            ?>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingDeliverable<?= $del_id ?>">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#collapseDeliverable<?= $del_id ?>" aria-expanded="false"
                                                                aria-controls="collapseDeliverable<?= $del_id ?>">
                                                                <?= $del_name ?>
                                                            </button>
                                                        </h2>
                                                        <div id="collapseDeliverable<?= $del_id ?>" class="accordion-collapse collapse"
                                                            data-bs-parent="#deliverableAccordion<?= $mrb_id ?>">
                                                            <div class="accordion-body">
                                                                <div class="accordion" id="activityAccordion<?= $del_id ?>">
                                                                    <?php
                                                                    $activity_query = "SELECT * FROM mrb_activities WHERE mrb_act_deliverable_id = $del_id";
                                                                    $activity_result = mysqli_query($connection, $activity_query);
                                                                    if (mysqli_num_rows($activity_result) > 0) {
                                                                        while ($activity = mysqli_fetch_assoc($activity_result)) {
                                                                            $act_id = $activity['mrb_act_id'];
                                                                            $act_name = htmlspecialchars($activity['mrb_act_activity']);
                                                                    ?>
                                                                            <div class="accordion-item">
                                                                                <h2 class="accordion-header" id="headingActivity<?= $act_id ?>">
                                                                                    <a href="activity-details.php?act_id=<?= $act_id ?>" class="accordion-button collapsed">
                                                                                        <?= $act_name ?>
                                                                                    </a>
                                                                                </h2>
                                                                            </div>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
</div>