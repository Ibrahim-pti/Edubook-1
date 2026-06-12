import os
import re

def replace_with_opacity(root_dir):
    pattern = re.compile(r'\.withOpacity\(([^)]+)\)')
    count = 0
    for dirpath, _, filenames in os.walk(root_dir):
        for filename in filenames:
            if filename.endswith('.dart'):
                filepath = os.path.join(dirpath, filename)
                with open(filepath, 'r', encoding='utf-8') as file:
                    content = file.read()
                
                new_content, num_replacements = pattern.subn(r'.withValues(alpha: \1)', content)
                if num_replacements > 0:
                    with open(filepath, 'w', encoding='utf-8') as file:
                        file.write(new_content)
                    count += 1
    print(f'Updated {count} files.')

if __name__ == '__main__':
    replace_with_opacity('lib')
