@extends('layouts.master')

@section('title', 'Galeri - Eksplor Sinjai')

@section('content')
<!-- Hero Section -->
<section class="relative h-96 flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 gradient-bg opacity-90"></div>
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://4.bp.blogspot.com/-22LGvBEguvU/V2aPuRmqX2I/AAAAAAAAAP0/GrbJuyyPuPw-B4zYQOlzkZCWZjebKmfrQCLcB/s1600/1424936332336973465.jpg'); opacity: 0.2;"></div>
    
    <div class="relative z-10 text-center text-white px-4 animate-fadeInUp">
        <h1 class="text-5xl md:text-6xl font-bold mb-4">Galeri Sinjai</h1>
        <p class="text-xl text-gray-100">Potret keindahan alam, budaya, dan kehidupan masyarakat Sinjai</p>
    </div>
</section>

<!-- Gallery Categories -->
<section class="py-12 px-4 bg-white sticky top-20 z-40 shadow-md">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-wrap justify-center gap-4">
            <button onclick="filterGallery('all')" class="gallery-filter px-6 py-3 rounded-full font-semibold transition gradient-bg text-white">
                <i class="fas fa-th mr-2"></i>Semua
            </button>
            <button onclick="filterGallery('alam')" class="gallery-filter px-6 py-3 rounded-full font-semibold transition bg-gray-200 text-gray-700 hover:bg-purple-100">
                <i class="fas fa-mountain mr-2"></i>Alam
            </button>
            <button onclick="filterGallery('pantai')" class="gallery-filter px-6 py-3 rounded-full font-semibold transition bg-gray-200 text-gray-700 hover:bg-purple-100">
                <i class="fas fa-umbrella-beach mr-2"></i>Pantai
            </button>
            <button onclick="filterGallery('budaya')" class="gallery-filter px-6 py-3 rounded-full font-semibold transition bg-gray-200 text-gray-700 hover:bg-purple-100">
                <i class="fas fa-drum mr-2"></i>Budaya
            </button>
            <button onclick="filterGallery('kuliner')" class="gallery-filter px-6 py-3 rounded-full font-semibold transition bg-gray-200 text-gray-700 hover:bg-purple-100">
                <i class="fas fa-utensils mr-2"></i>Kuliner
            </button>
        </div>
    </div>
</section>

<!-- Gallery Grid -->
<section class="py-20 px-4">
    <div class="max-w-7xl mx-auto">
        <div id="gallery-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Alam Category -->
            <div class="gallery-item alam animate-on-scroll group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer" onclick="openModal(this)">
                <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800" alt="Gunung Loka Bulan" class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-linear-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Gunung Loka Bulan</h3>
                        <p class="text-sm text-gray-200">Pemandangan spektakuler dari puncak</p>
                    </div>
                </div>
                <span class="absolute top-4 right-4 px-3 py-1 bg-green-500 text-white text-xs rounded-full">Alam</span>
            </div>

            <div class="gallery-item alam animate-on-scroll group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer" onclick="openModal(this)">
                <img src="https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?w=800" alt="Air Terjun" class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-linear-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Air Terjun Puncak Ilalang</h3>
                        <p class="text-sm text-gray-200">Kesegaran di tengah hutan tropis</p>
                    </div>
                </div>
                <span class="absolute top-4 right-4 px-3 py-1 bg-green-500 text-white text-xs rounded-full">Alam</span>
            </div>

            <!-- Pantai Category -->
            <div class="gallery-item pantai animate-on-scroll group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer" onclick="openModal(this)">
                <img src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800" alt="Pantai Baloiya" class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-linear-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Pantai Baloiya</h3>
                        <p class="text-sm text-gray-200">Pasir putih dan air jernih</p>
                    </div>
                </div>
                <span class="absolute top-4 right-4 px-3 py-1 bg-blue-500 text-white text-xs rounded-full">Pantai</span>
            </div>

            <div class="gallery-item pantai animate-on-scroll group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer" onclick="openModal(this)">
                <img src="https://images.unsplash.com/photo-1505142468610-359e7d316be0?w=800" alt="Sunset Pantai" class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-linear-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Sunset di Lappa Laona</h3>
                        <p class="text-sm text-gray-200">Momen magis senja di pantai</p>
                    </div>
                </div>
                <span class="absolute top-4 right-4 px-3 py-1 bg-blue-500 text-white text-xs rounded-full">Pantai</span>
            </div>

            <div class="gallery-item pantai animate-on-scroll group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer" onclick="openModal(this)">
                <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800" alt="Pulau Sembilan" class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-linear-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Pulau Sembilan</h3>
                        <p class="text-sm text-gray-200">Surga bawah laut Sinjai</p>
                    </div>
                </div>
                <span class="absolute top-4 right-4 px-3 py-1 bg-blue-500 text-white text-xs rounded-full">Pantai</span>
            </div>

            <!-- Budaya Category -->
            <div class="gallery-item budaya animate-on-scroll group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer" onclick="openModal(this)">
                <img src="https://images.unsplash.com/photo-1518709594023-6eab9bab7b23?w=800" alt="Situs Megalitikum" class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-linear-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Batu Pake Gojeng</h3>
                        <p class="text-sm text-gray-200">Warisan peradaban masa lampau</p>
                    </div>
                </div>
                <span class="absolute top-4 right-4 px-3 py-1 bg-purple-500 text-white text-xs rounded-full">Budaya</span>
            </div>

            <div class="gallery-item budaya animate-on-scroll group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer" onclick="openModal(this)">
                <img src="https://images.unsplash.com/photo-1533854775446-95c4609da544?w=800" alt="Tarian Tradisional" class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-linear-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Tarian Pakarena</h3>
                        <p class="text-sm text-gray-200">Keanggunan budaya Bugis</p>
                    </div>
                </div>
                <span class="absolute top-4 right-4 px-3 py-1 bg-purple-500 text-white text-xs rounded-full">Budaya</span>
            </div>

            <div class="gallery-item budaya animate-on-scroll group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer" onclick="openModal(this)">
                <img src="https://images.unsplash.com/photo-1478146896981-b80fe463b330?w=800" alt="Perahu Tradisional" class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-linear-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Perahu Phinisi</h3>
                        <p class="text-sm text-gray-200">Simbol kejayaan maritim Bugis</p>
                    </div>
                </div>
                <span class="absolute top-4 right-4 px-3 py-1 bg-purple-500 text-white text-xs rounded-full">Budaya</span>
            </div>

            <!-- Kuliner Category -->
            <div class="gallery-item kuliner animate-on-scroll group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer" onclick="openModal(this)">
                <img src="https://images.unsplash.com/photo-1615141982883-c7ad0e69fd62?w=800" alt="Ikan Parende" class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-linear-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Ikan Parende</h3>
                        <p class="text-sm text-gray-200">Signature dish khas Sinjai</p>
                    </div>
                </div>
                <span class="absolute top-4 right-4 px-3 py-1 bg-orange-500 text-white text-xs rounded-full">Kuliner</span>
            </div>

            <div class="gallery-item kuliner animate-on-scroll group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer" onclick="openModal(this)">
                <img src="https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=800" alt="Kapurung" class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-linear-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Kapurung</h3>
                        <p class="text-sm text-gray-200">Kuliner tradisional legendaris</p>
                    </div>
                </div>
                <span class="absolute top-4 right-4 px-3 py-1 bg-orange-500 text-white text-xs rounded-full">Kuliner</span>
            </div>

            <div class="gallery-item kuliner animate-on-scroll group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer" onclick="openModal(this)">
                <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=800" alt="Bolu Peca" class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-linear-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Bolu Peca</h3>
                        <p class="text-sm text-gray-200">Kue manis warisan leluhur</p>
                    </div>
                </div>
                <span class="absolute top-4 right-4 px-3 py-1 bg-orange-500 text-white text-xs rounded-full">Kuliner</span>
            </div>

            <!-- Additional Mixed Photos -->
            <div class="gallery-item alam animate-on-scroll group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer" onclick="openModal(this)">
                <img src="https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800" alt="Hutan Sinjai" class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-linear-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Hutan Tropis Sinjai</h3>
                        <p class="text-sm text-gray-200">Keanekaragaman hayati yang terjaga</p>
                    </div>
                </div>
                <span class="absolute top-4 right-4 px-3 py-1 bg-green-500 text-white text-xs rounded-full">Alam</span>
            </div>

            <div class="gallery-item pantai animate-on-scroll group relative overflow-hidden rounded-2xl shadow-lg cursor-pointer" onclick="openModal(this)">
                <img src="https://images.unsplash.com/photo-1535556116002-6281ff3e9f36?w=800" alt="Nelayan" class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-linear-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300">
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">Kehidupan Nelayan</h3>
                        <p class="text-sm text-gray-200">Aktivitas pagi hari di pelabuhan</p>
                    </div>
                </div>
                <span class="absolute top-4 right-4 px-3 py-1 bg-blue-500 text-white text-xs rounded-full">Pantai</span>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center p-4" onclick="closeModal()">
    <button class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300 transition" onclick="closeModal()">
        <i class="fas fa-times"></i>
    </button>
    <div class="max-w-5xl w-full" onclick="event.stopPropagation()">
        <img id="modalImage" src="" alt="" class="w-full h-auto rounded-lg shadow-2xl">
        <div id="modalCaption" class="text-white text-center mt-4 text-xl font-semibold"></div>
    </div>
</div>

<!-- Statistics Section -->
<section class="py-20 px-4 gradient-bg text-white">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4">Sinjai dalam Angka</h2>
            <p class="text-gray-100">Fakta menarik tentang Kabupaten Sinjai</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center animate-on-scroll">
                <div class="text-5xl font-bold mb-2 counter" data-target="15">0</div>
                <p class="text-gray-200">Destinasi Wisata</p>
            </div>
            <div class="text-center animate-on-scroll" style="animation-delay: 0.1s;">
                <div class="text-5xl font-bold mb-2 counter" data-target="25">0</div>
                <p class="text-gray-200">Kuliner Khas</p>
            </div>
            <div class="text-center animate-on-scroll" style="animation-delay: 0.2s;">
                <div class="text-5xl font-bold mb-2 counter" data-target="9">0</div>
                <p class="text-gray-200">Kecamatan</p>
            </div>
            <div class="text-center animate-on-scroll" style="animation-delay: 0.3s;">
                <div class="text-5xl font-bold mb-2 counter" data-target="820">0</div>
                <p class="text-gray-200">Km² Luas Wilayah</p>
            </div>
        </div>
    </div>
</section>

<script>
    // Gallery Filter
    function filterGallery(category) {
        const items = document.querySelectorAll('.gallery-item');
        const buttons = document.querySelectorAll('.gallery-filter');
        
        buttons.forEach(btn => {
            btn.classList.remove('gradient-bg', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-700');
        });
        
        event.target.classList.remove('bg-gray-200', 'text-gray-700');
        event.target.classList.add('gradient-bg', 'text-white');
        
        items.forEach(item => {
            if (category === 'all' || item.classList.contains(category)) {
                item.style.display = 'block';
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'scale(1)';
                }, 10);
            } else {
                item.style.opacity = '0';
                item.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    item.style.display = 'none';
                }, 300);
            }
        });
    }

    // Modal Functions
    function openModal(element) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        const modalCaption = document.getElementById('modalCaption');
        const img = element.querySelector('img');
        const title = element.querySelector('h3').textContent;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modalImg.src = img.src;
        modalCaption.textContent = title;
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    // Counter Animation
    const counters = document.querySelectorAll('.counter');
    const speed = 200;

    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const inc = target / speed;

            if (count < target) {
                counter.innerText = Math.ceil(count + inc);
                setTimeout(updateCount, 1);
            } else {
                counter.innerText = target;
            }
        };

        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                updateCount();
                observer.disconnect();
            }
        });

        observer.observe(counter);
    });

    // Keyboard navigation for modal
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endsection