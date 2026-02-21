document.addEventListener('DOMContentLoaded', () => {
  // Mobile Menu Logic
  const menuBtn = document.getElementById('menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');

  if (menuBtn && mobileMenu) {
    menuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  }

  // Pure Products Tabs Logic
  const tabBtns = document.querySelectorAll('.tab-btn');
  const productItems = document.querySelectorAll('.product-item');

  tabBtns.forEach((btn) => {
    btn.addEventListener('click', function () {
      // Remove active class from all buttons
      tabBtns.forEach((b) => {
        b.classList.remove(
          'bg-mainColor',
          'text-secColor',
          'active',
          'shadow-sm',
        );
        b.classList.add(
          'text-gray-500',
          'hover:text-secColor',
          'hover:bg-gray-100',
        );
      });

      // Add active class to clicked button
      this.classList.remove(
        'text-gray-500',
        'hover:text-secColor',
        'hover:bg-gray-100',
      );
      this.classList.add(
        'bg-mainColor',
        'text-secColor',
        'active',
        'shadow-sm',
      );

      // Filter Products
      const target = this.getAttribute('data-tab');

      productItems.forEach((item) => {
        const categories = item.getAttribute('data-categories');

        if (target === 'all') {
          item.classList.remove('hidden');
          item.classList.add('flex');
        } else {
          // Check if the product has the category class
          // We check if the data-categories string contains "cat-ID"
          if (categories && categories.includes(target)) {
            item.classList.remove('hidden');
            item.classList.add('flex');
          } else {
            item.classList.add('hidden');
            item.classList.remove('flex');
          }
        }
      });
    });
  });
});

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.increase-qty-btn').forEach((btn) => {
    btn.addEventListener('click', function () {
      const input = this.parentElement.querySelector('.qty-input');
      input.value = parseInt(input.value) + 1;
    });
  });

  document.querySelectorAll('.decrease-qty-btn').forEach((btn) => {
    btn.addEventListener('click', function () {
      const input = this.parentElement.querySelector('.qty-input');
      const val = parseInt(input.value);
      if (val > 1) input.value = val - 1;
    });
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const decreaseBtn = document.getElementById('decrease-qty');
  const increaseBtn = document.getElementById('increase-qty');
  const qtyVal = document.getElementById('qty-val');

  if (decreaseBtn && increaseBtn && qtyVal) {
    decreaseBtn.addEventListener('click', () => {
      let val = parseInt(qtyVal.innerText);
      if (val > 1) {
        qtyVal.innerText = val - 1;
      }
    });

    increaseBtn.addEventListener('click', () => {
      let val = parseInt(qtyVal.innerText);
      qtyVal.innerText = val + 1;
    });
  }
});

// FAQ Accordion Script
const faqItems = document.querySelectorAll('.faq-item');

faqItems.forEach((item) => {
  const btn = item.querySelector('button');

  btn.addEventListener('click', () => {
    // Check if current is active
    const isActive = item.classList.contains('active');

    // Close all items
    faqItems.forEach((otherItem) => {
      otherItem.classList.remove('active');
    });

    // Toggle current item based on previous state
    if (!isActive) {
      item.classList.add('active');
    }
  });
});
