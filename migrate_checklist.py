
import re
import sys
import os

def migrate_file(filepath):
    print(f"Migrating {filepath}...")
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
    except Exception as e:
        print(f"Error reading file: {e}")
        return

    # Regex for Checkboxes
    # Original: <input type="checkbox" name="result[1][]" id="checkbox4" value="PASS" class="large-checkbox">
    # Target:   <input type="checkbox" name="result[1][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(1, 'PASS', $saved_results); ?>>
    
    # We use a non-greedy match for attributes in between if any, but the provided file seems standard.
    # Pattern captures: 1=index, 2=id, 3=value
    checkbox_pattern = r'<input type="checkbox" name="result\[(\d+)\]\[\]" id="([^"]+)" value="([^"]+)" class="large-checkbox">'
    checkbox_replacement = r'<input type="checkbox" name="result[\1][]" id="\2" value="\3" class="large-checkbox" <?php echo isChecked(\1, \'\3\', $saved_results); ?>>'
    
    new_content = re.sub(checkbox_pattern, checkbox_replacement, content)
    
    # Regex for Remarks
    # Original: <input type="text" name="checklist_remark[1]">
    # Target:   <input type="text" name="checklist_remark[1]" value="<?php echo getRemark(1, $saved_remarks); ?>">
    
    # Pattern captures: 1=index
    remark_pattern = r'<input type="text" name="checklist_remark\[(\d+)\]">'
    remark_replacement = r'<input type="text" name="checklist_remark[\1]" value="<?php echo getRemark(\1, $saved_remarks); ?>">'
    
    new_content = re.sub(remark_pattern, remark_replacement, new_content)

    # Check if changes were made
    if content == new_content:
        print("No matches found or file already migrated.")
    else:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print("File updated successfully.")

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python migrate_checklist.py <filepath>")
    else:
        migrate_file(sys.argv[1])
