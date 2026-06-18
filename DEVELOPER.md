# API Request Flow & Endpoints

This document outlines how to call all of the endpoints we have implemented during this session, including the required payloads and URLs. 

All endpoints (except login/register) require the `Authorization: Bearer <token>` header.
Base URL: `http://localhost:8000/api/v1`

---

## 1. Authentication & Registration
When a user registers, if the `send_welcome_email` setting is `1` in the database, a welcome email will automatically be queued.

**Register User**
```http
POST /auth/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

---

## 2. Settings (Dynamic Database Config)
Manage global application settings natively. These directly override Laravel configs like SMTP.

**List Settings**
```http
GET /setting
Authorization: Bearer <token>
```

**Bulk Update Settings**
```http
PUT /setting/bulk_action/update
Authorization: Bearer <token>
Content-Type: application/json

{
    "settings": {
        "send_welcome_email": "1",
        "smtp_host": "smtp.mailtrap.io",
        "smtp_port": "2525",
        "smtp_username": "your_username",
        "smtp_password": "your_password",
        "smtp_encryption": "tls"
    }
}
```

---

## 3. Notifications
Manage the authenticated user's database notifications.

**List User's Notifications**
```http
GET /notification?limit=15
Authorization: Bearer <token>
```

**Mark Specific Notification as Read**
```http
PUT /notification/{notification_id}/mark-as-read
Authorization: Bearer <token>
```

**Mark All Notifications as Read**
```http
PUT /notification/mark-all-as-read
Authorization: Bearer <token>
```

**Delete a Notification**
```http
DELETE /notification/{notification_id}
Authorization: Bearer <token>
```

**Internal PHP Usage (How to trigger a notification to a target user)**
To actually create and send a notification to a user from within your Laravel backend code, use the `notify()` method natively provided by Laravel.

1. First, create your notification class: `php artisan make:notification SystemAlert`
2. Then, trigger it on the target user model:
```php
$user = User::find($targetUserId);

$user->notify(new \App\Notifications\SystemAlert([
    'title' => 'New Message',
    'message' => 'You have a new message from Admin.',
    'url' => '/messages/123'
]));
```

---

## 4. Users CRUD
Full management of users, their profile details, and their roles.

**List Users**
```http
GET /user?limit=10&page=1
Authorization: Bearer <token>
```

**Create User**
*(Automatically hashes password and syncs roles & profile details)*
```http
POST /user
Authorization: Bearer <token>
Content-Type: application/json

{
    "name": "Jane Smith",
    "email": "jane@example.com",
    "password": "securepassword",
    "roles": ["admin"],
    "bio": "I am an administrator",
    "phone": "+123456789"
}
```

**Update User**
```http
PUT /user/{user_id}
Authorization: Bearer <token>
Content-Type: application/json

{
    "name": "Jane Updated",
    "roles": ["user"],
    "bio": "Updated bio"
}
```

**Delete User**
```http
DELETE /user/{user_id}
Authorization: Bearer <token>
```

---

## 5. Roles & Permissions CRUD
Role-Based Access Control (RBAC) via Spatie.

**List Roles**
```http
GET /role
Authorization: Bearer <token>
```

**Create Role (and assign permissions)**
```http
POST /role
Authorization: Bearer <token>
Content-Type: application/json

{
    "name": "manager",
    "permissions": ["view users", "update users"]
}
```

**List Permissions**
```http
GET /permission
Authorization: Bearer <token>
```
