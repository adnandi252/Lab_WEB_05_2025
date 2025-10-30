@extends('layouts.master')

@section('title', 'Home')

@section('content')
<style>
    .hero-section {
        position: relative;
        height: 100vh;
        overflow: hidden;
    }
    
    .hero-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
    }
    
    .hero-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .hero-content {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
    }
    
    .feature-card {
        background: #374151;
        border-radius: 16px;
        padding: 0;
        text-align: center;
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transition: all 0.4s ease;
        height: 100%;
        overflow: hidden;
    }
    
    .feature-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .feature-image {
        width: 100%;
        height: 280px;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    
    .feature-card:hover .feature-image {
        transform: scale(1.05);
    }
    
    .feature-content {
        padding: 2.5rem 2rem;
    }
    
    .feature-title {
        font-size: 1.75rem;
        font-weight: bold;
        margin-bottom: 1.5rem;
        color: #ffffff;
        line-height: 1.3;
    }
    
    .feature-description {
        font-size: 1.125rem;
        line-height: 1.7;
        color: #ffffff;
    }
    
    .toraja-description {
        background: #374151;
        border-radius: 16px;
        padding: 4rem 3rem;
        text-align: center;
        border: 2px solid #4b5563;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .fact-card {
        background: white;
        padding: 2.5rem 2rem;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid #f3f4f6;
    }
    
    .fact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .tag {
        background: #4b5563;
        color: #e5e7eb;
        padding: 1rem 2rem;
        border-radius: 9999px;
        font-size: 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .tag:hover {
        background: #6b7280;
        transform: scale(1.08);
        border-color: #9ca3af;
    }
    
    .fact-number {
        font-size: 3rem;
        font-weight: bold;
        color: #d97706;
        margin-bottom: 1rem;
    }
    
    .section-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }
    
    .section-title {
        margin-bottom: 4rem;
    }
    
    .grid-spacing {
        gap: 3rem;
    }
    
    .card-spacing {
        margin-bottom: 2rem;
    }
    
    .image-grid {
        gap: 2rem;
        margin: 3rem 0;
    }
    
    .content-spacing {
        margin: 2rem 0;
    }
    
    .button-spacing {
        margin: 3rem 0 2rem 0;
    }
    
    .section-padding {
        padding: 6rem 0;
    }

    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 2.5rem;
        }
        
        .hero-content p {
            font-size: 1.125rem;
        }
        
        .feature-card {
            border-radius: 12px;
        }
        
        .section-container {
            padding: 0 1.5rem;
        }
        
        .grid-spacing {
            gap: 2rem;
        }
        
        .feature-image {
            height: 220px;
        }
        
        .feature-content {
            padding: 2rem 1.5rem;
        }
        
        .feature-title {
            font-size: 1.5rem;
        }
        
        .feature-description {
            font-size: 1rem;
        }
        
        .toraja-description {
            padding: 3rem 2rem;
            border-radius: 12px;
        }
        
        .section-padding {
            padding: 4rem 0;
        }

    }
</style>

<div class="hero-section bg-gray-800">
    <div class="hero-overlay"></div>
    <img src="/images/toraja.jpg" alt="Rumah Adat Toraja" class="hero-image">
    <div class="hero-content">
        <div class="section-container">
            <h1 class="text-4xl md:text-6xl font-bold mb-8">Selamat Datang di Tana Toraja</h1>
            <p class="text-lg md:text-2xl max-w-3xl mx-auto mb-10 leading-relaxed">
                Jelajahi Keunikan Budaya, Arsitektur Tradisional, dan Keindahan Alam Toraja yang Memukau
            </p>
        </div>
    </div>
</div>

<div class = "bg-gray-800">
    <div class="section-container section-padding">
        <div class="grid grid-cols-1 md:grid-cols-3 grid-spacing">
            <!-- Rumah Adat Tongkonan -->
            <div class="feature-card">
                <img src="/images/rumahToraja.jpeg" alt="Rumah Adat Tongkonan" class="feature-image">
                <div class="feature-content">
                    <h3 class="feature-title">Rumah Adat Tongkonan</h3>
                    <p class="feature-description">
                        Keunikan arsitektur tradisional dengan atap melengkung yang menjadi ikon budaya Toraja dan simbol status sosial masyarakat. Setiap detail ukiran memiliki makna filosofis yang dalam.
                    </p>
                </div>
            </div>
            
            <!-- Pemandangan Alam -->
            <div class="feature-card">
                <img src="/images/ollon.jpg" alt="Pemandangan Alam Toraja" class="feature-image">
                <div class="feature-content">
                    <h3 class="feature-title">Pemandangan Alam</h3>
                    <p class="feature-description">
                        Hamparan persawahan yang hijau, pegunungan yang megah, dan lembah yang memukau di jantung Sulawesi Selatan. Panorama alam yang tak terlupakan menanti Anda.
                    </p>
                </div>
            </div>
            
            <!-- Ritual Adat -->
            <div class="feature-card">
                <img src="/images/rambuSolo.jpeg" alt="Ritual Adat Toraja" class="feature-image">
                <div class="feature-content">
                    <h3 class="feature-title">Ritual Adat</h3>
                    <p class="feature-description">
                        Kekayaan tradisi dan upacara adat yang masih dilestarikan turun-temurun dengan makna filosofis yang dalam. Pengalaman budaya yang autentik dan penuh makna.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Deskripsi Toraja -->
    <div class="bg-gray-800">
        <div class="toraja-description">
            <h2 class="text-4xl font-bold text-white mb-4">Mengapa Toraja Sangat Istimewa?</h2>
            <div class="content-spacing">
                <p class="text-gray-200 text-xl leading-relaxed max-w-5xl mx-auto mb-8">
                    Toraja, yang terletak di dataran tinggi Sulawesi Selatan, merupakan destinasi wisata budaya yang memukau. 
                    Dikenal dengan rumah adat Tongkonan yang megah, upacara Rambu Solo' yang penuh makna, dan pemandangan alam 
                    yang memesona.
                </p>
                <p class="text-gray-200 text-xl leading-relaxed max-w-5xl mx-auto">
                    Setiap ukiran di rumah tradisional Toraja memiliki cerita filosofis yang dalam, 
                    mencerminkan kearifan lokal dan hubungan harmonis antara manusia dengan alam. Warisan budaya yang tetap 
                    terjaga hingga kini membuat Toraja menjadi destinasi yang tak terlupakan.
                </p>
            </div>
            <div class="flex flex-wrap justify-center gap-6 mt-12">
                <span class="tag">Budaya Kaya</span>
                <span class="tag">Arsitektur Unik</span>
                <span class="tag">Alam Memukau</span>
                <span class="tag">Tradisi Lestari</span>
                <span class="tag">Seni Ukir</span>
                <span class="tag">Kearifan Lokal</span>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="bg-gray-800 text-white py-24">
    <div class="section-container text-center">
        <h2 class="text-3xl font-bold mb-8">Siap Mengunjungi Toraja?</h2>
        <p class="text-gray-300 text-1xl mb-12 max-w-3xl mx-auto leading-relaxed">
            Mulai petualangan Anda dan rasakan pengalaman tak terlupakan di tanah budaya yang kaya akan tradisi dan keindahan alam.
        </p>
        <div class="flex flex-wrap justify-center gap-8">
            <a href="/destinasi" class="bg-white text-gray-800 hover:bg-gray-100 font-bold py-4 px-12 rounded-xl transition duration-300 transform hover:scale-105">
                Lihat Destinasi
            </a>
            <a href="/galeri" class="bg-white text-gray-800 hover:bg-gray-100 font-bold py-4 px-12 rounded-xl transition duration-300 transform hover:scale-105">
                Lihat Galeri
            </a>
        </div>
    </div>
</div>
@endsection