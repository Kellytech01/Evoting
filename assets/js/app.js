// Subtle entrance animation for sections
document.addEventListener('DOMContentLoaded', () => {
  const cards = document.querySelectorAll('.card');
  cards.forEach((c, i) => setTimeout(() => c.style.opacity = 1, 90 * i));
});
