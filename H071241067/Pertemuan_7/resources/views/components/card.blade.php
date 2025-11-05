<div style="border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    @if(isset($image))
    <img src="{{ $image }}" alt="{{ $title }}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 5px; margin-bottom: 10px;">
    @endif
    <h3 style="color: #8B4513; margin-bottom: 10px;">{{ $title }}</h3>
    <p style="color: #666;">{{ $description }}</p>
</div>