import re

with open('resources/views/portal/dashboard.blade.php', 'r') as f:
    content = f.read()

# Fix CSS
old_css = """.ql-editor { direction: rtl; text-align: right; }
  .ql-editor[dir="ltr"] { direction: ltr !important; text-align: left !important; }
  .ql-editor:not([dir="ltr"]) ol, .ql-editor:not([dir="ltr"]) ul { padding-left: 0; padding-right: 1.5em; }
  .ql-editor:not([dir="ltr"]) li::before { margin-left: 0 !important; margin-right: -1.5em !important; text-align: right !important; }"""

new_css = """.ql-editor { direction: rtl; text-align: right; }
  .quill-editor[dir="ltr"] .ql-editor { direction: ltr !important; text-align: left !important; }
  
  .quill-editor:not([dir="ltr"]) .ql-editor ol,
  .quill-editor:not([dir="ltr"]) .ql-editor ul { padding-left: 0; padding-right: 1.5em; }
  
  .quill-editor:not([dir="ltr"]) .ql-editor li::before { 
      margin-left: 0 !important; 
      margin-right: -1.5em !important; 
      text-align: right !important; 
  }"""
content = content.replace(old_css, new_css)

with open('resources/views/portal/dashboard.blade.php', 'w') as f:
    f.write(content)
