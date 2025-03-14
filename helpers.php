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
 * @param array @data data we want to pass in to partial - default is []
 * @return void
 */
function load_partial(string $partial_name, array $data = []): void
{
  $partial_path = base_path("App/views/partials/{$partial_name}.php");

  if (file_exists($partial_path)) {
    extract($data);
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

/**
 * Sanitize a dirty data
 *
 * @param string $dirty
 * @return string
 */
function sanitize(string $dirty): string
{
  return filter_var(trim($dirty), FILTER_SANITIZE_SPECIAL_CHARS);
}

/**
 * Redirect to a given path
 *
 * @param string $url - the path you want to redirect
 * @return void
 */
function redirect(string $url): void
{
  header("Location: {$url}");
  exit;
}
