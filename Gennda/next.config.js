/** @type {import('next').NextConfig} */
const nextConfig = {
  async rewrites() {
    return [
      {
        // Cuando en el frontend pidas http://127.0.0.1:8000/...
        source: 'http://127.0.0.1:8000/:path*', 
        // Vercel lo enviará a tu API de Render
        destination: 'https://gennda-api.onrender.com/:path*', 
      },
    ];
  },
};

module.exports = nextConfig;