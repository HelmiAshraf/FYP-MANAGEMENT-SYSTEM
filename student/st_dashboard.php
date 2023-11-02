<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .scrollable-timeline {
            overflow-x: auto;
            white-space: nowrap;
        }

        .scrollable-timeline::-webkit-scrollbar {
            width: 1px;
            /* Make the scrollbar slim */
        }

        .scrollable-timeline::-webkit-scrollbar-track {
            background: transparent;
            /* Track background color */
        }

        .scrollable-timeline::-webkit-scrollbar-thumb {
            background: #f1f1f1;
            /* Thumb color */
            border-radius: 10px;
            /* Make it rounded */
        }

        .scrollable-timeline::-webkit-scrollbar-thumb:hover {
            background: #e8e8e8;
            /* Thumb color on hover */
        }

        .custom-wrap {
            white-space: nowrap;
            /* Prevent text from wrapping */
            overflow: hidden;
            text-overflow: ellipsis;
            /* Display ellipsis (...) when text overflows */
            max-width: 13em;
            /* Set the maximum width for text to display on one line */
        }
    </style>
</head>

<?php

include 'includes/st_sidebar.php';


$studentId = $_SESSION['user_id'];

// Query to count total proposals
$countProposalQuery = "SELECT COUNT(*) AS totalProposals FROM proposal";
$countTaskQuery = "SELECT COUNT(*) AS totalTasks FROM task";

// Query to fetch task and proposal completion percentages
$query = "SELECT task_pieChart, proposal_pieChart FROM graph_student WHERE student_id = ?";

// Default values for pie charts
$totalProposals = 0;
$totalTasks = 0;
$completedProposals = 0;
$completedTasks = 0;

// Prepare and execute the count query for proposals
$countProposalStmt = $conn->prepare($countProposalQuery);
$countProposalStmt->execute();
$countProposalStmt->bind_result($totalProposals);

// Fetch the total number of proposals
if ($countProposalStmt->fetch()) {
    $countProposalStmt->close();

    // Prepare and execute the count query for tasks
    $countTaskStmt = $conn->prepare($countTaskQuery);
    $countTaskStmt->execute();
    $countTaskStmt->bind_result($totalTasks);

    // Fetch the total number of tasks
    if ($countTaskStmt->fetch()) {
        $countTaskStmt->close();

        // Prepare and execute the query for task and proposal completion percentages
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $studentId);
        $stmt->execute();
        $stmt->bind_result($taskPieChart, $proposalPieChart);

        // Fetch the results
        if ($stmt->fetch()) {
            // Calculate the percentage
            $completedTasks = $taskPieChart;
            $completedProposals = $proposalPieChart;
        }

        $stmt->close();
    }
}
?>


<h1 class="text-4xl font-bold mb-3">Dashboard</h1>
<div class="flex">
    <div class="w-1/2">
        <div class="bg-white rounded-lg shadow-md p-3 border">
            <h1 class="text-2xl font-semibold mb-4">Proposal Completion</h1>
            <div class="flex">
                <div class="w-1/2 h-60 text-center">
                    <canvas id="proposalPieChart"></canvas>
                </div>
                <div class="w-1/2 ml-6 flex flex-col">
                    <div>
                        <ul class="list-none">
                            <li>Total Proposal</li>
                            <li><?php echo $totalProposals; ?></li>
                        </ul>
                    </div>
                    <div>
                        <ul class="list-none">
                            <li>Complete Proposal</li>
                            <li><?php echo $completedProposals; ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-1/2 ml-6">
        <div class="bg-white rounded-lg shadow-md p-3 border">
            <h1 class="text-2xl font-semibold mb-4">Task Completion</h1>
            <div class="flex">
                <div class="w-1/2 h-60">
                    <canvas id="taskPieChart"></canvas>
                </div>
                <div class="w-1/2 ml-6 flex flex-col">
                    <div>
                        <ul class="list-none">
                            <li>Total Task</li>
                            <li><?php echo $totalTasks; ?></li>
                        </ul>
                    </div>
                    <div>
                        <ul class="list-none">
                            <li>Complete Task</li>
                            <li><?php echo $completedTasks; ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- proposal & task completion js -->
<script>
    // Replace these values with actual data
    const completedProposals = <?php echo $completedProposals; ?>;
    const totalProposals = <?php echo $totalProposals; ?>;
    const completedTasks = <?php echo $completedTasks; ?>;
    const totalTasks = <?php echo $totalTasks; ?>;

    // Proposal completion chart
    const proposalData = {
        labels: ["Completed Proposals", "Incomplete Proposals"],
        datasets: [{
            data: [completedProposals, totalProposals - completedProposals],
            backgroundColor: ["#1c64f2", "#e74694"],
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
                },
            }
        },
    });

    // Task completion chart
    const taskData = {
        labels: ["Completed Tasks", "Incomplete Tasks"],
        datasets: [{
            data: [completedTasks, totalTasks - completedTasks],
            backgroundColor: ["#1c64f2", "#e74694"],
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
                    position: "bottom", // Display legend at the bottom
                },
            }
        },
    });

    // Sample data for the timeline chart
    const timelineData = {
        labels: ["2023-01-01", "2023-03-15", "2023-05-10", "2023-07-20"],
        datasets: [{
            label: "Form Completion",
            backgroundColor: "rgba(54, 162, 235, 0.2)",
            borderColor: "rgba(54, 162, 235, 1)",
            borderWidth: 1,
            data: [2, 4, 6, 8],
        }, ],
    };
</script>


<!-- form timeline -->
<div class="w-full mt-5">
    <div class="bg-white rounded-lg shadow-md p-3 border">
        <h1 class="text-2xl font-semibold mb-4">Form Completion Timeline</h1>
        <div class="scrollable-timeline" style="width: 100%;">
            <ol class="flex items-center justify-start sm:flex py-4 px-2" style="min-width: 100%;">
                <?php
                // Prepare and execute the SQL query to retrieve forms
                $form_sql = "SELECT form_id, form_title, form_date_due FROM form";
                $form_stmt = $conn->prepare($form_sql);

                if ($form_stmt->execute()) {
                    $form_result = $form_stmt->get_result();
                    $num_rows = $form_result->num_rows;
                    $counter = 0;

                    while ($form_row = $form_result->fetch_assoc()) {
                        $form_id = $form_row['form_id'];
                        $form_date_due = date("h:i A d/m/Y", strtotime($form_row['form_date_due']));
                        $counter++;

                        // Check if there is a submission for the current form_id and user_id
                        $submission_sql = "SELECT COUNT(*) AS submission_count FROM form_submission WHERE form_id = ? AND student_id = ?";
                        $submission_stmt = $conn->prepare($submission_sql);
                        $submission_stmt->bind_param("ii", $form_id, $user_id); // Assuming $user_id holds the user's ID

                        if ($submission_stmt->execute()) {
                            $submission_result = $submission_stmt->get_result();
                            $row = $submission_result->fetch_assoc();
                            $submission_count = $row['submission_count'];

                            // Define the UI based on submission count
                            $uiClass = ($submission_count > 0) ? 'bg-green ounded-full ring-0 ring-green dark:white sm:ring-8 dark:ring-gray-900 shrink-0'
                                : 'bg-blue-100 rounded-full ring-0 ring-green dark:white sm:ring-8 dark:ring-gray-900 shrink-0';
                            $iconPath = ($submission_count > 0) ? "path-for-green-icon"
                                : "path-for-blue-icon";
                        }
                ?>
                        <li class="relative mb-6 sm:mb-0 <?php echo $marginLeft; ?>">
                            <div class="flex items-center">
                                <?php if ($submission_count > 0) { ?>
                                    <div class="z-10 flex items-center justify-center w-10 h-10 bg-green">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#192f41" class="w-10 h-10 ">
                                            <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 00-.673-0.05A3 3 0 0015 1.5h-1.5a.75.75 0 00-2.663 1.618c-.225.015-.45.032-.673-0.05C8.662 3.295 7.554 4.542 7.502 6zM13.5 3A1.5 1.5 0 0012 4.5h4.5A1.5 1.5 0 0015 3h-1.5z" clip-rule="evenodd" />
                                            <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875 0.84 1.875 1.875v11.25c0 1.035-0.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625V9.375zm9.586 4.594a.75.75 0 00-1.172-0.938l-2.476 3.096-0.908-0.907a.75.75 0 00-1.06 1.06l1.5 1.5a.75.75 0 001.116-0.062l3-3.75z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                <?php } else { ?>
                                    <div class="z-10 flex items-center justify-center w-10 h-10 bg-green">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#192f41" class="w-10 h-10">
                                            <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 00-.673-0.05A3 3 0 0015 1.5h-1.5a.75.75 0 00-2.663 1.618c-.225.015-.45.032-.673-0.05C8.662 3.295 7.554 4.542 7.502 6zM13.5 3A1.5 1.5 0 0012 4.5h4.5A1.5 1.5 0 0015 3h-1.5z" clip-rule="evenodd" />
                                            <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875 0.84 1.875 1.875v11.25c0 1.035-0.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625V9.375zm6 2.625a.75.75 0 01.75-0.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H9.75a.75.75 0 01-.75-0.75V12zm2.25 0a.75.75 0 01.75-0.75h3.75a.75.75 0 010 1.5H12a.75.75 0 01-.75-0.75zM9 15a.75.75 0 01.75-0.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H9.75a.75.75 0 01-.75-0.75V15zm2.25 0a.75.75 0 01.75-0.75h3.75a.75.75 0 010 1.5H12a.75.75 0 01-.75-0.75z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                <?php } ?>
                                <?php if ($counter < $num_rows) { ?>
                                    <div class="hidden sm:flex w-full bg-white h-0.5 dark:bg-gray-700"></div>
                                <?php } ?>
                            </div>
                            <div class="mt-3 sm:pr-8">
                                <h3 class="text-lg font-semibold text-gray-900 custom-wrap"><?php echo $form_row['form_title']; ?></h3>
                                <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Submit before <?php echo $form_date_due ?></time>
                            </div>
                        </li>
                <?php
                    }
                } else {
                    echo "Error executing the query: " . $form_stmt->error;
                }

                $submission_stmt->close();
                $form_stmt->close();
                ?>
            </ol>
        </div>
    </div>
</div>


<?php

// $servername = "localhost";
// $dbusername = "root";
// $dbpassword = "";
// $dbname = "fypms";

// // Create a database connection
// $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Assuming you have started a session


$studentId = $_SESSION['user_id'];

$query = "SELECT gantt_chart.gantt_chart_id, gantt_chart.student_id, gantt_chart.supervisor_id, gantt_chart_task.gantt_chart_task_id, gantt_chart_task.task_name, gantt_chart_task.start_date, gantt_chart_task.end_date, gantt_chart_task.status 
          FROM gantt_chart 
          INNER JOIN gantt_chart_task ON gantt_chart.gantt_chart_id = gantt_chart_task.gantt_chart_id 
          WHERE gantt_chart.student_id = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $studentId); // Assuming user_id is an integer
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

<script src="https://d3js.org/d3.v5.min.js"></script>
<style>
    .bar {
        fill: steelblue;
    }
</style>

<div class="w-full mt-5">
    <div class="bg-white rounded-lg shadow-md p-3 border">
        <h1 class="text-2xl font-semibold mb-4">Form Completion Timeline</h1>
        <div class="scrollable-timeline" style="width: 100%;">
            <div class="gantt-container">

                <?php
                // Check if there is Gantt chart data for the student
                if (count($data) > 0) {
                    // Display the Gantt chart SVG
                    echo '<div id="gantt-chart">';
                } else {
                    // Display a message if no Gantt chart data is available
                    echo "Your supervisor has not created a Gantt chart for you.";
                }
                ?>

            </div>
        </div>
    </div>
</div>




<script>
    // Your JSON data
    var jsonData = <?php echo $jsonData; ?>;

    // Sort the data by start date in ascending order
    jsonData.sort(function(a, b) {
        return new Date(a.StartDate) - new Date(b.StartDate);
    });

    // Width and height of the chart container
    var margin = {
        top: 20,
        right: 100,
        bottom: 40,
        left: 100
    };
    var width = 1500 - margin.left - margin.right;
    var height = 400 - margin.top - margin.bottom;

    // Gantt chart data processing and rendering
    var taskNames = jsonData.map(function(d) {
        return d.TaskName;
    });

    var startDate = d3.min(jsonData, function(d) {
        return new Date(d.StartDate);
    });

    var endDate = d3.max(jsonData, function(d) {
        return new Date(d.EndDate);
    });

    // Create an xScale based on the start and end dates
    var xScale = d3.scaleTime()
        .domain([startDate, endDate])
        .range([0, width]);

    // Adjust the yScale domain to consider the earliest start date
    var yScale = d3.scaleBand()
        .domain(taskNames)
        .range([0, height])
        .padding(0.1);

    // Create an SVG element
    var svg = d3.select("#gantt-chart")
        .append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    svg.selectAll(".bar")
        .data(jsonData)
        .enter()
        .append("rect")
        .attr("class", "bar")
        .attr("x", function(d) {
            return xScale(new Date(d.StartDate));
        })
        .attr("y", function(d) {
            return yScale(d.TaskName);
        })
        .attr("width", function(d) {
            return xScale(new Date(d.EndDate)) - xScale(new Date(d.StartDate));
        })
        .attr("height", yScale.bandwidth());

    // Create and add the x-axis
    var xAxis = d3.axisBottom(xScale).tickFormat(d3.timeFormat("%Y, %m, %d")).ticks(d3.timeMonth.every(1));
    svg.append("g")
        .attr("class", "x-axis")
        .call(xAxis)
        .attr("transform", "translate(0," + height + ")");

    // Create and add the y-axis
    var yAxis = d3.axisLeft(yScale);
    svg.append("g")
        .attr("class", "y-axis")
        .call(yAxis);

    // Style the date labels using Tailwind CSS
    d3.selectAll(".x-axis text").attr("class", "text-sm text-gray-600");
</script>




<!-- content end -->
</div>
</div>
</div>
</body>

</html>