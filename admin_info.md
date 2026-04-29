# Praveen Portfolio — Admin Access Credentials

> [!IMPORTANT]
> This document contains sensitive access information. Please store it securely and delete this file after noted.

## Admin Panel Details
- **Dashboard URL**: `/admin` or `/admin.php`
- **Login URL**: `/login` or `/login.php`

## Authentication Data
| User Role | Username | Password | Access Level |
|-----------|----------|----------|--------------|
| Super Admin | `admin` | `Praveen@2026` | Full Access |

## Technical Implementation
- **Auth Engine**: Session-based (PHP)
- **Credential Storage**: Defined in `auth.php`
- **Security**: Password protected with `require_login()` gate on all admin routes.

## Menu & Submenu Structure
1. **Home** (`/index`)
2. **About** (`/about`)
3. **Services** (`/services`)
4. **Skills** (`/skills`)
5. **Portfolio** (`/portfolio`)
6. **Blog** (`/blog`)
7. **Contact** (`/contact`)
8. **Legal**
   - Terms & Conditions (`/terms`)
   - Privacy Policy (`/privacy`)
9. **Admin**
   - Login (`/login`)
   - Dashboard (`/admin`)
   - Logout (`/logout`)
