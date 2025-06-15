<script>
    let currentIndex = 0;

    function slideLeft() {
        const slides = document.getElementById('carouselSlides');
        const totalSlides = slides.children.length;
        currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        slides.style.transform = `translateX(-${currentIndex * 100}%)`;
    }

    function slideRight() {
        const slides = document.getElementById('carouselSlides');
        const totalSlides = slides.children.length;
        currentIndex = (currentIndex + 1) % totalSlides;
        slides.style.transform = `translateX(-${currentIndex * 100}%)`;
    }
</script>
