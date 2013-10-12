# Summary: Strings! You can do just about everything that ordinary PHP
# can do with them.

title: "Strings", @import "test.php"

# String length

sentence: "What do you call it when you feed a stick of dynamite to a steer? Abominable!"

"The joke is " (sentence.length) " characters long. " .print

_flush_()

# Regex matching

"It contains " (sentence ~"y".length) " instances of the letter y!" .print

_flush_()

# Contains and conditional catch-all *:

"It " {sentence.contains ? "dynamite": "does", *: "does not"} " refer to explosives." .print

_flush_()

# String and regex replacement

compressed: sentence.replace " " "" .replace ~"[aeiouy]" ""

"Compressed, there are " (compressed.length) " characters, and it looks like: " compressed .print

_flush_()

# Object replacement

sentence.replace {"stick": "bundle", "dynamite": "hay", "Abom": "Straw"} .print

_flush_()

# Escaping Strings

w: '\\  \' \"  \' "  \'\' ""  \'\'\' \"""  /'

x: "\\  \' \"  ' \"  '' \"\"  \''' \"\"\"  /"

y:  '''\\  \' \"  ' "  '' ""  \''' """  /'''

z:  """\\  \' \"  ' "  '' ""  ''' \"""  /"""

'<pre>' ([w, x, y, z].join '<br/>') '</pre><br/>'.print

'Length of each string: '.print, [w, x, y, z]{it.length}.join ', ' '.'.print
