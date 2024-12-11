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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
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

        /* Green background for the File Encryption table headers */
        .file-encryption-table th {
            background-color: #28a745; /* Green background */
            color: white;
        }

        /* Removing hover effect */
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: transparent; /* No hover effect */
        }

        /* Buttons */
        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        /* File input and password input sizes */
        input[type="file"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        /* Adjust file input width for file inputs to match password input size */
        input[type="file"] {
            width: 100%; /* Ensures it fits within the available space */
            max-width: 350px; /* Set a maximum width to match password input */
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
                <form action="encrypt.php" method="POST" enctype="multipart/form-data">
                    <label for="fileToUpload">Mag-upload ng File:</label>
                    <input type="file" name="fileToUpload" required>
                    
                    <label for="encryption_key">Encryption Key:</label>
                    <input type="password" name="encryption_key" required>
                    
                    <button type="submit" class="btn-submit">Encrypt</button>
                </form>
            </div>

            <!-- Encrypted Files List Table -->
            <h3 class="mt-4">Encrypted Files List</h3>
            <table class="table table-bordered file-encryption-table">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Date and Time</th>
                        <th>Download</th>
                        <th>Actions</th> <!-- Added Actions column -->
                    </tr>
                </thead>
                <tbody id="encryptedFilesTable">
                    <?php
                    // Fetch encrypted files from the uploads folder
                    $directory = 'uploads/';
                    $files = array_diff(scandir($directory), array('..', '.'));

                    foreach ($files as $file) {
                        // Get the last modified time of the file
                        $fileTime = date("Y-m-d H:i:s", filemtime($directory . $file));
                        echo "<tr id='file_$file'>
                                <td>$file</td>
                                <td>$fileTime</td>
                                <td><a href='$directory$file' class='btn btn-success' download>Download</a></td>
                                <td>
                                    <!-- Transfer button -->
                                    <button class='btn btn-warning' onclick='transferFile(\"$file\")'><i class='fas fa-arrow-right'></i></button>
                                    <!-- Delete button -->
                                    <button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' onclick='setDeleteFile(\"$file\")'><i class='fas fa-trash'></i></button>
                                </td>
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
                <form action="decrypt.php" method="POST" enctype="multipart/form-data">
                    <label for="fileToDecrypt">Mag-upload ng Encrypted File:</label>
                    <input type="file" name="fileToDecrypt" required>
                    
                    <label for="decryption_key">Decryption Key:</label>
                    <input type="password" name="decryption_key" required>
                    
                    <button type="submit" class="btn-submit">Decrypt</button>
                </form>
            </div>

            <!-- Decrypted Files List Table -->
            <h3 class="mt-4">Decrypted Files List</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Date and Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Decrypted file list content here -->
                </tbody>
            </table>
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
                <tbody id="deletedFilesTable">
                    <!-- Deleted files content will be dynamically added -->
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Modal for Deletion Confirmation -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this file?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal()">No</button>
                <button type="button" class="btn btn-danger" onclick="deleteFile()">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and JS for handling dynamic sections -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let fileToDelete = '';

    // Function to set the file to delete
    function setDeleteFile(fileName) {
        fileToDelete = fileName;
    }

    // Function to confirm file deletion
    function deleteFile() {
        // Logic to move file to deleted section
        const deletedTable = document.getElementById('deletedFilesTable');
        const newRow = deletedTable.insertRow();
        newRow.innerHTML = `
            <td>${fileToDelete}</td>
            <td>${new Date().toLocaleString()}</td>
            <td><button class="btn btn-warning">Restore</button></td>
        `;

        // Remove the file from the encryption list
        const fileRow = document.getElementById('file_' + fileToDelete);
        fileRow.parentNode.removeChild(fileRow);

        // Reset fileToDelete
        fileToDelete = ''; 

        // Close the modal immediately after the action is performed
        const modalElement = document.getElementById('deleteModal');
        const modal = bootstrap.Modal.getInstance(modalElement);
        modal.hide(); // This hides the modal immediately
    }

    // Function to close the modal when clicking "No"
    function closeModal() {
        const modalElement = document.getElementById('deleteModal');
        const modal = bootstrap.Modal.getInstance(modalElement);
        modal.hide(); // This hides the modal immediately
    }

    // Function to transfer file to decryption section
    function transferFile(fileName) {
        alert("File is being transferred to the Decryption section.");
        // Code to handle transferring file (show decryption section)
        showSection('decrypt');
    }

    // Function to show sections
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
