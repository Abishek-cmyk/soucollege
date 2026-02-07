document.addEventListener('DOMContentLoaded', function () {
    const sliderWrappers = document.querySelectorAll('.slider-wrapper');

    sliderWrappers.forEach(wrapper => {
        const imageGrid = wrapper.querySelector('.image-grid');
        const items = wrapper.querySelectorAll('.gallery-item');
        const leftArrow = wrapper.querySelector('.left-arrow');
        const rightArrow = wrapper.querySelector('.right-arrow');

        if (items.length <= 1) {
            leftArrow.style.display = 'none';
            rightArrow.style.display = 'none';
            return;
        }

        let currentSlide = 0;

        function updateArrows() {
            leftArrow.disabled = currentSlide === 0;

            rightArrow.disabled = currentSlide === items.length - 1;
        }

        /**
         * @param {number} index - The new slide index (0 to items.length - 1).
         */
        function moveToSlide(index) {
            if (index < 0 || index >= items.length) return;
            currentSlide = index;
            const offset = -currentSlide * 100;
            imageGrid.style.transform = `translateX(${offset}%)`;

            updateArrows();
        }


        leftArrow.addEventListener('click', () => {
            moveToSlide(currentSlide - 1);
        });

        rightArrow.addEventListener('click', () => {
            moveToSlide(currentSlide + 1);
        });

        moveToSlide(0);
    });
});