<?php
include 'includes/header.php';
include 'includes/navbar.php';

// Month and year selection
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$selectedUser = isset($_GET['user']) ? $_GET['user'] : '';

// Days and starting day
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$firstDayOfWeek = date('w', strtotime("$year-$month-01"));

// Load .ics events if user is selected
$eventDates = [];

if (!empty($selectedUser)) {
    $icsPath = __DIR__ . "/shared-calendar/" . basename($selectedUser);
    if (file_exists($icsPath)) {
        $icsContent = file_get_contents($icsPath);
        preg_match_all('/DTSTART(?:;VALUE=DATE)?:(\d{4})(\d{2})(\d{2})/', $icsContent, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $y = $match[1];
            $m = ltrim($match[2], '0');
            $d = (int)$match[3];
            if ($y == $year && $m == $month) {
                $eventDates[] = $d;
            }
        }
    }
}

// Fetch list of .ics files in shared-calendar folder
$calendarFiles = glob(__DIR__ . "/shared-calendar/*.ics");
?>

<div class="container">
    <div class="mt-5 card p-3 w-50">
        <div class="mb-3">
            <form method="GET" action="">
                <input type="hidden" name="month" value="<?php echo $month; ?>">
                <input type="hidden" name="year" value="<?php echo $year; ?>">
                <label for="calendarSelect" class="form-label">Change Member</label>
                <select class="form-select" id="calendarSelect" name="user" onchange="this.form.submit()">
                    <option value="">-- Select a calendar --</option>
                    <?php foreach ($calendarFiles as $filePath): 
                        $filename = basename($filePath);
                    ?>
                        <option value="<?php echo htmlspecialchars($filename); ?>" <?php echo ($selectedUser === $filename) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars(pathinfo($filename, PATHINFO_FILENAME)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>

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

        <!-- Navigation Links -->
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
            $baseParams = "&user=" . urlencode($selectedUser);
            ?>
            <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear . $baseParams; ?>" class="btn btn-sm btn-warning">&laquo; Previous</a>
            <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear . $baseParams; ?>" class="btn btn-sm btn-warning">Next &raquo;</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
