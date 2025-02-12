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