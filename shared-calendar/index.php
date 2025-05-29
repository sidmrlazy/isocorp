<?php
include 'includes/header.php';
include 'includes/navbar.php';

// Month and year selection
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$selectedUser = isset($_GET['user']) ? $_GET['user'] : '';

// Handle file upload
$uploadMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['ics_file'])) {
    $uploadDir = realpath(__DIR__ . '/shared-calendar/uploads') . '/';
    $file = $_FILES['ics_file'];

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($ext !== 'ics') {
        $uploadMsg = '<div class="alert alert-danger">Only .ics files are allowed.</div>';
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $uploadMsg = '<div class="alert alert-danger">Error uploading file.</div>';
    } elseif (!is_writable($uploadDir)) {
        $uploadMsg = '<div class="alert alert-danger">Upload folder is not writable.</div>';
    } else {
        // Sanitize and safely name the file
        $safeName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', basename($file['name']));
        $targetPath = $uploadDir . $safeName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            header("Location: ?month=$month&year=$year&user=" . urlencode($safeName));
            exit;
        } else {
            $uploadMsg = '<div class="alert alert-danger">Failed to move uploaded file.</div>';
        }
    }
}

// Days and first day logic
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$firstDayOfWeek = date('w', strtotime("$year-$month-01"));

// Load events from selected .ics file
$eventDates = [];
if (!empty($selectedUser)) {
    $icsPath = __DIR__ . "/shared-calendar/uploads/" . basename($selectedUser);
    if (file_exists($icsPath)) {
        $icsContent = file_get_contents($icsPath);
        preg_match_all('/DTSTART(?:;VALUE=DATE)?:(\d{4})(\d{2})(\d{2})/', $icsContent, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $y = $match[1];
            $m = (int)$match[2];
            $d = (int)$match[3];
            if ($y == $year && $m == $month) {
                $eventDates[] = $d;
            }
        }
    }
}

// Fetch .ics files
$calendarFiles = glob(__DIR__ . "/shared-calendar/uploads/*.ics");
?>

<div class="container">
    <div class="row mt-5 m-1">
        <!-- Calendar Selector -->
        <div class="card p-3 mb-3 col-md-6">
            <form method="GET" action="">
                <input type="hidden" name="month" value="<?php echo htmlspecialchars($month); ?>">
                <input type="hidden" name="year" value="<?php echo htmlspecialchars($year); ?>">
                <label for="calendarSelect" class="form-label">Change Member</label>
                <select class="form-select" id="calendarSelect" name="user" onchange="this.form.submit()">
                    <option value="">-- Select a calendar --</option>
                    <?php foreach ($calendarFiles as $filePath):
                        $filename = basename($filePath);
                        $isSelected = ($selectedUser === $filename) ? 'selected' : '';
                    ?>
                        <option value="<?php echo htmlspecialchars($filename); ?>" <?php echo $isSelected; ?>>
                            <?php echo htmlspecialchars(pathinfo($filename, PATHINFO_FILENAME)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <!-- Upload Calendar -->
        <div class="card p-3 mb-3 col-md-6">
            <form method="POST" enctype="multipart/form-data">
                <label for="icsFile" class="form-label">Upload Your Calendar (.ics)</label>
                <input type="file" class="form-control" id="icsFile" name="ics_file" accept=".ics" required>
                <button type="submit" class="btn btn-primary mt-2">Upload</button>
            </form>
            <?php if (!empty($uploadMsg)) echo $uploadMsg; ?>
        </div>
    </div>

    <!-- Calendar Table -->
    <div class="mt-3 table-responsive card p-3">
        <div class="d-flex justify-content-start align-items-center">
            <h6 class="text-center mb-4"><?php echo date('F Y', strtotime("$year-$month-01")); ?></h6>
        </div>
        <table class="table table-bordered table-striped table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Sun</th>
                    <th scope="col">Mon</th>
                    <th scope="col">Tue</th>
                    <th scope="col">Wed</th>
                    <th scope="col">Thu</th>
                    <th scope="col">Fri</th>
                    <th scope="col">Sat</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $day = 1;
                for ($row = 0; $row < 6; $row++) {
                    echo "<tr>";
                    for ($col = 0; $col < 7; $col++) {
                        if ($row === 0 && $col < $firstDayOfWeek) {
                            echo "<td></td>";
                        } elseif ($day > $daysInMonth) {
                            echo "<td></td>";
                        } else {
                            $highlight = in_array($day, $eventDates) ? 'bg-warning fw-bold' : '';
                            echo "<td class='$highlight'>$day</td>";
                            $day++;
                        }
                    }
                    echo "</tr>";
                    if ($day > $daysInMonth) break;
                }
                ?>
            </tbody>
        </table>

        <!-- Navigation -->
        <div class="d-flex justify-content-center gap-3 mt-3">
            <?php
            $prevMonth = $month - 1;
            $prevYear = $year;
            if ($prevMonth < 1) {
                $prevMonth = 12;
                $prevYear--;
            }

            $nextMonth = $month + 1;
            $nextYear = $year;
            if ($nextMonth > 12) {
                $nextMonth = 1;
                $nextYear++;
            }

            $userParam = !empty($selectedUser) ? '&user=' . urlencode($selectedUser) : '';
            ?>
            <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear . $userParam; ?>" class="btn btn-sm btn-warning">&laquo; Previous</a>
            <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear . $userParam; ?>" class="btn btn-sm btn-warning">Next &raquo;</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
