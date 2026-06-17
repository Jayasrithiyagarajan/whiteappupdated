<?php
// Use absolute path to ensure config is found regardless of where this file is included
$config_path = dirname(__DIR__, 2) . '/file/config.php';
if (file_exists($config_path)) {
    include_once($config_path);
} else {
    // Fallback for direct execution if dirname fails in some environments
    include_once('../../file/config.php');
}

if (!function_exists('fetchSavedData')) {
    /**
     * Fetches saved checklist results and remarks.
     * 
     * @param int $checklist_id
     * @param int $start_range Not strictly used by this implementation but present for signature compatibility
     * @param int $end_range Not strictly used by this implementation but present for signature compatibility
     * @param mysqli $conn
     * @return array
     */
    function fetchSavedData($checklist_id, $start_range, $end_range, $conn) {
        $results = [];
        $remarks = [];

        $stmt = $conn->prepare("SELECT result, checklist_remark FROM checklist_results WHERE checklist_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $checklist_id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                // The results and remarks are saved as comma-separated strings in the database
                $raw_results = explode(',', $row['result']);
                $raw_remarks = explode(',', $row['checklist_remark']);

                // Map results to indices (1, 2, 3...)
                foreach ($raw_results as $index => $val) {
                    $item_no = $index + 1;
                    // Explode by | in case multiple values (PASS|FAIL) were stored, though UI usually limits to one
                    $results[$item_no] = explode('|', $val);
                }

                // Map remarks to indices (1, 2, 3...)
                foreach ($raw_remarks as $index => $val) {
                    $item_no = $index + 1;
                    $remarks[$item_no] = $val;
                }
            }
            $stmt->close();
        }

        return ['results' => $results, 'remarks' => $remarks];
    }
}

// Only execute the following logic if the script is called directly (AJAX request for DataTables)
if (isset($_SERVER['SCRIPT_FILENAME']) && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    session_start();
    $logged_in_user = $_SESSION['username'] ?? null;
    $user_role = $_SESSION['role'] ?? null;

    $columns = ['ci.checklist_id', 'ci.checklist_no', 'ci.project_no', 'ci.inspected_by', 'ci.equipment_type', 'ci.checklist_type', 'ci.client_name'];

    $start = $_POST['start'] ?? 0;
    $length = $_POST['length'] ?? 10;
    $search = $_POST['search']['value'] ?? '';

    $where = '';
    $params = [];
    if (!in_array($user_role, ['admin', 'document controller', 'quality controller', 'reviewer'])) {
        $where = "WHERE ci.inspected_by = ?";
        $params[] = $logged_in_user;
    }

    if (!empty($search)) {
        $where .= $where ? " AND" : " WHERE";
        $where .= " (ci.checklist_no LIKE ? OR ci.project_no LIKE ? OR ci.inspected_by LIKE ? OR ci.checklist_type LIKE ? OR ci.client_name LIKE ?)";
        $params = array_merge($params, array_fill(0, 5, "%$search%"));
    }

    $totalQuery = "SELECT COUNT(*) FROM checklist_information ci LEFT JOIN project_info pi ON ci.project_no = pi.project_no $where";
    $stmt = $conn->prepare($totalQuery);
    if ($params) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }
    $stmt->execute();
    $stmt->bind_result($totalFiltered);
    $stmt->fetch();
    $stmt->close();

    $dataQuery = "SELECT ci.*, pi.project_status 
                  FROM checklist_information ci 
                  LEFT JOIN project_info pi ON ci.project_no = pi.project_no 
                  $where 
                  ORDER BY ci.created_at DESC 
                  LIMIT ?, ?";
    $params[] = (int)$start;
    $params[] = (int)$length;

    $stmt = $conn->prepare($dataQuery);
    $types = str_repeat('s', count($params) - 2) . 'ii';
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $checklist_type_raw = $row['checklist_type'];
        $formatted_checklist_type = ucwords(str_replace(['-', '_'], ' ', $checklist_type_raw));
        $checklist_no = $row['checklist_id'];

        $inspector_name = $row['inspected_by'];
        $inspector_folder = strtolower(str_replace(' ', '_', $inspector_name));
        $profile_img = "../../inspector/uploads/{$inspector_folder}/images/profile_image.jpg";

        $imgTag = "<img src='{$profile_img}' onerror=\"this.src='../../assets/img/default-profile.jpg';\" style='width:30px;height:30px;border-radius:50%;margin-right:5px;'> {$inspector_name}";

        $viewIcon = "<a href='./type/view/{$checklist_type_raw}.php?checklist_type={$checklist_type_raw}&&checklist_no={$checklist_no}'>
                        <div class='icon text-primary'><i class='et-document'></i></div>
                     </a>";

        if (strcasecmp($row['project_status'] ?? '', 'Completed') !== 0) {
            if ($user_role === 'inspector') {
                $editIcon = "<a href='./type/{$checklist_type_raw}.php?checklist_type={$checklist_type_raw}&&checklist_no={$checklist_no}'>
                                <span class='contact-edit'><img src='../../assets/img/svg/c-edit.svg' class='svg'></span>
                             </a>";
            } else {
                $editIcon = "<span class='contact-edit disabled'>
                                <img src='../../assets/img/svg/c-edit.svg' class='svg' style='opacity:0.5;cursor:not-allowed;'>
                             </span>";
            }
            $deleteIcon = "<a href='#' class='delete-checklist' data-checklist-no='{$checklist_no}'>
                                <span class='contact-close'><img src='../../assets/img/svg/c-close.svg' class='svg'></span>
                           </a>";
        } else {
            $editIcon = "<span class='contact-edit disabled'>
                            <img src='../../assets/img/svg/c-edit.svg' class='svg' style='opacity:0.5;cursor:not-allowed;'>
                         </span>";
            $deleteIcon = "<span class='contact-close disabled'>
                                <img src='../../assets/img/svg/c-close.svg' class='svg' style='opacity:0.5;cursor:not-allowed;'>
                           </span>";
        }

        $data[] = [
            'icon' => $viewIcon,
            'checklist_no' => $row['checklist_no'],
            'project_no' => $row['project_no'],
            'inspected_by' => $imgTag,
            'equipment_type' => $row['equipment_type'],
            'checklist_type' => $formatted_checklist_type,
            'client_name' => $row['client_name'],
            'action' => $editIcon . $deleteIcon
        ];
    }

    echo json_encode([
        "draw" => intval($_POST['draw'] ?? 0),
        "recordsTotal" => $totalFiltered ?? 0,
        "recordsFiltered" => $totalFiltered ?? 0,
        "data" => $data
    ]);
}
