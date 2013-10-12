<?php

type::$string->print = function ($string) {
  echo $string;
};

type::$string->length = function ($string) {
  return strlen($string);
};

type::$string->html = function ($string) {
  return htmlspecialchars($string);
};

type::$string->{'#operator +'} =
type::$string->{'#apply array'} =
type::$string->{'#apply object'} =
type::$string->{'#apply string'} =
type::$string->{'#apply integer'} =
type::$string->{'#apply float'} = function ($left, $right) {
  if (is_object($right)) {
    $right = run($right);
  }
  if (is_object($right)) {
    return $left . get($right, 'to_json');
  }
  return $left . $right;
};

type::$string->{'#apply regex'} = function ($string, $regex, $context=null) {
  $matches = array();
  preg_match_all($regex->{'#value'}, $string, $matches);
  $arr = a($context);
  $arr->{'#value'} = count($matches) ? $matches[0] : array();
  return $arr;
};

type::$string->{'#operator ='} = function ($left, $right) {
  return $left === $right;
};

type::$string->{'#operator ~'} = function ($left, $right, $context=null) {
  $fn = type::$string->{'#apply regex'};
  $regex = regex($right, $context);
  return $fn($left, $regex, $context);
};

type::$string->trim = function ($string) {
  return trim($string);
};

type::$string->upper = function ($string) {
  return strtoupper($string);
};

type::$string->lower = function ($string) {
  return strtolower($string);
};

type::$string->contains = function ($string, $context=null) {
  return cmd('string.contains', $context, array(
    'string' => function ($command, $search) use ($string) {
      return strpos($string, $search) !== false;
    }
  ));
};

type::$string->split = function ($string, $context=null) {
  return cmd('string.split', $context, array(
    'string' => function ($command, $by) use ($string, $context) {
      $arr = a($context);
      $arr->{'#value'} = explode($by, $string);
      return $arr;
    }
  ));
};

type::$string->replace = function ($string, $context=null) {
  $cmd = cmd('string.replace', $context, array(
    
    /**
     * Handle "".replace {"old": "new"}
     */
    'object' => function ($command, $object) use ($string, $context) {
      $fn = type::$object->{'#each'};
      $fn($object, function ($key, $value) use (&$string) {
        $string = str_replace($key, $value, $string);
      });
      return $string;
    },
    
    /**
     * Handle "".replace "old" "new"
     */
    'regex|string|integer|float' => function ($command, $search) use ($string, $context) {
      $cmd = cmd('string.replace', null, array(
        'string|integer|float' => function ($command, $replace) use ($string, $context, $search) {
          if (isset($command->{'#regex'})) {
            return preg_replace($search->{'#value'}, $replace, $string);
          }
          return str_replace($search, $replace, $string);
        }
      ));
      
      if (is_object($search) && $search->{'#type'} === 'regex') {
        $cmd->{'#regex'} = true;
      }
      
      return $cmd;
    }
  ));
  
  /**
   * Handle "".replace ~"[a-z]" "_"
   */
  $cmd->{'#operator ~'} = function ($command, $right) use($context) {
    $regex = regex($right, $context);
    return apply($command, $regex);
  };
  return $cmd;
};
