<?php

/**
 * Get the base path to coordinate files and modules easily
 *
 * @param string $path
 * @return string
 */
function base_path(string $path = ""): string
{
  return __DIR__ . $path;
}

/**
 * Load a view
 *
 * @param string $view_name
 * @return void
 */
function load_view(string $view_name): void
{
  require base_path("views/{$view_name}.view.php");
}

/**
 * Load a partial
 *
 * @param string $partial_name
 * @return void
 */
function load_partial(string $partial_name): void 
{
  require base_path("views/partials/{$partial_name}.php");
}