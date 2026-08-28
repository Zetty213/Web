<?php
require 'config.php';

$stmt = $pdo->query('SELECT * FROM posts ORDER BY id DESC');
?>
<form action="post.php" method="post" enctype="multipart/form-data">
    <textarea name="body"></textarea><br>
    <input type="file" name="image"><br>
    <button type="submit">投稿</button>
</form>

<hr>

<?php foreach ($stmt as $row): ?>
    <div>
        <p>No.<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>
            <?= htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8') ?>
        </p>
        <p><?= htmlspecialchars($row['body'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php if ($row['image_path']): ?>
            <img src="<?= htmlspecialchars($row['image_path'], ENT_QUOTES, 'UTF-8') ?>" width="200">
        <?php endif; ?>
    </div>
<?php endforeach; ?>