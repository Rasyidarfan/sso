# 🔐 SSO BPS - Single Sign-On API

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Sistem Single Sign-On (SSO) untuk aplikasi internal BPS dengan OAuth 2.0 flow dan API data pegawai.


## 🎯 Overview

SSO BPS adalah sistem autentikasi terpusat yang memungkinkan user login sekali untuk mengakses multiple aplikasi internal BPS. Sistem ini menyediakan:

- **OAuth 2.0 Authentication Flow** untuk integrasi aplikasi
- **RESTful API** untuk data pegawai dan role
- **Admin Panel** untuk manajemen user, aplikasi, dan role
- **Public Documentation** yang dapat diakses tanpa login

### Architecture

```
┌─────────────────┐     ┌─────────────────┐      ┌─────────────────┐
│   Client App    │     │   SSO Server    │      │   Database      │
│                 │     │                 │      │                 │
│ 1. Redirect     │───▶ │ 2. Login Page   │      │ - Users         │
│ 4. Get Token    │◀─── │ 3. Auth Code    │      │ - Roles         │
│ 6. API Calls    │───▶ │ 5. User Data    │◀──▶ │ - Client Apps   │
└─────────────────┘     └─────────────────┘      └─────────────────┘
```

## ✨ Fitur Utama

### 🔐 Authentication & Authorization
- OAuth 2.0 authorization code flow
- JWT-like session management
- Role-based access control (RBAC)
- Admin-only user management

### 👥 User Management
- Admin dapat menambah/edit/hapus user
- Toggle status user (aktif/nonaktif)
- Multi-role assignment per user
- Profile management untuk user

### 🛡️ Role Management
- CRUD operations untuk role/tim
- Role descriptions dan metadata
- Role assignment ke user
- Protected admin role

### 🔧 Application Management
- Register client applications
- Generate client ID & secret
- Callback URL management
- Application status control

### 📊 Data API
- **Protected Endpoints**: Employee data (requires client secret)
- **Public Endpoints**: Role information (no auth required)
- JSON responses dengan pagination
- Comprehensive error handling

## 🚀 Instalasi

### Requirements

- PHP 8.1+
- Composer
- MySQL 5.7+ atau PostgreSQL
- Laravel 10.x

### Quick Start

```bash
# Clone repository
git clone https://github.com/Rasyidarfan/sso.git
cd sso-bps

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Start development server
php artisan serve
```

### Production Setup

```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Setup queue worker (optional)
php artisan queue:work
```

## ⚙️ Konfigurasi

### Environment Variables

```env
# App Configuration
APP_NAME="SSO BPS"
APP_ENV=production
APP_URL=https://sso.bps9702.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sso_bps
DB_USERNAME=username
DB_PASSWORD=password

# OAuth Configuration (optional)
SSO_TOKEN_EXPIRY=3600
SSO_CODE_EXPIRY=600
```

### Initial Admin User

Setelah migration dan seeding, login dengan:
- **Email**: `admin@bps.go.id`
- **Password**: `password`

⚠️ **Ganti password default setelah login pertama!**

## 📚 API Documentation

Base URL: `https://sso.bps9702.com/api/v1`

### Authentication

SSO BPS menggunakan dua jenis autentikasi:

1. **OAuth 2.0** - Untuk integrasi aplikasi client
2. **Client Secret** - Untuk protected API endpoints

---

## 🔐 OAuth 2.0 Flow

### Step 1: Authorization Request

Redirect user ke authorization endpoint:

```
GET /api/v1/authorize?client_id={CLIENT_ID}&state={STATE}
```

**Parameters:**
- `client_id` (required): ID aplikasi yang terdaftar
- `state` (optional): Random string untuk keamanan

**Response:** Redirect ke callback URL dengan authorization code

### Step 2: Token Exchange

Exchange authorization code untuk user data:

```bash
curl -X POST "https://sso.bps9702.com/api/v1/token" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "code=AUTH_CODE&client_id=CLIENT_ID&client_secret=CLIENT_SECRET"
```

**Response:**
```json
{
  "status": "success",
  "data": {
    "user_id": "123456789",
    "name": "John Doe",
    "nip_9": "123456789",
    "nip_18": "199001011234567890",
    "email": "john@bps.go.id",
    "roles": ["admin", "user"]
  }
}
```

### Complete Integration Example

```php
<?php
// 1. Redirect to SSO
$authUrl = "https://sso.bps9702.com/api/v1/authorize?" . http_build_query([
    'client_id' => 'your_client_id',
    'state' => bin2hex(random_bytes(16))
]);
header("Location: $authUrl");

// 2. Handle callback
if (isset($_GET['code'])) {
    $response = http_post('https://sso.bps9702.com/api/v1/token', [
        'code' => $_GET['code'],
        'client_id' => 'your_client_id',
        'client_secret' => 'your_client_secret'
    ]);
    
    $userData = json_decode($response, true);
    // Login user to your application
}
?>
```

---

## 📊 Data API Endpoints

### 🔒 Protected Endpoints (Requires Client Secret)

#### Get All Employees

```http
POST /api/v1/data/employees
Content-Type: application/x-www-form-urlencoded

client_secret=your_client_secret
```

**Response:**
```json
{
  "status": "success",
  "message": "Data pegawai berhasil diambil",
  "data": [
    {
      "nip_9": "123456789",
      "nip_18": "199001011234567890",
      "name": "John Doe",
      "email": "john@bps.go.id",
      "roles": ["admin", "user"]
    }
  ],
  "total": 50,
  "requested_by": "Your App Name"
}
```

#### Get Employees by Role

```http
POST /api/v1/data/employees/by-role
Content-Type: application/x-www-form-urlencoded

client_secret=your_client_secret&role=admin
```

**Response:**
```json
{
  "status": "success",
  "message": "Data pegawai dengan role 'admin' berhasil diambil",
  "data": [
    {
      "nip_9": "123456789",
      "nip_18": "199001011234567890",
      "name": "John Doe",
      "email": "john@bps.go.id",
      "roles": ["admin", "user"]
    }
  ],
  "role_info": {
    "name": "admin",
    "description": "Administrator sistem"
  },
  "total": 5,
  "requested_by": "Your App Name"
}
```

### 🌐 Public Endpoints (No Authentication Required)

#### Get All Roles

```http
GET /api/v1/data/roles
```

**Response:**
```json
{
  "status": "success",
  "message": "Data role berhasil diambil",
  "data": [
    {
      "name": "admin",
      "description": "Administrator sistem",
      "user_count": 5
    },
    {
      "name": "user",
      "description": "User biasa",
      "user_count": 45
    }
  ],
  "total": 2
}
```

#### Get Role Names

```http
GET /api/v1/data/role-names
```

**Response:**
```json
{
  "status": "success",
  "message": "Daftar nama role berhasil diambil",
  "data": ["admin", "user", "umum"],
  "total": 3
}
```

---

## 💡 Contoh Penggunaan

### JavaScript/Fetch API

```javascript
class SsoApiClient {
    constructor(baseUrl, clientSecret = '') {
        this.baseUrl = baseUrl + '/api/v1/data';
        this.clientSecret = clientSecret;
    }
    
    // Public endpoint - get all roles
    async getRoles() {
        const response = await fetch(`${this.baseUrl}/roles`);
        return response.json();
    }
    
    // Protected endpoint - get all employees
    async getEmployees() {
        const response = await fetch(`${this.baseUrl}/employees`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `client_secret=${this.clientSecret}`
        });
        return response.json();
    }
    
    // Protected endpoint - get employees by role
    async getEmployeesByRole(role) {
        const response = await fetch(`${this.baseUrl}/employees/by-role`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `client_secret=${this.clientSecret}&role=${role}`
        });
        return response.json();
    }
}

// Usage
const ssoApi = new SsoApiClient('https://sso.bps9702.com', 'your_client_secret');

// Get public data
const roles = await ssoApi.getRoles();
console.log('Available roles:', roles.data);

// Get protected data
const employees = await ssoApi.getEmployees();
console.log('Total employees:', employees.total);

const admins = await ssoApi.getEmployeesByRole('admin');
console.log('Admin users:', admins.data);
```

### PHP/cURL

```php
<?php
class SsoApiClient 
{
    private $baseUrl;
    private $clientSecret;
    
    public function __construct($baseUrl, $clientSecret = '') 
    {
        $this->baseUrl = rtrim($baseUrl, '/') . '/api/v1/data';
        $this->clientSecret = $clientSecret;
    }
    
    public function getRoles() 
    {
        $url = $this->baseUrl . '/roles';
        $response = file_get_contents($url);
        return json_decode($response, true);
    }
    
    public function getEmployees() 
    {
        $url = $this->baseUrl . '/employees';
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => 'client_secret=' . $this->clientSecret,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Accept: application/json']
        ]);
        
        $response = curl_exec($curl);
        curl_close($curl);
        
        return json_decode($response, true);
    }
    
    public function getEmployeesByRole($role) 
    {
        $url = $this->baseUrl . '/employees/by-role';
        $postData = 'client_secret=' . $this->clientSecret . '&role=' . urlencode($role);
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Accept: application/json']
        ]);
        
        $response = curl_exec($curl);
        curl_close($curl);
        
        return json_decode($response, true);
    }
}

// Usage
$ssoApi = new SsoApiClient('https://sso.bps9702.com', 'your_client_secret');

// Get public data
$roles = $ssoApi->getRoles();
echo "Available roles: " . implode(', ', array_column($roles['data'], 'name'));

// Get protected data
$employees = $ssoApi->getEmployees();
echo "Total employees: " . $employees['total'];

$admins = $ssoApi->getEmployeesByRole('admin');
echo "Admin count: " . $admins['total'];
?>
```

### Python/Requests

```python
import requests

class SsoApiClient:
    def __init__(self, base_url, client_secret=''):
        self.base_url = base_url.rstrip('/') + '/api/v1/data'
        self.client_secret = client_secret
    
    def get_roles(self):
        """Get all roles (public endpoint)"""
        response = requests.get(f'{self.base_url}/roles')
        return response.json()
    
    def get_employees(self):
        """Get all employees (protected endpoint)"""
        data = {'client_secret': self.client_secret}
        response = requests.post(f'{self.base_url}/employees', data=data)
        return response.json()
    
    def get_employees_by_role(self, role):
        """Get employees by role (protected endpoint)"""
        data = {
            'client_secret': self.client_secret,
            'role': role
        }
        response = requests.post(f'{self.base_url}/employees/by-role', data=data)
        return response.json()

# Usage
sso_api = SsoApiClient('https://sso.bps9702.com', 'your_client_secret')

# Get public data
roles = sso_api.get_roles()
print(f"Available roles: {[role['name'] for role in roles['data']]}")

# Get protected data
employees = sso_api.get_employees()
print(f"Total employees: {employees['total']}")

admins = sso_api.get_employees_by_role('admin')
print(f"Admin count: {admins['total']}")
```

---

## ❌ Error Handling

### HTTP Status Codes

- `200` - Success
- `400` - Bad Request (missing parameters, validation failed)
- `401` - Unauthorized (invalid client secret)
- `404` - Not Found (role not found)
- `405` - Method Not Allowed (wrong HTTP method)
- `500` - Internal Server Error

### Error Response Format

```json
{
  "status": "error",
  "message": "Human readable error message",
  "error_code": "MACHINE_READABLE_CODE",
  "errors": {
    "field": ["Detailed validation errors"]
  }
}
```

### Common Errors

#### Missing Client Secret (400)
```json
{
  "status": "error",
  "message": "Client secret diperlukan",
  "errors": {
    "client_secret": ["The client secret field is required."]
  },
  "error_code": "MISSING_CLIENT_SECRET"
}
```

#### Invalid Client Secret (401)
```json
{
  "status": "error",
  "message": "Client secret tidak valid atau aplikasi tidak aktif",
  "error_code": "INVALID_CLIENT_SECRET"
}
```

#### Role Not Found (404)
```json
{
  "status": "error",
  "message": "Role tidak ditemukan",
  "error_code": "ROLE_NOT_FOUND"
}
```

### Error Handling Best Practices

```javascript
async function safeApiCall() {
    try {
        const response = await fetch('/api/v1/data/employees', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'client_secret=your_secret'
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            switch (data.error_code) {
                case 'INVALID_CLIENT_SECRET':
                    // Handle invalid credentials
                    console.error('Invalid client secret - check app registration');
                    break;
                case 'MISSING_CLIENT_SECRET':
                    // Handle missing credentials
                    console.error('Client secret required for this endpoint');
                    break;
                default:
                    console.error('API Error:', data.message);
            }
            return null;
        }
        
        return data;
    } catch (error) {
        console.error('Network error:', error);
        return null;
    }
}
```

## 🔧 Development

### Setup Development Environment

```bash
# Clone dan setup
git clone [https://github.com/your-org/sso-bps.git](https://github.com/Rasyidarfan/sso.git)
cd sso

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed

# Asset compilation
npm run dev

# Start servers
php artisan serve
npm run watch
```

### Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run API tests only
php artisan test tests/Feature/Api/

# Test with coverage
php artisan test --coverage
```

### Code Style

```bash
# Format code
./vendor/bin/php-cs-fixer fix

# Static analysis
./vendor/bin/phpstan analyse

# Laravel specific checks
php artisan insights
```

---

### Menu yang Tersedia

#### 👥 Daftar Akun
- ➕ Tambah user baru
- ✏️ Edit data user
- 🔄 Toggle status aktif/nonaktif
- 🗑️ Hapus user permanen
- 🛡️ Assign roles ke user

#### 🔧 API & Aplikasi (Admin Only)
- ➕ Register aplikasi client baru
- 📋 Lihat daftar aplikasi terdaftar
- 🔑 Generate/regenerate client secret
- 📝 Update callback URLs
- ✅ Toggle status aplikasi

#### 🛡️ Tim/Role (Admin Only)
- ➕ Buat role/tim baru
- ✏️ Edit nama dan deskripsi role
- 👥 Lihat user per role
- 🗑️ Hapus role (kecuali admin)

### User Management Workflow

1. **Menambah User Baru**
   ```
   Admin Panel > Daftar Akun > Tambah Akun
   ├─ Isi data: Nama, Email, NIP
   ├─ Set password temporary
   ├─ Pilih role yang sesuai
   └─ User dapat login dan ganti password
   ```

2. **Register Aplikasi Client**
   ```
   Admin Panel > API & Aplikasi > Tambah Aplikasi
   ├─ Nama aplikasi
   ├─ Redirect URI untuk OAuth callback
   ├─ Deskripsi aplikasi
   └─ Dapatkan Client ID & Secret
   ```

---

### Documentation
- **Live API Docs**: https://sso.bps9702.com/docs


## 🤝 Contributing

### Development Guidelines

1. **Fork** repository ini
2. **Create feature branch** (`git checkout -b feature/amazing-feature`)
3. **Commit changes** (`git commit -m 'Add amazing feature'`)
4. **Push to branch** (`git push origin feature/amazing-feature`)
5. **Open Pull Request**

### Code Standards

- Follow **PSR-12** coding standard
- Write **unit tests** for new features
- Update **documentation** for API changes
- Use **conventional commits** format

### Pull Request Checklist

- [ ] Tests pass (`php artisan test`)
- [ ] Code follows PSR-12 standards
- [ ] Documentation updated
- [ ] No breaking changes (or properly documented)
- [ ] Security considerations reviewed

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---


**Made with ❤️ by BPS IT Jayawijaya Team**

Last Updated: May 2025
