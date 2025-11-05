@extends('layouts.master')

@section('content')
<h2 style="color: #8B4513; text-align: center; margin-bottom: 30px; font-size: 2em;">Galeri Toraja</h2>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
    
    <div style="overflow: hidden; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <img src="/images/tongkonan.jpg" alt="Tongkonan" style="width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
        <p style="text-align: center; padding: 10px; background: #f5f5f5; font-weight: bold;">Rumah Tongkonan</p>
    </div>

    <div style="overflow: hidden; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <img src="/images/patung tau tau.webp" alt="Tau-Tau" style="width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
        <p style="text-align: center; padding: 10px; background: #f5f5f5; font-weight: bold;">Patung Tau-Tau</p>
    </div>

    <div style="overflow: hidden; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <img src="/images/sawah.jpg" alt="Sawah" style="width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
        <p style="text-align: center; padding: 10px; background: #f5f5f5; font-weight: bold;">Sawah Bertingkat</p>
    </div>

    <div style="overflow: hidden; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <img src="/images/rambu solo.jpg" alt="Rambu Solo" style="width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
        <p style="text-align: center; padding: 10px; background: #f5f5f5; font-weight: bold;">Toraja People</p>
    </div>

    <div style="overflow: hidden; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <img src="/images/pegunungan.avif" alt="Pegunungan" style="width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
        <p style="text-align: center; padding: 10px; background: #f5f5f5; font-weight: bold;">Pegunungan Toraja</p>
    </div>

    <div style="overflow: hidden; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <img src="/images/lumbung padi.jpg" alt="Lumbung" style="width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
        <p style="text-align: center; padding: 10px; background: #f5f5f5; font-weight: bold;">Lumbung Padi</p>
    </div>

    <div style="overflow: hidden; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <img src="/images/ukiran.jpg" alt="Ukiran" style="width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
        <p style="text-align: center; padding: 10px; background: #f5f5f5; font-weight: bold;">Ukiran Khas Toraja</p>
    </div>

    <div style="overflow: hidden; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <img src="/images/tarian.webp" alt="Budaya" style="width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
        <p style="text-align: center; padding: 10px; background: #f5f5f5; font-weight: bold;">Tarian Toraja</p>
    </div>

      <div style="overflow: hidden; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <img src="/images/adat rambu solo.webp" alt="Budaya" style="width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
        <p style="text-align: center; padding: 10px; background: #f5f5f5; font-weight: bold;">adat rambu solo</p>
    </div>

     <div style="overflow: hidden; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <img src="/images/pakaian adat.webp" alt="Ukiran" style="width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
        <p style="text-align: center; padding: 10px; background: #f5f5f5; font-weight: bold;">pakaian adat</p>
    </div>

</div>
@endsection