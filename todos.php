<?php
// Initialize the edit mode flag
$edit_mode = false;

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "myTodoList";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted for adding or updating an item
if (isset($_POST["submit"])) {
    if ($_POST["action"] === "create") {
        // Retrieve the form data for adding an item
        $title = $_POST["title"];

        // Insert the data into the database
        $sql = "INSERT INTO myTodoList (todoName) VALUES ('$title')";
        if ($conn->query($sql) === TRUE) {
            $status_message = "New todo item created successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($_POST["action"] === "update") {
        // Retrieve the form data for updating an item
        $id = $_POST["id"];
        $title = $_POST["title"];

        // Update the data in the database
        $sql = "UPDATE myTodoList SET todoName='$title' WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            $status_message = "Todo item updated successfully.";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}

// Check if an action has been requested
if (isset($_POST["action"])) {
    if ($_POST["action"] === "edit") {
        // Set edit mode flag and retrieve the todo item to edit
        $edit_mode = true;
        $id = $_POST["id"];
        $sql = "SELECT * FROM myTodoList WHERE id='$id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $title = $row["todoName"];
        }
    } elseif ($_POST["action"] === "delete") {
        // Retrieve the todo item to delete
        $id = $_POST["id"];

        // Delete the data from the database
        $sql = "DELETE FROM myTodoList WHERE id='$id'";
        if ($conn->query($sql) === TRUE) {
            $status_message = "Todo item deleted successfully.";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }
}

// Retrieve all the todo items from the database
$sql = "SELECT * FROM myTodoList";
$result = $conn->query($sql);
?>

<?php
if (isset($status_message)) {
    echo "<div class='status-message'>$status_message</div>";
}
?>

<?php if (!$edit_mode) { ?>
    <form method="post">
        <input type="text" name="title" placeholder="Add a new todo item...">
        <input type="hidden" name="action" value="create">
        <input type="submit" name="submit" value="Add Item">
    </form>
<?php } else { ?>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <input type="text" name="title" value="<?php echo $title ?>">
        <input type="hidden" name="action" value="update">
        <input type="submit" name="submit" value="Update Item">
    </form>
<?php } ?>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="todo-item">
            <h2><?php echo $row["todoName"]; ?></h2>
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                <input type="hidden" name="action" value="edit">
                <button type="submit">Edit</button>
            </form>
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
                <input type="hidden" name="action" value="delete">
                <button type="submit">Delete</button>
            </form>
        </div>
        <?php
    }
} else {
    echo "<p>No todo items found.</p>";
}

// Close the database connection
$conn->close();
?>
