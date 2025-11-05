## 🧩 FINAL PROJECT PLAN

**Project Title:** Productivity Hub

**Concept:** A personalized, educational productivity website with task management, user login/register system, and admin control.

**Main Goal:** Help users stay organized while showcasing your skills in full-stack web development (HTML, CSS/Sass, JS, PHP, MySQL).

---

## 🧱 STEP 0: INITIAL SETUP (FOUNDATION)

1. **Create GitHub Repository**
    - Repo name: `productivity-hub`
    - Initialize with a README and `.gitignore` file
        
        (ignore `backend/db_config.php` to protect credentials)
        
    - Suggested structure:
        
        ```
        productivity-hub/
        ├── index.html
        ├── about.html
        ├── contact.html
        ├── login.html
        ├── register.html
        ├── dashboard.html
        │
        ├── assets/
        │   ├── css/
        │   ├── sass/
        │   ├── js/
        │   └── images/
        │
        └── backend/
            ├── db_config.php
            ├── register.php
            ├── login.php
            ├── logout.php
            ├── crud/
            │    ├── add_task.php
            │    ├── update_task.php
            │    ├── delete_task.php
            │    └── fetch_tasks.php
            ├── admin/
            │    ├── manage_users.php
            │    └── manage_tasks.php
            └── database/
                 └── productivity_hub.sql
        
        ```
        
2. **Set Up Local Environment**
    - Use **XAMPP** or **Laragon**
    - Create database `productivity_hub` in phpMyAdmin
3. **Database Design (3 Tables)**
    - **users**
        
        ```sql
        id INT AUTO_INCREMENT PRIMARY KEY
        username VARCHAR(50)
        email VARCHAR(100)
        password VARCHAR(255)
        role ENUM('user','admin') DEFAULT 'user'
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        
        ```
        
    - **tasks**
        
        ```sql
        id INT AUTO_INCREMENT PRIMARY KEY
        user_id INT
        task_name VARCHAR(255)
        status ENUM('Pending','Done') DEFAULT 'Pending'
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        
        ```
        
    - **contacts**
        
        ```sql
        id INT AUTO_INCREMENT PRIMARY KEY
        name VARCHAR(100)
        email VARCHAR(100)
        message TEXT
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        
        ```
        

---

## 🎨 STEP 1: FRONTEND DEVELOPMENT

> Build all the HTML + CSS + JavaScript (frontend) first — no backend logic yet.
> 

### Webpages (10 minimum)

1. **Home** – Overview of site, navigation links, and short motivational section
2. **Login** – User login form
3. **Register** – Registration form
4. **Dashboard** – Displays task list for logged-in users
5. **Add Task** – Form to create a new task
6. **Update Task** – Edit existing task details
7. **Contact** – Contact form (connects later to `contacts` table)
8. **About** – Info about site and creator (professional, not personal)
9. **Admin Panel** – Manage users and tasks
10. **Logout Page / Thank You Page**

### Frontend Tech Stack

- HTML5 for structure
- **Sass** for styling (modular SCSS files compiled to `/assets/css/style.css`)
- JavaScript for DOM manipulation (validation, task interactions, etc.)
- Responsive design (Flexbox or Grid)

### Example Sass Folder

```
assets/sass/
├── main.scss
├── _variables.scss
├── _mixins.scss
├── _navbar.scss
├── _forms.scss
├── _dashboard.scss

```

---

## ⚙️ STEP 2: BACKEND INTEGRATION (PHP + MySQL)

1. **Database Connection**
    - `backend/db_config.php` connects to MySQL.
2. **Registration & Login System**
    - Use `password_hash()` and `password_verify()` for secure login.
    - Optional: limit login attempts (3 tries → lockout message).
3. **CRUD System**
    - User can **Add**, **Edit**, **Delete**, **View** their own tasks.
    - Use AJAX or form submissions for smooth interactivity.
4. **Admin Panel**
    - Admin (`username: admin | password: admin1234`) can:
        - View all users
        - Delete or promote users
        - Manage all tasks
5. **Contact Form**
    - Saves data to `contacts` table using `contact_submit.php`.

---

## 🔐 STEP 3: SECURITY IMPLEMENTATION

- Hash passwords using:
    
    ```php
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    ```
    
- Validate input data (server + client side).
- Use prepared statements to prevent SQL injection:
    
    ```php
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    $stmt->execute();
    
    ```
    
- Add `session_start()` and session checks for protected pages.
- Use `.gitignore` to exclude sensitive config files.

---

## 🧪 STEP 4: TESTING (LOCALHOST)

- Test all pages through XAMPP’s `http://localhost/productivity-hub/`
- Ensure CRUD, login/register, and contact form all work properly.
- Check responsive design and validate all forms.

---

## 🚀 STEP 5: DEPLOYMENT

1. **Export Database**
    - `phpMyAdmin → Export → productivity_hub.sql`
2. **Choose Hosting**
    - Example: 000webhost, InfinityFree, or Hostinger.
3. **Upload Files**
    - Upload your entire project folder.
    - Import your `.sql` file to your web host’s MySQL DB.
4. **Update Config**
    - Edit `db_config.php` with new host credentials.
5. **Verify**
    - Login with `admin | admin1234`
    - Test every feature online.

---

## 💡 Optional Improvement Suggestions

If you still have time or want to improve:

- Add a **search bar** to filter tasks.
- Include **motivational quotes** that change daily.
- Use **localStorage** to cache data for better UX.
- Add a **dark/light mode toggle** (small JS feature).
- Display **activity summary** (total tasks done, pending, etc.)

---

## 🏁 FINAL SUBMISSION CHECKLIST

✅ 10 pages minimum

✅ Clean UI using Sass

✅ Functional login/register system

✅ CRUD for tasks

✅ Contact form saves to DB

✅ Admin with full CRUD control

✅ Password hashing and validation

✅ Database file included (`.sql`)

✅ Secure configuration

✅ Uploaded on domain or full demo video in Drive

```jsx
TASKMASTER/
├── index.html
├── about.html
├── contact.html
├── assets/
│   ├── styles/
				├──styles.css
				├──styles.scss
				├──styles.css.map
│   ├── js/
				├──main.js
│   └── images/
│
├──includes/
		├──contact_process.php
		├──db_connect.php
├──readme.md
```

Since I want this to become CRUD website add the page :

Dashboard – Displays task list for logged-in users

Add Task– Form to create a new task

Update Task– Edit existing task details

Admin Panel – Manage users and tasks

the style of this and the design is base on my theme in my previous pages of course make it responsive and check the code from the sass to see the responsiveness of the pages.

Also I want you to organize my assets/css for easy check the code and seperate the files from styles.scss since it is too long. consider this as your reference below.

assets/sass/

├── main.scss

├── _variables.scss

├── _mixins.scss

├── _navbar.scss

├── _forms.scss

├── _dashboard.scss