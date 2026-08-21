document.addEventListener('DOMContentLoaded', () => {
  // Mobile Menu Logic
  const menuBtn = document.getElementById('menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');

  if (menuBtn && mobileMenu) {
    menuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  }

  // Pure Products Tabs & AJAX Load More Logic
  const productSections = document.querySelectorAll('.el-nakaa-products');

  productSections.forEach((section) => {
    const tabBtns = section.querySelectorAll('.tab-btn');

    tabBtns.forEach((btn) => {
      btn.addEventListener('click', function () {
        // Remove active class from all buttons in this section
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

        // Filter Products (query dynamically to include any newly AJAX loaded cards)
        const target = this.getAttribute('data-tab');
        const currentProductItems = section.querySelectorAll('.product-item');

        currentProductItems.forEach((item) => {
          const categories = item.getAttribute('data-categories') || '';
          const catList = categories.split(/\s+/).filter(Boolean);

          if (target === 'all') {
            item.classList.remove('hidden');
            item.classList.add('flex');
          } else {
            if (catList.includes(target)) {
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

    // AJAX Load More Logic for this section
    const loadMoreBtn = section.querySelector('.load-more-products-btn');
    const productsContainer = section.querySelector('#products-container');

    if (loadMoreBtn && productsContainer) {
      loadMoreBtn.addEventListener('click', function () {
        const btn = this;
        let currentPage = parseInt(btn.getAttribute('data-page')) || 1;
        const maxPages = parseInt(btn.getAttribute('data-max-pages')) || 1;
        const perPage = parseInt(btn.getAttribute('data-per-page')) || 8;
        const template = btn.getAttribute('data-template') || '1';
        const categories = btn.getAttribute('data-categories') || '';
        const defaultText = btn.getAttribute('data-btn-text') || 'مشاهده المزيد';

        if (currentPage >= maxPages) {
          const wrapper = btn.closest('.load-more-wrapper');
          if (wrapper) wrapper.style.display = 'none';
          return;
        }

        const nextPage = currentPage + 1;
        const btnText = btn.querySelector('.btn-text');
        const btnSpinner = btn.querySelector('.btn-spinner');

        // Loading UI state
        btn.disabled = true;
        if (btnSpinner) btnSpinner.classList.remove('hidden');
        if (btnText) btnText.textContent = 'جاري التحميل...';

        const ajaxUrl =
          (typeof elNakaaAjax !== 'undefined' && elNakaaAjax.ajaxurl) ||
          (typeof window.yith_wcwl_l10n !== 'undefined'
            ? window.yith_wcwl_l10n.ajax_url
            : '/wp-admin/admin-ajax.php');

        const formData = new FormData();
        formData.append('action', 'el_nakaa_load_more_products');
        formData.append('page', nextPage);
        formData.append('per_page', perPage);
        formData.append('template', template);
        formData.append('categories', categories);

        fetch(ajaxUrl, {
          method: 'POST',
          body: formData,
        })
          .then((res) => res.json())
          .then((response) => {
            if (response && response.success && response.data.html) {
              const tempDiv = document.createElement('div');
              tempDiv.innerHTML = response.data.html;
              const newItems = Array.from(
                tempDiv.querySelectorAll('.product-item'),
              );

              // Check current active tab
              const activeTabBtn = section.querySelector('.tab-btn.active');
              const activeTab = activeTabBtn
                ? activeTabBtn.getAttribute('data-tab')
                : 'all';

              newItems.forEach((item) => {
                const itemCats = item.getAttribute('data-categories') || '';
                const catList = itemCats.split(/\s+/).filter(Boolean);

                if (activeTab !== 'all' && !catList.includes(activeTab)) {
                  item.classList.add('hidden');
                  item.classList.remove('flex');
                }

                productsContainer.appendChild(item);
              });

              btn.setAttribute('data-page', nextPage);

              if (
                !response.data.has_more ||
                nextPage >= response.data.max_pages
              ) {
                const wrapper = btn.closest('.load-more-wrapper');
                if (wrapper) wrapper.style.display = 'none';
              }
            } else {
              const wrapper = btn.closest('.load-more-wrapper');
              if (wrapper) wrapper.style.display = 'none';
            }
          })
          .catch((err) => {
            console.error('Error loading more products:', err);
          })
          .finally(() => {
            btn.disabled = false;
            if (btnSpinner) btnSpinner.classList.add('hidden');
            if (btnText) btnText.textContent = defaultText;
          });
      });
    }
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

// Hook into YITH Wishlist native AJAX events to update header counters
jQuery(document).on(
  'added_to_wishlist removed_from_wishlist yith_wcwl_fragments_loaded yith_wcwl_init',
  function () {
    // We bypass YITH fragments completely and just ask our server for the truth
    if (typeof jQuery !== 'undefined') {
      const ajaxUrl =
        typeof window.yith_wcwl_l10n !== 'undefined'
          ? window.yith_wcwl_l10n.ajax_url
          : '/wp-admin/admin-ajax.php';

      jQuery.post(
        ajaxUrl,
        { action: 'get_wishlist_count' },
        function (response) {
          if (response && response.success !== undefined) {
            const newCount = parseInt(response.data) || 0;
            const wishlistCounters = document.querySelectorAll(
              '.yith-wcwl-items-count',
            );

            wishlistCounters.forEach((counter) => {
              counter.innerText = newCount;
              counter.classList.remove('scale-110');
              setTimeout(
                () =>
                  counter.classList.add('scale-110', 'transition-transform'),
                50,
              );
              setTimeout(() => counter.classList.remove('scale-110'), 300);
            });
          }
        },
      );
    }
  },
);

// Update the counter when fragments are refreshed
jQuery(document).on('yith_wcwl_fragments_refreshed', function (e, fragments) {
  const wishlistCounters = document.querySelectorAll('.yith-wcwl-items-count');

  if (fragments && fragments['.yith-wcwl-items-count']) {
    // If YITH provides the fragment, that's great
    const newCountHTML = fragments['.yith-wcwl-items-count'];

    wishlistCounters.forEach((counter) => {
      // Extract just the number from the returned HTML string securely
      const tempDiv = document.createElement('div');
      tempDiv.innerHTML = newCountHTML;
      const count = parseInt(tempDiv.innerText) || 0;

      counter.innerText = count;
      counter.classList.remove('scale-110');
      setTimeout(
        () => counter.classList.add('scale-110', 'transition-transform'),
        50,
      );
      setTimeout(() => counter.classList.remove('scale-110'), 300);
    });
  }
});
