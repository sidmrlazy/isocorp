<?php
include('includes/header.php');
include('includes/navbar.php');
include('includes/connection.php');

$filterDate = $_GET['date'] ?? null;
// $filterMonth = $_GET['month'] ?? null;

$query = "SELECT * FROM `iso_training`";
if ($filterDate) {
    $query .= " WHERE `training_date` = '$filterDate'";
}
$query .= " ORDER BY `training_date` DESC";
$result = $connection->query($query);

// For calendar highlighting
$calendarQuery = "SELECT DISTINCT `training_date` FROM iso_training";
$calendarResult = $connection->query($calendarQuery);
$trainingDates = [];
while ($row = $calendarResult->fetch_assoc()) {
    $trainingDates[] = $row['training_date'];
}
$jsonDates = json_encode($trainingDates);
?>

<div class="dashboard-container">
    <div class="screen-name-container">
        <h1>TRAINING</h1>
        <h2><a href="dashboard.php">Dashboard</a> > Training</h2>
    </div>
    <div class="d-flex justify-content-end mb-3 mt-3">
        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#addTrainingModal">Add Training</button>
    </div>

    <!-- ===== Add Training Modal ===== -->
    <div class="modal fade" id="addTrainingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="trainings.php" method="POST" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Add Training</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Training Date</label>
                        <input type="date" name="training_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Topic</label>
                        <input type="text" name="training_topic" class="form-control" required>
                    </div>

                    <div class="WYSIWYG-editor mb-3">
                        <textarea name="training_description" id="editorNew"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload Document</label>
                        <input type="file" name="training_document" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit_training" class="btn btn-sm btn-outline-success">Save</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    // Handle insert
    if (isset($_POST['submit_training'])) {
        $training_topic = $_POST['training_topic'];
        $training_description = $_POST['training_description'];
        $training_date = $_POST['training_date'];
        $training_created_by = $_SESSION['user_name'];
        $training_created_at = date('Y-m-d H:i:s');

        $training_document_path = '';
        if (isset($_FILES['training_document']) && $_FILES['training_document']['error'] == 0) {
            $uploadDir = 'uploads/trainings/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $training_document_path = $uploadDir . basename($_FILES['training_document']['name']);
            move_uploaded_file($_FILES['training_document']['tmp_name'], $training_document_path);
        }

        $stmt = $connection->prepare("INSERT INTO iso_training (training_topic, training_description, training_date, training_document_path, training_created_at, training_created_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $training_topic, $training_description, $training_date, $training_document_path, $training_created_at, $training_created_by);
        // $stmt->execute();
        // echo "<script>location.href='trainings.php';</script>";
        if ($stmt->execute()) {
            echo "<script>location.href='trainings.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    if (isset($_POST['delete_training'])) {
        $delete_id = $_POST['delete_training_id'];

        // Optional: delete associated file
        $fileQuery = $connection->prepare("SELECT training_document_path FROM iso_training WHERE training_id = ?");
        $fileQuery->bind_param("i", $delete_id);
        $fileQuery->execute();
        $fileResult = $fileQuery->get_result();
        if ($fileResult && $fileRow = $fileResult->fetch_assoc()) {
            $filePath = $fileRow['training_document_path'];
            if (!empty($filePath) && file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $deleteQuery = $connection->prepare("DELETE FROM iso_training WHERE training_id = ?");
        $deleteQuery->bind_param("i", $delete_id);
        $deleteQuery->execute();
        echo "<script>location.href='trainings.php';</script>";
    }

    ?>

    <div class="row">
        <!-- LEFT TABLE -->
        <div class="col-md-6 mb-5">
            <div class="card p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <form method="GET" class="row g-2 align-items-center">
                        <div class="col-auto">
                            <label for="date" class="col-form-label">Filter by Date:</label>
                        </div>
                        <div class="col-auto">
                            <input type="date" id="date" name="date" class="form-control form-control-sm" value="<?= htmlspecialchars($filterDate) ?>" onchange="this.form.submit()">
                        </div>
                    </form>

                    <a href="trainings.php" class="btn btn-outline-secondary btn-sm">Reset Filter</a>
                </div>
            </div>


            <div class="table-responsive card p-3">
                <?php if ($filterDate): ?>
                    <label class="mb-3">Showing trainings for the date: <strong><?= htmlspecialchars($filterDate) ?></strong></label>
               
                <?php else: ?>
                    <label class="mb-3">Showing all trainings</label>
                <?php endif; ?>

                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th style="font-size: 12px !important;">ID</th>
                            <th style="font-size: 12px !important;">Topic</th>
                            <th style="font-size: 12px !important;">Date</th>
                            <th style="font-size: 12px !important;">View</th>
                            <th style="font-size: 12px !important;">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $serial = 1;
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td style="font-size: 12px !important;">
                                    <?php echo $serial++; ?>
                                </td>
                                <td style="font-size: 12px !important;"><?= htmlspecialchars($row['training_topic']) ?></td>
                                <td style="font-size: 12px !important;"><?= $row['training_date'] ?></td>
                                <td style="font-size: 12px !important;">
                                    <a style="font-size: 12px !important;" class="btn btn-sm btn-outline-success" href="training-details.php?id=<?= $row['training_id'] ?>">View</a>
                                </td>
                                <td>
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this training?');">
                                        <input type="hidden" name="delete_training_id" value="<?= $row['training_id'] ?>">
                                        <button type="submit" name="delete_training" class="btn btn-sm btn-outline-danger" style="font-size: 12px !important;">Delete</button>
                                    </form>
                                </td>

                            </tr>
                        <?php endwhile; ?>
                        <?php if ($result->num_rows === 0): ?>
                            <tr>
                                <td style="font-size: 12px !important;" colspan="5">No trainings found for selected date/month.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RIGHT CALENDAR -->
        <div class="col-md-6 mb-5">
            <div class="card p-3">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<script>
    const trainingDates = <?= $jsonDates ?>;

    function renderCalendar() {
        function getParam(name) {
            const url = new URL(window.location.href);
            return url.searchParams.get(name);
        }

        const today = new Date();
        let paramMonth = getParam('month');
        let currentMonth = today.getMonth();
        let currentYear = today.getFullYear();

        if (paramMonth) {
            const parts = paramMonth.split("-");
            if (parts.length === 2) {
                currentYear = parseInt(parts[0]);
                currentMonth = parseInt(parts[1]) - 1;
            }
        }

        function buildCalendar(month, year) {
            const daysEl = document.getElementById('calendar');
            daysEl.innerHTML = '';
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            let html = `<h5>Training Calendar - ${new Date(year, month).toLocaleString('default', { month: 'long' })} ${year}</h5>`;

            // Weekday headers
            const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            html += `<div class='d-flex flex-wrap mb-2'>`;
            dayNames.forEach(day => {
                html += `<div class='p-2 text-center fw-bold border ' style='width:14.2%; font-size: 12px !important'>${day}</div>`;
            });
            html += `</div><div class='d-flex flex-wrap'>`;

            // Empty slots before the 1st
            for (let i = 0; i < firstDay; i++) {
                html += `<div class='p-2 border' style='width:14.2%'></div>`;
            }

            // Days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const isHighlighted = trainingDates.includes(dateStr);
                html += `<div class='p-2 border text-center' style='width:14.2%; cursor:pointer; background:${isHighlighted ? "#266c49" : "inherit"}; color:${isHighlighted ? "white" : "inherit"}' onclick="selectDate('${dateStr}')">${day}</div>`;

            }

            html += `</div>`;
            html += `<div class="mt-4">
                        <button style="font-size: 12px !important" class="btn btn-sm btn-outline-success me-1" onclick="changeMonth(-1)">Prev</button>
                        <button style="font-size: 12px !important" class="btn btn-sm btn-outline-success" onclick="changeMonth(1)">Next</button>
                    </div>`;

            daysEl.innerHTML = html;
        }

        window.changeMonth = function(offset) {
            currentMonth += offset;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            } else if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            const newMonthParam = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}`;
            window.location = `trainings.php?month=${newMonthParam}`;
        };

        window.selectDate = function(date) {
            window.location = `trainings.php?date=${date}`;
        };

        buildCalendar(currentMonth, currentYear);
    }

    renderCalendar();
</script>
<?php include('includes/footer.php'); ?>