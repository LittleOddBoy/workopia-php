<?php

/**
 * Get the base path to coordinate files and modules easily
 *
 * @param string $path
 * @return string
 */
function base_path(string $path = ""): string
{
  return __DIR__ . "/" . $path;
}

/**
 * Load a view
 *
 * @param string $view_name
 * @return void
 */
function load_view(string $view_name): void
{
  $view_path = base_path("views/{$view_name}.view.php");

  if (file_exists($view_path)) {
    require($view_path);
  } else {
    echo "View <i>{$view_name}</i> doesn't exist!";
  }
}

/**
 * Load a partial
 *
 * @param string $partial_name
 * @return void
 */
function load_partial(string $partial_name): void 
{
  $partial_path = base_path("views/partials/{$partial_name}.php");

  if (file_exists($partial_path)) {
    require($partial_path);
  } else {
    echo "Partial <i>{$partial_name}</i> doesn't exist!";
  }
}