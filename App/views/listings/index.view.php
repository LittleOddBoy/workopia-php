<?= load_partial('head') ?>
<?= load_partial('nav') ?>
<?= load_partial('top-banner') ?>

<!-- Job Listings -->
<section>
  <div class="container mx-auto p-4 mt-4">
    <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3">
      <?php if (isset($keywords)) : ?>
        Search results for: <?= htmlspecialchars($keywords) ?>
      <?php else: ?>
        All Jobs
      <?php endif; ?>
      <?php if (isset($location)) : ?>
        in <?= htmlspecialchars($location) ?>
      <?php endif; ?>
    </div>
    <?= load_partial('message') ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <?php foreach ($listings as $l): ?>
        <div class="rounded-lg shadow-md bg-white">
          <div class="p-4">
            <h2 class="text-xl font-semibold"><?= $l->title ?></h2>
            <p class="text-gray-700 text-lg mt-2">
              <?= $l->description ?>
            </p>
            <ul class="my-4 bg-gray-100 p-4 rounded">
              <li class="mb-2"><strong>Salary:</strong> <?= format_salary($l->salary) ?></li>
              <li class="mb-2">
                <strong>Location:</strong> <?= $l->city ?>
              </li>
              <li class="mb-2">
                <strong>Tags:</strong>
                <?= $l->tags ?>
              </li>
            </ul>
            <a href="/listings/<?= $l->id ?>"
              class="block w-full text-center px-5 py-2.5 shadow-sm rounded border text-base font-medium text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
              Details
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
</section>

<?= load_partial('bottom-banner') ?>
</section>


<?= load_partial('bottom') ?>