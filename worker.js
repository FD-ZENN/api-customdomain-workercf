export default {
  async fetch(request, env) {
    let url = new URL(request.url);

    // Dapatkan host asli dari header (contoh: abc.vip-id1.zenxray.my.id)
    let originalHost = request.headers.get("host");

    // Cek apakah path diawali dengan '/'
    if (url.pathname.startsWith('/')) {
      // Pisahkan host berdasarkan titik
      let parts = originalHost.split(".");

      // Pastikan jumlah bagian cukup untuk subdomain (contoh: abc.vip-id1.zenxray.my.id)
      if (parts.length >= 5) {
        // Ambil domain target tanpa sub-subdomain (contoh: vip-id1.zenxray.my.id)
        let targetHost = parts.slice(-4).join(".");

        // Set hostname tujuan
        url.hostname = targetHost;

        let new_request = new Request(url, request);
        return fetch(new_request);
      }
    }

    // Kalau tidak memenuhi syarat, arahkan ke  default
    return env.ASSETS.fetch(request);
  },
}
