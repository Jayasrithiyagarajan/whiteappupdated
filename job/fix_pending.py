with open('c:/xampp/htdocs/whiteapp1/job/overall-job-list.php', 'r', encoding='utf-8') as f:
    overall = f.read()

with open('c:/xampp/htdocs/whiteapp1/job/pending_projects.php', 'r', encoding='utf-8') as f:
    pending = f.read()

# 1. Get the exact main-content from overall-job-list
main_content_start = overall.find('<div class="main-content d-flex flex-column overall-jobs-directory">')
main_content_end = overall.find('<?php include_once(\'../inc/footer.php\'); ?>')
overall_main = overall[main_content_start:main_content_end]

# 2. Modify overall_main for pending_projects
new_main = overall_main.replace('Project Directory', 'Pending Projects')
new_main = new_main.replace('Manage project progress, assignments, inspection status, and reports', 'Review pending jobs, monitor ageing work, and filter active or expired project assignments.')
new_main = new_main.replace('<h5>Project </h5>', '<h5>Pending Projects</h5>')
new_main = new_main.replace('View project records, track completion, and open details', 'View pending project records, track completion, and open details')

# Remove status filter block correctly
status_filter_block = """            <div class="filter-item">
                <label>Status</label>
                <select id="status-filter">
                    <option value="">All Statuses</option>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>"""
new_main = new_main.replace(status_filter_block, '')

# Replace the table headers with the pending ones
overall_table = new_main[new_main.find('<table'):new_main.find('</table>')+8]
pending_table = """<table id="job-table" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Project ID</th>
                        <th>Date</th>
                        <th>Progress</th>
                        <th>Checklist</th>
                        <th>Report</th>
                        <th>Inspection Type</th>
                        <th>Reviewer</th>
                        <th>Certificate</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Action</th>
                        <th>Equip.ID</th>
                        <th>Checklist Name</th>
                        <th>Sticker No</th>
                        <th>Certificate Type</th>
                        <th>Equip.Type</th>
                        <th>Location</th>
                        <th>Inspector</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>"""
new_main = new_main.replace(overall_table, pending_table)

# 3. Replace the main-content in pending_projects.php
pending_main_start = pending.find('<div class="main-content')
pending_main_end = pending.find('<?php include_once(\'../inc/footer.php\'); ?>')
pending = pending[:pending_main_start] + new_main + pending[pending_main_end:]

with open('c:/xampp/htdocs/whiteapp1/job/pending_projects.php', 'w', encoding='utf-8') as f:
    f.write(pending)
