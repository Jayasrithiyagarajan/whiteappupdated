import re
from pathlib import Path
path = Path('document/checklist/type/view/side-boom-tractors.php')
text = path.read_text(encoding='utf-8', errors='ignore')
pattern = re.compile(r'<tr>\s*<td><strong>([^<]+)</strong></td>\s*<td><strong>([^<]+)</strong></td>\s*<td[^>]*><strong>([^<]+)</strong></td>', re.IGNORECASE)
rows = pattern.findall(text)
print('rows:', len(rows))
for i, (num, crit, ref) in enumerate(rows[:80]):
    print(f'{i} {num.strip()} || {crit.strip().replace(chr(10), " ")} || {ref.strip().replace(chr(10), " ")}')
print('---')
for num, crit, ref in rows[-10:]:
    print(f'{num.strip()} || {crit.strip().replace(chr(10), " ")} || {ref.strip().replace(chr(10), " ")}')
