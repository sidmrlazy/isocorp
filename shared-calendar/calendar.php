<?php
session_start();
$loggedInUser = $_SESSION['isms_user_name'] ?? 'unknown';  // fallback if not set
include 'includes/connection.php';
include 'includes/header.php';
include 'includes/navbar.php';
// Connect to DB with PDO
$pdo = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8mb4", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// --- Functions for ICS parsing ---

// Unfold ICS lines (concatenate folded lines)
function unfold_ics_lines(string $icsContent): string
{
    return preg_replace('/\r\n[ \t]/', '', $icsContent);
}

// Parse ICS date-time strings into Y-m-d H:i:s or null
function parse_ics_datetime(string $dtString): ?string
{
    if (preg_match('/^(\d{8})T?(\d{6})?Z?$/', $dtString, $m)) {
        $datePart = $m[1];
        $timePart = $m[2] ?? '000000';

        $dt = DateTime::createFromFormat('YmdHis', $datePart . $timePart, new DateTimeZone('UTC'));
        if ($dt === false) return null;
        $dt->setTimezone(new DateTimeZone(date_default_timezone_get()));
        return $dt->format('Y-m-d H:i:s');
    } elseif (preg_match('/^(\d{8})$/', $dtString, $m)) {
        $dt = DateTime::createFromFormat('Ymd', $dtString);
        if ($dt === false) return null;
        return $dt->format('Y-m-d 00:00:00');
    }
    return null;
}

// Parse VEVENTs from unfolded ICS content
function parse_ics_events(string $icsContent): array
{
    $events = [];
    preg_match_all('/BEGIN:VEVENT(.*?)END:VEVENT/s', $icsContent, $matches);

    foreach ($matches[1] as $eventText) {
        $extractField = function (string $fieldName, string $text): ?string {
            if (preg_match('/^' . preg_quote($fieldName, '/') . '(?:;[^:]*)?:(.*)$/mi', $text, $m)) {
                return trim($m[1]);
            }
            return null;
        };

        $summary = $extractField('SUMMARY', $eventText) ?? '';
        $description = $extractField('DESCRIPTION', $eventText) ?? '';
        $location = $extractField('LOCATION', $eventText) ?? '';

        $dtstartRaw = $extractField('DTSTART', $eventText);
        $dtendRaw = $extractField('DTEND', $eventText);

        $dtstart = $dtstartRaw ? parse_ics_datetime($dtstartRaw) : null;
        $dtend = $dtendRaw ? parse_ics_datetime($dtendRaw) : null;

        $organizer = '';
        if (preg_match('/^ORGANIZER(?:;CN=([^:]+))?:mailto:([^\r\n]+)/mi', $eventText, $m)) {
            $organizer = trim($m[1] ?? $m[2]);
        }

        $events[] = [
            'SUMMARY' => $summary,
            'DESCRIPTION' => $description,
            'LOCATION' => $location,
            'DTSTART' => $dtstart,
            'DTEND' => $dtend,
            'ORGANIZER' => $organizer,
        ];
    }
    return $events;
}

// --- Handle ICS File Upload and Parsing ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['icsfile'])) {
    $file = $_FILES['icsfile'];
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $safeName = basename($file['name']);
    $targetPath = $uploadDir . $safeName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $icsData = file_get_contents($targetPath);
        $icsData = unfold_ics_lines($icsData);

        $events = parse_ics_events($icsData);

        foreach ($events as $ev) {
            $stmt = $pdo->prepare("INSERT INTO in3_calendar 
        (in3_c_filename, in3_c_summary, in3_c_description, in3_c_location, in3_c_start_datetime, in3_c_end_datetime, in3_c_owner) 
        VALUES (:filename, :summary, :description, :location, :start, :end, :owner)");
            $stmt->execute([
                ':filename' => $safeName,
                ':summary' => mb_convert_encoding($ev['SUMMARY'], 'UTF-8'),
                ':description' => mb_convert_encoding($ev['DESCRIPTION'], 'UTF-8'),
                ':location' => $ev['LOCATION'],
                ':start' => $ev['DTSTART'],
                ':end' => $ev['DTEND'],
                ':owner' => $loggedInUser
            ]);
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "<p style='color:red;'>Failed to upload the file.</p>";
    }
}

// If a user is selected from dropdown, filter by owner
$selectedUser = $_POST['selected_owner'] ?? null;

$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
$firstDayTimestamp = mktime(0, 0, 0, $month, 1, $year);
$firstDayWeekday = (int)date('N', $firstDayTimestamp);
$daysInMonth = (int)date('t', $firstDayTimestamp);

$startMonth = "$year-$month-01 00:00:00";
$endMonth = date('Y-m-t 23:59:59', $firstDayTimestamp);

if ($selectedUser) {
    $stmt = $pdo->prepare("SELECT * FROM in3_calendar WHERE in3_c_start_datetime BETWEEN :start AND :end AND in3_c_owner = :owner ORDER BY in3_c_start_datetime ASC");
    $stmt->execute([':start' => $startMonth, ':end' => $endMonth, ':owner' => $selectedUser]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM in3_calendar WHERE in3_c_start_datetime BETWEEN :start AND :end ORDER BY in3_c_start_datetime ASC");
    $stmt->execute([':start' => $startMonth, ':end' => $endMonth]);
}

$eventsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
$eventsByDay = [];
foreach ($eventsRaw as $event) {
    $day = date('Y-m-d', strtotime($event['in3_c_start_datetime']));
    $eventsByDay[$day][] = $event;
}

$todayDate = date('Y-m-d');

// Calculate previous and next month for pagination links
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

// --- Handle Calendar Update: Delete existing events and upload new ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_calendar' && isset($_FILES['icsfile_update'])) {
    $file = $_FILES['icsfile_update'];
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $safeName = basename($file['name']);
    $targetPath = $uploadDir . $safeName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $icsData = file_get_contents($targetPath);
        $icsData = unfold_ics_lines($icsData);
        $events = parse_ics_events($icsData);

        // DELETE existing events by the logged-in user
        $deleteStmt = $pdo->prepare("DELETE FROM in3_calendar WHERE in3_c_owner = :owner");
        $deleteStmt->execute([':owner' => $loggedInUser]);

        // INSERT new events
        foreach ($events as $ev) {
            $stmt = $pdo->prepare("INSERT INTO in3_calendar 
                (in3_c_filename, in3_c_summary, in3_c_description, in3_c_location, in3_c_start_datetime, in3_c_end_datetime, in3_c_owner) 
                VALUES (:filename, :summary, :description, :location, :start, :end, :owner)");
            $stmt->execute([
                ':filename' => $safeName,
                ':summary' => mb_convert_encoding($ev['SUMMARY'], 'UTF-8'),
                ':description' => mb_convert_encoding($ev['DESCRIPTION'], 'UTF-8'),
                ':location' => $ev['LOCATION'],
                ':start' => $ev['DTSTART'],
                ':end' => $ev['DTEND'],
                ':owner' => $loggedInUser
            ]);
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "<p style='color:red;'>Failed to upload the updated file.</p>";
    }
}

?>

<div class="container mb-5">
    <div class="row mt-5 mb-3">
        <!-- ========= CALENDAR .ICS FILE UPLOAD FORM ========= -->
        <form method="post" enctype="multipart/form-data" class="card m-1 p-3 col-md-3">
            <div class="mb-3">
                <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Upload Calendar File</label>
                <input style="font-size: 12px !important;" type="file" name="icsfile" accept=".ics" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <button type="submit" class="btn btn-sm btn-outline-success" style="font-size: 12px !important;">Upload</button>
        </form>

        <!-- ========= DROPDOWN TO SHOW SELECTED USERS CALENDAR ========= -->
        <?php
        // Fetch unique calendar owners from the database
        $ownersStmt = $pdo->query("SELECT DISTINCT in3_c_owner FROM in3_calendar ORDER BY in3_c_owner ASC");
        $owners = $ownersStmt->fetchAll(PDO::FETCH_COLUMN);

        // Capture selected user from POST (or fallback to null)
        $selectedUser = $_POST['selected_owner'] ?? null;
        ?>
        <form action="" method="POST" class="col-md-3 card m-1 p-3">
            <div class="mb-3">
                <label style="font-size: 12px !important;" class="form-label">Select a leader to get calendar details</label>
                <select name="selected_owner" style="font-size: 12px !important;" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Select a user --</option>
                    <?php foreach ($owners as $owner): ?>
                        <option value="<?= htmlspecialchars($owner) ?>" <?= ($owner === $selectedUser ? 'selected' : '') ?>>
                            <?= htmlspecialchars($owner) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <!-- ========= UPDATE EXISTING CALENDAR FORM ========= -->
        <form method="post" enctype="multipart/form-data" class="card m-1 p-3 col-md-3" onsubmit="return confirm('This will delete your existing calendar events. Are you sure you want to proceed?');">
            <div class="mb-3">
                <label style="font-size: 12px !important;" class="form-label">Update Existing Calendar</label>
                <input style="font-size: 12px !important;" type="file" name="icsfile_update" accept=".ics" required class="form-control">
            </div>
            <input type="hidden" name="action" value="update_calendar">
            <button type="submit" class="btn btn-sm btn-outline-success" style="font-size: 12px !important;">Update Existing Calendar</button>
        </form>


    </div>

    <!-- ========= SHOW PAGINATION ========= -->
    <div class="d-flex justify-content-between align-items-center my-3 mt-5">
        <a class="btn btn-sm btn-outline-success" href="?month=<?= $prevMonth ?>&year=<?= $prevYear ?><?= $selectedUser ? '&selected_owner=' . urlencode($selectedUser) : '' ?>">← Previous</a>
        <h6 class="m-0">Calendar for <?= date('F Y', $firstDayTimestamp); ?></h6>
        <a class="btn btn-sm btn-outline-success" href="?month=<?= $nextMonth ?>&year=<?= $nextYear ?><?= $selectedUser ? '&selected_owner=' . urlencode($selectedUser) : '' ?>">Next →</a>
    </div>


    <div class="table-responsive mt-5">
        <div>
            <!-- <p>Showing Calendar for: <strong><?php echo htmlspecialchars($owner) ?></strong></p> -->
        </div>
        <!-- <h6>Calendar for <?php echo date('F Y', $firstDayTimestamp); ?></h6> -->
        <table class="calendar  table table-bordered">
            <thead>
                <tr>
                    <th>Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                    <th>Sat</th>
                    <th>Sun</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Trailing days from previous month
                $prevMonthDays = ($firstDayWeekday - 1 + 7) % 7;
                $prevMonthTimestamp = mktime(0, 0, 0, $month - 1, 1, $year);
                $daysInPrevMonth = (int)date('t', $prevMonthTimestamp);
                $dayCounter = 1;
                $printedCells = 0;

                while ($dayCounter <= $daysInMonth || ($printedCells % 7) !== 0) {
                    echo "<tr>";
                    for ($i = 0; $i < 7; $i++) {
                        if ($printedCells < $prevMonthDays) {
                            // Previous month's trailing days
                            $prevDate = sprintf("%04d-%02d-%02d", $prevYear, $prevMonth, $daysInPrevMonth - $prevMonthDays + $printedCells + 1);
                            echo '<td class="bg-light text-muted"><small>' . date('j', strtotime($prevDate)) . '</small></td>';
                        } elseif ($dayCounter <= $daysInMonth) {
                            $currentDate = sprintf("%04d-%02d-%02d", $year, $month, $dayCounter);
                            $cellEvents = $eventsByDay[$currentDate] ?? [];

                            $isToday = $currentDate === $todayDate;
                            $eventDataJson = htmlspecialchars(json_encode($cellEvents), ENT_QUOTES, 'UTF-8');

                            echo '<td class="date-cell ' . ($isToday ? 'bg-warning-subtle' : '') . '" data-date="' . $currentDate . '" data-events="' . $eventDataJson . '">';
                            echo '<div class="day-number fw-bold">' . $dayCounter . '</div>';

                            foreach ($cellEvents as $ev) {
                                echo '<div class="badge bg-primary text-wrap text-light my-1 d-block" title="' . htmlspecialchars($ev['in3_c_summary']) . '">' . htmlspecialchars($ev['in3_c_summary']) . '</div>';
                            }

                            echo '</td>';
                            $dayCounter++;
                        } else {
                            // Next month's leading days
                            $nextDate = sprintf("%04d-%02d-%02d", $nextYear, $nextMonth, $printedCells - $daysInMonth - $prevMonthDays + 1);
                            echo '<td class="bg-light text-muted"><small>' . date('j', strtotime($nextDate)) . '</small></td>';
                        }

                        $printedCells++;
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Event Details for <span id="modalDate"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Event details will be injected here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Show modal with event details on clicking a date cell
    document.querySelectorAll('.date-cell').forEach(cell => {
        cell.addEventListener('click', () => {
            const date = cell.getAttribute('data-date');
            const events = JSON.parse(cell.getAttribute('data-events'));

            const modalDate = document.getElementById('modalDate');
            const modalBody = document.getElementById('modalBody');

            modalDate.textContent = date;

            if (events.length === 0) {
                modalBody.innerHTML = '<p>No events for this date.</p>';
            } else {
                let html = '<div class="list-group">';
                events.forEach((ev, i) => {
                    html += `<div class="list-group-item mb-3">
                        <h5>${escapeHtml(ev.in3_c_summary || 'No Title')}</h5>
                        <p><strong>Description:</strong> ${formatDescription(ev.in3_c_description || 'N/A')}</p>
                        <p><strong>Location:</strong> ${escapeHtml(ev.in3_c_location || 'N/A')}</p>
                        <p><strong>Start:</strong> ${escapeHtml(ev.in3_c_start_datetime || 'N/A')}</p>
                        <p><strong>End:</strong> ${escapeHtml(ev.in3_c_end_datetime || 'N/A')}</p>
                        
                    </div>`;
                });
                html += '</div>';
                modalBody.innerHTML = html;
            }

            const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
            eventModal.show();
        });
    });

    // Escape plain text to prevent HTML injection (used for all fields except description)
    function escapeHtml(text) {
        if (!text) return '';
        return text.replace(/[&<>"'`=\/]/g, function(s) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;',
                '/': '&#x2F;',
                '`': '&#x60;',
                '=': '&#x3D;'
            } [s];
        });
    }

    // Format and sanitize description (preserve <br> but escape everything else)
    // Escape everything safely except line breaks
    function formatDescription(text) {
        if (!text) return '';

        // First decode escaped ICS characters
        let unescaped = text
            .replace(/\\\\/g, '\\') // double backslash
            .replace(/\\n/g, '\n') // newline
            .replace(/\\,/g, ',') // comma
            .replace(/\\;/g, ';') // semicolon
            .replace(/\\r/g, ''); // carriage return

        // Escape HTML
        let escaped = unescaped.replace(/[&<>"']/g, function(s) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;',
            } [s];
        });

        // Finally, replace newlines with <br> to preserve line breaks
        return escaped.replace(/\n/g, '<br>');
    }
</script>

<!-- <p><strong>Organizer:</strong> ${escapeHtml(ev.in3_c_owner || 'N/A')}</p> -->
<?php include 'includes/footer.php' ?>