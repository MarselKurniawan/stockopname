<?php
session_start();
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
require_once __DIR__ . '/../core/v2/config.php';
require_once __DIR__ . '/../core/v2/database.php';
require_once __DIR__ . '/../core/func/csrf_protection.php';

include_once 'interface/header.php';
$mysqli = db_connect();

// Handle adding or updating a note
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $page_title = $_POST['page_title'];
    $content = $_POST['content'];

    if ($id) {
        // Update the existing note
        $stmt = $mysqli->prepare("UPDATE notes SET page_title = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $page_title, $content, $id);
    } else {
        // Insert a new note
        $stmt = $mysqli->prepare("INSERT INTO notes (page_title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $page_title, $content);
    }

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch all notes
$notes_result = $mysqli->query("SELECT * FROM notes ORDER BY created_at DESC");
$notes = $notes_result->fetch_all(MYSQLI_ASSOC);

// Fetch a specific note if editing
$current_note = null;
if (isset($_GET['edit'])) {
    $edit_id = (int) $_GET['edit'];
    $result = $mysqli->query("SELECT * FROM notes WHERE id = $edit_id");
    $current_note = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Notepad</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-5xl mx-auto bg-white shadow-lg p-6 rounded-lg">
        <h1 class="text-2xl font-bold mb-4">PHP Notepad</h1>

        <!-- Form for creating/updating a note -->
        <form method="POST" class="mb-6">
            <input type="hidden" name="id" value="<?= $current_note['id'] ?? '' ?>">
            <div class="mb-4">
                <label for="page_title" class="block font-medium text-gray-700">Page Title</label>
                <input type="text" id="page_title" name="page_title" value="<?= $current_note['page_title'] ?? '' ?>"
                    required class="mt-1 p-2 border border-gray-300 rounded w-full">
            </div>
            <div class="mb-4">
                <label for="content" class="block font-medium text-gray-700">Content</label>
                <textarea id="content" name="content" rows="10" required
                    class="mt-1 p-2 border border-gray-300 rounded w-full"><?= $current_note['content'] ?? '' ?></textarea>
            </div>
            <button type="submit"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"><?= isset($current_note) ? 'Update Note' : 'Add Note' ?></button>
            <?php if (isset($current_note)): ?>
                <a href="index.php" class="ml-4 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</a>
            <?php endif; ?>
        </form>

        <!-- List of notes -->
        <h2 class="text-xl font-bold mb-4">Notes</h2>
        <?php if (count($notes) > 0): ?>
            <ul class="space-y-4">
                <?php foreach ($notes as $note): ?>
                    <li class="p-4 bg-gray-100 rounded shadow">
                        <h3 class="text-lg font-bold"><?= htmlspecialchars($note['page_title']) ?></h3>
                        <p class="text-sm text-gray-600"><?= htmlspecialchars(substr($note['content'], 0, 100)) ?>...</p>
                        <div class="mt-2">
                            <a href="index.php?edit=<?= $note['id'] ?>"
                                class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Edit</a>
                            <a href="delete.php?id=<?= $note['id'] ?>"
                                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                                onclick="return confirm('Are you sure you want to delete this note?')">Delete</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-600">No notes found. Add your first note above!</p>
        <?php endif; ?>
    </div>
</body>

</html>