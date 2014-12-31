<?php

type::$boolean->print = function ($bool) {
  echo $bool ? '@true' : '@false';
};

type::$boolean->to_source = function ($bool) {
  return $bool ? '@true' : '@false';
};
