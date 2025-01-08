<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Cutoffs</title>
    <style>
        /* Reset some default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body and container styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Header style */
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        /* Table styles */
        .cutoff-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .cutoff-table th, .cutoff-table td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .cutoff-table th {
            background-color: #f2f2f2;
            color: #333;
            font-size: 1.1em;
        }

        .cutoff-table td {
            font-size: 1em;
            color: #555;
        }

        /* Hover effect for table rows */
        .cutoff-table tr:hover {
            background-color: #f9f9f9;
        }

        /* Alternate row colors */
        .cutoff-table tr:nth-child(even) {
            background-color: #fafafa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Department Cutoffs</h1>
        <table class="cutoff-table">
            <thead>
                <tr>
                    <th>Department Name</th>
                    <th>Previous Year Cutoff</th>
                    <th>Current Year Cutoff</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Computer Science</td>
                    <td>85%</td>
                    <td>87%</td>
                </tr>
                <tr>
                    <td>Mechanical Engineering</td>
                    <td>78%</td>
                    <td>80%</td>
                </tr>
                <tr>
                    <td>Civil Engineering</td>
                    <td>75%</td>
                    <td>77%</td>
                </tr>
                <tr>
                    <td>Electrical Engineering</td>
                    <td>80%</td>
                    <td>82%</td>
                </tr>
                <tr>
                    <td>Information Technology</td>
                    <td>82%</td>
                    <td>84%</td>
                </tr>
                <tr>
                    <td>Chemical Engineering</td>
                    <td>74%</td>
                    <td>76%</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
