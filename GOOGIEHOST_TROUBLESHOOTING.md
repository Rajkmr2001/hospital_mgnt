# GoogieHost Troubleshooting Guide

## Common Issues and Solutions

### Patient Registration Not Working

#### Symptoms
- Registration form submits but nothing happens
- No error messages displayed
- Works on localhost but not on GoogieHost

#### Possible Causes and Solutions

1. **Database Connection Issues**
   - Check if the database credentials in `register.php` match your GoogieHost credentials
   - Verify that the database and tables exist on GoogieHost
   - Check the `register_log.txt` file for connection errors

2. **Table Structure Issues**
   - Ensure the `patient_register` table exists with the correct structure
   - Required columns: `mobile_no`, `name`, `gender`, `password`, `register_date`, `register_time`
   - Run the diagnostic tool: `http://yourdomain.com/db_diagnostics.php`

3. **Path Issues**
   - Ensure all file paths are correct for the GoogieHost environment
   - Check for any hardcoded localhost paths

4. **Permission Issues**
   - Set appropriate file permissions (644 for files, 755 for directories)
   - Ensure PHP has write permissions for log files

### Feedback System Not Working

#### Symptoms
- "Failed to load feedback" message
- Cannot submit new feedback
- Works on localhost but not on GoogieHost

#### Possible Causes and Solutions

1. **Database Connection Issues**
   - Check if the database credentials in `db/config.php` match your GoogieHost credentials
   - Verify that the database and tables exist on GoogieHost
   - Check the `feedback_log.txt` file for connection errors

2. **Table Structure Issues**
   - Ensure the `feedback` table exists with the correct structure
   - Required columns: `id`, `name`, `number`, `comment`, `likes`, `timestamp`
   - Run the diagnostic tool: `http://yourdomain.com/db_diagnostics.php`

3. **AJAX Issues**
   - Check browser console for JavaScript errors
   - Verify that the AJAX calls are using the correct paths

## Diagnostic Tools

### Using the Diagnostic Tool

1. Access the diagnostic tool at `http://yourdomain.com/db_diagnostics.php`
2. Check the status of each component:
   - Database Connection
   - Patient Registration System
   - Admin Login System
   - Feedback System
   - Messages System
3. Use the "Test Connection" button to verify your database connection
4. Review any error messages displayed

### Using Log Files

We've added logging to help diagnose issues:

- `register_log.txt` - Contains logs for patient registration attempts
- `feedback_log.txt` - Contains logs for feedback submission attempts

Check these files for error messages and connection issues.

## Database Setup on GoogieHost

1. **Create Database**
   - Log in to your GoogieHost cPanel
   - Navigate to MySQL Databases
   - Create a new database (e.g., `hospit27_hospital_db`)

2. **Create Database User**
   - Create a new database user (e.g., `hospit27_rajskmr`)
   - Set a strong password
   - Assign the user to the database with all privileges

3. **Import Database Schema**
   - Navigate to phpMyAdmin
   - Select your database
   - Click on the Import tab
   - Upload your SQL file and import

4. **Update Configuration Files**
   - Update `db/config.php` with your GoogieHost credentials
   - Update any other files that contain database connection information

## File Permissions

Set the correct file permissions on GoogieHost:

```bash
chmod 644 *.php *.html *.css *.js
chmod 755 */
chmod 777 register_log.txt feedback_log.txt
```

## Contact Support

If you continue to experience issues after trying these solutions, please contact support with the following information:

1. Error messages from log files
2. Screenshots of the diagnostic tool results
3. Steps to reproduce the issue
4. Any changes made to the codebase