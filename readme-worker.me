# Cloudflare Worker Script - Subdomain Redirector

Script Cloudflare Worker untuk mengarahkan request dari subdomain wildcard ke target domain dengan struktur yang sudah ditentukan.

## 🎯 Fungsi Utama

Worker ini berfungsi untuk:
- Menangkap request dari subdomain wildcard (contoh: `abc.vip-id1.zenxray.my.id`)
- Mengarahkan request ke target domain yang sesuai (contoh: `vip-id1.zenxray.my.id`)
- Mempertahankan path dan query parameters asli

## 📝 Cara Kerja

### Input dan Output

**Input**: `https://abc.vip-id1.zenxray.my.id/path?query=123`

**Output**: Request diarahkan ke `https://vip-id1.zenxray.my.id/path?query=123`

### Alur Kerja

1. **Ambil Host Asli**: Worker membaca header `host` dari request
2. **Parse Domain**: Memisahkan domain berdasarkan titik (`.`)
3. **Validasi**: Memastikan domain memiliki minimal 5 bagian
4. **Extract Target**: Mengambil 4 bagian terakhir sebagai target domain
5. **Redirect**: Membuat request baru ke target domain
6. **Fallback**: Jika tidak sesuai kriteria, gunakan default assets

## 🔧 Setup Worker

### 1. Buat Worker Baru

1. Login ke [Cloudflare Dashboard](https://dash.cloudflare.com)
2. Pilih **Workers & Pages**
3. Klik **Create Application**
4. Pilih **Create Worker**
5. Beri nama worker (contoh: `subdomain-redirector`)

### 2. Deploy Script

1. Copy script di bawah ini ke editor Cloudflare Worker:

```javascript
export default {
  async fetch(request, env) {
    let url = new URL(request.url);
    
    // Dapatkan host asli dari header (contoh: abc.vip-id1.zenxray.my.id)
    let originalHost = request.headers.get("host");
    
    // Cek apakah path diawali dengan '/'
    if (url.pathname.startsWith('/')) {
      // Pisahkan host berdasarkan titik
      let parts = originalHost.split(".");
      
      // Pastikan jumlah bagian cukup untuk subdomain (minimal 5 bagian)
      if (parts.length >= 5) {
        // Ambil domain target tanpa sub-subdomain (4 bagian terakhir)
        let targetHost = parts.slice(-4).join(".");
        
        // Set hostname tujuan
        url.hostname = targetHost;
        
        // Buat request baru dengan URL yang sudah dimodifikasi
        let new_request = new Request(url, request);
        return fetch(new_request);
      }
    }
    
    // Kalau tidak memenuhi syarat, arahkan ke default
    return env.ASSETS.fetch(request);
  },
}
```

2. Klik **Save and Deploy**

### 3. Setup Custom Domain

1. Di halaman Worker, pilih **Triggers**
2. Klik **Add Custom Domain**
3. Masukkan domain pattern (contoh: `*.vip-id1.zenxray.my.id`)
4. Atau gunakan aplikasi Domain Manager yang sudah dibuat

## 📊 Contoh Penggunaan

### Struktur Domain

```
Format: [wildccard].[subdomain].[domain].[tld]
Contoh: abc.vip-id1.zenxray.my.id

Breakdown:
- abc: wildcard (bagian yang bisa berubah)
- vip-id1: subdomain tetap
- zenxray.my.id: domain utama
```

### Skenario Penggunaan

| Input Domain | Target Domain | Status |
|--------------|---------------|---------|
| `zoom.vip-id1.zenxray.my.id` | `vip-id1.zenxray.my.id` | ✅ Redirect |
| `teams.vip-sg1.zenxray.my.id` | `vip-sg1.zenxray.my.id` | ✅ Redirect |
| `app.vip-id2.zenxray.my.id/api` | `vip-id2.zenxray.my.id/api` | ✅ Redirect |
| `vip-id1.zenxray.my.id` | - | ❌ Default Assets |
| `zenxray.my.id` | - | ❌ Default Assets |

### URL Examples

```bash
# Request ke wildcard subdomain
curl https://zoom.vip-id1.zenxray.my.id/api/health

# Akan diarahkan ke:
# https://vip-id1.zenxray.my.id/api/health
```

## 🔧 Kustomisasi

### 1. Mengubah Jumlah Bagian Domain

Jika struktur domain Anda berbeda, ubah logika parsing:

```javascript
// Untuk domain dengan 3 bagian: app.example.com -> example.com
if (parts.length >= 3) {
  let targetHost = parts.slice(-2).join(".");
  // ...
}

// Untuk domain dengan 6 bagian: sub.app.vip.example.co.id -> vip.example.co.id  
if (parts.length >= 6) {
  let targetHost = parts.slice(-3).join(".");
  // ...
}
```

### 2. Menambah Validasi Custom

```javascript
export default {
  async fetch(request, env) {
    let url = new URL(request.url);
    let originalHost = request.headers.get("host");
    
    // Tambahkan validasi custom
    const allowedSubdomains = ['vip-id1', 'vip-id2', 'vip-id3', 'vip-sg1'];
    
    if (url.pathname.startsWith('/')) {
      let parts = originalHost.split(".");
      
      if (parts.length >= 5) {
        // Validasi apakah subdomain diizinkan
        let subdomain = parts[1]; // vip-id1, vip-id2, etc.
        
        if (allowedSubdomains.includes(subdomain)) {
          let targetHost = parts.slice(-4).join(".");
          url.hostname = targetHost;
          
          let new_request = new Request(url, request);
          return fetch(new_request);
        }
      }
    }
    
    return env.ASSETS.fetch(request);
  },
}
```

### 3. Menambah Logging

```javascript
export default {
  async fetch(request, env) {
    let url = new URL(request.url);
    let originalHost = request.headers.get("host");
    
    // Log untuk debugging
    console.log(`Original Host: ${originalHost}`);
    console.log(`Path: ${url.pathname}`);
    
    if (url.pathname.startsWith('/')) {
      let parts = originalHost.split(".");
      console.log(`Domain Parts: ${JSON.stringify(parts)}`);
      
      if (parts.length >= 5) {
        let targetHost = parts.slice(-4).join(".");
        console.log(`Target Host: ${targetHost}`);
        
        url.hostname = targetHost;
        let new_request = new Request(url, request);
        return fetch(new_request);
      }
    }
    
    return env.ASSETS.fetch(request);
  },
}
```

## 🧪 Testing

### 1. Test di Worker Editor

1. Buka Cloudflare Worker dashboard
2. Pilih worker Anda
3. Klik tab **Test**
4. Masukkan test request:

```
URL: https://abc.vip-id1.zenxray.my.id/test
Method: GET
Headers: 
  host: abc.vip-id1.zenxray.my.id
```

### 2. Test via cURL

```bash
# Test basic redirect
curl -H "host: zoom.vip-id1.zenxray.my.id" https://your-worker.your-subdomain.workers.dev/

# Test dengan path
curl -H "host: teams.vip-sg1.zenxray.my.id" https://your-worker.your-subdomain.workers.dev/api/health

# Test dengan query parameters
curl -H "host: app.vip-id2.zenxray.my.id" https://your-worker.your-subdomain.workers.dev/search?q=test
```

### 3. Test Browser

Setelah setup custom domain:
1. Buka browser
2. Akses `https://test.vip-id1.zenxray.my.id`
3. Periksa apakah request diarahkan dengan benar

## 📋 Best Practices

### 1. Error Handling

```javascript
export default {
  async fetch(request, env) {
    try {
      let url = new URL(request.url);
      let originalHost = request.headers.get("host");
      
      if (!originalHost) {
        return new Response("Missing host header", { status: 400 });
      }
      
      // ... rest of logic
      
    } catch (error) {
      console.error("Worker error:", error);
      return new Response("Internal Server Error", { status: 500 });
    }
  },
}
```

### 2. Rate Limiting

```javascript
export default {
  async fetch(request, env) {
    // Basic rate limiting (contoh sederhana)
    const clientIP = request.headers.get("CF-Connecting-IP");
    
    // Implementasi rate limiting sesuai kebutuhan
    // ...
    
    // Rest of worker logic
  },
}
```

### 3. Caching

```javascript
export default {
  async fetch(request, env) {
    // ... logic redirect
    
    let response = await fetch(new_request);
    
    // Set cache headers jika diperlukan
    const newResponse = new Response(response.body, response);
    newResponse.headers.set("Cache-Control", "public, max-age=300");
    
    return newResponse;
  },
}
```

## 🐛 Troubleshooting

### Error: "env.ASSETS is not defined"

**Solusi**: Worker tidak terhubung dengan assets. Pastikan:
1. Worker dikonfigurasi dengan benar
2. Atau ganti dengan response default:

```javascript
// Ganti ini:
return env.ASSETS.fetch(request);

// Dengan ini:  
return new Response("Default response", { status: 200 });
```

### Error: "TypeError: Cannot read property 'split' of null"

**Solusi**: Header host tidak ada. Tambahkan validasi:

```javascript
let originalHost = request.headers.get("host");
if (!originalHost) {
  return new Response("Missing host header", { status: 400 });
}
```

### Redirect Loop

**Penyebab**: Target domain mengarah kembali ke worker yang sama.

**Solusi**: Pastikan target domain memiliki konfigurasi yang benar dan tidak mengarah kembali ke worker.

## 📊 Monitoring

### 1. Analytics

Monitor performa worker di:
1. Cloudflare Dashboard → Workers & Pages → [Your Worker] → Analytics
2. Lihat metrics: Requests, Errors, CPU Time, etc.

### 2. Real User Monitoring

```javascript
export default {
  async fetch(request, env) {
    const startTime = Date.now();
    
    // ... worker logic
    
    const endTime = Date.now();
    const duration = endTime - startTime;
    
    // Log performance metrics
    console.log(`Request processed in ${duration}ms`);
    
    return response;
  },
}
```

## 🔒 Security Considerations

1. **Validate Input**: Selalu validate domain dan path input
2. **Rate Limiting**: Implementasi rate limiting untuk mencegah abuse
3. **Access Control**: Batasi akses jika diperlukan
4. **Logging**: Log suspicious activities

## 📝 Changelog

### Version 1.0.0
- Initial worker script
- Basic subdomain to domain redirection
- Support untuk path dan query parameters
- Error handling dasar

## 🤝 Integration

Worker ini dirancang untuk bekerja dengan:
- **Domain Manager App**: Untuk mengelola domain mapping
- **Cloudflare DNS**: Untuk routing domain
- **External Services**: Sebagai proxy/gateway

## 💡 Use Cases

1. **API Gateway**: Routing API calls berdasarkan subdomain
2. **Multi-tenant Apps**: Routing berdasarkan tenant subdomain
3. **Load Balancing**: Distribusi traffic ke server berbeda
4. **A/B Testing**: Routing berdasarkan variant subdomain
