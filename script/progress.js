// Define data for the progress chart
var data = {
    labels: ["Completed", "Remaining"],
    datasets: [{
        data: [75, 25], // Values should represent completed and remaining progress percentages
        backgroundColor: ["#36A2EB", "#FF6384"],
    }],
};

// Define options for the progress chart
var options = {
    responsive: true,
    maintainAspectRatio: false,
    legend: {
        display: true,
        position: 'bottom',
    },
};

// Get the canvas element
var ctx = document.getElementById("progressChart").getContext("2d");

// Create the progress chart
var progressChart = new Chart(ctx, {
    type: 'doughnut', // You can use other chart types based on your preference
    data: data,
    options: options,
});
