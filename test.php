<?php

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "fypms";

// Create a database connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming you have started a session


$studentId = 2022937731;

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
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<style>
    .bar {
        fill: steelblue;
    }
</style>

<div class="w-full mt-5">
    <div class="bg-white rounded-lg shadow-md p-3 border" style="max-height: 100%; overflow: auto;">
        <h1 class="text-2xl font-semibold mb-4">Your Development and Writing Timeline</h1>
        <div class="gantt-container">
            <div id="gantt-chart" class="bg-white p-3 rounded-lg shadow-md">
                <!-- Include the SVG element for the Gantt chart here -->
            </div>
        </div>
    </div>
</div>



<script>
    // Your JSON data
    var jsonData = <?php echo $jsonData; ?>; // Insert the JSON data here

    // Width and height of the chart container
    var margin = {
        top: 20,
        right: 100,
        bottom: 40,
        left: 100
    };
    var width = 1500 - margin.left - margin.right;
    var height = 400 - margin.top - margin.bottom;

    // Create an SVG element
    var svg = d3.select("#gantt-chart") // Select the existing gantt-chart div
        .append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    // Gantt chart data processing and rendering
    var taskNames = jsonData.map(function(d) {
        return d.TaskName;
    });

    var startDate = d3.min(jsonData, function(d) {
        return new Date(d.StartDate);
    });

    // Set the end date to one year from the start date
    var endDate = new Date(startDate);
    endDate.setFullYear(startDate.getFullYear() + 1);

    var xScale = d3.scaleTime()
        .domain([startDate, endDate])
        .range([0, width]);

    var yScale = d3.scaleBand()
        .domain(taskNames)
        .range([0, height])
        .padding(0.1);

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

    // Add axis
    var xAxis = d3.axisBottom(xScale).tickFormat(d3.timeFormat("%Y, %m, %d")).ticks(d3.timeMonth.every(1));
    var yAxis = d3.axisLeft(yScale);

    svg.append("g")
        .attr("class", "x-axis")
        .call(xAxis)
        .attr("transform", "translate(0," + height + ")");

    svg.append("g")
        .attr("class", "y-axis")
        .call(yAxis);

    // Style the date labels using Tailwind CSS
    d3.selectAll(".x-axis text").attr("class", "text-sm text-gray-600");
</script>
