<?= load_partial('head') ?>
<?= load_partial('nav') ?>

<section>
  <div class="container mx-auto p-4 mt-4">
    <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3"><?= $status ?> Error</div>
    <p class="text-center text-2xl mb-4">
      <?= $message ?>
    </p>
    <a class='block text-center' href="/listings">Go Back to Listings</a>
  </div>
</section>

<?= load_partial('bottom') ?>