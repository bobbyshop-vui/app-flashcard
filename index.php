<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashcards Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Flashcards Management</h1>

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "flashcard_db";
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['term'], $_POST['definition'])) {
            $term = $conn->real_escape_string($_POST['term']);
            $definition = $conn->real_escape_string($_POST['definition']);
            $sql = "INSERT INTO flashcards (term, definition) VALUES ('$term', '$definition')";
            if ($conn->query($sql) === TRUE) {
                echo '<div class="alert alert-success" role="alert">Flashcard added successfully!</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
            $id = $conn->real_escape_string($_GET['id']);
            $sql = "SELECT * FROM flashcards WHERE id=$id";
            $result = $conn->query($sql);
            $flashcard = $result->fetch_assoc();
        }
        ?>

        <div class="row">
            <div class="col-md-6">
                <h2 class="mt-4">Add New Flashcard</h2>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="term">Term</label>
                        <input type="text" class="form-control" id="term" name="term" required>
                    </div>
                    <div class="form-group">
                        <label for="definition">Definition</label>
                        <textarea class="form-control" id="definition" name="definition" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Flashcard</button>
                </form>
            </div>

            <div class="col-md-6">
                <h2 class="mt-4">Flashcards List</h2>
                <ul class="list-group">
                    <?php
                    $sql = "SELECT * FROM flashcards";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<li class="list-group-item"><a href="?id=' . $row['id'] . '">' . $row['term'] . '</a></li>';
                        }
                    } else {
                        echo '<li class="list-group-item">No flashcards found</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <?php if (isset($flashcard)) { ?>
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-4">Flashcard Details</h2>
                <p><strong>Term:</strong> <?php echo $flashcard['term']; ?></p>
                <p><strong>Definition:</strong> <?php echo $flashcard['definition']; ?></p>
            </div>
        </div>
        <?php } ?>

    </div>
</body>
</html>
