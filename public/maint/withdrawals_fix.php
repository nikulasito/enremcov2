<?php
/**
 * Maintenance tool for normalizing withdrawals.employees_id to users.id
 * and (optionally) creating a clean withdrawals_v2 table.
 *
 * Place at: public/maint/withdrawals_fix.php
 * Visit:    /maint/withdrawals_fix.php?token=YOUR_SECRET
 *
 * IMPORTANT: Delete this file after use.
 */

declare(strict_types=1);

// ---------- CONFIG ----------
$SECRET_TOKEN = 'nicoistheverymuchgwapo'; // <<< CHANGE THIS
// ---------------------------

if (!isset($_GET['token']) || $_GET['token'] !== $SECRET_TOKEN) {
    http_response_code(403);
    echo "Forbidden";
    exit;
}

// Bootstrap Laravel so we can use DB facade & your existing connection
$base = dirname(__DIR__, 1);               // public/
$root = dirname($base);                     // project root
require $root . '/vendor/autoload.php';
$app = require $root . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$action   = $_GET['action']   ?? '';
$confirm  = isset($_GET['confirm']) && $_GET['confirm'] === '1';
$dryRun   = !$confirm;
$now      = date('Y-m-d H:i:s');

function box($title, $bodyHtml) {
    echo '<div style="border:1px solid #ddd;padding:16px;margin:16px;border-radius:8px;background:#fff">';
    echo "<h3 style='margin-top:0'>{$title}</h3>";
    echo $bodyHtml;
    echo '</div>';
}

echo '<!doctype html><html><head><meta charset="utf-8"><title>Withdrawals Maintenance</title>';
echo '<style>body{font-family:system-ui,Arial,sans-serif;background:#f6f7f9;padding:24px;color:#222} a.button{display:inline-block;background:#0d6efd;color:#fff;padding:10px 14px;border-radius:6px;text-decoration:none;margin-right:8px} .secondary{background:#6c757d} .danger{background:#dc3545} pre{background:#f3f4f6;border:1px solid #e5e7eb;padding:10px;border-radius:6px;overflow:auto;}</style>';
echo '</head><body>';
echo '<h1>Withdrawals Maintenance</h1>';
echo '<p>Current time: ' . h($now) . '</p>';

try {
    // ------------- Quick stats -------------
    $uCnt = DB::table('users')->count();
    $wCnt = DB::table('withdrawals')->count();

    // Rows that already link by users.id
    $okById = DB::table('withdrawals as w')
        ->join('users as u', 'u.id', '=', 'w.employees_id')
        ->count();

    // Rows that can link by users.employee_ID (string codes)
    $okByEmpCode = DB::table('withdrawals as w')
        ->join('users as u', 'u.employee_ID', '=', 'w.employees_id')
        ->count();

    // Rows that could link by name+office (fallback)
    $okByNameOffice = DB::table('withdrawals as w')
        ->join('users as u', function($j){
            $j->on('u.name', '=', 'w.name')->on('u.office', '=', 'w.office');
        })->count();

    // Rows with no match at all (bad)
    $noMatch = $wCnt - ($okById + $okByEmpCode + $okByNameOffice);

    box('Database Stats', '
        <ul>
          <li>Total users: <b>' . h($uCnt) . '</b></li>
          <li>Total withdrawals: <b>' . h($wCnt) . '</b></li>
          <li>match by <code>users.id</code>: <b>' . h($okById) . '</b></li>
          <li>match by <code>users.employee_ID</code> (old code): <b>' . h($okByEmpCode) . '</b></li>
          <li>match by <code>name+office</code>: <b>' . h($okByNameOffice) . '</b></li>
          <li>no match (need manual review): <b>' . h($noMatch) . '</b></li>
        </ul>
    ');

    // ------------- Actions -------------
    if ($action === '') {
        $baseUrl = strtok($_SERVER['REQUEST_URI'], '?');
        $qsBase = '?token=' . urlencode($_GET['token']);
        box('What do you want to do?', '
            <p><a class="button" href="' . h($baseUrl . $qsBase . '&action=fix-in-place') . '">Fix in place (normalize and add FK)</a></p>
            <p><a class="button secondary" href="' . h($baseUrl . $qsBase . '&action=new-table') . '">Create new table & backfill (clean slate)</a></p>
            <p><a class="button danger" href="' . h($baseUrl . $qsBase . '&action=show-bad') . '">Preview rows with NO match (no changes)</a></p>
        ');
        echo '</body></html>'; exit;
    }

    // ---------- Preview rows with no match ----------
    if ($action === 'show-bad') {
        $rows = DB::select("
            SELECT w.*
            FROM withdrawals w
            LEFT JOIN users u
              ON (u.id = w.employees_id)
               OR (u.employee_ID = w.employees_id)
               OR (u.name = w.name AND u.office = w.office)
            WHERE u.id IS NULL
            LIMIT 200
        ");
        $html = '<p>Showing up to 200 withdrawals that don\'t match any user. Fix these (or their user records) and rerun.</p>';
        $html .= '<pre>'.h(json_encode($rows, JSON_PRETTY_PRINT)).'</pre>';
        box('Rows with NO match', $html);
        echo '<p><a class="button" href="?token='.h($_GET['token']).'">Back</a></p>';
        echo '</body></html>'; exit;
    }

    // ---------- Option A: Fix in place ----------
    if ($action === 'fix-in-place') {
        $baseUrl = strtok($_SERVER['REQUEST_URI'], '?');
        $qsBase = '?token=' . urlencode($_GET['token']) . '&action=fix-in-place';

        $sqls = [
            // 1) Backup table once
            "CREATE TABLE IF NOT EXISTS withdrawals_backup LIKE withdrawals",
            "INSERT IGNORE INTO withdrawals_backup SELECT * FROM withdrawals",

            // 2) Update by users.id
            "UPDATE withdrawals w
             JOIN users u ON u.id = w.employees_id
             SET w.employees_id = u.id",

            // 3) Update by users.employee_ID (old code -> id)
            "UPDATE withdrawals w
             JOIN users u ON u.employee_ID = w.employees_id
             SET w.employees_id = u.id",

            // 4) Update by name+office
            "UPDATE withdrawals w
             JOIN users u ON u.name = w.name AND u.office = w.office
             SET w.employees_id = u.id",

            // 5) Add FK (if not already). We must ensure column type is unsigned big int.
            "ALTER TABLE withdrawals MODIFY employees_id BIGINT UNSIGNED",
            "ALTER TABLE withdrawals 
             ADD INDEX idx_withdraw_employees_id (employees_id)",
            // ADD CONSTRAINT guarded: ignore if it already exists
            // We'll try/catch when running.
        ];

        $fkSql = "ALTER TABLE withdrawals
                  ADD CONSTRAINT fk_withdrawals_employees_id
                  FOREIGN KEY (employees_id) REFERENCES users(id)
                  ON UPDATE CASCADE ON DELETE RESTRICT";

        if ($dryRun) {
            $html = "<p><b>DRY RUN</b> – nothing will be changed.</p><p>These statements will be executed in order:</p><pre>" .
                h(implode(";\n\n", $sqls) . ";\n\n" . $fkSql . ";") .
                "</pre>";
            $html .= '<p><a class="button" href="' . h($baseUrl . $qsBase . '&confirm=1') . '">Run now</a> ';
            $html .= '<a class="button secondary" href="?token=' . h($_GET['token']) . '">Cancel</a></p>';
            box('Fix in place – Preview', $html);
            echo '</body></html>'; exit;
        }

        // Execute for real
        DB::beginTransaction();
        try {
            foreach ($sqls as $sql) {
                DB::statement($sql);
            }
            try {
                DB::statement($fkSql);
            } catch (\Throwable $e) {
                // ignore if FK exists
            }
            DB::commit();
            box('Fix in place – Done', '<p>Normalization completed successfully. Review your savings page now. If anything looks off, restore from <code>withdrawals_backup</code>.</p>');
        } catch (\Throwable $e) {
            DB::rollBack();
            box('Fix in place – Error', '<pre>'.h($e->getMessage()).'</pre>');
        }
        echo '<p><a class="button" href="?token='.h($_GET['token']).'">Back</a></p>';
        echo '</body></html>'; exit;
    }

    // ---------- Option B: New table & backfill ----------
    if ($action === 'new-table') {
        $baseUrl = strtok($_SERVER['REQUEST_URI'], '?');
        $qsBase = '?token=' . urlencode($_GET['token']) . '&action=new-table';

        $create = "
            CREATE TABLE IF NOT EXISTS withdrawals_v2 (
                withdrawals_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                employees_id BIGINT UNSIGNED NOT NULL,
                name VARCHAR(255) NULL,
                office VARCHAR(255) NULL,
                date_of_withdrawal DATE NOT NULL,
                amount_withdrawn DECIMAL(12,2) NOT NULL,
                reference_no VARCHAR(255) NULL,
                covered_month TINYINT UNSIGNED NULL,
                covered_year SMALLINT UNSIGNED NULL,
                remarks TEXT NULL,
                date_created DATE NULL,
                date_updated TIMESTAMP NULL DEFAULT NULL,
                INDEX idx_emp (employees_id),
                INDEX idx_period (covered_year, covered_month),
                INDEX idx_ref (reference_no),
                CONSTRAINT fk_w2_emp FOREIGN KEY (employees_id)
                    REFERENCES users(id) ON UPDATE CASCADE ON DELETE RESTRICT
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        $insert = "
            INSERT INTO withdrawals_v2 (
                employees_id, name, office, date_of_withdrawal, amount_withdrawn,
                reference_no, covered_month, covered_year, remarks, date_created, date_updated
            )
            SELECT
                u.id AS employees_id,
                COALESCE(w.name, u.name) AS name,
                COALESCE(w.office, u.office) AS office,
                w.date_of_withdrawal,
                w.amount_withdrawn,
                w.reference_no,
                w.covered_month,
                w.covered_year,
                w.remarks,
                w.date_created,
                w.date_updated
            FROM withdrawals w
            JOIN users u
              ON (u.id = w.employees_id)
               OR (u.employee_ID = w.employees_id)
               OR (u.name = w.name AND u.office = w.office)
        ";

        $unmatchedPreview = "
            SELECT w.*
            FROM withdrawals w
            LEFT JOIN users u
              ON (u.id = w.employees_id)
               OR (u.employee_ID = w.employees_id)
               OR (u.name = w.name AND u.office = w.office)
            WHERE u.id IS NULL
            LIMIT 200
        ";

        if ($dryRun) {
            $bad = DB::select($unmatchedPreview);
            $html = "<p><b>DRY RUN</b> – will create <code>withdrawals_v2</code> and copy matching rows only.</p>";
            $html .= "<p>Unmatched sample (fix these or their users):</p><pre>" . h(json_encode($bad, JSON_PRETTY_PRINT)) . "</pre>";
            $html .= "<p>Statements to execute:</p><pre>" . h($create . "\n\n" . $insert) . "</pre>";
            $html .= '<p><a class="button" href="' . h($baseUrl . $qsBase . '&confirm=1') . '">Run now</a> ';
            $html .= '<a class="button secondary" href="?token=' . h($_GET['token']) . '">Cancel</a></p>';
            box('New table & backfill – Preview', $html);
            echo '</body></html>'; exit;
        }

        // Execute for real
        DB::beginTransaction();
        try {
            DB::statement($create);
            DB::statement($insert);
            DB::commit();

            $html = '<p>Created <code>withdrawals_v2</code> and copied all rows that map to a valid <code>users.id</code>.</p>';
            $html .= '<p><b>Next steps (optional):</b></p>';
            $html .= '<pre>-- backup & swap when ready:
START TRANSACTION;
CREATE TABLE withdrawals_old LIKE withdrawals;
INSERT INTO withdrawals_old SELECT * FROM withdrawals;
RENAME TABLE withdrawals TO withdrawals_trash, withdrawals_v2 TO withdrawals;
COMMIT;</pre>';
            box('New table & backfill – Done', $html);
        } catch (\Throwable $e) {
            DB::rollBack();
            box('New table & backfill – Error', '<pre>'.h($e->getMessage()).'</pre>');
        }

        echo '<p><a class="button" href="?token='.h($_GET['token']).'">Back</a></p>';
        echo '</body></html>'; exit;
    }

    echo '<p><a class="button" href="?token='.h($_GET['token']).'">Back</a></p>';

} catch (\Throwable $e) {
    echo '<div style="color:#b91c1c"><b>Error:</b><pre>'.h($e->getMessage()).'</pre></div>';
    echo '</body></html>';
}
