<?php

type::$string->print = function ($string) {
  echo $string;
};

type::$string->length = function ($string) {
  return strlen($string);
};

type::$string->lines = function ($string, $context=null) {
  $arr = a($context);
  $arr->{'#value'} = preg_split('/\R/', $string);
  return $arr;
};

type::$string->md5 = function ($string) {
  return md5($string);
};

type::$string->esc = function ($string) {
  return addslashes($string);
};

type::$string->html = function ($string) {
  return htmlspecialchars($string);
};

type::$string->htmlnl = function ($string) {
  return nl2br(htmlspecialchars($string));
};

type::$string->{'#operator +'} =
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

type::$string->slice = function ($string, $context=null) {
  return cmd('string.slice', $context, array(
    'array' => function ($cmd, $array) {
      $fn = type::$array->{'#each'};
      $arr = $fn($array, function ($key, $item) use ($cmd) {
        return apply($cmd, $item);
      });
      $cmd = get($arr, 'join');
      return apply($cmd, '');
    },
    'integer' => function ($cmd, $integer) use ($string) {
      return $string[$integer];
    }
  ));
};

type::$string->{'#apply regex'} = function ($string, $regex, $context=null) {
  $matches = array();
  $regex = $regex->{'#value'};
  $regex = "/$regex/";
  preg_match_all($regex, $string, $matches);
  $arr = a($context);
  $arr->{'#value'} = count($matches) ? $matches[0] : array();
  return $arr;
};

type::$string->{'#operator ='} = function ($left, $right, $context=null) {
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

type::$string->title = function ($string) {
  return ucwords($string);
};

type::$string->contains = function ($string, $context=null) {
  return cmd('string.contains', $context, array(
    'string' => function ($command, $search) use ($string) {
      return strpos($string, $search) !== false;
    }
  ));
};

type::$string->repeat = function ($string, $context=null) {
  return cmd('string.repeat', $context, array(
    'integer' => function ($command, $quantity) use ($string) {
      return str_repeat($string, $quantity);
    }
  ));
};

type::$string->split = function ($string, $context=null) {
  $cmd = cmd('string.split', $context, array(
    
    /**
     * Handle "".split "by"
     */
    'regex|string|integer|float' => function ($command, $split) use ($string, $context) {
      if (is_object($split) && $split->{'#type'} === 'regex') {
        $regex = str_replace('/', '\\/', $split->{'#value'});
        $regex = "/$regex/";
        return preg_split($regex, $string);
      }
      return explode($split, $string);
    }
  ));
  
  /**
   * Handle "".split ~"[a-z]"
   */
  $cmd->{'#operator ~'} = function ($command, $right) use($context) {
    $regex = regex($right, $context);
    return apply($command, $regex);
  };
  return $cmd;
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
            $regex = str_replace('/', '\\/', $search->{'#value'});
            $regex = "/$regex/";
            return preg_replace($regex, $replace, $string);
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
