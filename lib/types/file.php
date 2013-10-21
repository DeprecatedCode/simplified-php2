<?php

type::$file->{'#run'} = function ($file) {
  return $file;
};

type::$file->read = function ($file) {
  return file_get_contents($file->path);
};

type::$file->name = function ($file) {
  return basename($file->path);
};

type::$file->delete = function ($file) {
  return unlink($file->path);
};

type::$file->write = function ($file) {
  return cmd('file.write', $file, array('*' => function ($command, $value) use ($file) {
    file_put_contents($file->path, $value);
  }));
};

type::$file->lines = function ($file, $context=null) {
  $arr = a($context);
  $arr->{'#value'} = file($file->path);
  return $arr;
};
