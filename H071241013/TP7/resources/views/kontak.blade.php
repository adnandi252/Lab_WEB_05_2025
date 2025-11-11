@extends('layouts.master')

@section('title', 'Kontak - Eksplor Sinjai')

@section('content')
<!-- Hero Section -->
<section class="relative h-96 flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 gradient-bg opacity-90"></div>
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://4.bp.blogspot.com/-22LGvBEguvU/V2aPuRmqX2I/AAAAAAAAAP0/GrbJuyyPuPw-B4zYQOlzkZCWZjebKmfrQCLcB/s1600/1424936332336973465.jpg'); opacity: 0.2;"></div>
    
    <div class="relative z-10 text-center text-white px-4 animate-fadeInUp">
        <h1 class="text-5xl md:text-6xl font-bold mb-4">Hubungi Kami</h1>
        <p class="text-xl text-gray-100">Kami siap membantu merencanakan perjalanan Anda ke Sinjai</p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-20 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="animate-on-scroll">
                <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10">
                    <h2 class="text-3xl font-bold gradient-text mb-6">Kirim Pesan</h2>
                    <p class="text-gray-600 mb-8">Punya pertanyaan? Isi form di bawah ini dan kami akan segera menghubungi Anda.</p>
                    
                    <form class="space-y-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fas fa-user mr-2 text-purple-600"></i>Nama Lengkap
                            </label>
                            <input type="text" placeholder="Masukkan nama lengkap Anda" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fas fa-envelope mr-2 text-purple-600"></i>Email
                            </label>
                            <input type="email" placeholder="nama@email.com" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fas fa-phone mr-2 text-purple-600"></i>Nomor Telepon
                            </label>
                            <input type="tel" placeholder="08xx xxxx xxxx" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fas fa-list mr-2 text-purple-600"></i>Kategori
                            </label>
                            <select class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition">
                                <option>Informasi Destinasi Wisata</option>
                                <option>Paket Tour</option>
                                <option>Rekomendasi Kuliner</option>
                                <option>Akomodasi & Penginapan</option>
                                <option>Kerjasama & Partnership</option>
                                <option>Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fas fa-comment mr-2 text-purple-600"></i>Pesan
                            </label>
                            <textarea rows="5" placeholder="Tuliskan pesan Anda di sini..." class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none transition resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full gradient-bg text-white font-semibold py-4 rounded-lg hover:shadow-2xl hover:scale-105 transition duration-300">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="space-y-6 animate-on-scroll">
                <div class="bg-linear-to-br from-purple-50 to-blue-50 rounded-2xl shadow-lg p-8">
                    <h2 class="text-3xl font-bold gradient-text mb-6">Informasi Kontak</h2>
                    <p class="text-gray-600 mb-8">Jangan ragu untuk menghubungi kami melalui salah satu cara berikut:</p>

                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-14 h-14 gradient-bg rounded-full flex items-center justify-center shrink-0">
                                <i class="fas fa-map-marker-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-800 mb-1">Alamat</h3>
                                <p class="text-gray-600">Jl. Jenderal Sudirman No. 1<br>Sinjai Utara, Kabupaten Sinjai<br>Sulawesi Selatan 92611</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-14 h-14 gradient-bg rounded-full flex items-center justify-center shrink-0">
                                <i class="fas fa-phone text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-800 mb-1">Telepon</h3>
                                <p class="text-gray-600">+62 482 21234<br>+62 812 3456 7890</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-14 h-14 gradient-bg rounded-full flex items-center justify-center shrink-0">
                                <i class="fas fa-envelope text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-800 mb-1">Email</h3>
                                <p class="text-gray-600">info@eksplorsinjai.com<br>pariwisata@sinjaikab.go.id</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="w-14 h-14 gradient-bg rounded-full flex items-center justify-center shrink-0">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-800 mb-1">Jam Operasional</h3>
                                <p class="text-gray-600">Senin - Jumat: 08:00 - 16:00 WITA<br>Sabtu: 08:00 - 12:00 WITA<br>Minggu & Hari Libur: Tutup</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold gradient-text mb-6">Ikuti Kami</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="#" class="flex items-center justify-center space-x-3 px-6 py-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600 hover:scale-105 transition">
                            <i class="fab fa-facebook-f text-2xl"></i>
                            <span class="font-semibold">Facebook</span>
                        </a>
                        <a href="#" class="flex items-center justify-center space-x-3 px-6 py-4 h-16 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 hover:scale-105 transition">
                            <i class="fab fa-instagram text-2xl"></i>
                            <span class="font-semibold">Instagram</span>
                        </a>
                        <a href="#" class="flex items-center justify-center space-x-3 px-6 py-4 bg-blue-400 text-white rounded-lg hover:bg-blue-500 hover:scale-105 transition">
                            <i class="fab fa-twitter text-2xl"></i>
                            <span class="font-semibold">Twitter</span>
                        </a>
                        <a href="#" class="flex items-center justify-center space-x-3 px-6 py-4 bg-red-500 text-white rounded-lg hover:bg-red-600 hover:scale-105 transition">
                            <i class="fab fa-youtube text-2xl"></i>
                            <span class="font-semibold">YouTube</span>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-linear-to-br from-purple-100 to-blue-100 rounded-2xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold gradient-text mb-6">Link Berguna</h3>
                    <div class="space-y-3">
                        <a href="#" class="flex items-center space-x-3 text-gray-700 hover:text-purple-600 transition">
                            <i class="fas fa-external-link-alt"></i>
                            <span>Website Resmi Pemkab Sinjai</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 text-gray-700 hover:text-purple-600 transition">
                            <i class="fas fa-external-link-alt"></i>
                            <span>Dinas Pariwisata Sulawesi Selatan</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 text-gray-700 hover:text-purple-600 transition">
                            <i class="fas fa-external-link-alt"></i>
                            <span>Indonesia Travel</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 text-gray-700 hover:text-purple-600 transition">
                            <i class="fas fa-external-link-alt"></i>
                            <span>E-Tourism Sinjai</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-20 px-4 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12 animate-on-scroll">
            <h2 class="text-4xl font-bold gradient-text mb-4">Lokasi Kami</h2>
            <div class="w-24 h-1 gradient-bg mx-auto mb-6"></div>
            <p class="text-gray-600 text-lg">Temukan kami di pusat Kabupaten Sinjai</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-on-scroll">
            <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127089.80792726477!2d120.19363584863282!3d-5.141666399999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbe91a6a4e9e1e5%3A0x3030bfbcaf77c10!2sSinjai%2C%20Kabupaten%20Sinjai%2C%20Sulawesi%20Selatan!5e0!3m2!1sid!2sid!4v1234567890123!5m2!1sid!2sid" 
                    width="100%" 
                    height="500" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    class="w-full">
                </iframe>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12 animate-on-scroll">
            <h2 class="text-4xl font-bold gradient-text mb-4">Pertanyaan Umum</h2>
            <div class="w-24 h-1 gradient-bg mx-auto mb-6"></div>
            <p class="text-gray-600 text-lg">Temukan jawaban atas pertanyaan yang sering diajukan</p>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-on-scroll">
                <button onclick="toggleFAQ(this)" class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition">
                    <span class="font-semibold text-lg text-gray-800">Bagaimana cara menuju ke Sinjai?</span>
                    <i class="fas fa-chevron-down text-purple-600 transition-transform"></i>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-gray-600">
                    <p>Sinjai dapat diakses melalui jalur darat dari Makassar dengan jarak sekitar 220 km (4-5 jam perjalanan). Anda bisa menggunakan kendaraan pribadi atau bus antar kota. Dari Bandara Internasional Sultan Hasanuddin, ambil bus atau travel menuju Sinjai. Perjalanan melewati pemandangan indah pesisir selatan Sulawesi.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-on-scroll">
                <button onclick="toggleFAQ(this)" class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition">
                    <span class="font-semibold text-lg text-gray-800">Kapan waktu terbaik mengunjungi Sinjai?</span>
                    <i class="fas fa-chevron-down text-purple-600 transition-transform"></i>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-gray-600">
                    <p>Waktu terbaik mengunjungi Sinjai adalah saat musim kemarau (April - Oktober) ketika cuaca cerah dan cocok untuk aktivitas outdoor. Namun Sinjai indah sepanjang tahun. Hindari puncak musim hujan (Desember - Februari) jika ingin mengunjungi pantai atau gunung.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-on-scroll">
                <button onclick="toggleFAQ(this)" class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition">
                    <span class="font-semibold text-lg text-gray-800">Apakah ada paket wisata yang tersedia?</span>
                    <i class="fas fa-chevron-down text-purple-600 transition-transform"></i>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-gray-600">
                    <p>Ya, kami menyediakan berbagai paket wisata mulai dari day trip hingga paket 3 hari 2 malam. Paket mencakup transportasi, guide, makan, dan tiket masuk destinasi. Hubungi kami untuk detail paket dan harga khusus grup.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-on-scroll">
                <button onclick="toggleFAQ(this)" class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition">
                    <span class="font-semibold text-lg text-gray-800">Dimana bisa menginap di Sinjai?</span>
                    <i class="fas fa-chevron-down text-purple-600 transition-transform"></i>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-gray-600">
                    <p>Sinjai memiliki berbagai pilihan akomodasi mulai dari hotel berbintang, guest house, hingga homestay. Kami merekomendasikan untuk booking terlebih dahulu terutama saat weekend dan high season. Kami dapat membantu merekomendasikan penginapan sesuai budget Anda.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-on-scroll">
                <button onclick="toggleFAQ(this)" class="w-full px-6 py-5 text-left flex items-center justify-between hover:bg-gray-50 transition">
                    <span class="font-semibold text-lg text-gray-800">Apakah ada guide lokal yang tersedia?</span>
                    <i class="fas fa-chevron-down text-purple-600 transition-transform"></i>
                </button>
                <div class="faq-content hidden px-6 pb-5 text-gray-600">
                    <p>Tentu! Kami memiliki tim guide lokal bersertifikat yang berpengalaman dan menguasai bahasa Indonesia dan Inggris. Guide kami akan membawa Anda menjelajahi destinasi tersembunyi dan memberikan insight tentang budaya dan sejarah Sinjai. Booking guide dapat dilakukan minimal 3 hari sebelum kedatangan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 px-4 gradient-bg">
    <div class="max-w-4xl mx-auto text-center text-white animate-on-scroll">
        <i class="fas fa-comments text-6xl mb-6 animate-float"></i>
        <h2 class="text-4xl font-bold mb-6">Masih Punya Pertanyaan?</h2>
        <p class="text-xl text-gray-100 mb-8">
            Tim kami siap membantu Anda 24/7. Jangan ragu untuk menghubungi kami kapan saja!
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="https://wa.me/6281234567890" class="inline-flex items-center justify-center px-8 py-4 bg-green-500 text-white rounded-full font-semibold hover:bg-green-600 hover:scale-105 transition">
                <i class="fab fa-whatsapp text-2xl mr-3"></i>Chat WhatsApp
            </a>
            <a href="tel:+6248221234" class="inline-flex items-center justify-center px-8 py-4 bg-white text-purple-600 rounded-full font-semibold hover:scale-105 transition">
                <i class="fas fa-phone mr-3"></i>Hubungi Sekarang
            </a>
        </div>
    </div>
</section>

<script>
    function toggleFAQ(button) {
        const content = button.nextElementSibling;
        const icon = button.querySelector('i');
        const allContents = document.querySelectorAll('.faq-content');
        const allIcons = document.querySelectorAll('.faq-content').length;
        
        // Close all other FAQs
        document.querySelectorAll('.faq-content').forEach(item => {
            if (item !== content) {
                item.classList.add('hidden');
            }
        });
        
        document.querySelectorAll('button i.fa-chevron-down, button i.fa-chevron-up').forEach(item => {
            if (item !== icon) {
                item.classList.remove('fa-chevron-up', 'rotate-180');
                item.classList.add('fa-chevron-down');
            }
        });
        
        // Toggle current FAQ
        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }

    // Form submission handler (demo)
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Terima kasih! Pesan Anda telah dikirim. Kami akan menghubungi Anda segera.');
        this.reset();
    });
</script>
@endsection