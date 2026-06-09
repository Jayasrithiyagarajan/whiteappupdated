import re

with open('c:/xampp/htdocs/whiteapp1/job/overall-job-list.php', 'r', encoding='utf-8') as f:
    overall = f.read()

with open('c:/xampp/htdocs/whiteapp1/job/pending_projects.php', 'r', encoding='utf-8') as f:
    pending = f.read()

# Extract CSS from overall
style_match = re.search(r'(<style>.*?</style>)', overall, re.DOTALL)
overall_style = style_match.group(1)

# Extract main body structure from overall
body_match = re.search(r'(<div class="main-content.*?</div>\s*</div>)', overall, re.DOTALL)
overall_body = body_match.group(1)

# Extract pending specific elements
# 1. Page title
# "Pending Projects" and "Review pending jobs..."
new_body = overall_body.replace('Project Directory', 'Pending Projects')
new_body = new_body.replace('Manage project progress, assignments, inspection status, and reports', 'Review pending jobs, monitor ageing work, and filter active or expired project assignments.')

# 2. Status filter removal
status_filter_re = r'<div class="filter-item">\s*<label>Status</label>\s*<select id="status-filter">.*?</select>\s*</div>'
new_body = re.sub(status_filter_re, '<!-- No Status Filter as this is Pending Only page -->', new_body, flags=re.DOTALL)

# 3. Table Headers
pending_table_match = re.search(r'(<table id="job-table" class="display nowrap" style="width:100%">.*?</table>)', pending, re.DOTALL)
pending_table = pending_table_match.group(1)

overall_table_re = r'<table id="job-table" class="display nowrap" style="width:100%">.*?</table>'
new_body = re.sub(overall_table_re, pending_table, new_body, flags=re.DOTALL)

# Now, we construct the new pending_projects.php
# Replace style
pending_new = re.sub(r'<style>.*?</style>', overall_style, pending, flags=re.DOTALL)

# Replace main content
pending_new = re.sub(r'<div class="main-content.*?</div>\s*</div>', new_body, pending_new, flags=re.DOTALL)

# Add #job-search to JS if it's missing in pending
js_search = """
    $('#job-search').on('input', function() {
        table.search(this.value).draw();
    });
"""
if "job-search" not in pending_new:
    pending_new = pending_new.replace("loadStats();\n\n    // 3. Attach Filter Events", "loadStats();\n\n    " + js_search + "\n\n    // 3. Attach Filter Events")

# Ensure clearFilters in JS handles #job-search and we use correct stats updating
clear_filters_re = r'function clearFilters\(\)\{.*?(table\.ajax\.reload\(\);)'
clear_filters_replacement = """function clearFilters(){
    $('#filter-inspector').val('');
    $('#filter-client').val('');
    $('#filter-date-from').val('');
    $('#filter-date-to').val('');
    $('#filter-year').val('');
    $('#filter-expiry-status').val('');
    $('#job-search').val('');
    
    table.search('');
    table.ajax.reload();"""
pending_new = re.sub(r'function clearFilters\(\)\{.*?(table\.ajax\.reload\(\);)', clear_filters_replacement, pending_new, flags=re.DOTALL)

# Replace stats IDs in loadStats
pending_new = pending_new.replace("$('#stats-total').text(res.total);", "$('#stats-total, #hero-stats-total').text(res.total);")
pending_new = pending_new.replace("$('#stats-active').text(res.active);", "$('#stats-active, #hero-stats-active').text(res.active);")
pending_new = pending_new.replace("$('#stats-expired').text(res.expired);", "$('#stats-expired, #hero-stats-expired').text(res.expired);")

with open('c:/xampp/htdocs/whiteapp1/job/pending_projects.php', 'w', encoding='utf-8') as f:
    f.write(pending_new)

print("Done rewrite")
