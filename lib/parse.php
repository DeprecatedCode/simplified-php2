<?php

function parse($code) {
  static $root;
  if (!isset($root)) {
    $root = new stdClass;
    $root->request = new stdClass;
    $root->request->args = (object) $_GET;
    $root->request->form = (object) $_POST;
    $root->request->method = $_SERVER['REQUEST_METHOD'];
    $root->request->time = time(true);
  }
  
  $file = dirname(__DIR__) . '/cache/' . substr(md5($code), 0, 8) . '.php';
  if (true || !file_exists($file)) {
    ob_start();
    echo '<?php $S = n($root);';
    $buffer = array('b($S, 0); return $S;');
    
    /**
     * Parse code
     */
    $syntax = array(
      '(' => ')'   ,
      '[' => ']'   ,
      '{' => '}'   ,
     '/*' => '*/'  ,
      '#' => "\n"  ,
    '"""' => '"""' ,
    "'''" => "'''" ,
     '"'  =>  '"'  ,
     "'"  =>  "'"
    );
    
    $nest = array(
      '(' => 1,
      '[' => 1,
      '{' => 1
    );
    
    $current = new stdClass;
    $current->nest = true;
    $current->token = '{';
    $current->stop = '}';

    $stack = array($current);

    $ql = $current->line = $line = 1;
    $qc = $current->column = $column = 0;

    $length = strlen($code);
    $queue = '';

    /**
     * Main Parse Loop
     */
    for($pos = 0; $pos < $length; $pos++) {

        if($code[$pos] == "\r") {
            ;
        } else if($code[$pos] == "\n") {
            $column = 0;
            $line++;
        } else {
            $column++;
        }

        /**
         * First, check for the current stop block.
         */
        if(isset($current->stop)) {
            $slen = strlen($current->stop);
            $chars = substr(
                $code, $pos, $slen
            );

            if($chars === $current->stop) {
                if($queue !== '') {
                    process($current, $queue, $ql, $qc);
                    $queue = '';
                }
                if(count($stack) === 0) {
                  break;
                }
                
                if (isset($current->end)) {
                  echo $current->end;
                }

                array_pop($stack);
                $current = $stack[count($stack) - 1];

                $pos += $slen - 1;
                continue;
            }
        }
        
        /**
         * Search for matching characters, from 3 to 1
         */
        if($current->nest) {
            for($blen = 3; $blen >= 1; $blen--) {
                $chars = substr(
                    $code, $pos, $blen
                );
                if(isset($syntax[$chars])) {
                    if($queue !== '') {
                        process($current, $queue, $ql, $qc);
                        $queue = '';
                    }

                    $new = new stdClass;
                    $new->token    = $chars;
                    $new->stop     = $syntax[$chars];
                    $new->nest     = isset($nest[$chars]);
                    $new->line     = $line;
                    $new->column   = $column;
                    
                    $new->end = block($chars, $line, $column);

                    $current = $new;
                    $stack[] = $current;
                    $pos += $blen - 1;
                    continue 2;
                }
            }
        }

        /**
         * No match, add to queue and continue
         */
        if($queue === '') {
            /**
             * Note: If the queue is empty and the first character
             * is a newline, $line has already been incremented
             * above. We need to account for that and subtract 1.
             */
            $ql = $line - ($code[$pos] === "\n" ? 1 : 0);
            $qc = $column;
        }
        $queue .= $code[$pos];
    }
    process($current, $queue, $ql, $qc);
    $queue = '';
    if(count($stack) > 1) {
        $sline = $current->line;
        $scolumn = $current->column;
        throw new Exception("Unclosed block starting with `$current->token` " .
            "at line $sline column $scolumn");
    }

    /**
     * Finalize cached file
     */
    echo ' ' . implode(' ', array_reverse($buffer));
    file_put_contents($file, ob_get_clean());
  }
  
  return require($file);
}

function block($code, $line, $column) {
  switch($code) {
    case '{':
      echo ' $L = function ($P) { $S = n($P);';
      return " b(\$S, 0); return \$S; }; v(\$S, \$L(\$S),$line,$column);";
    case '[';
      echo ' $L = function ($P) { $S = a($P);';
      return " b(\$S, 0); return \$S; }; v(\$S, \$L(\$S),$line,$column);";
  }
}

function process($current, $code, $line, $column) {
  switch($current->token) {
    case '#':
    case '/*':
      return;
    case '"':
      echo " v(\$S,\"$code\",$line,$column);";
      return;
    case "'":
      echo " v(\$S,'$code',$line,$column);";
      return;
    default:
      expr($current, $code, $line, $column);
  }
}

function expr(&$current, $expr, $line, $column) {
    static $regex = array(
        '[+-]?(\d+(\.\d+)?([eE][+-]?\d+)?)' => 'v',
        '[a-zA-Z0-9_]+'                     => 'i',
        '\.\.|\=\=|[^\sa-zA-Z0-9_]'         => 'o',
        '\n+'                               => 'b',
        '\s+'                               => 's'
    );
    while(strlen($expr) > 0) {
        foreach($regex as $re => $type) {
            $match = preg_match("/^($re)/", $expr, $groups);
            if($match) {
                $str = $groups[0];
                $len = strlen($str);
                $expr = substr($expr, $len);
                
                /**
                 * Output
                 */
                switch($type) {
                  case 'i':
                  case 'o':
                    $value = "'$str'";
                    break;
                  case 'b':
                    $value = 0;
                    break;
                  default:
                    $value = $str;
                }
                if ($type !== 's') {
                  echo " $type(\$S,$value,$line,$column);";
                }

                /**
                 * Update Line and Column
                 */
                for($i = 0; $i < $len; $i++) {
                    if($str[$i] == "\r") {
                        ;
                    } else if($str[$i] == "\n") {
                        $column = 1;
                        $line++;
                    } else {
                        $column++;
                    }
                }
            }
        }
    }
}
