@extends('layouts.master')

@section('content')
<style>
    /* Hapus semua margin dan padding */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    .hero-section {
        background-image: url('/images/backround.avif');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        width: 100vw;
        min-height: 100vh;
        margin-left: calc(-50vw + 50%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    
    /* Overlay gelap agar text lebih terbaca */
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5));
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
        color: white;
        text-align: center;
        padding: 40px 20px;
        max-width: 900px;
    }
    
    .hero-content h1 {
        font-size: 4rem;
        font-weight: bold;
        margin-bottom: 30px;
        text-shadow: 3px 3px 8px rgba(0,0,0,0.9);
        line-height: 1.2;
    }
    
    .hero-content p {
        font-size: 1.4rem;
        line-height: 1.8;
        text-shadow: 2px 2px 6px rgba(0,0,0,0.9);
    }
</style>

<div class="hero-section">
    <div class="hero-content">
        <h1>Selamat Datang di Tanah Toraja</h1>
        <p>Toraja adalah sebuah kabupaten di Provinsi Sulawesi Selatan yang terkenal dengan kebudayaan uniknya, pemandangan alam yang memukau, dan tradisi yang kaya.</p>
    </div>
</div>
@endsection