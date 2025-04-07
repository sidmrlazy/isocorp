<div style="display: flex; justify-content: center; align-items: center; margin-top: 50px;">
    <div class="floating-search-input">
        <input type="text" id="searchInput" class="form-control" placeholder="Search" onkeyup="searchPolicies()" onblur="delayedReset()">
    </div>

    <!-- ============= EXPAND | COLLAPSE BUTTON ============= -->
    <div style="display: flex; justify-content: center; align-items: center;">
        <button class="btn btn-sm btn-outline-dark" id="expandAllBtn">Expand All</button>
        <button class="btn btn-sm btn-outline-dark" id="collapseAllBtn" style="display: none;">Collapse All</button>
    </div>

</div>

<script>
    const expandBtn = document.getElementById('expandAllBtn');
    const collapseBtn = document.getElementById('collapseAllBtn');

    expandBtn.addEventListener('click', function () {
        // Expand all accordions
        document.querySelectorAll('.accordion-collapse').forEach(collapse => {
            collapse.classList.add('show');
            const button = collapse.previousElementSibling?.querySelector('.accordion-button');
            if (button) button.classList.remove('collapsed');
        });

        // Toggle buttons
        expandBtn.style.display = 'none';
        collapseBtn.style.display = 'inline-block';
    });

    collapseBtn.addEventListener('click', function () {
        // Collapse all accordions
        document.querySelectorAll('.accordion-collapse').forEach(collapse => {
            collapse.classList.remove('show');
            const button = collapse.previousElementSibling?.querySelector('.accordion-button');
            if (button) button.classList.add('collapsed');
        });

        // Toggle buttons
        collapseBtn.style.display = 'none';
        expandBtn.style.display = 'inline-block';
    });
</script>

<div class="main-controls-section">
    <div class="accordion" id="accordionExample">
        <?php
        include 'includes/connection.php';

        $policy_query = "SELECT * FROM `policy`";
        $policy_result = mysqli_query($connection, $policy_query);

        if ($policy_result && mysqli_num_rows($policy_result) > 0) {
            while ($policy = mysqli_fetch_assoc($policy_result)) {
                $policy_id = $policy['policy_id'];
                $policy_clause = htmlspecialchars($policy['policy_clause']);
                $policy_name = htmlspecialchars($policy['policy_name']);


                $sub_policy_query = "SELECT * FROM `sub_control_policy` WHERE `main_control_policy_id` = $policy_id";
                $sub_policy_result = mysqli_query($connection, $sub_policy_query);
                $has_sub_policies = mysqli_num_rows($sub_policy_result) > 0;
        ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingPolicy<?= $policy_id ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#policy<?= $policy_id ?>" aria-expanded="false"
                            aria-controls="policy<?= $policy_id ?>">
                            <?= $policy_clause . " " . $policy_name ?>
                        </button>
                    </h2>

                    <div id="policy<?= $policy_id ?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="accordion" id="subPolicyAccordion<?= $policy_id ?>">

                                <?php

                                if (!$has_sub_policies) { ?>
                                    <form action="policy-details.php" target="_blank" method="GET">
                                        <input type="hidden" name="policy_id" value="<?= $policy_id ?>">
                                        <button class="btn btn-sm btn-outline-success mt-3 mb-3">View Details</button>
                                    </form>
                                    <?php }

                                if ($has_sub_policies) {
                                    while ($sub_policy = mysqli_fetch_assoc($sub_policy_result)) {
                                        $sub_policy_id = $sub_policy['sub_control_policy_id'];
                                        $sub_policy_number = htmlspecialchars($sub_policy['sub_control_policy_number']);
                                        $sub_policy_heading = htmlspecialchars($sub_policy['sub_control_policy_heading']);
                                        $sub_control_policy_det = $sub_policy['sub_control_policy_det'];


                                        $linked_policy_query = "SELECT * FROM `linked_control_policy` WHERE `sub_control_policy_id` = $sub_policy_id";
                                        $linked_policy_result = mysqli_query($connection, $linked_policy_query);
                                        $has_linked_policies = mysqli_num_rows($linked_policy_result) > 0;
                                    ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingSubPolicy<?= $sub_policy_id ?>">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#subPolicy<?= $sub_policy_id ?>" aria-expanded="false"
                                                    aria-controls="subPolicy<?= $sub_policy_id ?>">
                                                    <?= $sub_policy_number . " " . $sub_policy_heading ?>
                                                </button>
                                            </h2>
                                            <div id="subPolicy<?= $sub_policy_id ?>" class="accordion-collapse collapse"
                                                data-bs-parent="#subPolicyAccordion<?= $policy_id ?>">
                                                <div class="accordion-body">
                                                    <?= $sub_control_policy_det ?>

                                                    <?php

                                                    if (!$has_linked_policies) { ?>
                                                        <form action="policy-details.php" target="_blank" method="GET">
                                                            <input type="hidden" name="policy_id" value="<?= $sub_policy_id ?>">
                                                            <button class="btn btn-sm btn-outline-success mt-3 mb-3">View Details</button>
                                                        </form>
                                                    <?php } ?>

                                                    <div class="accordion" id="linkedPolicyAccordion<?= $sub_policy_id ?>">
                                                        <?php
                                                        if ($has_linked_policies) {
                                                            while ($linked_policy = mysqli_fetch_assoc($linked_policy_result)) {
                                                                $linked_policy_id = $linked_policy['linked_control_policy_id'];
                                                                $linked_control_policy_number = htmlspecialchars($linked_policy['linked_control_policy_number']);
                                                                $linked_control_policy_heading = htmlspecialchars($linked_policy['linked_control_policy_heading']);
                                                                $linked_control_policy_det = $linked_policy['linked_control_policy_det'];


                                                                $inner_linked_policy_query = "SELECT * FROM `inner_linked_control_policy` WHERE `linked_control_policy_id` = $linked_policy_id";
                                                                $inner_linked_policy_result = mysqli_query($connection, $inner_linked_policy_query);
                                                                $has_inner_linked_policies = mysqli_num_rows($inner_linked_policy_result) > 0;
                                                        ?>
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header"
                                                                        id="headingLinkedPolicy<?= $linked_policy_id ?>">
                                                                        <button class="accordion-button collapsed" type="button"
                                                                            data-bs-toggle="collapse"
                                                                            data-bs-target="#linkedPolicy<?= $linked_policy_id ?>"
                                                                            aria-expanded="false"
                                                                            aria-controls="linkedPolicy<?= $linked_policy_id ?>">
                                                                            <?= $linked_control_policy_number . " - " . $linked_control_policy_heading ?>
                                                                        </button>
                                                                    </h2>
                                                                    <div id="linkedPolicy<?= $linked_policy_id ?>"
                                                                        class="accordion-collapse collapse"
                                                                        data-bs-parent="#linkedPolicyAccordion<?= $sub_policy_id ?>">
                                                                        <div class="accordion-body">
                                                                            <?= $linked_control_policy_det ?>

                                                                            <?php

                                                                            if (!$has_inner_linked_policies) { ?>
                                                                                <form action="policy-details.php" target="_blank" method="GET">
                                                                                    <input type="hidden" name="linked_policy_id" value="<?= $linked_policy_id ?>">
                                                                                    <button class="btn btn-sm btn-outline-success mt-3 mb-3">View Details</button>
                                                                                </form>
                                                                            <?php } ?>

                                                                            <div class="accordion" id="innerLinkedPolicyAccordion<?= $linked_policy_id ?>">
                                                                                <?php
                                                                                if ($has_inner_linked_policies) {
                                                                                    while ($inner_linked_policy = mysqli_fetch_assoc($inner_linked_policy_result)) {
                                                                                        $inner_policy_id = $inner_linked_policy['inner_linked_control_policy_id'];
                                                                                        $inner_policy_number = htmlspecialchars($inner_linked_policy['inner_linked_control_policy_number']);
                                                                                        $inner_policy_heading = htmlspecialchars($inner_linked_policy['inner_linked_control_policy_heading']);
                                                                                        $inner_policy_det = $inner_linked_policy['inner_linked_control_policy_det'];
                                                                                ?>
                                                                                        <div class="accordion-item">
                                                                                            <h2 class="accordion-header"
                                                                                                id="headingInnerLinkedPolicy<?= $inner_policy_id ?>">
                                                                                                <button class="accordion-button collapsed" type="button"
                                                                                                    data-bs-toggle="collapse"
                                                                                                    data-bs-target="#innerLinkedPolicy<?= $inner_policy_id ?>"
                                                                                                    aria-expanded="false"
                                                                                                    aria-controls="innerLinkedPolicy<?= $inner_policy_id ?>">
                                                                                                    <?= $inner_policy_number . " - " . $inner_policy_heading ?>
                                                                                                </button>
                                                                                            </h2>
                                                                                            <div id="innerLinkedPolicy<?= $inner_policy_id ?>"
                                                                                                class="accordion-collapse collapse"
                                                                                                data-bs-parent="#innerLinkedPolicyAccordion<?= $linked_policy_id ?>">
                                                                                                <div class="accordion-body">
                                                                                                    <?= $inner_policy_det ?>

                                                                                                    <!-- Always show button at Inner Linked Policy Level -->
                                                                                                    <form action="policy-details.php" target="_blank" method="GET">
                                                                                                        <input type="hidden" name="inner_policy_id" value="<?= $inner_policy_id ?>">
                                                                                                        <button class="btn btn-sm btn-outline-success mt-3 mb-3">View Details</button>
                                                                                                    </form>
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
        <?php
            }
        }
        ?>
    </div>
</div>