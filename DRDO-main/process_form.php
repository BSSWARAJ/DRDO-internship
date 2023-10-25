<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "cabs";

    // Create a database connection
    $mysqli = new mysqli($servername, $username, $password, $database);

    // Check if the connection was successful
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $refNo = $_POST['refno'];
    $dated = $_POST['dated'];
    $group = $_POST['group'];
    $divisions = $_POST['divisions'];
    $month = $_POST['month'];
    $refOfEvidence = $_POST['ref_of_evidence_'];

    // Handle table contents (KPI data)
    for ($i = 1; $i <= 9; $i++) {
        $completedTasks = $_POST['completed_tasks_' . $i];
        $actionTaken = $_POST['action_taken_' . $i];

        // Get the KPI name and measurement method for this row
        $kpiData = [
            ["Part level Inspection (mechanical)", "Number of inspections carried out within time vs Total number of inspections coordinated* (1 Part per one hour if the number of dimensions are less than 50)"],
            ["Assembly/Sub Assembly Inspection", "Number of inspections carried out within time vs Total number of inspections coordinated* (1hr per Assembly/Sub Assembly if less than 10 parts)"],
            ["Kit Part Inspection", "Number of inspections carried out within time vs Total number of inspections coordinated* (20 components per one hour)"],
            ["Bare PCB Inspection", "Number of inspections carried out within time vs Total number of inspections coordinated* (1 hour for 2 Layer PCB & number of Components are less than 30)"],
            ["PCB assembly Inspection", "Number of inspections carried out within time vs Total number of inspections coordinated* (30 Mins per LRU)"],
            ["Physical Inspection and visual Examination (Pre-Installation check & after removal from A/c)", "Number of tests Coordinated vs Total number of requests received*"],
            ["Witness of ESS Test/Qualification Test/others", "Number of tests Coordinated vs Total number of requests received*"],
            ["STIR Test/Functional Check", "Number of tests Coordinated vs Total number of requests received*"],
            ["A/C ground test & servicing", "Number of tests Coordinated vs Total number of requests received*"]
        ];
        $kpiName = $kpiData[$i - 1][0];
        $measurementMethod = $kpiData[$i - 1][1];

        // Default target percentage
        $targetPercentage = "100%";
        $achievementPercentage = "NA";

        if (preg_match('/^(\d+)\/(\d+)$/', $completedTasks, $matches)) {
            $completed = $matches[1];
            $total = $matches[2];
            if ($total > 0) {
                $achievementPercentage = ($completed / $total) * 100;
            }
        }

        // Create and execute the SQL query to insert data into the kpi_table_contents table
        $tableSql = "INSERT INTO kpi_table_contents (kpi_name, measurement_method, completed_tasks, target_percentage, achievement_percentage, action_taken, ref_of_evidence) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $tableStmt = $mysqli->prepare($tableSql);

        $tableStmt->bind_param("ssdssss", $kpiName, $measurementMethod, $completedTasks, $targetPercentage, $achievementPercentage, $actionTaken, $refOfEvidence);

        $tableStmt->execute();
    }

    if ($tableStmt) {
        echo "Data inserted successfully!";
    } else {
        echo "Error: " . $mysqli->error;
    }

    $tableStmt->close();
    $mysqli->close();
} else {
    echo "Form not submitted.";
}
?>
