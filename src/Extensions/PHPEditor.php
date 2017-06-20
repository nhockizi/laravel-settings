<?php

namespace Kizi\Settings\Extensions;

use Kizi\Settings\Form\Field;

class PHPEditor extends Field
{
    protected $view = 'admin::form.editor';

    protected static $js = [
        '/packages/admin/codemirror/lib/codemirror.js',
        '/packages/admin/codemirror/addon/edit/matchbrackets.js',
        '/packages/admin/codemirror/mode/htmlmixed/htmlmixed.js',
        '/packages/admin/codemirror/mode/xml/xml.js',
        '/packages/admin/codemirror/mode/markdown/markdown.js',
        '/packages/admin/codemirror/mode/javascript/javascript.js',
        '/packages/admin/codemirror/mode/css/css.js',
        '/packages/admin/codemirror/mode/clike/clike.js',
        '/packages/admin/codemirror/mode/php/php.js',
        '/packages/admin/codemirror/addon/hint/show-hint.js',
        '/packages/admin/codemirror/addon/hint/css-hint.js',
        '/packages/admin/codemirror/addon/hint/html-hint.js',
        '/packages/admin/codemirror/addon/hint/javascript-hint.js',
        '/packages/admin/codemirror/mode/clike/clike.js',
        '/packages/admin/codemirror/addon/display/fullscreen.js',
        '/packages/admin/codemirror/keymap/sublime.js',
        '/packages/admin/codemirror/addon/search/search.js',
        '/packages/admin/codemirror/addon/search/searchcursor.js',
        '/packages/admin/codemirror/addon/search/jump-to-line.js',
        '/packages/admin/codemirror/addon/dialog/dialog.js',
        '/packages/admin/codemirror/addon/scroll/simplescrollbars.js',
    ];

    protected static $css = [
        '/packages/admin/codemirror/lib/codemirror.css',
        '/packages/admin/codemirror/addon/hint/show-hint.css',
        '/packages/admin/codemirror/addon/display/fullscreen.css',
        '/packages/admin/codemirror/addon/dialog/dialog.css',
        '/packages/admin/codemirror/addon/scroll/simplescrollbars.css',
    ];

    public function render()
    {
        $this->script = <<<EOT
// The bindings defined specifically in the Sublime Text mode
var bindings = {
    "Ctrl-X Cmd-X":"cut",
    "Ctrl-S Cmd-S":"save",
    "Ctrl-C Cmd-C":"copy",
    "Ctrl-V Cmd-V":"paste",
    "Ctrl-F Cmd-F":"Start searching",
    "Ctrl-G Cmd-G":"Find next",
    "Shift-Ctrl-G Shift-Cmd-G":"Find previous",
    "Shift-Ctrl-F Shift-Cmd-F":"Replace",
    "Shift-Ctrl-R Shift-Cmd-R":"Replace all",
    "Alt-F":"Persistent search",
    "Alt-G":"Jump to line",
}

// The implementation of joinLines
function joinLines(cm) {
  var ranges = cm.listSelections(), joined = [];
  for (var i = 0; i < ranges.length; i++) {
    var range = ranges[i], from = range.from();
    var start = from.line, end = range.to().line;
    while (i < ranges.length - 1 && ranges[i + 1].from().line == end)
      end = ranges[++i].to().line;
    joined.push({start: start, end: end, anchor: !range.empty() && from});
  }
  cm.operation(function() {
    var offset = 0, ranges = [];
    for (var i = 0; i < joined.length; i++) {
      var obj = joined[i];
      var anchor = obj.anchor && Pos(obj.anchor.line - offset, obj.anchor.ch), head;
      for (var line = obj.start; line <= obj.end; line++) {
        var actual = line - offset;
        if (line == obj.end) head = Pos(actual, cm.getLine(actual).length + 1);
        if (actual < cm.lastLine()) {
          cm.replaceRange(" ", Pos(actual), Pos(actual + 1, /^\s*/.exec(cm.getLine(actual + 1))[0].length));
          ++offset;
        }
      }
      ranges.push({anchor: anchor || head, head: head});
    }
    cm.setSelections(ranges, 0);
  });
}
CodeMirror.fromTextArea(document.getElementById("{$this->id}"), {
    lineNumbers: true,
    scrollbarStyle:"overlay",
    mode: "text/x-php",
    extraKeys: {
        "Tab": function(cm){
            cm.replaceSelection("   " , "end");
        },
        "F11": function(cm) {
          cm.setOption("fullScreen", !cm.getOption("fullScreen"));
        },
        "Esc": function(cm) {
          if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
        },
        "Ctrl-Space": "autocomplete",
     },
      commandsOptions: {
                        edit: {
                          mimes: [],
                          editors: [{
                            mimes: ['text/plain', 'text/x-php', 'application/x-httpd-php', 'text/html', 'text/javascript'],
                            load: function(textarea) {
                              var mimeType = this.file.mime;
                              return CodeMirror.fromTextArea(textarea, {
                                mode: mimeType,
                                lineNumbers: true,
                                indentUnit: 4
                              });
                            },
                            save: function(textarea, editor) {
                              $(textarea).val(editor.getValue());
                            }
                          }]
                        }
                      }
});

EOT;
        return parent::render();
    }
}
