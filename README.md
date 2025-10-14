# 🗂️ Dantedocs Office Document Management System (EDMS)

## 1. Introduction

In today’s digital era, organizations are shifting from traditional paper-based documentation to electronic document management systems (EDMS). This transition helps reduce paperwork, enhance efficiency, ensure data security, and improve accessibility.

The **Dantedocs Office Document Management System** is designed to help offices — such as education departments, hospitals, or government agencies — store, organize, manage, and retrieve documents electronically in a secure and user-friendly environment.

---

## 2. Project Objectives

- Eliminate paper-based records by providing a secure digital storage system.  
- Simplify document access, sharing, and version control.  
- Enhance document security through role-based access control.  
- Ensure easy tracking of document activities using system logs.  
- Improve efficiency and reduce operational costs related to paperwork.  

---

## 3. System Scope

The system will:

- Allow users to upload, download, edit, or delete documents.  
- Organize documents by category, department, or type.  
- Support user management and roles (Admin, Editor, Viewer).  
- Record all document actions in logs for auditing.  
- Provide dashboard reports and analytics.  
- Support PDF report export and notifications (optional).  

---

## 4. Development Tools and Technologies

| Component | Technology |
|------------|-------------|
| **Programming Language** | Core PHP (Procedural) |
| **Frontend** | HTML5, CSS3, Bootstrap 5, JavaScript, AJAX |
| **Database** | MySQL |
| **File Storage** | Local folder `/uploads/` organized by category |
| **Server Environment** | Apache (XAMPP, WAMP, or LAMP) |
| **Libraries/Plugins** | DataTables, SweetAlert2, Chart.js, TCPDF |
| **Optional APIs** | Twilio (SMS), Gmail SMTP (Email Notifications) |

---

## 5. System Modules

### 5.1 Authentication Module
- Login & Logout  
- Password Reset  
- Session timeout and CSRF protection  

### 5.2 User Management Module
- Create, edit, delete users  
- Assign roles and permissions (Admin, Editor, Viewer)  

### 5.3 Document Management Module
- Upload, download, rename, or delete files  
- Organize files in category folders (e.g., Circulars, Reports)  
- File preview (PDF, image)  
- Maintain document versions  
- Validate uploads (type, size, and naming)  

### 5.4 Document Logs Module
- Record all document-related activities  
- Display user actions (upload, edit, delete, view)  
- Export logs to CSV or PDF  

### 5.5 Category Management Module
- Add or edit document categories  
- Group documents by type or department  

### 5.6 Dashboard Module
- Display statistics (total documents, users, categories)  
- Graphs showing uploads per month or department  
- Recent document activities  

### 5.7 Notification Module (Optional)
- Send email or SMS alerts when a new document is uploaded  
- Notify admins of pending approvals  

---

## 6. Database Design

### 6.1 Tables

#### 🧑‍💼 `users`
| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| name | VARCHAR(100) | User full name |
| email | VARCHAR(100) | Unique email |
| password | VARCHAR(255) | Hashed password |
| role | ENUM('Admin', 'Editor', 'Viewer') | User role |
| status | TINYINT | Active or inactive |

#### 📄 `documents`
| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| title | VARCHAR(255) | Document title |
| filename | VARCHAR(255) | Saved file name |
| category | VARCHAR(100) | Folder/category name |
| uploaded_by | INT | Linked to `users.id` |
| upload_date | DATETIME | Upload timestamp |
| version | INT | File version number |
| status | TINYINT | Active/Archived |

#### 🧾 `document_logs`
| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| document_id | INT | Document reference |
| action | VARCHAR(50) | Upload/Edit/Delete/View |
| user_id | INT | Linked to `users.id` |
| timestamp | DATETIME | Time of action |

#### 🗃️ `categories`
| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| name | VARCHAR(100) | Category name |
| description | TEXT | Details about the category |

---

## 7. Folder Structure

Dantedocs/
│
├── assets/
│ ├── css/
│ ├── js/
│ └── images/
│
├── uploads/
│ ├── Reports/
│ ├── Circulars/
│ └── Letters/
│
├── php/
│ ├── db_connect.php
│ ├── upload_document.php
│ ├── delete_document.php
│ ├── manage_users.php
│ ├── update_category.php
│ ├── get_document_logs.php
│ ├── log_action.php
│ └── auth.php
│
├── includes/
│ ├── header.php
│ └── sidebar.php
│
├── pages/
│ ├── dashboard.php
│ ├── upload_document.php
│ ├── manage_documents.php
│ ├── document_logs.php
│ ├── categories.php
│ └── users.php
│
└── index.php

---

## 8. Security Implementation

- Use **prepared statements** with PDO or MySQLi.  
- Validate file uploads (`mime_content_type`, size limits).  
- Rename uploaded files to avoid conflicts.  
- Protect all pages with `session_check.php`.  
- Use **CSRF tokens** in all forms.  
- Store sensitive configurations (DB credentials) outside the web root.  

---

## 9. Testing and Quality Assurance

### 9.1 Testing Phases
- **Unit Testing:** Test individual scripts (upload, login, delete).  
- **Integration Testing:** Ensure all modules interact correctly.  
- **Security Testing:** Validate user inputs and file uploads.  
- **User Acceptance Testing (UAT):** Let staff test in real scenarios.  

### 9.2 Performance Testing
- Test with large file uploads.  
- Check performance under multiple concurrent users.  

---

## 10. Expected Outcomes

✅ Reduced paper usage and faster document retrieval  
✅ Secure, centralized document storage  
✅ Enhanced collaboration among staff  
✅ Easy tracking of office communications and approvals  
✅ Reduced document loss or misplacement  

---

## 11. Future Enhancements

- Integration with **M-PESA** or **NHIF APIs** (for medical offices).  
- **Digital signatures** for document approvals.  
- **AI-based search** (using OCR and keywords).  
- **Mobile app version (PWA)**.  
- **Cloud backup and synchronization**.  

---

## 12. Conclusion

The **Go Paperless Office Document Management System** will transform how offices handle files by offering a secure, efficient, and modern platform for document storage and retrieval. This system will save time, space, and resources — aligning with **Kenya’s digital transformation goals** and sustainable development vision.

---

### 👨‍💻 Developed by:
**Dantechdevs**  
📧 damnngwasi@gmail.com  
🌐 [https://github.com/Dantechdevs](https://github.com/Dantechdevs)
