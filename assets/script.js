document.addEventListener('DOMContentLoaded', function () {

    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', function () {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-in-up').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        observer.observe(el);
    });

    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }, 10000);
            }
        });
    });

    // Resend OTP countdown handler
    (function () {
        const resendTimer = document.getElementById('resendTimer');
        const resendBtn = document.getElementById('resendBtn');
        if (!resendTimer) return;

        let remaining = parseInt(resendTimer.dataset.remaining) || 0;

        function startCountdown(sec) {
            let counter = sec;
            if (resendBtn) {
                resendBtn.disabled = true;
                resendBtn.classList.add('disabled');
            }
            resendTimer.textContent = counter > 0 ? `Tunggu ${counter}s` : '';

            const iv = setInterval(() => {
                counter--;
                resendTimer.textContent = counter > 0 ? `Tunggu ${counter}s` : '';
                if (counter <= 0) {
                    clearInterval(iv);
                    if (resendBtn) {
                        resendBtn.disabled = false;
                        resendBtn.classList.remove('disabled');
                    }
                    resendTimer.textContent = '';
                }
            }, 1000);
        }

        if (remaining > 0) startCountdown(remaining);

        if (resendBtn) {
            resendBtn.addEventListener('click', function () {
                // immediate local disable to prevent accidental double-click
                startCountdown(60);
            });
        }

        // Copy OTP to clipboard helper
        const copyBtn = document.getElementById('copyOtpBtn');
        if (copyBtn) {
            copyBtn.addEventListener('click', function () {
                const otpInput = document.getElementById('otpInput');
                if (!otpInput) return;
                const otp = otpInput.value || otpInput.getAttribute('value') || '';
                if (!otp) {
                    alert('Tidak ada kode OTP untuk disalin.');
                    return;
                }
                navigator.clipboard?.writeText(otp).then(() => {
                    copyBtn.innerText = '✅ Tersalin';
                    setTimeout(() => copyBtn.innerText = '📋 Salin Kode', 2000);
                }).catch(() => {
                    // Fallback: select then execCommand
                    otpInput.select();
                    try { document.execCommand('copy'); copyBtn.innerText = '✅ Tersalin'; } catch (e) { alert('Gagal menyalin.'); }
                    setTimeout(() => copyBtn.innerText = '📋 Salin Kode', 2000);
                });
            });
        }
    })();
});