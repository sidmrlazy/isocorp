<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';
?>
<div class="dashboard-container mb-5">
    <?php
    $clause_options = [];
    $clause_query = "
        SELECT 'policy' AS type, policy_id AS id, CONCAT(policy_clause, ' ', policy_name) AS name FROM policy
        UNION 
        SELECT 'sub_control_policy', sub_control_policy_id, CONCAT(p.policy_clause, ' ', p.policy_name, ' > ', s.sub_control_policy_number, ' ', s.sub_control_policy_heading)
        FROM sub_control_policy s 
        JOIN policy p ON p.policy_id = s.main_control_policy_id
        UNION 
        SELECT 'linked_control_policy', l.linked_control_policy_id, CONCAT(p.policy_clause, ' ', p.policy_name, ' > ', s.sub_control_policy_number, ' ', s.sub_control_policy_heading, ' > ', l.linked_control_policy_number, ' ', l.linked_control_policy_heading) 
        FROM linked_control_policy l
        JOIN sub_control_policy s ON s.sub_control_policy_id = l.sub_control_policy_id
        JOIN policy p ON p.policy_id = s.main_control_policy_id
    ";

    $res = mysqli_query($connection, $clause_query);
    while ($row = mysqli_fetch_assoc($res)) {
        $clause_options[] = $row;
    }

    $soa_data = [];
    $soa_query = "
    SELECT s1.*
    FROM soa s1
    INNER JOIN (
        SELECT soa_policy_type, soa_policy_id, MAX(soa_created_at) AS max_time
        FROM soa
        GROUP BY soa_policy_type, soa_policy_id
    ) s2 ON s1.soa_policy_type = s2.soa_policy_type 
         AND s1.soa_policy_id = s2.soa_policy_id 
         AND s1.soa_created_at = s2.max_time
";

    $res_soa = mysqli_query($connection, $soa_query);
    while ($row = mysqli_fetch_assoc($res_soa)) {
        $key = $row['soa_policy_type'] . '_' . $row['soa_policy_id'];
        $soa_data[$key] = $row;
    }

    ?>
    <form method="POST" action="save_soa.php" id="policyForm">
        <!-- <div class="text-end mt-3">
            <button type="submit" name="download_excel" class="btn btn-sm btn-outline-success mb-3" style="font-size: 12px;">Download Statement of Applicability</button>
        </div> -->


        <!-- Save Button -->
        <div class="text-end mb-3">
            <button type="submit" name="save_only" formaction="save_soa.php" class="btn btn-sm btn-outline-primary" style="font-size: 12px;">Save Applicability</button>
            <button type="submit" name="download_excel" formaction="download_policies_excel.php" class="btn btn-sm btn-outline-success" style="font-size: 12px;">Download Statement of Applicability</button>
        </div>
        <div class="card p-3 table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th style="font-size: 12px;">Policy</th>
                        <th style="font-size: 12px;">Applicable</th>
                        <th style="font-size: 12px;">Not Applicable</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clause_options as $index => $clause):
                        $key = $clause['type'] . '_' . $clause['id'];
                        $saved = $soa_data[$key] ?? null;
                        $saved_applicable = $saved['soa_applicable'] ?? null;
                        $saved_justification = $saved['soa_justification'] ?? '';
                    ?>
                        <tr>
                            <td style="font-size: 12px;">
                                <?= htmlspecialchars($clause['name']) ?>
                                <input type="hidden" name="policy_name[<?= $index ?>]" value="<?= htmlspecialchars($clause['name']) ?>">
                                <input type="hidden" name="policy_type[<?= $index ?>]" value="<?= $clause['type'] ?>">
                                <input type="hidden" name="policy_id[<?= $index ?>]" value="<?= $clause['id'] ?>">
                                <input type="hidden" id="justification_<?= $index ?>" name="justification[<?= $index ?>]" value="<?= htmlspecialchars($saved_justification) ?>">
                            </td>
                            <td style="text-align: center;" class="<?= $saved_applicable === '1' ? 'table-success' : '' ?>">
                                <input type="checkbox" class="form-check-input applicable-checkbox" data-index="<?= $index ?>" data-type="applicable" name="applicable_status[<?= $index ?>]" value="1" <?= $saved_applicable === '1' ? 'checked' : '' ?>>
                            </td>
                            <td style="text-align: center;" class="<?= $saved_applicable === '0' ? 'table-danger' : '' ?>">
                                <input type="checkbox" class="form-check-input applicable-checkbox" data-index="<?= $index ?>" data-type="not_applicable" name="applicable_status[<?= $index ?>]" value="0" onclick="openModal(<?= $index ?>)" <?= $saved_applicable === '0' ? 'checked' : '' ?>>
                            </td>

                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>



    </form>
</div>

<!-- Justification Modal -->
<div class="modal fade" id="justificationModal" tabindex="-1" aria-labelledby="justificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Justify, why is this policy not applicable?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea id="modalJustification" class="form-control" rows="4"></textarea>
                <input type="hidden" id="currentIndex">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveJustification()">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(index) {
        document.getElementById('currentIndex').value = index;
        document.getElementById('modalJustification').value = document.getElementById('justification_' + index).value;
        new bootstrap.Modal(document.getElementById('justificationModal')).show();
    }

    function saveJustification() {
        const index = document.getElementById('currentIndex').value;
        const justification = document.getElementById('modalJustification').value;
        document.getElementById('justification_' + index).value = justification;
        bootstrap.Modal.getInstance(document.getElementById('justificationModal')).hide();
    }

    document.querySelector("form").addEventListener("submit", function(e) {
        const isDownload = e.submitter?.name === "download_excel";
        if (isDownload) {
            const justifications = document.querySelectorAll("textarea[id^='justification_']");
            const radios = document.querySelectorAll("input[type='radio']:checked");

            for (let radio of radios) {
                if (radio.value === "0") { // Not applicable
                    const index = radio.name.match(/\[(\d+)\]/)[1];
                    const justification = document.getElementById("justification_" + index).value;
                    if (!justification.trim()) {
                        alert("Justification required for non-applicable policies.");
                        e.preventDefault();
                        return false;
                    }
                }
            }
        }
    });

    function submitForDownload() {
        const form = document.getElementById("policyForm");
        const formData = new FormData(form);
        let justificationMissing = false;

        // Check justification for non-applicable policies
        const radios = form.querySelectorAll("input[type='radio']:checked");
        for (let radio of radios) {
            if (radio.value === "0") {
                const index = radio.name.match(/\[(\d+)\]/)[1];
                const justification = document.getElementById("justification_" + index).value.trim();
                if (!justification) {
                    alert("Justification required for non-applicable policies.");
                    justificationMissing = true;
                    break;
                }
            }
        }

        if (justificationMissing) return;

        // Submit data to download script via POST
        const downloadForm = document.createElement('form');
        downloadForm.method = 'POST';
        downloadForm.action = 'download_policies_excel.php';
        downloadForm.style.display = 'none';

        for (let [key, value] of formData.entries()) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            downloadForm.appendChild(input);
        }

        document.body.appendChild(downloadForm);
        downloadForm.submit();
        document.body.removeChild(downloadForm);
    }

    document.querySelectorAll('.applicable-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const index = this.dataset.index;
            const type = this.dataset.type;

            const applicableCheckbox = document.querySelector(`input[data-index="${index}"][data-type="applicable"]`);
            const notApplicableCheckbox = document.querySelector(`input[data-index="${index}"][data-type="not_applicable"]`);

            if (this.checked) {
                if (type === 'applicable') {
                    notApplicableCheckbox.checked = false;
                    // Add success style
                    applicableCheckbox.closest('td').classList.add('table-success');
                    notApplicableCheckbox.closest('td').classList.remove('table-danger');
                } else {
                    applicableCheckbox.checked = false;
                    // Add danger style
                    notApplicableCheckbox.closest('td').classList.add('table-danger');
                    applicableCheckbox.closest('td').classList.remove('table-success');
                }
            } else {
                // Remove both styles if neither is selected
                applicableCheckbox.closest('td').classList.remove('table-success');
                notApplicableCheckbox.closest('td').classList.remove('table-danger');
            }
        });
    });
</script>


<?php include 'includes/footer.php'; ?>