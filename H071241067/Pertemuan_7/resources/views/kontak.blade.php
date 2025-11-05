@extends('layouts.master')

@section('content')
<style>
    #map {
        width: 100%;
        height: 450px;
        margin: 30px auto;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .contact-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }
    
    .form-container {
        max-width: 600px;
        margin: 0 auto;
    }
</style>

<div class="contact-wrapper">
    <h2 style="color: #8B4513; text-align: center; margin-bottom: 40px; font-size: 2.5em;">Hubungi Kami</h2>

    <!-- LEAFLET MAP menambahkan map ke halaman web--> 

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Koordinat Tana Toraja (sesuaikan dengan lokasi)
        var map = L.map('map').setView([-2.9995, 119.8371], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        var marker = L.marker([-2.9995, 119.8371]).addTo(map);
        marker.bindPopup('<b>Eksplor Pariwisata Toraja</b><br>Tana Toraja, Sulawesi Selatan').openPopup();
    </script>

    <div class="form-container">
        <!-- Informasi Kontak -->
        <div style="background: #f9f9f9; padding: 25px; border-radius: 10px; margin: 30px 0; box-shadow: 0 2px 5px rgba(0,0,0,0.08);">
            <h3 style="color: #8B4513; margin-bottom: 20px; font-size: 1.5em;">Informasi Kontak</h3>
            <p style="margin-bottom: 12px; line-height: 1.6;"><strong>📍 Alamat:</strong> Jl. Pongtiku, Makale, Tana Toraja, Sulawesi Selatan</p>
            <p style="margin-bottom: 12px; line-height: 1.6;"><strong>📞 Telepon:</strong> (0423) 12345</p>
            <p style="margin-bottom: 12px; line-height: 1.6;"><strong>✉️ Email:</strong> info@wisatatoraja.com</p>
            <p style="margin-bottom: 0; line-height: 1.6;"><strong>🕐 Jam Operasional:</strong> Senin - Minggu (08.00 - 17.00 WITA)</p>
        </div>

        <!-- Form Kirim Pesan -->
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="color: #8B4513; margin-bottom: 25px; font-size: 1.5em;">Kirim Pesan</h3>
            <form action="#" method="POST">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 600;">Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Masukkan nama Anda" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; transition: border-color 0.3s;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 600;">Email</label>
                    <input type="email" name="email" placeholder="Masukkan email Anda" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; transition: border-color 0.3s;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 600;">Nomor Telepon</label>
                    <input type="tel" name="telepon" placeholder="Masukkan nomor telepon" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; transition: border-color 0.3s;">
                </div>
                
                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 600;">Pesan</label>
                    <textarea name="pesan" placeholder="Tulis pesan Anda di sini" rows="6" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; resize: vertical; transition: border-color 0.3s;"></textarea>
                </div>
                
                <button type="submit" style="background-color: #8B4513; color: white; padding: 14px 30px; border: none; border-radius: 5px; font-size: 16px; font-weight: 600; cursor: pointer; width: 100%; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#6d3410'" onmouseout="this.style.backgroundColor='#8B4513'">
                    Kirim Pesan
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    /* Hover effect untuk input */
    input:focus, textarea:focus {
        outline: none;
        border-color: #8B4513 !important;
    }
</style>
@endsection