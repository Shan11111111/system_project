<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "system_project");
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$result = [];
if ($keyword !== '') {
    $stmt = $conn->prepare("SELECT project_id, title FROM fundraising_projects WHERE project_id LIKE CONCAT('%', ?, '%') OR title LIKE CONCAT('%', ?, '%') LIMIT 10");
    $stmt->bind_param("ss", $keyword, $keyword);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $result[] = $row;
    }
    $stmt->close();
}
echo json_encode($result);
$conn->close();