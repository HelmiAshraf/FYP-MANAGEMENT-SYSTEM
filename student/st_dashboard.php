<head>
    <style>
        .scrollable-timeline {
            overflow-x: auto;
            white-space: nowrap;
        }

        .scrollable-timeline::-webkit-scrollbar {
            width: 4px;
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


// Connect to your database here

// Query to count total proposals
$countProposalQuery = "SELECT COUNT(*) AS totalProposals FROM proposal";
$countTaskQuery = "SELECT COUNT(*) AS totalTasks FROM task";

// Query to fetch task and proposal completion percentages
$query = "SELECT task_pieChart, proposal_pieChart FROM graph_student WHERE student_id = ?";

$studentId = $_SESSION['user_id'];

// Prepare and execute the count query for proposals
$countProposalStmt = $conn->prepare($countProposalQuery);
$countProposalStmt->execute();
$countProposalStmt->bind_result($totalProposals);

// Fetch the total number of proposals
if ($countProposalStmt->fetch()) {
    // Now you have the total number of proposals

    // Close the proposal count statement
    $countProposalStmt->close();

    // Prepare and execute the count query for tasks
    $countTaskStmt = $conn->prepare($countTaskQuery);
    $countTaskStmt->execute();
    $countTaskStmt->bind_result($totalTasks);

    // Fetch the total number of tasks
    if ($countTaskStmt->fetch()) {
        // Now you have the total number of tasks

        // Close the task count statement
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
            // $totalTasks and $totalProposals were obtained from the count queries
        } else {
            echo "Data not found for the given student ID.";
        }

        // Close the task and proposal completion statement
        $stmt->close();
    } else {
        echo "Error counting total tasks.";
    }
} else {
    echo "Error counting total proposals.";
}

// Close the database connection
// $conn->close();



?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>




<h1 class="text-4xl font-bold mb-3">Dashboard</h1>
<div class="flex">
    <div class="w-1/2">
        <div class="bg-white rounded-lg shadow-md p-3 border">
            <h1 class="text-2xl font-semibold mb-4">Proposal Completion</h1>
            <div class="flex">
                <div class="w-1/2 h-60 text-center">
                    <canvas id="proposalPieChart"></canvas>
                </div>
                <div class="w-1/2 ml-6 flex flex-col ">
                    <div>
                        <ul class="list-none">
                            <li>Total Proposal</li>
                            <li><?php echo $totalProposals; ?></li> <!-- Use echo to display the PHP variable -->
                        </ul>
                    </div>
                    <div>
                        <ul class="list-none">
                            <li>Complete Proposal</li>
                            <li><?php echo $completedProposals; ?></li> <!-- Use echo to display the PHP variable -->
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
                            <li><?php echo $totalTasks; ?></li> <!-- Use echo to display the PHP variable -->
                        </ul>
                    </div>
                    <div>
                        <ul class="list-none">
                            <li>Complete Task</li>
                            <li><?php echo $completedTasks; ?></li> <!-- Use echo to display the PHP variable -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- form timeline -->
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
                mysqli_close($conn);
                ?>
            </ol>
        </div>
    </div>
</div>





<div class="w-full mt-5">
    <div class="bg-white rounded-lg shadow-md p-3 border ">
        <h1 class="text-2xl font-semibold mb-4">Your Development and Writing Timeline</h1>
        <div class="gantt-container ">
            <div id="gantt_chart_div"></div>
        </div>
    </div>
</div>



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
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['gantt']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Task ID');
        data.addColumn('string', 'Task Name');
        data.addColumn('string', 'Resource');
        data.addColumn('date', 'Start Date');
        data.addColumn('date', 'End Date');
        data.addColumn('number', 'Duration');
        data.addColumn('number', 'Percent Complete');
        data.addColumn('string', 'Dependencies');

        data.addRows([
            ['Task1', 'Task 1', 'Resource 1', new Date(2023, 0, 1), new Date(2023, 2, 31), null, 50, null],
            ['Task2', 'Task 2', 'Resource 2', new Date(2023, 3, 1), new Date(2023, 5, 30), null, 30, 'Task1'],
            ['Task3', 'Task 3', 'Resource 3', new Date(2023, 5, 1), new Date(2023, 8, 31), null, 40, 'Task1'],
            ['Task4', 'Task 4', 'Resource 4', new Date(2023, 6, 1), new Date(2023, 2, 31), null, 50, null],
            ['Task5', 'Task 5', 'Resource 5', new Date(2023, 7, 1), new Date(2023, 5, 30), null, 30, 'Task1'],
            ['Task6', 'Task 6', 'Resource 6', new Date(2023, 8, 1), new Date(2023, 8, 31), null, 40, 'Task1'],
            // Add more tasks as needed
        ]);


        var options = {
            height: 350,
        };

        var chart = new google.visualization.Gantt(document.getElementById('gantt_chart_div'));
        chart.draw(data, options);
    }
</script>



<!-- content end -->
</div>
</div>
</div>
</body>

</html>