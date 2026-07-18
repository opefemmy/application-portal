# Online Application Portal - Specification Document

## 1. Project Overview

**Project Name:** Online Application Portal
**Project Type:** Web Application (Laravel 12)
**Core Functionality:** A secure, offline-application portal where applicants can apply without registration/login. Only administrators have system access.
**Target Users:** Government institutions, tertiary institutions, NGOs, and companies

## 2. Technology Stack

- **Framework:** Laravel 12
- **PHP Version:** 8.3+
- **Database:** MySQL
- **Frontend:** Bootstrap 5, JavaScript, AJAX, HTML5, CSS3
- **Charts:** Chart.js
- **Icons:** Bootstrap Icons
- **PDF Generation:** DomPDF
- **QR Code:** Simple QR Code package

## 3. Database Schema

### Tables

#### administrators
- id (bigint, PK)
- name (varchar(255))
- email (varchar(255), unique)
- password (varchar(255))
- role_id (bigint, FK)
- avatar (varchar(255), nullable)
- remember_token (varchar(100), nullable)
- created_at (timestamp)
- updated_at (timestamp)

#### roles
- id (bigint, PK)
- name (varchar(100))
- slug (varchar(100), unique)
- description (text, nullable)
- permissions (json)
- created_at (timestamp)
- updated_at (timestamp)

#### applications
- id (bigint, PK)
- application_number (varchar(50), unique)
- personal_info (json)
- academic_info (json)
- employment_info (json)
- application_details (json)
- status (enum: pending, reviewed, shortlisted, interview_scheduled, accepted, rejected, completed)
- notes (text, nullable)
- created_at (timestamp)
- updated_at (timestamp)
- deleted_at (timestamp, soft deletes)

#### application_documents
- id (bigint, PK)
- application_id (bigint, FK)
- document_type (varchar(100))
- file_name (varchar(255))
- file_path (varchar(255))
- file_size (integer)
- mime_type (varchar(100))
- created_at (timestamp)
- updated_at (timestamp)

#### interviews
- id (bigint, PK)
- application_id (bigint, FK)
- venue (varchar(255))
- interview_date (date)
- interview_time (time)
- panel (varchar(255))
- meeting_link (varchar(255), nullable)
- notes (text, nullable)
- created_at (timestamp)
- updated_at (timestamp)

#### notifications
- id (bigint, PK)
- type (varchar(100))
- title (varchar(255))
- message (text)
- is_read (boolean, default false)
- data (json, nullable)
- created_at (timestamp)
- updated_at (timestamp)

#### settings
- id (bigint, PK)
- key (varchar(100), unique)
- value (text)
- created_at (timestamp)
- updated_at (timestamp)

#### form_fields
- id (bigint, PK)
- section (varchar(100))
- field_name (varchar(100))
- field_label (varchar(255))
- field_type (varchar(50))
- options (json, nullable)
- is_visible (boolean, default true)
- is_required (boolean, default false)
- sort_order (integer)
- validation_rules (json, nullable)
- created_at (timestamp)
- updated_at (timestamp)

#### email_templates
- id (bigint, PK)
- name (varchar(255))
- slug (varchar(255), unique)
- subject (varchar(255))
- body (text)
- variables (json)
- created_at (timestamp)
- updated_at (timestamp)

#### activity_logs
- id (bigint, PK)
- user_id (bigint, nullable)
- user_type (varchar(100))
- action (varchar(100))
- description (text)
- old_values (json, nullable)
- new_values (json, nullable)
- ip_address (varchar(45))
- user_agent (text)
- created_at (timestamp)

## 4. UI/UX Specification

### Color Palette
- **Primary:** #1e3a5f (Deep Navy Blue)
- **Secondary:** #3498db (Bright Blue)
- **Accent:** #e74c3c (Coral Red)
- **Success:** #27ae60 (Green)
- **Warning:** #f39c12 (Orange)
- **Danger:** #c0392b (Dark Red)
- **Light:** #ecf0f1 (Light Gray)
- **Dark:** #2c3e50 (Dark Blue Gray)
- **Background:** #f8f9fa (Off White)

### Typography
- **Primary Font:** 'Poppins', sans-serif
- **Secondary Font:** 'Roboto', sans-serif
- **Heading Sizes:** h1: 2.5rem, h2: 2rem, h3: 1.75rem, h4: 1.5rem, h5: 1.25rem, h6: 1rem
- **Body Font Size:** 1rem (16px)
- **Line Height:** 1.6

### Layout Structure

#### Frontend (Public Pages)
- **Header:** Fixed navigation with logo, nav links, apply button
- **Hero Section:** Full-width banner with background image, headline, CTA button
- **Content Sections:** Card-based layouts with shadows
- **Footer:** Multi-column with contact info, quick links, social icons

#### Backend (Admin Dashboard)
- **Sidebar:** Fixed left sidebar (240px width) with navigation
- **Header:** Top bar with search, notifications, user menu
- **Content Area:** Main content with breadcrumbs
- **Responsive:** Collapsible sidebar on mobile

### Components

#### Form Elements
- Input fields with floating labels
- Custom select dropdowns
- Date pickers
- File upload with drag & drop
- Custom checkboxes and radio buttons
- Progress indicator

#### Tables
- Striped rows
- Hover effects
- Sortable columns
- Pagination
- Bulk action checkboxes

#### Cards
- Shadow effects (box-shadow: 0 4px 6px rgba(0,0,0,0.1))
- Rounded corners (border-radius: 8px)
- Hover animations

#### Modals
- Centered positioning
- Backdrop blur
- Smooth fade animations

## 5. Functionality Specification

### Frontend Features

#### Home Page
- Hero banner with animated background
- Quick stats display
- Application guidelines
- Requirements checklist
- Timeline
- FAQ accordion
- Contact form
- Social links

#### Application Form
- Multi-section form with progress indicator
- Personal Information section
- Academic Information section
- Employment Information section (optional)
- Application Details section
- Document Upload section
- Declaration section
- Auto-save to localStorage
- Real-time validation
- Conditional fields

#### Confirmation Page
- Success message with confetti animation
- Application details summary
- QR Code display
- Barcode display
- Print button
- Download PDF button

### Backend Features

#### Authentication
- Login with email/password
- Remember me functionality
- Forgot password with email reset
- Password reset link expiration
- Rate limiting on login attempts
- Session management

#### Dashboard
- Total applications count
- Applications today/week/month
- Status breakdown (shortlisted, rejected, pending)
- Gender distribution chart
- Applications by state chart
- Applications by qualification chart
- Monthly trend line chart
- Recent applications list
- Quick action buttons

#### Application Management
- List view with filters
- Search by application number, name, email, phone
- Filter by status, state, gender, qualification, date
- Sort by any column
- Bulk actions (shortlist, reject, delete, archive)
- Individual application view
- Document preview and download
- Application status update
- Notes and comments

#### Interview Management
- Create interview schedule
- Edit interview details
- Send interview notification emails
- View interview list

#### Settings
- Portal configuration (name, logo, dates)
- SMTP email settings
- File upload configuration
- Email template management
- Maintenance mode toggle

#### Form Builder
- Drag-and-drop field creation
- Field types: text, select, checkbox, radio, date, file, textarea
- Field visibility control
- Required/optional toggle
- Validation rules
- Conditional logic

#### Reports
- Generate reports by multiple criteria
- Filter by date range
- Export to Excel/CSV/PDF
- Charts and visualizations

#### Notifications
- New application alerts
- Status change notifications
- Dashboard notification center
- Email notifications

### Security Features
- CSRF protection on all forms
- XSS sanitization
- SQL injection prevention via Eloquent
- Rate limiting
- Secure file storage
- Password hashing with bcrypt
- Session encryption
- Audit logging

## 6. Acceptance Criteria

### Functional Requirements
- [ ] Applicants can submit applications without registration
- [ ] Application number auto-generates correctly
- [ ] Confirmation email sends immediately after submission
- [ ] Admin can log in and access dashboard
- [ ] Dashboard shows accurate statistics
- [ ] Application list supports search, filter, sort
- [ ] Bulk actions work correctly
- [ ] Documents can be uploaded and downloaded
- [ ] Interview scheduling sends notifications
- [ ] Settings can be configured via admin panel
- [ ] Form fields can be customized
- [ ] Reports generate correctly

### Non-Functional Requirements
- [ ] Page load time < 3 seconds
- [ ] Mobile responsive design
- [ ] Cross-browser compatibility
- [ ] Accessible (WCAG 2.1 AA)
- [ ] Secure against common vulnerabilities
- [ ] Error handling with user-friendly messages

### Visual Checkpoints
- [ ] Home page hero section displays correctly
- [ ] Application form renders all sections
- [ ] Admin dashboard displays charts
- [ ] Tables are properly styled
- [ ] Modals open/close smoothly
- [ ] Dark/light mode toggle works
- [ ] Print layout is clean

## 7. File Structure

```
application-portal/
├── app/
│   ├── Console/
│   ├── Events/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   ├── Requests/
│   │   └── Resources/
│   ├── Models/
│   ├── Policies/
│   ├── Providers/
│   └── Services/
├── config/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
├── storage/
├── tests/
└── vendor/
```

## 8. Routes

### Public Routes
- `/` - Home
- `/about` - About
- `/requirements` - Requirements
- `/how-to-apply` - How to Apply
- `/faq` - FAQ
- `/contact` - Contact
- `/apply` - Application Form
- `/apply/submit` - Submit Application
- `/application/acknowledge/{id}` - Acknowledgement Slip
- `/application/track` - Track Application

### Admin Routes
- `/admin/login` - Login
- `/admin/forgot-password` - Forgot Password
- `/admin/reset-password` - Reset Password
- `/admin/dashboard` - Dashboard
- `/admin/applications` - Applications List
- `/admin/applications/{id}` - Application Detail
- `/admin/interviews` - Interviews
- `/admin/notifications` - Notifications
- `/admin/settings` - Settings
- `/admin/form-builder` - Form Builder
- `/admin/reports` - Reports
- `/admin/users` - Admin Users
- `/admin/roles` - Roles & Permissions