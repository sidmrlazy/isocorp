<?php
include 'includes/connection.php';
include 'includes/header.php';
include 'includes/navbar.php';
?>
<style>
    table.calendar {
        table-layout: fixed;
    }

    table.calendar th,
    table.calendar td {
        width: 14.28%;
        vertical-align: top;
        border: 1px solid #ccc;
        padding: 5px;
        height: 120px;
        cursor: pointer;
    }

    .day-number {
        font-weight: bold;
    }

    .event-summary {
        margin-top: 4px;
        font-size: 0.85em;
        background-color: #d9edf7;
        border-radius: 3px;
        padding: 2px 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>



<?php


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
                ':summary' => $ev['SUMMARY'],
                ':description' => $ev['DESCRIPTION'],
                ':location' => $ev['LOCATION'],
                ':start' => $ev['DTSTART'],
                ':end' => $ev['DTEND'],
                ':owner' => $ev['ORGANIZER'] ?: 'unknown'
            ]);
        }
        // Redirect to self to avoid reupload on refresh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "<p style='color:red;'>Failed to upload the file.</p>";
    }
}

// --- Prepare calendar display for current month/year or user selection ---
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

// Get first day of month and total days
$firstDayTimestamp = mktime(0, 0, 0, $month, 1, $year);
$firstDayWeekday = (int)date('N', $firstDayTimestamp); // 1 (Mon) to 7 (Sun)
$daysInMonth = (int)date('t', $firstDayTimestamp);

// Fetch all events for the month
$startMonth = "$year-$month-01 00:00:00";
$endMonth = date('Y-m-t 23:59:59', $firstDayTimestamp);

$stmt = $pdo->prepare("SELECT * FROM in3_calendar WHERE in3_c_start_datetime BETWEEN :start AND :end ORDER BY in3_c_start_datetime ASC");
$stmt->execute([':start' => $startMonth, ':end' => $endMonth]);
$eventsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize events by day (Y-m-d)
$eventsByDay = [];
foreach ($eventsRaw as $event) {
    $day = date('Y-m-d', strtotime($event['in3_c_start_datetime']));
    $eventsByDay[$day][] = $event;
}

// --- HTML output starts here ---
?>

<div class="container">
    <h2>Upload ICS Calendar File</h2>
    <form method="post" enctype="multipart/form-data" class="mb-4">
        <input type="file" name="icsfile" accept=".ics" required />
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <h3>Calendar for <?php echo date('F Y', $firstDayTimestamp); ?></h3>
    <div class="table-responsive card p-3">
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
                // Calculate blanks before first day (week starts Monday=1)
                $dayCounter = 1;
                $cellsInWeek = 7;
                $printedCells = 0;

                // Row loop (weeks)
                while ($dayCounter <= $daysInMonth) {
                    echo "<tr>";
                    for ($weekday = 1; $weekday <= 7; $weekday++) {
                        if (($printedCells + 1) < $firstDayWeekday && $dayCounter === 1) {
                            // Empty cells before first day
                            echo "<td></td>";
                            $printedCells++;
                        } elseif ($dayCounter <= $daysInMonth) {
                            $currentDate = sprintf("%04d-%02d-%02d", $year, $month, $dayCounter);
                            $cellEvents = $eventsByDay[$currentDate] ?? [];

                            // Data attributes for JS to show modal
                            $eventDataJson = htmlspecialchars(json_encode($cellEvents), ENT_QUOTES, 'UTF-8');

                            echo '<td class="date-cell" data-date="' . $currentDate . '" data-events="' . $eventDataJson . '">';
                            echo '<div class="day-number">' . $dayCounter . '</div>';

                            // Show event summaries
                            foreach ($cellEvents as $ev) {
                                echo '<div class="event-summary" title="' . htmlspecialchars($ev['in3_c_summary']) . '">' . htmlspecialchars($ev['in3_c_summary']) . '</div>';
                            }

                            echo '</td>';
                            $dayCounter++;
                            $printedCells++;
                        } else {
                            echo "<td></td>";
                            $printedCells++;
                        }
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
                    <p><strong>Organizer:</strong> ${escapeHtml(ev.in3_c_owner || 'N/A')}</p>
                </div>`;
                });
                html += '</div>';
                modalBody.innerHTML = html;
            }

            const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
            eventModal.show();
        });
    });

    // Function to unescape and format description text from ICS
    function formatDescription(text) {
        if (!text) return '';
        // Replace escaped sequences with HTML equivalents
        let unescaped = text
            .replace(/\\n/g, '<br>') // newline
            .replace(/\\,/g, ',') // escaped comma
            .replace(/\\;/g, ';') // escaped semicolon
            .replace(/\\r/g, ''); // remove carriage returns if any
        // Escape other HTML special chars except <br>
        return unescaped.replace(/[&<>"'`=\/]/g, function(s) {
            if (s === '<' || s === '>') return s; // Allow <br> tags intact
            return {
                '&': '&amp;',
                '"': '&quot;',
                "'": '&#39;',
                '/': '&#x2F;',
                '`': '&#x60;',
                '=': '&#x3D;',
            } [s] || s;
        });
    }

    // Simple function to escape HTML in JS
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
</script>
<?php include 'includes/footer.php' ?>