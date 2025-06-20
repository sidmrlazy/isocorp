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
    UNION
    SELECT 'soa_ra', soa_id, CONCAT('Risk Assessment > ', soa_policy_name) FROM soa WHERE soa_policy_type = 'soa_ra'
    UNION
    SELECT 'soa_br_bp', soa_id, CONCAT('Business Recovery > ', soa_policy_name) FROM soa WHERE soa_policy_type = 'soa_br_bp'
    UNION
    SELECT 'soa_lr_co', soa_id, CONCAT('Legal & Regulatory > ', soa_policy_name) FROM soa WHERE soa_policy_type = 'soa_lr_co'
    ";

    $res = mysqli_query($connection, $clause_query);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $clause_options[] = $row;
        }
    } else {
        echo '<div class="alert alert-danger">Failed to fetch clauses: ' . mysqli_error($connection) . '</div>';
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
    if ($res_soa) {
        while ($row = mysqli_fetch_assoc($res_soa)) {
            $key = $row['soa_policy_type'] . '_' . $row['soa_policy_id'];
            $soa_data[$key] = $row;
        }
    } else {
        echo '<div class="alert alert-danger">Failed to fetch saved SOA data: ' . mysqli_error($connection) . '</div>';
    }


    ?>
    <div id="soaResponse" style="margin-top: 15px;"></div>
    <form method="POST" action="save_soa.php" id="soaForm">
        <div class="text-end mb-3">
            <button type="submit" name="save_only" class="btn btn-sm btn-outline-primary" style="font-size: 12px;">Save Applicability</button>
            <button type="submit" name="download_excel" formaction="download_policies_excel.php" class="btn btn-sm btn-outline-success" style="font-size: 12px;">Download Statement of Applicability</button>
        </div>
        <div class="card p-3 table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th style="font-size: 12px;">Policy</th>
                        <th style="font-size: 12px;">
                            <a style="text-decoration: none !important; color: #fff !important" href="#" data-bs-placement="top" data-bs-toggle="tooltip" title="RA - Risk Assessment">RA</a>
                        </th>
                        <th style="font-size: 12px;">
                            <a style="text-decoration: none !important; color: #fff !important" href="#" data-bs-placement="top" data-bs-toggle="tooltip" title="BR/BP - Business Requirement/Best Practices">BR / BP</a>
                        </th>
                        <th style="font-size: 12px;">
                            <a style="text-decoration: none !important; color: #fff !important" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="LR/CO - Legal Requirement/Contractual Obligation">LR / CO</a>
                        </th>

                        <th style="font-size: 12px; text-align:center;">Applicable</th>
                        <th style="font-size: 12px; text-align:center;">Not Applicable</th>
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
                                <input type="hidden" id="applicable_reason_<?= $index ?>" name="applicable_reason[<?= $index ?>]" value="<?= htmlspecialchars($saved['soa_applicable_reason'] ?? '') ?>">
                            </td>
                            <td><input type="checkbox" class="form-check-input" name="soa_ra[<?= $index ?>]" <?= isset($saved) && $saved['soa_ra'] ? 'checked' : '' ?>></td>
                            <td><input type="checkbox" class="form-check-input" name="soa_br_bp[<?= $index ?>]" <?= isset($saved) && $saved['soa_br_bp'] ? 'checked' : '' ?>></td>
                            <td><input type="checkbox" class="form-check-input" name="soa_lr_co[<?= $index ?>]" <?= isset($saved) && $saved['soa_lr_co'] ? 'checked' : '' ?>></td>


                            <td style="text-align: center;" class="<?= $saved_applicable === 'Y' ? 'table-success' : '' ?>">
                                <input type="radio" class="form-check-input applicable-radio"
                                    data-index="<?= $index ?>" data-type="applicable"
                                    name="applicable_status[<?= $index ?>]" value="1"
                                    <?= $saved_applicable === 'Y' ? 'checked' : '' ?>>
                            </td>
                            <td style="text-align: center;" class="<?= $saved_applicable === 'N' ? 'table-danger' : '' ?>">
                                <input type="radio" class="form-check-input applicable-radio"
                                    data-index="<?= $index ?>" data-type="not_applicable"
                                    name="applicable_status[<?= $index ?>]" value="0"
                                    <?= $saved_applicable === 'N' ? 'checked' : '' ?>>
                            </td>


                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </form>
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

    <!-- Applicable Reason Modal -->
    <div class="modal fade" id="applicableReasonModal" tabindex="-1" aria-labelledby="applicableReasonModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Why is this policy applicable?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea id="modalApplicableReason" class="form-control" rows="4"></textarea>
                    <input type="hidden" id="currentApplicableIndex">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="saveApplicableReason()">Save</button>
                </div>
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
                    applicableCheckbox.closest('td').classList.add('table-success');
                    notApplicableCheckbox.closest('td').classList.remove('table-danger');
                    openApplicableModal(index);
                } else {
                    applicableCheckbox.checked = false;
                    notApplicableCheckbox.closest('td').classList.add('table-danger');
                    applicableCheckbox.closest('td').classList.remove('table-success');
                    openModal(index); // <-- Important line added
                }
            } else {
                applicableCheckbox.closest('td').classList.remove('table-success');
                notApplicableCheckbox.closest('td').classList.remove('table-danger');
            }

        });
    });

    function openApplicableModal(index) {
        document.getElementById('currentApplicableIndex').value = index;
        document.getElementById('modalApplicableReason').value = document.getElementById('applicable_reason_' + index).value;
        new bootstrap.Modal(document.getElementById('applicableReasonModal')).show();
    }

    function saveApplicableReason() {
        const index = document.getElementById('currentApplicableIndex').value;
        const reason = document.getElementById('modalApplicableReason').value;
        document.getElementById('applicable_reason_' + index).value = reason;
        bootstrap.Modal.getInstance(document.getElementById('applicableReasonModal')).hide();
    }

    document.querySelectorAll('.applicable-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            const index = this.dataset.index;
            const type = this.dataset.type;

            const applicableCell = document.querySelector(`input[name="applicable_status[${index}]"][value="1"]`).closest('td');
            const notApplicableCell = document.querySelector(`input[name="applicable_status[${index}]"][value="0"]`).closest('td');

            // Reset both
            applicableCell.classList.remove('table-success');
            notApplicableCell.classList.remove('table-danger');

            if (type === 'applicable') {
                applicableCell.classList.add('table-success');
                openApplicableModal(index);
            } else {
                notApplicableCell.classList.add('table-danger');
                openModal(index);
            }
        });
    });

    // Save justification modal
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

    // Save applicable reason modal
    function openApplicableModal(index) {
        document.getElementById('currentApplicableIndex').value = index;
        document.getElementById('modalApplicableReason').value = document.getElementById('applicable_reason_' + index).value;
        new bootstrap.Modal(document.getElementById('applicableReasonModal')).show();
    }

    function saveApplicableReason() {
        const index = document.getElementById('currentApplicableIndex').value;
        const reason = document.getElementById('modalApplicableReason').value;
        document.getElementById('applicable_reason_' + index).value = reason;
        bootstrap.Modal.getInstance(document.getElementById('applicableReasonModal')).hide();
    }

    // Validate on form submit (download only)
    document.getElementById('policyForm').addEventListener('submit', function(e) {
        const isDownload = e.submitter?.name === "download_excel";
        if (!isDownload) return;

        const radios = document.querySelectorAll("input[type='radio']:checked");
        for (let radio of radios) {
            if (radio.value === "0") { // Not Applicable
                const index = radio.name.match(/\[(\d+)\]/)[1];
                const justification = document.getElementById("justification_" + index).value.trim();
                if (!justification) {
                    alert("Justification required for non-applicable policies.");
                    e.preventDefault();
                    return false;
                }
            }
        }
    });
</script>
<?php include 'includes/footer.php'; ?>