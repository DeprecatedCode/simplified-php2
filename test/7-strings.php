# Summary: Strings! You can do just about everything that ordinary PHP
# can do with them.

title: "Strings", @import "test.php"

# String length

sentence: "What do you call it when you feed a stick of dynamite to
a steer? Abominable!"

"<p>The joke is " (sentence.length) " characters long.</p>" .print

_flush_()

# Regex matching

"<p>It contains " (sentence ~"y".length) " instances of the letter
y!</p>" .print

_flush_()

# Contains and conditional catch-all *:

"<p>It " {sentence.contains ? "dynamite": "does", *: "does not"} " refer
to explosives.</p>" .print

_flush_()

# String and regex replacement

compressed: sentence.replace " " "" .replace ~"[aeiouy]" ""

"<p>Compressed, there are " (compressed.length) " characters, and it
looks like: " compressed "</p>".print

_flush_()

# Object replacement

[
  '<p>'
  sentence.replace {"stick": "bundle", "dynamite": "hay", "Abom": "Straw"}
  '</p>'
].print

_flush_()

# Escaping Strings

w: '\\  \' \"  \' "  \'\' ""  \'\'\' \"""  /'

x: "\\  \' \"  ' \"  '' \"\"  \''' \"\"\"  /"

y:  '''\\  \' \"  ' "  '' ""  \''' """  /'''

z:  """\\  \' \"  ' "  '' ""  ''' \"""  /"""

'<pre>' ([w, x, y, z].join '<br/>') '</pre>'.print

'<p>Length of each string: '.print, [w, x, y, z]{it.length}.join ', ' '.</p>'.print

_flush_()

# Array application

str: "The quick brown fox jumps over the lazy dog."

'<p>' str [' Twice.', ' Thrice!'] '</p>'.print

_flush_()

# Substring with slice

'<p>' (str.slice[0..2, 3, 35..38, 3, 10..14, 3, 39..43]) '</p>'.print

'<p>' (str.slice(0..18)) '.</p>' .print
