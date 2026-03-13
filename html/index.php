<?php
// Docker ağı sayesinde IP yazmamıza gerek yok, direkt servis adını ('db') yazıyoruz!
$host = 'db';
$db = 'appdb';
$user = 'root';
$pass = 'secretpassword';

try {
    // Veritabanına Bağlan
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Formdan veri geldiyse veritabanına yaz
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['task'])) {
        $stmt = $pdo->prepare('INSERT INTO tasks (description) VALUES (:task)');
        $stmt->execute(['task' => $_POST['task']]);
        header('Location: index.php'); // Sayfayı yenile
        exit;
    }

    // Veritabanındaki kayıtları oku
    $stmt = $pdo->query('SELECT * FROM tasks ORDER BY created_at DESC');
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Aboov! Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">  <title>Troubleshooter Görev Tahtası</title>
</head>
<body style="font-family: sans-serif; background-color: #f4f4f9; padding: 40px;">
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); max-width: 600px; margin: auto;">
        <h2 style="color: #333;">🛠️ Troubleshooter Kayıt Panosu</h2>
        <form method="POST" style="margin-bottom: 20px;">
            <input type="text" name="task" placeholder="Yeni bir sorun/görev yaz..." required style="padding: 10px; width: 70%; border: 1px solid #ccc; border-radius: 4px;">
            <button type="submit" style="padding: 10px 15px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">Kaydet</button>
        </form>
        <hr style="border: 0; border-top: 1px solid #eee;">
        <ul style="list-style-type: none; padding: 0;">
            <?php foreach ($tasks as $task): ?>
                <li style="padding: 10px; border-bottom: 1px solid #eee;">
                    <strong><?= htmlspecialchars($task['description']) ?></strong> 
                    <span style="color: #888; font-size: 0.85em; float: right;"><?= $task['created_at'] ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
