<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketing Perumahan</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (via CDN) -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #EEEEEE;
        }
    </style>
</head>
<body class="antialiased min-h-screen relative overflow-x-hidden bg-[#EEEEEE]">
    
    <!-- Main Wrapper (Landing Page) -->
    <!-- Original div width: 100%; height: 100%; position: relative; background: #EEEEEE; overflow: hidden; border-radius: 20px -->
    <div class="w-full min-h-screen relative overflow-hidden mx-auto pb-20 lg:pb-0" style="max-width: 1440px;">
        
        <!-- Navbar -->
        <nav class="w-full flex justify-center lg:justify-start items-center gap-8 lg:gap-16 pt-8 lg:pt-[46px] px-6 lg:pl-[203px] flex-wrap z-20 relative">
            <a href="#" class="text-[#393939] text-lg lg:text-[24px] font-medium hover:text-blue-600 transition-colors">Home</a>
            <a href="#" class="text-[#393939] text-lg lg:text-[24px] font-medium hover:text-blue-600 transition-colors whitespace-nowrap">Unit tersedia</a>
            <a href="#" class="text-[#393939] text-lg lg:text-[24px] font-medium hover:text-blue-600 transition-colors whitespace-nowrap">Site plan</a>
            <a href="#" class="text-[#393939] text-lg lg:text-[24px] font-medium hover:text-blue-600 transition-colors">Simulasi</a>
            <a href="#" class="text-[#393939] text-lg lg:text-[24px] font-medium hover:text-blue-600 transition-colors whitespace-nowrap">Tentang kami</a>
        </nav>

        <!-- Hero Content Section -->
        <main class="w-full flex flex-col-reverse lg:flex-row items-center justify-between mt-12 lg:mt-[150px] relative">
            
            <!-- Left Text -->
            <div class="w-full lg:w-1/2 lg:pl-[82px] px-6 z-10 text-center lg:text-left">
                <!-- Original Text: width: 540px; height: 59px; left: 82px; top: 433px; position: absolute; color: #676767; font-size: 24px; font-family: Inter; font-weight: 500; line-height: 30px -->
                <p class="text-[#676767] text-[20px] lg:text-[24px] font-medium leading-[38px] lg:leading-[30px] max-w-[540px] mx-auto lg:mx-0 drop-shadow-sm">
                    Lihat ketersediaan unit per blok, cek lokasi langsung di peta, dan pesan rumah impian Anda sekarang juga.
                </p>
                
                <!-- CTA Buttons (Tambahan Estetika) -->
                <div class="mt-8 flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                    <button class="bg-[#393939] text-white px-8 py-3 rounded-[20px] font-medium hover:bg-black transition-colors shadow-lg">Pesan Sekarang</button>
                    <button class="bg-transparent text-[#393939] px-8 py-3 rounded-[20px] font-medium border-2 border-[#393939] hover:bg-gray-100 transition-colors">Pelajari Lebih Lanjut</button>
                </div>
            </div>

            <!-- Right Image Area -->
            <!-- Original Background Rectangle (1148): width: 671px; height: 768px; left: 720px; top: 147px; background: #D9D9D9; border-radius: 20px -->
            <div class="w-full lg:w-1/2 relative min-h-[400px] lg:min-h-[768px] flex justify-center lg:justify-end mb-12 lg:mb-0">
                
                <!-- Background Shape -->
                <div class="hidden lg:block absolute right-0 -top-[50px] w-full max-w-[671px] h-[768px] bg-[#D9D9D9] rounded-l-[20px] z-0"></div>
                <!-- Mobile Background Shape -->
                <div class="block lg:hidden absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[90%] h-[110%] bg-[#D9D9D9] rounded-[20px] z-0"></div>

                <!-- Hero Image -->
                <!-- Original Image 1: width: 972.24px; height: 965.55px; left: 512.88px; top: -28.85px; -->
                <!-- Kami memisahkan gambar base64 ke folder public/images/hero.png agar performa HTML lebih cepat -->
                <img src="{{ asset('images/hero.png') }}" 
                     alt="Rumah Impian" 
                     class="relative z-10 w-[90%] lg:w-full lg:max-w-[972px] h-auto object-contain lg:absolute lg:right-[50px] lg:-top-[150px] drop-shadow-2xl hover:scale-[1.02] transition-transform duration-700">
            </div>
            
        </main>
    </div>

</body>
</html>
