<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Feedback App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f0f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>

<body>
    <div id="app" class="loading">Loading feedback...</div>

    <script>
        // 🟢 Change only this line to load the desired JSON
        const DATA_FILE = "feedback_multi.json"; // or "feedback_single.json"

        async function loadFeedbackData() {
            try {
                const response = await fetch(DATA_FILE);
                if (!response.ok) throw new Error("Failed to load data");
                const data = await response.json();

                // Save to sessionStorage
                sessionStorage.setItem("feedbackData", JSON.stringify(data));

                // Determine which page to load
                let targetPage = "";
                if (data.Question || (data.Questions && data.Questions[0]?.Type === "single")) {
                    targetPage = "customer_feedback_single.html";
                } else if (data.Questions || (data.Questions && data.Questions[0]?.Type === "multiple")) {
                    targetPage = "customer_feedback_multi.html";
                }

                // Redirect
                window.location.href = targetPage;
            } catch (error) {
                console.error("Error loading feedback:", error);
                document.getElementById("app").innerHTML =
                    `<div class="text-danger text-center">Failed to load feedback data.</div>`;
            }
        }

        loadFeedbackData();
    </script>
</body>

</html>
