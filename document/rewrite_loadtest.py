import re

with open('c:/xampp/htdocs/whiteapp1/document/lifting/index.php', 'r', encoding='utf-8') as f:
    lifting = f.read()

with open('c:/xampp/htdocs/whiteapp1/document/loadtest/index.php', 'r', encoding='utf-8') as f:
    loadtest = f.read()

# 1. Get CSS from lifting
lifting_style = re.search(r'(<style>.*?</style>)', lifting, re.DOTALL).group(1)

# 2. Get main content from lifting
lifting_main_start = lifting.find('<div class="main-content d-flex flex-column overall-jobs-directory">')
lifting_main_end = lifting.find('<?php \ninclude_once(\'../../inc/footer.php\');')
if lifting_main_end == -1:
    lifting_main_end = lifting.find('<?php\ninclude_once(\'../../inc/footer.php\');')
if lifting_main_end == -1:
    lifting_main_end = lifting.find('<?php include_once(\'../../inc/footer.php\'); ?>')
lifting_main = lifting[lifting_main_start:lifting_main_end]

# 3. Create the new loadtest body
new_main = lifting_main

# Replace Titles
new_main = new_main.replace('Lifting Gear Inspection Registry', 'Certificate of Thorough Examination')
new_main = new_main.replace('Monitor lifting gear certificates, active and expired items, clients, inspectors, and exam dates.', 'Review load test certificates, equipment details, client records, locations, and examination dates.')

# In lifting, there are KPI stats and filters. Loadtest does not use them in backend.
# But "exact UI" implies the layout of the top should look similar.
# The user wants exact UI. Let's keep the filter UI but we won't hook it to the table since it's "don't change any functionality" OR I can just remove the filter section. Let's remove the filter section to prevent broken filters, or maybe keep the exact UI without the filter section.
# Actually, lifting UI means using `overall-jobs-directory` class, the `directory-hero`, and `table-panel-header`.
# I'll just remove the `.filter-section` completely.
filter_start = new_main.find('<div class="filter-section">')
filter_end = new_main.find('<!-- Table Section -->')
new_main = new_main[:filter_start] + new_main[filter_end:]

# Replace the table headers in new_main with loadtest table
loadtest_table = re.search(r'(<table id="loadtest-table".*?</table>)', loadtest, re.DOTALL).group(1)
lifting_table = re.search(r'(<table id="lifting-table".*?</table>)', new_main, re.DOTALL).group(1)
new_main = new_main.replace(lifting_table, loadtest_table)

# Replace table title
new_main = new_main.replace('<h5>Lifting Gear Records</h5>', '<h5>Load Test Certificate Register</h5>')
new_main = new_main.replace('Manage lifting gear certificates with inspection details and expiry tracking', 'Search, sort, export, edit, and manage certificate records.')

# Replace KPI stats with loadtest's summary strips? The user wants exact UI from lifting, which has Total, Active, Expired stats.
# Loadtest backend does NOT return KPI. 
# Let's just remove the hero-actions or keep them as 0 to maintain exact UI. I will keep them.

# 4. Replace the old loadtest CSS and body
loadtest_new = re.sub(r'<style>.*?</style>', lifting_style, loadtest, flags=re.DOTALL)

# Replace the main-content
loadtest_main_start = loadtest_new.find('<div class="main-content')
loadtest_main_end = loadtest_new.find('<?php include_once(\'../../inc/footer.php\'); ?>')
loadtest_new = loadtest_new[:loadtest_main_start] + new_main + loadtest_new[loadtest_main_end:]

# 5. Fix Javascript:
# We need to add the `#customSearch` event to the loadtest table.
js_search = """
$('#customSearch').on('keyup', function() {
    $('#loadtest-table').DataTable().search(this.value).draw();
});
"""
loadtest_new = loadtest_new.replace('});\n\nfunction redirectToEditLoadTest', '});\n' + js_search + '\nfunction redirectToEditLoadTest')

# Also, loadtest uses DataTables but currently the "Export to Excel" button is appended in dom: 'Bfrtip'.
# In lifting, it's appended to `#table-buttons` via `initComplete: function() { this.api().buttons().container().appendTo('#table-buttons'); }`.
# Let's inject `initComplete` into the loadtest datatable.
init_complete = """,
        initComplete: function() {
            this.api().buttons().container().appendTo('#table-buttons');
        }"""
loadtest_new = loadtest_new.replace('autoWidth: false\n    });', 'autoWidth: false' + init_complete + '\n    });')

# Remove `dom: 'Bfrtip'` and replace with `dom: 'Brtip'` to hide default search
loadtest_new = loadtest_new.replace("dom: 'Bfrtip'", "dom: 'Brtip'")

# Replace the 'Export to Excel' button class to match lifting
btn_replace = """text: '<i class="fa fa-file-excel me-1"></i> Export Excel',
                className: 'btn btn-primary',
                action:"""
loadtest_new = loadtest_new.replace("text: 'Export to Excel',\n                action:", btn_replace)

# Add the premium-directory.css link if missing
if 'premium-directory.css' not in loadtest_new:
    loadtest_new = loadtest_new.replace('<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&display=swap" rel="stylesheet">', '<link rel="stylesheet" href="../../assets/css/premium-directory.css">\n<link rel="stylesheet" href="../../assets/css/premium-nav.css">')

with open('c:/xampp/htdocs/whiteapp1/document/loadtest/index.php', 'w', encoding='utf-8') as f:
    f.write(loadtest_new)

print("Done")
