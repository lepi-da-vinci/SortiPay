import QRCode from 'qrcode';

document.addEventListener('DOMContentLoaded', () => {
    // Render crisp SVG QR codes for any element with data-qr-value
    document.querySelectorAll('[data-qr-value]').forEach(async (el) => {
        const value = el.getAttribute('data-qr-value');
        const size = parseInt(el.getAttribute('data-qr-size') || '180', 10);
        if (!value) return;

        try {
            const svg = await QRCode.toString(value, {
                type: 'svg',
                width: size,
                margin: 1,
                color: {
                    dark: '#0f172a',
                    light: '#ffffff',
                },
            });
            el.innerHTML = svg;
        } catch (err) {
            console.error('QR render failed:', err);
        }
    });

    // Auto-dismiss flash alerts after 5 seconds if marked
    document.querySelectorAll('[data-auto-dismiss]').forEach((el) => {
        setTimeout(() => {
            el.classList.add('opacity-0', 'transition-opacity', 'duration-300');
            setTimeout(() => el.remove(), 300);
        }, 5000);
    });
});
