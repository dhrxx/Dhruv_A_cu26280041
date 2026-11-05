<?php if (isset($notice) && is_string($notice)): ?><p role="status"><?= escape($notice) ?></p><?php endif; ?>
<form method="post" action="/upload" enctype="multipart/form-data" class="form-card">
  <?= csrf_field() ?>
  <label>JPEG, PNG, or WebP (maximum 2 MiB)
    <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" required>
  </label>
  <button type="submit">Upload</button>
</form>
