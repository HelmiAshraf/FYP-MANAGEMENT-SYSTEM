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
$conn->close();



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
<div class="w-full mt-5">
    <div class="bg-white rounded-lg shadow-md p-3 border ">
        <h1 class="text-2xl font-semibold mb-4">Form Completion Timeline</h1>
        <ol class="flex items-center justify-center sm:flex py-4 px-2">
            <li class="relative mb-6 sm:mb-0">
                <div class="flex items-center">
                    <div class="z-10 flex items-center justify-centerw-6 h-6 bg-blue-100 rounded-full ring-0 ring-greemn dark:white sm:ring-8 dark:ring-gray-900 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="hidden sm:flex w-full bg-white h-0.5 dark:bg-gray-700"></div>
                </div>
                <div class="mt-3 sm:pr-8">
                    <h3 class="text-lg font-semibold text-gray-900 ">Form 1</h3>
                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Released on December 2, 2021</time>
                </div>
            </li>
            <li class="relative mb-6 sm:mb-0">
                <div class="flex items-center">
                    <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-greemn dark:white sm:ring-8 dark:ring-gray-900 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="hidden sm:flex w-full bg-white h-0.5 dark:bg-gray-700"></div>
                </div>
                <div class="mt-3 sm:pr-8">
                    <h3 class="text-lg font-semibold text-gray-900 ">Form 2</h3>
                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Released on December 2, 2021</time>
                </div>
            </li>
            <li class="relative mb-6 sm:mb-0">
                <div class="flex items-center">
                    <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-greemn dark:white sm:ring-8 dark:ring-gray-900 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="hidden sm:flex w-full bg-white h-0.5 dark:bg-gray-700"></div>
                </div>
                <div class="mt-3 sm:pr-8">
                    <h3 class="text-lg font-semibold text-gray-900 ">Form 3</h3>
                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Released on December 2, 2021</time>
                </div>
            </li>
            <li class="relative mb-6 sm:mb-0">
                <div class="flex items-center">
                    <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-greemn dark:white sm:ring-8 dark:ring-gray-900 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="hidden sm:flex w-full bg-white h-0.5 dark:bg-gray-700"></div>
                </div>
                <div class="mt-3 sm:pr-8">
                    <h3 class="text-lg font-semibold text-gray-900 ">Form 4</h3>
                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Released on December 2, 2021</time>
                </div>
            </li>
            <li class="relative mb-6 sm:mb-0">
                <div class="flex items-center">
                    <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-greemn dark:white sm:ring-8 dark:ring-gray-900 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="hidden sm:flex w-full bg-white h-0.5 dark:bg-gray-700"></div>
                </div>
                <div class="mt-3 sm:pr-8">
                    <h3 class="text-lg font-semibold text-gray-900 ">Form 5</h3>
                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Released on December 2, 2021</time>
                </div>
            </li>
            <li class="relative mb-6 sm:mb-0">
                <div class="flex items-center">
                    <div class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-greemn dark:white sm:ring-8 dark:ring-gray-900 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 sm:pr-8">
                    <h3 class="text-lg font-semibold text-gray-900 ">Form 6</h3>
                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">Released on December 2, 2021</time>
                </div>
            </li>
        </ol>
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