<?php

type::$file->{'#run'} = function ($file) {
  return $file;
};

type::$file->read = function ($file) {
  return file_get_contents($file->path);
};
