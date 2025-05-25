
document.addEventListener('DOMContentLoaded', function () {
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxClose = document.getElementById('lightbox-close');

    // 綁定所有含有 .lightbox-trigger class 的圖片
    document.querySelectorAll('.lightbox-trigger').forEach(img => {
        img.addEventListener('click', function () {
            const src = this.getAttribute('data-full') || this.src;
            lightboxImg.src = src;
            lightbox.style.display = 'flex';
        });
    });

    // 關閉按鈕或點空白處時關閉
    lightboxClose.addEventListener('click', () => {
        lightbox.style.display = 'none';
    });

    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            lightbox.style.display = 'none';
        }
    });
});

