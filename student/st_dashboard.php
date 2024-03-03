<?php

include 'includes/st_sidebar.php';

$batch_id = $_SESSION['batch_id'];
$studentId = $_SESSION['user_id'];



// Query to count total assignments
$countAssignmentQuery = "SELECT COUNT(*) AS total_assignments
FROM assignment
WHERE batch_id = ?";

// Query to count completed assignments
$completeAssignmentQuery = "SELECT COUNT(*) AS total_submitted_assignments
FROM assignment_submission
WHERE ass_status = 1 AND ass_student_id = ?";


// Prepare and execute the count query for assignments
$countAssignmentStmt = $conn->prepare($countAssignmentQuery);
$countAssignmentStmt->bind_param("i", $batch_id);
$countAssignmentStmt->execute();
$countAssignmentStmt->bind_result($totalAssignments);
$countAssignmentStmt->fetch();
$countAssignmentStmt->close();

// Prepare and execute the count query for completed assignments
$completeAssignmentStmt = $conn->prepare($completeAssignmentQuery);
$completeAssignmentStmt->bind_param("i", $studentId);
$completeAssignmentStmt->execute();
$completeAssignmentStmt->bind_result($completedAssignments);
$completeAssignmentStmt->fetch();
$completeAssignmentStmt->close();

// Prepare and execute the count query for tasks


?>

<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<div class="flex justify-between items-center">
    <div>
        <p class="inline-flex items-center text-sm font-medium text-gray-400">Login as: Student</p>
    </div>
    <div class="ml-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="#" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-gray-600 hover:font-bold ">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Dashboard
                    </a>
                </li>
            </ol>
        </nav>
    </div>
</div>
<div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

<h1 class="text-4xl font-bold mb-3">Dashboard</h1>


<div class="flex ">
    <div class="w-1/3">
        <div class="bg-white rounded-lg shadow-md p-3 ">
            <h1 class="text-2xl font-semibold mb-5">Assignment Completion</h1>
            <div class="flex item-center justify-center flex-col md:flex-row ">
                <div class="mx-9 mb-4 md:mb-0 ">
                    <?php if ($totalAssignments > 0) { ?>
                        <canvas class="" id="proposalPieChart" width="250" height="250"></canvas>
                        <div class="w-full border-b mt-2 border-gray-400 mb-3"></div>
                        <div class="mb-2">
                            <ul class="list-none">
                                <li class="flex justify-between text-gray-600">
                                    <span>Total Assignment</span>
                                    <span class="font-bold text-gray-900"><?php echo $totalAssignments; ?></span>
                                </li>
                                <li class="flex justify-between text-gray-600">
                                    <span>Complete Assignment</span>
                                    <span class="font-bold text-gray-900"><?php echo $completedAssignments; ?></span>
                                </li>
                            </ul>


                            <div class="relative overflow-x-auto">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-g">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Product name
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Color
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Category
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Price
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                Apple MacBook Pro 17"
                                            </th>
                                            <td class="px-6 py-4">
                                                Silver
                                            </td>
                                            <td class="px-6 py-4">
                                                Laptop
                                            </td>
                                            <td class="px-6 py-4">
                                                $2999
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                        </div>
                    <?php } else { ?>
                        <div class="w-full p-8 text-center">
                            <p class="text-gray-500 font-bold">No Assignment for you.</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Replace these values with actual data
        const completedProposals = <?php echo $completedAssignments; ?>;
        const totalProposals = <?php echo $totalAssignments; ?>;

        // Proposal completion chart
        const proposalData = {
            labels: ["Completed", "Incomplete"],
            datasets: [{
                data: [completedProposals, totalProposals - completedProposals],
                backgroundColor: ["#192f41", "#1c64f2"],
            }],
        };

        const proposal = document.getElementById("proposalPieChart").getContext("2d");
        new Chart(proposal, {
            type: "pie",
            data: proposalData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "bottom", // Display legend at the bottom
                        align: "center", // Align legend items to the start (left)
                        labels: {
                            boxWidth: 20, // Set the width of legend color boxes
                            padding: 15, // Set padding between legend items
                        },
                    },
                }
            },
        });
    </script>

    <?php

    // Query to count total and completed tasks
    $countTaskQuery = "SELECT
    s.st_id,
    COUNT(DISTINCT ts.task_id) AS total_complete_task,
    COUNT(DISTINCT t.task_id) + COUNT(DISTINCT tstu.task_id) AS total_assigned_task
FROM
    student s
LEFT JOIN
    task_submission ts ON s.st_id = ts.student_id
LEFT JOIN
    (
    SELECT t1.task_id, t1.batch_id
    FROM task t1
    JOIN student s ON t1.batch_id = s.st_batch
    UNION
    SELECT t2.task_id, t2.batch_id
    FROM task_student tstu
    JOIN task t2 ON tstu.task_id = t2.task_id
    JOIN student s ON tstu.student_id = s.st_id
    ) t ON t.batch_id = s.st_batch
LEFT JOIN
    task_student tstu ON s.st_id = tstu.student_id
WHERE
    s.st_id = ?
GROUP BY
    s.st_id";


    $countTaskStmt = $conn->prepare($countTaskQuery);
    $countTaskStmt->bind_param("i", $studentId);
    $countTaskStmt->execute();
    $countTaskStmt->bind_result($st_id, $totalCompleteTask, $totalAssignedTask);
    $countTaskStmt->fetch();
    $countTaskStmt->close();
    ?>
    <div class="w-1/3 ml-6">
        <div class="bg-white rounded-lg shadow-md p-3 ">
            <h1 class="text-2xl font-semibold mb-5">Task Completion</h1>
            <div class="flex item-center justify-center flex-col md:flex-row ">
                <div class="mx-9 mb-4 md:mb-0 ">
                    <?php if ($totalAssignedTask > 0) { ?>
                        <canvas class="" id="taskPieChart" width="250" height="250"></canvas>
                        <div class="w-full border-b mt-2 border-gray-400 mb-3"></div>
                        <div class="mb-2 ">
                            <ul class="list-none">
                                <li class="flex justify-between text-gray-600">
                                    <span>Total Task</span>
                                    <span class="font-bold text-gray-900"><?php echo $totalAssignedTask; ?></span>
                                </li>
                                <li class="flex justify-between text-gray-600">
                                    <span>Complete Task</span>
                                    <span class="font-bold text-gray-900"><?php echo $totalCompleteTask; ?></span>
                                </li>
                            </ul>
                        </div>
                    <?php } else { ?>
                        <div class="w-full p-8 text-center">
                            <p class="text-gray-500 font-bold">No task for you.</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <!-- proposal & task completion js -->
    <script>
        const completedTasks = <?php echo $totalCompleteTask; ?>;
        const totalTasks = <?php echo $totalAssignedTask; ?>;

        // Task completion chart
        const taskData = {
            labels: ["Completed", "Incomplete"],
            datasets: [{
                data: [completedTasks, totalTasks - completedTasks],
                backgroundColor: ["#192f41", "#1c64f2"],
            }],
        };

        const task = document.getElementById("taskPieChart").getContext("2d");
        new Chart(task, {
            type: "pie",
            data: taskData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "bottom",
                        align: "center",
                        labels: {
                            boxWidth: 20,
                            padding: 15,
                        },
                    },
                }
            },
        });
    </script>


    <?php
    //!!DOCUMENT CHART
    ?>

    <div class="w-1/3 ml-6 overflow-y-scroll max-h-[403px] bg-white rounded-lg shadow-md p-3 ">
        <h1 class="text-2xl font-semibold mb-5">Document Completion</h1>
        <ol class="relative">
            <?php
            // Prepare and execute the SQL query to retrieve docs
            $doc_sql = "SELECT doc_id, doc_title, doc_date_due FROM document WHERE batch_id = $batch_id";
            $doc_stmt = $conn->prepare($doc_sql);

            if ($doc_stmt->execute()) {
                $doc_result = $doc_stmt->get_result();
                $num_rows = $doc_result->num_rows;
                $counter = 0;

                if ($num_rows > 0) {

                    while ($doc_row = $doc_result->fetch_assoc()) {
                        $doc_id = $doc_row['doc_id'];
                        $doc_date_due = date("h:i A d/m/Y", strtotime($doc_row['doc_date_due']));
                        $counter++;

                        // Check if there is a submission for the current doc_id and user_id
                        $submission_sql = "SELECT COUNT(*) AS submission_count FROM document_submission WHERE doc_id = ? AND doc_student_id = ?";
                        $submission_stmt = $conn->prepare($submission_sql);
                        $submission_stmt->bind_param("ii", $doc_id, $user_id); // Assuming $user_id holds the user's ID

                        if ($submission_stmt->execute()) {
                            $submission_result = $submission_stmt->get_result();
                            $row = $submission_result->fetch_assoc();
                            $submission_count = $row['submission_count'];
                        }
            ?>
                        <li class="mb-10 ms-1 flex items-center relative ">
                            <?php if ($submission_count > 0) { ?>
                                <div class="z-10 flex items-center justify-center w-10 h-10 bg-green relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#192f41" class="w-10 h-10 ">
                                        <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 00-.673-0.05A3 3 0 0015 1.5h-1.5a.75.75 0 00-2.663 1.618c-.225.015-.45.032-.673-0.05C8.662 3.295 7.554 4.542 7.502 6zM13.5 3A1.5 1.5 0 0012 4.5h4.5A1.5 1.5 0 0015 3h-1.5z" clip-rule="evenodd" />
                                        <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875 0.84 1.875 1.875v11.25c0 1.035-0.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625V9.375zm9.586 4.594a.75.75 0 00-1.172-0.938l-2.476 3.096-0.908-0.907a.75.75 0 00-1.06 1.06l1.5 1.5a.75.75 0 001.116-0.062l3-3.75z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            <?php } else { ?>
                                <div class="z-10 flex items-center justify-center w-10 h-10 bg-green relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#1c64f2" class="w-10 h-10">
                                        <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 00-.673-0.05A3 3 0 0015 1.5h-1.5a.75.75 0 00-2.663 1.618c-.225.015-.45.032-.673-0.05C8.662 3.295 7.554 4.542 7.502 6zM13.5 3A1.5 1.5 0 0012 4.5h4.5A1.5 1.5 0 0015 3h-1.5z" clip-rule="evenodd" />
                                        <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875 0.84 1.875 1.875v11.25c0 1.035-0.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625V9.375zm6 2.625a.75.75 0 01.75-0.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H9.75a.75.75 0 01-.75-0.75V12zm2.25 0a.75.75 0 01.75-0.75h3.75a.75.75 0 010 1.5H12a.75.75 0 01-.75-0.75zM9 15a.75.75 0 01.75-0.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H9.75a.75.75 0 01-.75-0.75V15zm2.25 0a.75.75 0 01.75-0.75h3.75a.75.75 0 010 1.5H12a.75.75 0 01-.75-0.75z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            <?php } ?>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold custom-wrap"><?php echo $doc_row['doc_title']; ?></h3>
                                <time class="block text-sm font-normal leading-none ">Submit before <?php echo $doc_date_due ?></time>
                            </div>
                            <?php if ($counter < $num_rows) { ?>
                                <div class="absolute top-full left-4 -ml-px w-0.5 h-full bg-gray-500"></div>
                            <?php } ?>
                        </li>
            <?php
                    }
                } else {
                    // Display message when no documents are found
                    echo ' <div class="w-full p-8 text-center">
                    <p class="text-gray-500 font-bold">No document for you.</p>
                </div>';
                }
            }
            ?>
        </ol>
    </div>
</div>




<?php
//!! GANTT CHART
$studentId = $_SESSION['user_id'];

$query = "SELECT gantt_chart.gantt_chart_id, 
            gantt_chart.student_id, 
            gantt_chart.supervisor_id, 
            gantt_chart_task.gantt_chart_task_id, 
            gantt_chart_task.task_name, 
            gantt_chart_task.start_date, 
            gantt_chart_task.end_date, 
            gantt_chart_task.status 
          FROM gantt_chart 
          INNER JOIN gantt_chart_task ON gantt_chart.gantt_chart_id = gantt_chart_task.gantt_chart_id 
          WHERE gantt_chart.student_id = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $studentId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}

// Create a PHP array to hold the Gantt chart data
$data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $ganttChartId = $row['gantt_chart_id'];
    $studentId = $row['student_id'];
    $supervisorId = $row['supervisor_id'];
    $taskName = $row['task_name'];
    $startDate = $row['start_date'] ? new DateTime($row['start_date']) : null;
    $endDate = $row['end_date'] ? new DateTime($row['end_date']) : null;
    $status = $row['status'];

    $data[] = [
        'GanttChartID' => $ganttChartId,
        'StudentID' => $studentId,
        'SupervisorID' => $supervisorId,
        'TaskName' => $taskName,
        'StartDate' => $startDate ? $startDate->format('Y, n, j') : null,
        'EndDate' => $endDate ? $endDate->format('Y, n, j') : null,
        'Status' => $status,
    ];
}

// Convert the PHP array to JSON format
$jsonData = json_encode($data);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gantt Chart</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/gantt.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

    <style>
        /* Style to position the exporting button above the chart */
        #gantt-chart-container {
            position: relative;
        }

        .highcharts-exporting-group {
            position: absolute;
            top: 0;
            right: 0;
            margin: 10px;
        }
    </style>

    <script type="text/javascript">
        function drawChart() {
            // Your JSON data
            var jsonData = <?php echo $jsonData; ?>;

            if (jsonData.length > 0) {
                // Convert the date strings to JavaScript Date objects
                jsonData.forEach(function(task) {
                    task.StartDate = new Date(task.StartDate);
                    task.EndDate = new Date(task.EndDate);
                });

                // Prepare data for Highcharts Gantt
                var highchartsData = jsonData.map(function(task) {
                    return {
                        name: task.TaskName,
                        start: task.StartDate.getTime(),
                        end: task.EndDate.getTime()
                    };
                });

                var options = {
                    chart: {
                        renderTo: 'gantt-chart-container',
                        type: 'gantt'
                    },
                    title: { // Title added here
                        text: 'Your FYP Timeline',
                        align: 'center',
                        style: {
                            fontSize: '25px',
                            fontFamily: 'Arial' // Set font family to Arial

                        }
                    },
                    xAxis: {
                        currentDateIndicator: true,
                        lineColor: '#333',
                        lineWidth: 1
                    },
                    yAxis: {
                        gridLineColor: '#eee'
                    },
                    series: [{
                        name: 'Tasks',
                        data: highchartsData,
                        color: '#3498db',
                        dataLabels: {
                            style: {
                                color: 'white',
                                fontWeight: 'bold'
                            }
                        }
                    }],
                    exporting: {
                        menuItemDefinitions: {
                            viewFullPage: {
                                onclick: function() {
                                    window.open(location.href, '_blank');
                                },
                                text: 'View Full Page'
                            },
                            downloadPDF: {
                                onclick: function() {
                                    this.exportChart({
                                        type: 'application/pdf'
                                    });
                                },
                                text: 'Download PDF'
                            }
                        },
                        buttons: {
                            contextButton: {
                                menuItems: [
                                    'viewFullPage', 'downloadPDF', 'separator', 'printChart', 'separator', 'downloadPNG',
                                    'downloadJPEG', 'downloadSVG', 'separator', 'downloadCSV', 'downloadXLS'
                                ]
                            }
                        }
                    }
                };

                var chart = Highcharts.ganttChart(options);
            } else {
                // Handle case when there is no data
                document.getElementById('gantt-chart-container').innerHTML = `
        <div class="w-full p-8 text-center">
            <p class="text-gray-500 font-bold">Tell your supervisor to create a timeline for you.</p>
        </div>
    `;
            }

        }
    </script>
</head>

<body class="bg-gray-100">
    <?php
    if (!empty($jsonData)) {
    ?>
        <div class="w-full mt-5 p-3 border bg-white rounded-lg shadow-md">
            <div id="gantt-chart-container"></div>
            <div id="tooltip"></div>
        </div>
        <script>
            drawChart();
        </script>
    <?php
    } else {
    ?>
        <div class="w-full mt-5 p-3 border bg-white rounded-lg shadow-md text-center">
            <p class="text-gray-500">Tell your supervisor to create a timeline for you.</p>
        </div>
    <?php
    }
    ?>
</body>

</html>



<!-- content end -->
</div>
</div>
</div>
</body>

</html>