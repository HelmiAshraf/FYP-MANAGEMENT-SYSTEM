<?php
include 'includes/sidebar.php';

$supervisor_id = $_SESSION["user_id"];


$batchCategories = ['CSP600', 'CSP650'];

// Check if $batchIds is already set in the session
if (!isset($_SESSION['batchIds'])) {
    $_SESSION['batchIds'] = []; // Initialize if not set
}

$batchIds = $_SESSION['batchIds'];

foreach ($batchCategories as $category) {
    // Check if the batch_id is not already set in the session
    if (!isset($batchIds[$category])) {
        $sql = "SELECT batch_id FROM batches WHERE batch_category = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $batchIds[$category] = $row['batch_id'];
        } else {
            $batchIds[$category] = null;
        }

        $stmt->close();
    }
}

?>

<div class="flex justify-between items-center">
    <div>
        <p class="inline-flex items-center text-sm font-medium text-gray-400">Login as: FYP Course Lecturer</p>
    </div>
    <div class="ml-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="insight.php" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-gray-600 hover:font-bold ">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Insight
                    </a>
                </li>
            </ol>
        </nav>
    </div>
</div>
<div class="w-full border-b mt-1 border-gray-400 mb-2"></div>



<h1 class="text-4xl font-bold mb-4">Insight</h1>

<div class="">
    <div class="">
        <div class="grid grid-cols-4 gap-4 mb-4">

            <?php

            // Save the updated $batchIds back to the session
            $_SESSION['batchIds'] = $batchIds;

            // Now you can use $batchIds['CSP600'] and $batchIds['CSP650'] to get the batch_ids

            $sqlCSP600 = "SELECT COUNT(*) as total_assignments_CSP600
            FROM assignment a
            JOIN fyp_lecturer fl ON a.ass_fl_id = fl.fl_id
            WHERE a.batch_id = ? AND fl.fl_id = ?";
            $stmtCSP600 = $conn->prepare($sqlCSP600);
            $stmtCSP600->bind_param("ii", $batchIds['CSP600'], $supervisor_id);
            $stmtCSP600->execute();
            $resultCSP600 = $stmtCSP600->get_result();
            $rowCSP600 = $resultCSP600->fetch_assoc();
            $totalAssignmentsCSP600 = $rowCSP600['total_assignments_CSP600'];


            // Example: Get total assignments for CSP650
            $sqlCSP650 = "SELECT COUNT(*) as total_assignments_CSP650
            FROM assignment a
            JOIN fyp_lecturer fl ON a.ass_fl_id = fl.fl_id
            WHERE a.batch_id = ? AND fl.fl_id = ?";
            $stmtCSP650 = $conn->prepare($sqlCSP650);
            $stmtCSP650->bind_param("ii", $batchIds['CSP650'], $supervisor_id);
            $stmtCSP650->execute();
            $resultCSP650 = $stmtCSP650->get_result();
            $rowCSP650 = $resultCSP650->fetch_assoc();
            $totalAssignmentsCSP650 = $rowCSP650['total_assignments_CSP650'];


            // Example: Get total tasks for CSP650
            $sqlCSP600 = "SELECT COUNT(*) as total_tasks_CSP600 FROM task t
            JOIN fyp_lecturer fl ON t.task_sv_id = fl.fl_id
            WHERE batch_id = ? AND fl.fl_id = ?";
            $stmtCSP600 = $conn->prepare($sqlCSP600);
            $stmtCSP600->bind_param("ii", $batchIds['CSP600'], $supervisor_id);
            $stmtCSP600->execute();
            $resultCSP600 = $stmtCSP600->get_result();
            $rowCSP600 = $resultCSP600->fetch_assoc();
            $totalTasksCSP600 = $rowCSP600['total_tasks_CSP600'];

            $sqlCSP650 = "SELECT COUNT(*) as total_tasks_CSP650 FROM task t
            JOIN fyp_lecturer fl ON t.task_sv_id = fl.fl_id
            WHERE batch_id = ? AND fl.fl_id = ?";
            $stmtCSP650 = $conn->prepare($sqlCSP650);
            $stmtCSP650->bind_param("ii", $batchIds['CSP650'], $supervisor_id);
            $stmtCSP650->execute();
            $resultCSP650 = $stmtCSP650->get_result();
            $rowCSP650 = $resultCSP650->fetch_assoc();
            $totalTasksCSP650 = $rowCSP650['total_tasks_CSP650'];
            ?>

            <div class="py-6 px-4 flex items-center justify-center rounded-md bg-gray-900">
                <p class="text-2xl text-blue-500 ">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10">
                        <path d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z" />
                        <path d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z" />
                        <path d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z" />
                    </svg>
                </p>
                <p class="ml-6 text-sm text-gray-300 ">You've created assignment <span class="font-extrabold text-blue-500"><?php echo $totalAssignmentsCSP600 ?></span> for <span class="font-bold">CSP600</span> and <span class="font-extrabold text-blue-500"><?php echo $totalAssignmentsCSP650 ?></span> for <span class="font-bold">CSP650</span></p>
            </div>

            <div class="py-6 px-4 flex items-center justify-center rounded-md bg-gray-900">
                <p class="text-2xl text-green-500 ">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10">
                        <path d="M11.644 1.59a.75.75 0 0 1 .712 0l9.75 5.25a.75.75 0 0 1 0 1.32l-9.75 5.25a.75.75 0 0 1-.712 0l-9.75-5.25a.75.75 0 0 1 0-1.32l9.75-5.25Z" />
                        <path d="m3.265 10.602 7.668 4.129a2.25 2.25 0 0 0 2.134 0l7.668-4.13 1.37.739a.75.75 0 0 1 0 1.32l-9.75 5.25a.75.75 0 0 1-.71 0l-9.75-5.25a.75.75 0 0 1 0-1.32l1.37-.738Z" />
                        <path d="m10.933 19.231-7.668-4.13-1.37.739a.75.75 0 0 0 0 1.32l9.75 5.25c.221.12.489.12.71 0l9.75-5.25a.75.75 0 0 0 0-1.32l-1.37-.738-7.668 4.13a2.25 2.25 0 0 1-2.134-.001Z" />
                    </svg>
                </p>
                <p class="ml-6 text-sm text-gray-300 ">You've created task <span class="font-extrabold text-blue-500"><?php echo $totalTasksCSP600 ?></span> for <span class="font-bold">CSP600</span> and <span class="font-extrabold text-blue-500"><?php echo $totalTasksCSP650 ?></span> for <span class="font-bold">CSP650</span></p>
            </div>

            <div class="p-6 flex items-center justify-center  rounded-md bg-gray-900">
                <p class="text-2xl text-orange-500 ">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10">
                        <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
                        <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                    </svg>

                </p>
                <?php
                $sql = "SELECT
                            COUNT(CASE WHEN b.batch_category = 'CSP600' THEN 1 END) AS total_csp600_students,
                            COUNT(CASE WHEN b.batch_category = 'CSP650' THEN 1 END) AS total_csp650_students
                        FROM
                            student s
                        JOIN
                            batches b ON s.st_batch = b.batch_id
                        WHERE
                            b.batch_category IN ('CSP600', 'CSP650');";
                $result = $conn->query($sql);

                $row = $result->fetch_assoc();
                $total_csp600_students = $row['total_csp600_students'];
                $total_csp650_students = $row['total_csp650_students'];
                ?>
                <p class="ml-6  text-sm text-gray-300 ">There are <span class="font-bold text-blue-500"><?php echo $total_csp600_students ?></span> <span class="font-bold">CSP600</span> students and <span class="font-bold text-blue-500"><?php echo $total_csp650_students ?></span> <span class="font-bold"> CSP650 </span>are active right now</p>
            </div>

            <div class="p-6 flex items-center justify-center rounded-md bg-gray-900">
                <p class="text-2xl text-yellow-500">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-2.625 6c-.54 0-.828.419-.936.634a1.96 1.96 0 00-.189.866c0 .298.059.605.189.866.108.215.395.634.936.634.54 0 .828-.419.936-.634.13-.26.189-.568.189-.866 0-.298-.059-.605-.189-.866-.108-.215-.395-.634-.936-.634zm4.314.634c.108-.215.395-.634.936-.634.54 0 .828.419.936.634.13.26.189.568.189.866 0 .298-.059.605-.189.866-.108.215-.395.634-.936.634-.54 0-.828-.419-.936-.634a1.96 1.96 0 01-.189-.866c0-.298.059-.605.189-.866zm-4.34 7.964a.75.75 0 01-1.061-1.06 5.236 5.236 0 013.73-1.538 5.236 5.236 0 013.695 1.538.75.75 0 11-1.061 1.06 3.736 3.736 0 00-2.639-1.098 3.736 3.736 0 00-2.664 1.098z" clip-rule="evenodd" />
                    </svg>
                </p>
                <?php
                // Query to get total rejected assignments for CSP600
                $sqlCSP600 = "SELECT COUNT(*) as totalRejectedAssignmentsCSP600 FROM assignment_submission s
              JOIN assignment a ON s.ass_id = a.ass_id
              JOIN batches b ON a.batch_id = b.batch_id
              WHERE s.ass_status = 0
              AND b.batch_category = 'CSP600'";
                $resultCSP600 = $conn->query($sqlCSP600);
                $rowCSP600 = $resultCSP600->fetch_assoc();
                $totalRejectedAssignmentsCSP600 = $rowCSP600['totalRejectedAssignmentsCSP600'];

                // Query to get total rejected assignments for CSP650
                $sqlCSP650 = "SELECT COUNT(*) as totalRejectedAssignmentsCSP650 FROM assignment_submission s
              JOIN assignment a ON s.ass_id = a.ass_id
              JOIN batches b ON a.batch_id = b.batch_id
              WHERE s.ass_status = 0
              AND b.batch_category = 'CSP650'";
                $resultCSP650 = $conn->query($sqlCSP650);
                $rowCSP650 = $resultCSP650->fetch_assoc();
                $totalRejectedAssignmentsCSP650 = $rowCSP650['totalRejectedAssignmentsCSP650'];
                ?>

                <p class="ml-6 text-sm text-gray-300">
                    <span class="font-bold text-blue-500"><?php echo $totalRejectedAssignmentsCSP600 ?></span> <span class="font-bold">CSP600</span>
                    and
                    <span class="font-bold text-blue-500"><?php echo $totalRejectedAssignmentsCSP650 ?></span> <span class="font-bold">CSP650</span>
                    Assignment reject by supervisor
                </p>

            </div>

        </div>


        <div class="flex flex-row space-x-4 overflow-x-auto">

            <?php
            $sql = "SELECT 
            a.ass_id,
            a.ass_title,
            a.ass_fl_id,
            s.ass_status,
            b.batch_category,
            COUNT(DISTINCT CASE WHEN s.ass_status = 1 THEN s.ass_submission_id END) AS num_submitted,
            SUM(CASE WHEN s.ass_status = 1 AND COALESCE(s.ass_submissiondate, a.ass_date_due) > a.ass_date_due THEN 1 ELSE 0 END) AS num_late_submissions,
            (SELECT COUNT(DISTINCT st.st_id) FROM student st WHERE st.st_batch = a.batch_id) - COUNT(DISTINCT CASE WHEN s.ass_status = 1 THEN s.ass_submission_id END) AS num_missing_submissions
        FROM 
            assignment a
        JOIN 
            batches b ON a.batch_id = b.batch_id
        LEFT JOIN 
            assignment_submission s ON a.ass_id = s.ass_id
        WHERE 
            a.ass_fl_id = ? 
            AND b.batch_category IN ('CSP600', 'CSP650')
        GROUP BY 
            a.ass_id, a.ass_title, b.batch_category
        ORDER BY 
            a.ass_id;
                ";

            $stmt = $conn->prepare($sql);

            // Bind the parameter to the prepared statement
            $stmt->bind_param("i", $supervisor_id);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
            ?>

                <div class="flex-1">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-gray-800">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-400">
                            <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-white bg-gray-900">
                                Assignment Submission
                            </caption>
                            <thead class="text-xs uppercase text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3 bg-gray-900">
                                        Assignment
                                    </th>
                                    <th scope="col" class="px-3 py-3">
                                        Submit
                                    </th>
                                    <th scope="col" class="px-3 py-3 bg-gray-900">
                                        Late
                                    </th>
                                    <th scope="col" class="px-3 py-3">
                                        Missing
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0) {
                                    // Fetch and display data
                                    while ($row = $result->fetch_assoc()) { ?>
                                        <tr class="border-b border-gray-700">
                                            <th scope="row" class="px-6 py-4 font-medium  whitespace-nowrap text-white bg-gray-900">
                                                <a href="fypl_assignment_details.php?ass_id=<?php echo $row['ass_id']; ?>" class="hover:text-blue-500 hover:font-bold">
                                                    <?php echo htmlspecialchars($row['ass_title']); ?>
                                                </a>
                                                <span class="text-xs font-medium me-2 px-1 py-0.5 rounded bg-blue-900 text-blue-300"><?php echo htmlspecialchars($row['batch_category']); ?></span>
                                            </th>
                                            <td class="px-6 py-4">
                                                <?php echo htmlspecialchars($row['num_submitted']); ?>
                                            </td>
                                            <td class="px-6 py-4 bg-gray-900 text-yellow-400">
                                                <?php echo htmlspecialchars($row['num_late_submissions']); ?>
                                            </td>
                                            <td class="px-6 py-4 text-red-500">
                                                <?php echo htmlspecialchars($row['num_missing_submissions']); ?>
                                            </td>
                                        </tr>
                            <?php }
                                } else {
                                    echo "<tbody>";
                                    echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                                    echo "<td colspan='4' class='px-6 py-4 text-center'>No Assignment found</td>";
                                    echo "</tr>";
                                    echo "</tbody>";
                                }
                            } // end if
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php
                $sql = "SELECT
                            d.doc_id,
                            d.doc_title,
                            d.doc_fl_id,
                            b.batch_category,
                            COUNT(ds.doc_submission_id) AS num_submitted,
                            SUM(CASE WHEN ds.doc_submissiondate > d.doc_date_due THEN 1 ELSE 0 END) AS num_late_submission,
                            (SELECT COUNT(*) FROM student s WHERE s.st_batch = d.batch_id) - COUNT(ds.doc_submission_id) AS num_missing_submission
                        FROM
                            document d
                        JOIN
                            batches b ON d.batch_id = b.batch_id
                        LEFT JOIN
                            document_submission ds ON d.doc_id = ds.doc_id
                        WHERE
                            d.doc_fl_id = ? AND b.batch_category IN ('CSP600', 'CSP650')
                        GROUP BY
                            d.doc_id, d.doc_title, d.doc_date_due, b.batch_category;
                    ";

                $stmt = $conn->prepare($sql);

                // Bind the parameter to the prepared statement
                $stmt->bind_param("i", $supervisor_id);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                ?>

                    <div class="flex-1">
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-gray-800">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-400">
                                <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-white bg-gray-900">
                                    Document Submission
                                </caption>
                                <thead class="text-xs uppercase text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 bg-gray-900">
                                            Document
                                        </th>
                                        <th scope="col" class="px-3 py-3">
                                            Submit
                                        </th>
                                        <th scope="col" class="px-3 py-3 bg-gray-900">
                                            Late
                                        </th>
                                        <th scope="col" class="px-3 py-3">
                                            Missing
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result->num_rows > 0) {
                                        // Fetch and display data
                                        while ($row = $result->fetch_assoc()) { ?>
                                            <tr class="border-b border-gray-700">
                                                <th scope="row" class="px-6 py-4 font-medium  whitespace-nowrap text-white bg-gray-900">
                                                    <a href="fypl_document_details.php?doc_id=<?php echo $row['doc_id']; ?>" class="hover:text-blue-500 hover:font-bold">
                                                        <?php echo htmlspecialchars($row['doc_title']); ?>
                                                    </a>
                                                    <span class="text-xs font-medium me-2 px-1 py-0.5 rounded bg-blue-900 text-blue-300"><?php echo htmlspecialchars($row['batch_category']); ?></span>
                                                </th>
                                                <td class="px-6 py-4">
                                                    <?php echo htmlspecialchars($row['num_submitted']); ?>
                                                </td>
                                                <td class="px-6 py-4 bg-gray-900 text-yellow-400">
                                                    <?php echo htmlspecialchars($row['num_late_submission']); ?>
                                                </td>
                                                <td class="px-6 py-4 text-red-500">
                                                    <?php echo htmlspecialchars($row['num_missing_submission']); ?>
                                                </td>
                                            </tr>
                                <?php }
                                    } else {
                                        echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                                        echo "<td colspan='4' class='px-6 py-4 text-center'>No Document found</td>";
                                        echo "</tr>";
                                    }
                                } // end if
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>




                    <?php
                    $sql = "SELECT
                                t.task_id,
                                t.task_title,
                                t.task_date_due,
                                b.batch_category,
                                t.task_sv_id,
                                COUNT(ts.task_submission_id) AS num_submitted,
                                SUM(CASE WHEN ts.submissiondate > t.task_date_due THEN 1 ELSE 0 END) AS num_late_submission,
                                (SELECT COUNT(*) FROM student s WHERE s.st_batch = t.batch_id) - COUNT(ts.task_submission_id) AS num_missing_submission
                            FROM
                                task t
                            JOIN
                                batches b ON t.batch_id = b.batch_id
                            LEFT JOIN
                                task_submission ts ON t.task_id = ts.task_id
                            WHERE
                                t.task_sv_id = ? AND b.batch_category IN ('CSP600', 'CSP650')
                            GROUP BY
                                t.task_id, t.task_title, t.task_date_due, b.batch_category, t.task_sv_id;
                        ";

                    $stmt = $conn->prepare($sql);

                    // Bind the parameter to the prepared statement
                    $stmt->bind_param("i", $supervisor_id);

                    if ($stmt->execute()) {
                        $result = $stmt->get_result();
                    ?>

                        <div class="flex-1">
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-gray-800">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-400">
                                    <caption class="p-5 text-lg font-semibold text-left rtl:text-right  text-white bg-gray-900">
                                        Task Submission
                                    </caption>
                                    <thead class="text-xs  uppercase text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 bg-gray-900">
                                                Task
                                            </th>
                                            <th scope="col" class="px-3 py-3">
                                                Submit
                                            </th>
                                            <th scope="col" class="px-3 py-3 bg-gray-900">
                                                Late
                                            </th>
                                            <th scope="col" class="px-3 py-3">
                                                Missing
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            // Fetch and display data
                                            while ($row = $result->fetch_assoc()) { ?>
                                                <tr class="border-b border-gray-700">
                                                    <th scope="row" class="px-6 py-4 font-medium  whitespace-nowrap text-white bg-gray-900">
                                                        <a href="fypl_task_details.php?task_id=<?php echo $row['task_id']; ?>" class="hover:text-blue-500 hover:font-bold">
                                                            <?php echo htmlspecialchars($row['task_title']); ?>
                                                        </a>
                                                        <span class="text-xs font-medium me-2 px-1 py-0.5 rounded bg-blue-900 text-blue-300"><?php echo htmlspecialchars($row['batch_category']); ?></span>
                                                    </th>
                                                    <td class="px-6 py-4">
                                                        <?php echo htmlspecialchars($row['num_submitted']); ?>
                                                    </td>
                                                    <td class="px-6 py-4 bg-gray-900 text-yellow-400">
                                                        <?php echo htmlspecialchars($row['num_late_submission']); ?>
                                                    </td>
                                                    <td class="px-6 py-4 text-red-500">
                                                        <?php echo htmlspecialchars($row['num_missing_submission']); ?>
                                                    </td>
                                                </tr>
                                    <?php }
                                        } else {
                                            // Display a single row with a message when no documents are found
                                            echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                                            echo "<td colspan='4' class='px-6 py-4 text-center'>No Task found</td>";
                                            echo "</tr>";
                                        }
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                        $conn->close();
                        ?>
        </div>
    </div>
</div>

</div>
<!-- content end -->
</div>
</div>
</div>
</body>

</html>