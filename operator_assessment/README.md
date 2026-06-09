# Operator Assessment Module

## Overview
This module provides a comprehensive two-step operator assessment workflow:
1. **Admin creates assessment** - Basic details and inspector assignment
2. **Inspector fills details** - Complete operator information, equipment details, and document uploads

## Features
- ✅ Auto-generated assessment numbers (CIMS-OAT-001, CIMS-OAT-002, etc.)
- ✅ Client and inspector selection from existing database
- ✅ Dynamic equipment fields (supports 1-3 equipment per assessment)
- ✅ Six document upload types with camera capture support
- ✅ Status tracking (PENDING, IN_PROGRESS, COMPLETED)
- ✅ DataTables integration for list view
- ✅ Print-friendly assessment view
- ✅ Responsive design

## Installation

### Step 1: Database Setup
Run the SQL script to create required tables:

```bash
# Navigate to phpMyAdmin or MySQL command line
# Import the SQL file or run the queries
```

Execute: `setup_operator_assessment.sql`

This will create three tables:
- `operator_assessments` - Main assessment records
- `operator_equipment` - Equipment details (1-3 per assessment)
- `operator_documents` - Document uploads (up to 6 types)

### Step 2: Verify Folder Structure
Ensure the following folders exist:
- `operator_assessment/` - Module files
- `uploads/operator_assessments/` - File upload directory

## File Structure

```
operator_assessment/
├── setup_operator_assessment.sql    # Database schema
├── create-assessment.php            # Admin: Create new assessment
├── add-assessment.php               # Admin: Backend handler
├── assessment-list.php              # View all assessments
├── fill-assessment.php              # Inspector: Fill details
├── update-assessment.php            # Inspector: Backend handler
├── view-assessment.php              # View complete assessment
├── delete-assessment.php            # Delete assessment
└── README.md                        # This file
```

## Usage

### Admin Workflow (Step 1)
1. Navigate to `create-assessment.php`
2. Fill in basic details:
   - Assessment number (auto-generated)
   - Date
   - Operator name and ID/Passport
   - Select client from dropdown
   - Enter location
   - Select operating location (Onshore/Offshore)
   - Select number of equipment (1-3)
   - Assign inspector
3. Click "Create Assessment"
4. Assessment is created with status "PENDING"

### Inspector Workflow (Step 2)
1. Navigate to `assessment-list.php`
2. Click "Fill Details" on a PENDING assessment
3. Complete the form:
   - Verify operator details
   - Enter license number
   - Fill equipment details (manufacturer, model, capacity)
   - Upload required documents:
     * IQAMA or Passport
     * Heavy Equipment License
     * Operator Photo (can use camera)
     * Medical Certificate
     * Previous Certificate (optional)
     * Additional files (optional)
   - Set assessment and expiry dates
4. Click "Save Assessment"
5. Status changes to "COMPLETED"

## Document Upload Types

| No. | Document Type | Required | Accept |
|-----|--------------|----------|---------|
| 1 | IQAMA or Passport | Yes | Image, PDF |
| 2 | Heavy Equipment License | Yes | Image, PDF |
| 3 | Operator Photo | Yes | Image (camera supported) |
| 4 | Medical Certificate | Yes | Image, PDF |
| 5 | Previous Certificate | No | Image, PDF |
| 6 | Additional Files | No | Image, PDF |

## Database Schema

### operator_assessments
- Primary table storing assessment records
- Links to customers (client_id) and new_users (inspector_id)
- Tracks status: PENDING → IN_PROGRESS → COMPLETED

### operator_equipment
- Stores equipment details (1-3 per assessment)
- Foreign key: assessment_id
- Fields: manufacturer, model, capacity

### operator_documents
- Stores uploaded files
- Foreign key: assessment_id
- Document types: IQAMA_PASSPORT, LICENSE, PHOTO, MEDICAL_CERT, PREVIOUS_CERT, ADDITIONAL

## Access URLs

- **Create Assessment**: `http://localhost/whiteappupdated/operator_assessment/create-assessment.php`
- **Assessment List**: `http://localhost/whiteappupdated/operator_assessment/assessment-list.php`
- **Fill Assessment**: `http://localhost/whiteappupdated/operator_assessment/fill-assessment.php?id={ID}`
- **View Assessment**: `http://localhost/whiteappupdated/operator_assessment/view-assessment.php?id={ID}`

## Security Features
- Session-based authentication
- SQL injection prevention (prepared statements)
- File upload validation
- Transaction support for data integrity

## Troubleshooting

### Issue: Assessment number not auto-generating
**Solution**: Ensure the database table `operator_assessments` exists and is accessible.

### Issue: File uploads failing
**Solution**: 
1. Check folder permissions: `uploads/operator_assessments/` should be writable (777)
2. Verify PHP upload settings in `php.ini`:
   - `upload_max_filesize = 20M`
   - `post_max_size = 20M`

### Issue: Inspector dropdown empty
**Solution**: Ensure users with role 'inspector' exist in the `new_users` table.

### Issue: Client dropdown empty
**Solution**: Add clients via the customer module first.

## Future Enhancements
- Email notifications to inspectors
- PDF export of assessments
- Bulk upload functionality
- Assessment approval workflow
- Mobile app integration

## Support
For issues or questions, contact the development team.
