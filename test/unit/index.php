# SimplifiedPHP Unit Test Runner
# @author Nate Ferrero

# Discover all tests

{ ? @request.args ?? discover: {

    groups: []

    @dir '.' .dirs {dir:it
    
      # Create an entry in paths
      paths: {name: dir.name, tests: []}
      groups.push paths
      
      # Loop through all files in dir and add to paths
      dir.files {
        paths.tests.push('/' (dir.name) '/' (it.name))
      }
    }
    
    # Return JSON and exit
    groups.to_json.print, @exit
  }$
}$

# Colors

bright:     '#fff'
normal:     '#f9fbfd'
tint:       '#ddd'
highlight:  '#6ad'
dark:       '#444'
purple:     '#a0a'
yellow:     '#da0'
red:        '#a00'
green:      '#0a0'
gray:       '#aaa'

"""
<!doctype html>
<html>
<head>
  <title>SimplifiedPHP Unit Test Suite</title>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
  <script>
    var unit = {};

    $(function () {
    
      unit.el = $('#unit');
      unit.baseUrl = window.location.pathname.replace(/unit.*$/, 'unit');
      $('#run').click(start);
      
      start();
    });
    
    unit.clear = function () {
      unit.el.empty();
    };
    
    var start = function () {
      unit.clear();
      $.getJSON('?discover', function (groups) {
        unit.groups = groups;
        render();
      });
    };
    
    var render = function () {
      unit.clear();
      
      unit.groups.map(function (group) {
        unit.el.append($('<h2>').text(group.name));
        
        group.tests.map(function (testUrl) {
          testUrl = unit.baseUrl + testUrl;
          var el = $('<div class="test">');
          unit.el.append(el);
          runTest(testUrl, el);
        });
      });
    };
    
    var runTest = function (testUrl, el) {
      $.getJSON(testUrl)
      .done(function (result) {
        el.click(function () {
          inspect(el, testUrl, result);
        });
        if (result && result.pass == result.total) {
          el.addClass('pass');
        }
        else if (result && result.pass) {
          el.addClass('fail');
        }
        else {
          el.addClass('error');
        }
      })
      .fail(function (data) {
        el.click(function () {
          inspect(el, testUrl, data);
        });
        el.addClass('error');
      });
    };
    
    var inspect = function (el, testUrl, result) {
      var clear = el.hasClass('selected');
      $('.selected').removeClass('selected');
      var content = $('#inspect .content');
      content.empty();
      if (clear) {return;}
      el.addClass('selected');
      content.append($('<p>').append(
          $('<a target="_blank">').attr('href', testUrl).text(testUrl)
      ));
      result.tests && result.tests.forEach(function (test) {
        content.append($('<h2>').text(test.status));
        test.message && content.append($('<pre>').text(test.message));
      });
    };
  
  </script>
  <link href="http://fonts.googleapis.com/css?family=Oxygen+Mono|Open+Sans:400,700" rel="stylesheet" type="text/css" />
  <style>
    body {
      font-family: "Open Sans", Tahoma, Arial, sans-serif;
      font-size: 15px;
      padding: 0 0.75em;
      color: #333;
      padding-right: 552px;
    }
    
    a, .link {
      color: """highlight""";
      text-decoration: none;
      cursor: pointer;
    }
    
    a:hover, .link:hover {
      text-decoration: underline;
    }
    
    a:active, .link:active {
      opacity: 0.8;
    }

    h2 {
      font-size: 16px;
    }
    
    #inspect {
      width: 498px;
      border-left: 2px solid """tint""";
      background: """normal""";
      position: absolute;
      top: 0;
      bottom: 0;
      right: 0;
      padding: 0 20px;
      overflow-y: scroll;
    }
    
    .test {
      display: inline-block;
      overflow: hidden;
      width: 15px;
      height: 15px;
      padding: 5px;
      border: 3px solid rgba(0, 0, 0, 0.25);
      border-radius: 2px;
      margin: 0 1em 1em 0;
      background: """tint""";
      position: relative;
      color: transparent;
      user-select: none;
      -webkit-user-select: none;
      -moz-user-select: none;
      transition: background 2s;
    }
    
    .test.selected {
      border-color: rgba(255, 255, 255, 0.5);
    }
    
    .test.pass {
      background: """green""";
    }
    
    .test.fail {
      background: """yellow""";
    }
    
    .test.error {
      background: """red""";
      color: white;
    }
    
    .test a {
      position: absolute;
      top: -7px;
      left: -7px;
      width: 20px;
      height: 20px;
      background: """dark""";
      border-radius: 20px;
      font-size: 0;
    }
    
    .test a:hover {
      background: """highlight""";
    }
  </style>
</head>
<body>
  <h1><a href="../../">SimplifiedPHP</a> &bull; Unit Test Suite &bull;
    <span id="run" class="link">Run</span></h1>
  <div id="unit"></div>
  <div id="inspect">
    <h1>Test Inspector</h1>
    <div class="content"></div>
  </div>
</body>
""".print
