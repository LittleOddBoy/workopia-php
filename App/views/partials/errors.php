<?php if (isset($errors)) : ?>
  <?php foreach ($errors as $e) : ?>
    <div class="message bg-red-100 my-3"><?= $e ?></div>
  <?php endforeach; ?>
<?php endif; ?>