
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