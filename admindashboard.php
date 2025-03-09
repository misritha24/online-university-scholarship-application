<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: url('addash.png') no-repeat center center/cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100vh;
            margin: 0;
            padding-top: 50px; /* Moves title higher */
        }

        h1 {
            font-size: 60px;
            text-align: center;
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.94);
            margin-bottom: 40px;
        }

        .button-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2 buttons per row */
            gap: 30px;
            width: 80%; /* Increases button width */
            max-width: 700px;
        }

        .button {
            padding: 25px;
            font-size: 20px;
            font-weight: bold;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 120px; /* Increases button height */
            width: 100%; /* Makes buttons wider */
            transition: all 0.3s ease-in-out;
            box-shadow: 0 0 15px rgba(160, 7, 130, 0.6); /* Constant glow */
        }

        .merit { background-color: #007BFF; }
        .need { background-color: #28a745; }
        .international { background-color: #ffc107; color: black; }
        .minority { background-color: #dc3545; }

        .button:hover {
            transform: scale(1.1); /* Popup effect */
            box-shadow: 0 0 25px rgba(255, 255, 255, 1); /* Intense glow on hover */
        }
    </style>
</head>
<body>

    <h1>Admin Dashboard</h1>

    <div class="button-container">
        <a href="adminmerit.php" class="button merit">Merit-Based Scholarships</a>
        <a href="adminneed.php" class="button need">Need-Based Scholarships</a>
        <a href="admininternational.php" class="button international">International Scholarships</a>
        <a href="adminminority.php" class="button minority">Minority Scholarships</a>
    </div>

</body>
</html>
