<?php

/**
 * Dump value(s) within <pre> tags
 *
 * @param mixed $val
 * @return void
 */
function d(mixed $val): void
{
  echo "<pre>";
  var_dump($val);
  echo "</pre>";
}

/**
 * Dump and die
 *
 * @param mixed $val
 * @return void
 */
function dd(mixed $val): void
{
  echo "<pre>";
  var_dump($val);
  echo "</pre>";
  die();
}

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
 * @param array $data - default is [] - the data we wanna pass in to the view
 * @return void
 */
function load_view(string $view_name, array $data = []): void
{
  $view_path = base_path("App/views/{$view_name}.view.php");

  if (file_exists($view_path)) {
    extract($data);
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
  $partial_path = base_path("App/views/partials/{$partial_name}.php");

  if (file_exists($partial_path)) {
    require($partial_path);
  } else {
    echo "Partial <i>{$partial_name}</i> doesn't exist!";
  }
}


/**
 * Load the environment variables manually
 *
 * @return void
 */
function load_env(): void
{
  // create the path to that file
  $envFile = base_path(".env");

  // check if file exists
  if (file_exists($envFile)) {
    // parsing the lines
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
      if (strpos(trim($line), '#') === 0) continue; // skip comments
      list($key, $value) = explode('=', $line, 2);
      $key = trim($key);
      $value = trim($value);
      putenv("$key=$value"); // set as an environment variable
    }
  }
}

/**
 * Format the salary
 *
 * @param string $salary
 * @return string
 */
function format_salary(string $salary): string
{
  return "$" . number_format(floatval($salary));
}
