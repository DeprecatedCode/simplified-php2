<?php

/**
 * SimplifiedPHP
 * @author Nate Ferrero
 */

echo "[ ] Installing sphp command...\n";

$code = <<<'EOF'
#!/usr/bin/env php
<?php

define('CLI', true);
require('*dir*/simplified.php');
EOF;

$code = str_replace('*dir*', __DIR__, $code);

$file = "/usr/bin/sphp";
file_put_contents($file, $code);
chmod($file, 0755);

echo "[x] Installed! Try: sphp -c \"'Hello World\\n'.print\"\n";
