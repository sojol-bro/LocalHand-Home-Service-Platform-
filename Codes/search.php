<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_query'])) {
    // Using prepared statements to prevent SQL injection
    $search_query = '%' . $_POST['search_query'] . '%';
    $sql = "SELECT * FROM workers WHERE specialty LIKE :search_query";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':search_query', $search_query, PDO::PARAM_STR);
    $stmt->execute();

    echo "<style>
            body { background-color:rgb(153, 208, 215); font-family: Arial, sans-serif; }
            .worker-card { background: #fff; padding: 15px; margin: 10px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            .worker-card a { text-decoration: none; color:rgb(103, 163, 228); }
            .worker-card button { background-color:rgb(97, 169, 224); border: none; color: white; padding: 10px 15px; border-radius: 5px; cursor: pointer; }
            .worker-card button:hover { background-color: #0056b3; }
            .back-button { position: absolute; top: 10px; right: 10px; padding: 10px 15px; background: #ff1744; color: white; border: none; border-radius: 5px; cursor: pointer; }
            .back-button:hover { background:rgb(228, 133, 133); }
          </style>
          <button class='back-button' onclick='window.history.back()'>Back</button>";

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='worker-card'>
                    <p><strong>Name:</strong> " . htmlspecialchars($row['name']) . "</p>
                    <p><strong>Specialty:</strong> " . htmlspecialchars($row['specialty']) . "</p>
                    <form action='worker_profile_view_nonuser.php' method='GET'>
                        <input type='hidden' name='worker_id' value='" . htmlspecialchars($row['worker_id']) . "'>
                        <button type='submit'>See Profile</button>
                    </form>
                  </div>";
        }
    } else {
        echo "<p>No results found.</p>";
    }
}
$pdo = null; // Closing the connection
?>
