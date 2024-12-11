<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Encryption Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
       
        .sidebar .logout-btn {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
        }
        .sidebar .logout-btn button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 18px;
            border-radius: 30px;
            width: 80%;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .sidebar .logout-btn button:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        /* Table Styles */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #f4f4f4;
            color: #333;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }

        /* Hidden Section */
        .hidden-section {
            display: none;
        }
    </style>
</head>
<body>


<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-img-container">
        <img src="1.png" alt="Sidebar Image" class="sidebar-img">
        <span class="sidebar-text">Microm Credit Corporation</span>
    </div>
    <h2 class="text-center text-white">Dashboard</h2>
    <a href="javascript:void(0)" onclick="showSection('welcome')">
        <i class="fas fa-home"></i> Overview
    </a>
    <a href="javascript:void(0)" onclick="showSection('encrypt')">
        <i class="fas fa-lock"></i> File Encryption
    </a>
    <a href="javascript:void(0)" onclick="showSection('decrypt')">
        <i class="fas fa-unlock"></i> File Decryption
    </a>
    <a href="javascript:void(0)" onclick="showSection('deleted')">
        <i class="fas fa-trash-alt"></i> Deleted Files
    </a>

    <!-- Logout Button -->
    <div class="logout-btn">
        <button onclick="window.location.href='logout.php'">Logout</button>
    </div>
</div>

<!-- Main Content -->
<div class="content" style="margin-left: 250px; padding: 20px;">
    <div class="container">
        <!-- Welcome Section -->
        <div id="welcome-section">
            <h1 class="main-header">Welcome to the Dashboard</h1>
            <p>Choose an option from the sidebar to get started.</p>
            <div id="chart"></div> <!-- Container for the chart -->
        </div>

        <!-- Encrypt Section -->
        <div id="encrypt-section" class="hidden-section">
            <h2>Encrypt File</h2>
            <div class="form-container">
                <form action="upload.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="fileToUpload" class="form-label">Select file to upload:</label>
                        <input type="file" name="fileToUpload" id="fileToUpload" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="encryption_key" class="form-label">Enter Password for Encryption:</label>
                        <input type="password" name="encryption_key" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload and Encrypt</button>
                </form>
            </div>

            <!-- Encrypted Files List Table -->
            <h3 class="mt-4">Encrypted Files List</h3>
            <table class="table table-bordered file-table">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Date and Time</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch encrypted files from the uploads folder
                    $directory = 'uploads/';
                    $files = array_diff(scandir($directory), array('..', '.'));

                    foreach ($files as $file) {
                        // Get the last modified time of the file
                        $fileTime = date("Y-m-d H:i:s", filemtime($directory . $file));
                        echo "<tr>
                                <td>$file</td>
                                <td>$fileTime</td>
                                <td><a href='$directory$file' class='btn btn-success' download>Download</a></td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Decrypt Section -->
        <div id="decrypt-section" class="hidden-section">
            <h2>Decrypt File</h2>
            <div class="form-container">
                <form action="decrypt.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="fileToDecrypt" class="form-label">Select encrypted file to decrypt:</label>
                        <input type="file" name="fileToDecrypt" id="fileToDecrypt" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="decryption_key" class="form-label">Enter Password for Decryption:</label>
                        <input type="password" name="decryption_key" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Decrypt File</button>
                </form>
            </div>
        </div>

        <!-- Deleted Files Section -->
        <div id="deleted-section" class="hidden-section">
            <h2>Deleted Files</h2>
            <p>List of deleted files:</p>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Date and Time</th>
                        <th>Restore</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>example1.txt</td>
                        <td>2024-11-20 14:30:00</td>
                        <td><button class="btn btn-warning">Restore</button></td>
                    </tr>
                    <tr>
                        <td>example2.txt</td>
                        <td>2024-11-20 15:00:00</td>
                        <td><button class="btn btn-warning">Restore</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Bootstrap JS and JS for handling dynamic sections -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function showSection(section) {
        // Hide all sections first
        const sections = document.querySelectorAll('.hidden-section');
        sections.forEach(function(sec) {
            sec.style.display = 'none';
        });

        // Show the clicked section
        document.getElementById(section + '-section').style.display = 'block';

        // Also hide the welcome section when switching to encrypt or decrypt
        if (section !== 'welcome') {
            document.getElementById('welcome-section').style.display = 'none';
        } else {
            document.getElementById('welcome-section').style.display = 'block';
        }
    }

     // ApexCharts data and options
     var options = {
          series: [{
          name: 'Income',
          type: 'column',
          data: [1.4, 2, 2.5, 1.5, 2.5, 2.8, 3.8, 4.6]
        }, {
          name: 'Cashflow',
          type: 'column',
          data: [1.1, 3, 3.1, 4, 4.1, 4.9, 6.5, 8.5]
        }, {
          name: 'Revenue',
          type: 'line',
          data: [20, 29, 37, 36, 44, 45, 50, 58]
        }],
          chart: {
          height: 350,
          type: 'line',
          stacked: false
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          width: [1, 1, 4]
        },
        title: {
          text: 'Microm Credit Corporation (2024)',
          align: 'left',
          offsetX: 110
        },
        xaxis: {
          categories: [2015, 2016, 2017, 2012, 2018, 2019, 2020, 2021],
        },
        yaxis: [
          {
            seriesName: 'Income',
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: '#008FFB'
            },
            labels: {
              style: {
                colors: '#008FFB',
              }
            },
            title: {
              text: "Income (thousand crores)",
              style: {
                color: '#008FFB',
              }
            },
            tooltip: {
              enabled: true
            }
          },
          {
            seriesName: 'Cashflow',
            opposite: true,
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: '#00E396'
            },
            labels: {
              style: {
                colors: '#00E396',
              }
            },
            title: {
              text: "Operating Cashflow (thousand crores)",
              style: {
                color: '#00E396',
              }
            },
          },
          {
            seriesName: 'Revenue',
            opposite: true,
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: '#FEB019'
            },
            labels: {
              style: {
                colors: '#FEB019',
              },
            },
            title: {
              text: "Revenue (thousand crores)",
              style: {
                color: '#FEB019',
              }
            }
          },
        ],
        tooltip: {
          fixed: {
            enabled: true,
            position: 'topLeft', // topRight, topLeft, bottomRight, bottomLeft
            offsetY: 30,
            offsetX: 60
          },
        },
        legend: {
          horizontalAlign: 'left',
          offsetX: 40
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>

</body>
</html>
