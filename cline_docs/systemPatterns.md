# System Patterns

## Architecture
1. Role-Based System
   - Three-tier role system (admin, teacher, student)
   - Role-specific dashboards
   - Permission-based access control
   - Secure authentication

2. File Structure
   - Root level PHP files for different modules
   - Role-specific pages (teacher_*, student_*)
   - Shared components (header.php)
   - Common utilities (message.php, sweet-alert.js)
   - Grade management files (grade-*.php)
   - Schedule management files (schedule-*.php)

## Design Patterns
1. Role-Based Access Control (RBAC)
   - Role-specific navigation
   - Permission checks
   - Session management
   - Security validation

2. Component Pattern
   - Reusable header component
   - Shared alert system
   - Common styling patterns
   - Role-based navigation
   - Grade management forms
   - Schedule management forms

3. Database Patterns
   - Direct SQL queries with mysqli
   - Role-based data filtering
   - Security with mysqli_real_escape_string
   - Join-based data retrieval
   - Foreign key constraints
   - Performance indexes
   - InnoDB engine for referential integrity

4. Grade Management Pattern
   - CRUD operations
   - Grade validation
   - Term/year organization
   - History tracking
   - Audit logging
   - Performance optimization

5. Schedule Management Pattern
   - CRUD operations
   - Conflict detection
   - Room allocation
   - Time slot management
   - Schedule validation
   - Performance optimization

6. Authentication Pattern
   - Role-based session management
   - Login/logout functionality
   - Role validation
   - Access control

7. UI Patterns
   - Role-specific dashboards
   - Consistent card layouts
   - Responsive tables
   - Interactive alerts
   - Mobile-first design
   - Grade display patterns
   - Schedule calendar views

## Code Organization
1. Role-Based Modules
   - Admin module (full access)
   - Teacher module (department-specific)
   - Student module (personal access)
   - Authentication module
   - Grade management module
   - Schedule management module

2. Component Organization
   - Header with role-based navigation
   - Alert system with SweetAlert2
   - Message handling
   - Shared styles
   - Grade components
   - Schedule components

3. Security Organization
   - Session-based authentication
   - Role validation
   - Input sanitization
   - Access control checks
   - Grade modification audit
   - Schedule change tracking

4. Database Organization
   - Users table with roles
   - Reference IDs for role linking
   - Department-based filtering
   - Course-based organization
   - Grade history tracking
   - Schedule conflict detection
   - Foreign key relationships
   - Performance indexes

5. UI Components
   - Bootstrap 5.1.3 framework
   - Bootstrap Icons
   - SweetAlert2 alerts
   - Role-specific layouts
   - Responsive design
   - Grade management forms
   - Schedule management forms

6. Feature Organization
   - Admin: Full system management
   - Teacher: Subject and student management
   - Student: Course enrollment and tracking
   - Shared: Authentication and profiles
   - Grade: Complete grade management
   - Schedule: Complete schedule management

7. Navigation Structure
   - Role-based menu items
   - Context-aware active states
   - Hierarchical organization
   - Clear user feedback
   - Grade management section
   - Schedule management section

8. Data Flow
   - Role-based data access
   - Filtered query results
   - Secure data transmission
   - Validated user input
   - Grade calculation flow
   - Schedule validation flow
   - Foreign key integrity
   - Performance optimization