@extends('layouts.app')

@section('title', 'Dokumentasi API - SSO BPS')

@section('content')
<div class="card">
    <div class="card-body">
        <h2 class="mb-4">📚 Dokumentasi API SSO BPS</h2>
        
        @php
            use App\Helpers\SsoHelper;
            $currentApiUrl = SsoHelper::getApiBaseUrl();
            $currentDomain = SsoHelper::getApiDomain();
        @endphp

        <h3>🖥️ Environment Configuration</h3>
        <p>SSO BPS menggunakan mekanisme redirect untuk autentikasi. Setelah berhasil login, pengguna akan diarahkan kembali ke aplikasi dengan membawa authorization code.</p>
        <strong>Base URL: </strong> 
        <code>https://sso.bps9702.com/api/v1/</code>

        <div class="row">
            <div class="col-md-12">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-oauth-tab" data-bs-toggle="tab" data-bs-target="#nav-oauth" type="button" role="tab">
                            🔐 OAuth Endpoints
                        </button>
                        <button class="nav-link" id="nav-data-tab" data-bs-toggle="tab" data-bs-target="#nav-data" type="button" role="tab">
                            📊 Data Endpoints
                        </button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    
                    <!-- OAuth Endpoints Tab -->
                    <div class="tab-pane fade show active" id="nav-oauth" role="tabpanel">
                        <div class="mt-4">
                            <h3>🔐 OAuth Endpoints</h3>
                            <p class="text-muted">Endpoint untuk proses autentikasi SSO</p>
                            
                            <div class="mt-4">
                                <h4>1. 🚪 Authorize</h4>
                                <div class="p-3 bg-light rounded border">
                                    <p><span class="badge bg-success">GET</span> <code>{{ $currentDomain }}/api/v1/authorize</code></p>
                                    <p><strong>📥 Parameters:</strong></p>
                                    <ul>
                                        <li><code>client_id</code> (required) - ID aplikasi client</li>
                                        <li><code>state</code> (optional) - State parameter untuk keamanan</li>
                                    </ul>
                                    <p><strong>📤 Response:</strong> Redirect ke callback URL dengan authorization code</p>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <h4>2. 🔑 Token</h4>
                                <div class="p-3 bg-light rounded border">
                                    <p><span class="badge bg-warning">POST</span> <code>{{ $currentDomain }}/api/v1/token</code></p>
                                    <p><strong>📥 Body Parameters:</strong></p>
                                    <ul>
                                        <li><code>code</code> (required) - Authorization code dari step authorize</li>
                                        <li><code>client_id</code> (required) - ID aplikasi client</li>
                                        <li><code>client_secret</code> (required) - Secret aplikasi client</li>
                                    </ul>
                                    <p><strong>📤 Response:</strong></p>
                                    <pre class="bg-dark text-white p-3 rounded small"><code>{
  "status": "success",
  "data": {
    "user_id": "123456789",
    "name": "John Doe",
    "nip_9": "123456789",
    "nip_18": "199001011234567890",
    "email": "john@example.com",
    "roles": ["admin", "user"]
  }
}</code></pre>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <h4>3. ✅ Check</h4>
                                <div class="p-3 bg-light rounded border">
                                    <p><span class="badge bg-warning">POST</span> <code>{{ $currentDomain }}/api/v1/check</code></p>
                                    <p><strong>📥 Body Parameters:</strong></p>
                                    <ul>
                                        <li><code>code</code> (required) - Authorization code untuk dicek</li>
                                    </ul>
                                    <p><strong>📤 Response:</strong> Data user seperti endpoint token</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Endpoints Tab -->
                    <div class="tab-pane fade" id="nav-data" role="tabpanel">
                        <div class="mt-4">
                            <h3>📊 Data Endpoints</h3>
                            <p class="text-muted">Endpoint untuk mengambil data pegawai dan role (hanya user aktif)</p>
                            
                            
                            <div class="alert alert-warning">
                                <strong>⚠️ Penting:</strong> Semua endpoint ini hanya mengembalikan user dengan status <code>is_active = true</code>
                            </div>
                            
                            <!-- 1. Get All Employees -->
                            <div class="mt-4">
                                <h4>1. 👥 Get All Employees</h4>
                                <div class="p-3 bg-light rounded border">
                                    <p><span class="badge bg-warning">POST</span> <code>{{ $currentDomain }}/api/v1/data/employees</code></p>
                                    <p><strong>📖 Deskripsi:</strong> Mengambil data semua pegawai dengan status aktif</p>
                                    <p><strong>🔒 Keamanan:</strong> Memerlukan <code>client_secret</code> dari aplikasi yang terdaftar</p>
                                    <p><strong>📥 Body Parameters:</strong></p>
                                    <ul>
                                        <li><code>client_secret</code> (required) - Client secret dari aplikasi yang terdaftar</li>
                                    </ul>
                                    <p><strong>🧪 Test cURL:</strong></p>
                                    <div class="p-2 bg-secondary rounded">
                                        <code class="text-white">curl -X POST "{{ $currentDomain }}/api/v1/data/employees" \<br>
  -H "Content-Type: application/x-www-form-urlencoded" \<br>
  -H "Accept: application/json" \<br>
  -d "client_secret=your_client_secret"</code>
                                    </div>
                                    <p class="mt-2"><strong>📤 Response:</strong></p>
                                    <pre class="bg-dark text-white p-3 rounded small"><code>{
  "status": "success",
  "message": "Data pegawai berhasil diambil",
  "data": [
    {
      "nip_9": "123456789",
      "nip_18": "199001011234567890", 
      "name": "John Doe",
      "email": "john@example.com",
      "roles": ["admin", "user"]
    }
  ],
  "total": 50,
  "requested_by": "Nama Aplikasi"
}</code></pre>
                                </div>
                            </div>
                            
                            <!-- 2. Get All Roles -->
                            <div class="mt-4">
                                <h4>2. 🛡️ Get All Roles</h4>
                                <div class="p-3 bg-light rounded border">
                                    <p><span class="badge bg-primary">GET</span> <code>{{ $currentDomain }}/api/v1/data/roles</code></p>
                                    <p><strong>📖 Deskripsi:</strong> Mengambil data semua role dengan informasi lengkap dan jumlah user aktif</p>
                                    <p><strong>🧪 Test URL:</strong></p>
                                    <div class="p-2 bg-secondary rounded">
                                        <code class="text-white">curl -X GET "{{ $currentDomain }}/api/v1/data/roles" -H "Accept: application/json"</code>
                                    </div>
                                    <p class="mt-2"><strong>📤 Response:</strong></p>
                                    <pre class="bg-dark text-white p-3 rounded small"><code>{
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
    },
    {
      "name": "umum",
      "description": "User umum",
      "user_count": 20
    }
  ],
  "total": 3
}</code></pre>
                                </div>
                            </div>
                            
                            <!-- 3. Get Role Names -->
                            <div class="mt-4">
                                <h4>3. 📝 Get Role Names</h4>
                                <div class="p-3 bg-light rounded border">
                                    <p><span class="badge bg-primary">GET</span> <code>{{ $currentDomain }}/api/v1/data/role-names</code></p>
                                    <p><strong>📖 Deskripsi:</strong> Mengambil daftar nama role saja (list sederhana untuk dropdown/select)</p>
                                    <p><strong>🧪 Test URL:</strong></p>
                                    <div class="p-2 bg-secondary rounded">
                                        <code class="text-white">curl -X GET "{{ $currentDomain }}/api/v1/data/role-names" -H "Accept: application/json"</code>
                                    </div>
                                    <p class="mt-2"><strong>📤 Response:</strong></p>
                                    <pre class="bg-dark text-white p-3 rounded small"><code>{
  "status": "success",
  "message": "Daftar nama role berhasil diambil",
  "data": ["admin", "user", "umum"],
  "total": 3
}</code></pre>
                                </div>
                            </div>
                            
                            <!-- 4. Get Employees by Role -->
                            <div class="mt-4">
                                <h4>4. 👥🛡️ Get Employees by Role</h4>
                                <div class="p-3 bg-light rounded border">
                                    <p><span class="badge bg-warning">POST</span> <code>{{ $currentDomain }}/api/v1/data/employees/by-role</code></p>
                                    <p><strong>📖 Deskripsi:</strong> Mengambil data pegawai yang memiliki role tertentu</p>
                                    <p><strong>🔒 Keamanan:</strong> Memerlukan <code>client_secret</code> dari aplikasi yang terdaftar</p>
                                    <p><strong>📥 Body Parameters:</strong></p>
                                    <ul>
                                        <li><code>client_secret</code> (required) - Client secret dari aplikasi yang terdaftar</li>
                                        <li><code>role</code> (required) - Nama role yang dicari (case insensitive)</li>
                                    </ul>
                                    <p><strong>🧪 Test cURL:</strong></p>
                                    <div class="p-2 bg-secondary rounded mb-2">
                                        <code class="text-white">curl -X POST "{{ $currentDomain }}/api/v1/data/employees/by-role" \<br>
  -H "Content-Type: application/x-www-form-urlencoded" \<br>
  -H "Accept: application/json" \<br>
  -d "client_secret=your_client_secret&role=admin"</code>
                                    </div>
                                    <div class="p-2 bg-secondary rounded mb-2">
                                        <code class="text-white">curl -X POST "{{ $currentDomain }}/api/v1/data/employees/by-role" \<br>
  -H "Content-Type: application/x-www-form-urlencoded" \<br>
  -H "Accept: application/json" \<br>
  -d "client_secret=your_client_secret&role=user"</code>
                                    </div>
                                    <p class="mt-2"><strong>📤 Success Response:</strong></p>
                                    <pre class="bg-dark text-white p-3 rounded small"><code>{
  "status": "success",
  "message": "Data pegawai dengan role 'admin' berhasil diambil",
  "data": [
    {
      "nip_9": "123456789",
      "nip_18": "199001011234567890",
      "name": "John Doe",
      "email": "john@example.com", 
      "roles": ["admin", "user"]
    },
    {
      "nip_9": "111222333",
      "nip_18": "199203151112223334",
      "name": "Admin User",
      "email": "admin@example.com",
      "roles": ["admin"]
    }
  ],
  "role_info": {
    "name": "admin",
    "description": "Administrator sistem"
  },
  "total": 2,
  "requested_by": "Nama Aplikasi"
}</code></pre>
                                </div>
                            </div>
                            
                            <!-- Error Responses -->
                            <div class="mt-4">
                                <h4>❌ Error Responses</h4>
                                <div class="p-3 bg-light rounded border">
                                    <p><strong>🔒 Missing Client Secret (400):</strong></p>
                                    <pre class="bg-danger text-white p-3 rounded small"><code>{
  "status": "error",
  "message": "Client secret diperlukan",
  "errors": {
    "client_secret": ["The client secret field is required."]
  },
  "error_code": "MISSING_CLIENT_SECRET"
}</code></pre>
                                    
                                    <p class="mt-3"><strong>🚫 Invalid Client Secret (401):</strong></p>
                                    <pre class="bg-danger text-white p-3 rounded small"><code>{
  "status": "error",
  "message": "Client secret tidak valid atau aplikasi tidak aktif",
  "error_code": "INVALID_CLIENT_SECRET"
}</code></pre>
                                    
                                    <p class="mt-3"><strong>🔍 Role Not Found (404):</strong></p>
                                    <pre class="bg-danger text-white p-3 rounded small"><code>{
  "status": "error",
  "message": "Role tidak ditemukan",
  "error_code": "ROLE_NOT_FOUND"
}</code></pre>
                                    
                                    <p class="mt-3"><strong>⚠️ Invalid Request (400):</strong></p>
                                    <pre class="bg-danger text-white p-3 rounded small"><code>{
  "status": "error",
  "message": "Parameter tidak valid",
  "errors": {
    "role": ["The role field is required."]
  },
  "error_code": "INVALID_REQUEST"
}</code></pre>

                                    <p class="mt-3"><strong>💥 Internal Server Error (500):</strong></p>
                                    <pre class="bg-danger text-white p-3 rounded small"><code>{
  "status": "error",
  "message": "Terjadi kesalahan internal server",
  "error_code": "INTERNAL_SERVER_ERROR"
}</code></pre>
                                </div>
                            </div>

                            <!-- Usage Examples -->
                            <div class="mt-4">
                                <h4>💡 Usage Examples</h4>
                                <div class="p-3 bg-info text-white rounded">
                                    <p><strong>📱 JavaScript/Fetch (Protected Endpoints):</strong></p>
                                    <pre class="text-white"><code>// Get all employees (POST with client_secret)
const response = await fetch('/api/v1/data/employees', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded',
    'Accept': 'application/json'
  },
  body: 'client_secret=your_client_secret'
});
const data = await response.json();

// Get employees by role (POST with client_secret)
const admins = await fetch('/api/v1/data/employees/by-role', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded',
    'Accept': 'application/json'
  },
  body: 'client_secret=your_client_secret&role=admin'
});
const adminData = await admins.json();</code></pre>
                                    
                                    <p class="mt-3"><strong>📱 JavaScript/Fetch (Public Endpoints):</strong></p>
                                    <pre class="text-white"><code>// Get all roles (GET, no client_secret needed)
const roles = await fetch('/api/v1/data/roles');
const roleData = await roles.json();

// Get role names (GET, no client_secret needed)
const roleNames = await fetch('/api/v1/data/role-names');
const nameData = await roleNames.json();</code></pre>
                                    
                                    <p class="mt-3"><strong>🐘 PHP/cURL (Protected Endpoints):</strong></p>
                                    <pre class="text-white"><code>// Get all employees
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => '{{ $currentDomain }}/api/v1/data/employees',
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => 'client_secret=your_client_secret',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Accept: application/json']
]);
$response = curl_exec($curl);
$data = json_decode($response, true);</code></pre>
                                    
                                    <p class="mt-3"><strong>🐘 PHP/cURL (Public Endpoints):</strong></p>
                                    <pre class="text-white"><code>// Get all roles (no client_secret needed)
$response = file_get_contents('{{ $currentDomain }}/api/v1/data/roles');
$data = json_decode($response, true);</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        @guest
        <div class="mt-4 p-4 bg-primary text-white rounded">
            <h5><i class="fas fa-info-circle"></i> Tentang SSO BPS</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>🔐 Single Sign-On (SSO)</strong></p>
                    <p>Sistem autentikasi terpusat untuk semua aplikasi internal BPS Jayawijaya. Dengan satu kali login, user dapat mengakses berbagai aplikasi tanpa perlu login berulang kali.</p>
                    
                    <p><strong>👥 Untuk Developer:</strong></p>
                    <ul>
                        <li>Integrasikan aplikasi Anda dengan SSO</li>
                        <li>Gunakan OAuth 2.0 flow untuk autentikasi</li>
                        <li>Akses data pegawai dan role melalui API</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <p><strong>🚀 Cara Memulai:</strong></p>
                    <ol>
                        <li><strong>Hubungi admin</strong> untuk mendapatkan akun</li>
                        <li><strong>Login</strong> dengan akun yang diberikan</li>
                        <li><strong>Daftarkan aplikasi</strong> Anda (client app)</li>
                        <li><strong>Dapatkan Client ID & Secret</strong></li>
                        <li><strong>Implementasikan OAuth flow</strong> di aplikasi</li>
                        <li><strong>Test integrasi</strong> dengan endpoint yang tersedia</li>
                    </ol>
                    
                    <p><strong>📞 Butuh Bantuan?</strong></p>
                    <p>Hubungi tim IT BPS Jayawijaya untuk bantuan teknis dan registrasi akun developer.</p>
                </div>
            </div>
        </div>
        @endguest

        <div class="mt-4 p-3 bg-warning rounded">
            <h5>🚨 Important Notes</h5>
            <ul class="mb-0">
                <li>Semua Data Endpoints mengembalikan <strong>hanya user aktif</strong> (<code>is_active = true</code>)</li>
                <li><strong>Protected Endpoints</strong> (POST): <code>/employees</code> dan <code>/employees/by-role</code> memerlukan <code>client_secret</code></li>
                <li><strong>Public Endpoints</strong> (GET): <code>/roles</code> dan <code>/role-names</code> dapat diakses tanpa <code>client_secret</code></li>
                <li>OAuth endpoints tetap menggunakan path <code>/api/v1/</code></li>
                <li>Data endpoints menggunakan path <code>/api/v1/data/</code></li>
                <li>Semua response menggunakan format JSON yang konsisten</li>
                <li>Parameter <code>role</code> pada endpoint employees/by-role bersifat case-insensitive</li>
                <li><strong>Client Secret harus dari aplikasi yang terdaftar dan aktif</strong></li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Auto-refresh untuk memastikan dokumentasi terbaru
document.addEventListener('DOMContentLoaded', function() {
    // Show current timestamp
    console.log('📖 Dokumentasi API loaded at:', new Date().toISOString());
    
    // Add click handlers for copy buttons
    document.querySelectorAll('code').forEach(function(block) {
        block.style.cursor = 'pointer';
        block.title = 'Click to copy';
        
        block.addEventListener('click', function() {
            navigator.clipboard.writeText(this.textContent);
            
            // Show temporary feedback
            const originalText = this.textContent;
            this.textContent = '✅ Copied!';
            setTimeout(() => {
                this.textContent = originalText;
            }, 1000);
        });
    });
});
</script>
@endsection
