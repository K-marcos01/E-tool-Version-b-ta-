/* ─── SCROLL REVEAL ──────────────────────────────── */
(function () {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target); // fire once
        }
      });
    },
    { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
  );

  document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));
})();

/* ─── SEARCH FILTER ──────────────────────────────── */
document.getElementById('shop-search').addEventListener('input', function () {
  const q = this.value.toLowerCase();
  document.querySelectorAll('.shop-card-item').forEach((item) => {
    const name = item.querySelector('.shop-card__name').textContent.toLowerCase();
    const desc = item.querySelector('.shop-card__desc').textContent.toLowerCase();
    item.style.display = (name.includes(q) || desc.includes(q)) ? '' : 'none';
  });
});