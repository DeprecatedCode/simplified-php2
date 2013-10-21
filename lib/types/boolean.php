<?php

type::$boolean->print = function ($bool) {
  echo $bool ? '@true' : '@false';
};
