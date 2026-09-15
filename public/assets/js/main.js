document.addEventListener('DOMContentLoaded', () => {
  // 1. Mobile Menu Toggle
  const mobileToggle = document.getElementById('mobileToggle');
  const navLinks = document.getElementById('navLinks');

  if (mobileToggle && navLinks) {
    mobileToggle.addEventListener('click', () => {
      navLinks.classList.toggle('open');
      const isOpen = navLinks.classList.contains('open');
      mobileToggle.setAttribute('aria-expanded', isOpen);
    });

    // Close mobile menu on link click
    navLinks.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        navLinks.classList.remove('open');
      });
    });
  }

  // 2. Portfolio Category Filtering
  const filterBtns = document.querySelectorAll('.filter-btn');
  const portfolioItems = document.querySelectorAll('.portfolio-card');

  if (filterBtns.length > 0 && portfolioItems.length > 0) {
    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const filterValue = btn.getAttribute('data-filter');

        portfolioItems.forEach(item => {
          const itemCategories = item.getAttribute('data-category') || '';
          if (filterValue === 'all' || itemCategories.includes(filterValue)) {
            item.style.display = 'flex';
            setTimeout(() => {
              item.style.opacity = '1';
              item.style.transform = 'translateY(0)';
            }, 50);
          } else {
            item.style.opacity = '0';
            item.style.transform = 'translateY(10px)';
            setTimeout(() => {
              item.style.display = 'none';
            }, 300);
          }
        });
      });
    });
  }

  // 3. FAQ Accordion
  const faqItems = document.querySelectorAll('.faq-item');
  faqItems.forEach(item => {
    const questionBtn = item.querySelector('.faq-question');
    if (questionBtn) {
      questionBtn.addEventListener('click', () => {
        const isActive = item.classList.contains('active');
        // Close other FAQs
        faqItems.forEach(other => other.classList.remove('active'));
        if (!isActive) {
          item.classList.add('active');
        }
      });
    }
  });

  // 4. Testimonials Slider
  const testimonialCards = document.querySelectorAll('.testimonial-card');
  const prevBtn = document.getElementById('testimPrev');
  const nextBtn = document.getElementById('testimNext');
  let currentTestim = 0;
  let testimInterval;

  function showTestimonial(index) {
    if (testimonialCards.length === 0) return;
    testimonialCards.forEach((card, i) => {
      card.classList.toggle('active', i === index);
    });
    currentTestim = index;
  }

  function nextTestimonial() {
    let nextIndex = (currentTestim + 1) % testimonialCards.length;
    showTestimonial(nextIndex);
  }

  function prevTestimonial() {
    let prevIndex = (currentTestim - 1 + testimonialCards.length) % testimonialCards.length;
    showTestimonial(prevIndex);
  }

  if (testimonialCards.length > 0) {
    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        nextTestimonial();
        resetTestimAuto();
      });
    }
    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        prevTestimonial();
        resetTestimAuto();
      });
    }

    function startTestimAuto() {
      testimInterval = setInterval(nextTestimonial, 7000);
    }
    function resetTestimAuto() {
      clearInterval(testimInterval);
      startTestimAuto();
    }
    startTestimAuto();
  }

  // 5. AJAX Contact Form Submission
  const contactForms = document.querySelectorAll('form[data-ajax="true"]');
  contactForms.forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const submitBtn = form.querySelector('button[type="submit"]');
      const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
      const responseBox = form.querySelector('.form-response-alert');

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>Ուղարկվում է...</span>';
      }

      if (responseBox) {
        responseBox.style.display = 'none';
        responseBox.className = 'form-response-alert alert';
      }

      const formData = new FormData(form);
      const url = form.getAttribute('action') || '/contact';

      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          },
          body: formData,
        });

        const data = await response.json();

        if (response.ok && data.success) {
          if (responseBox) {
            responseBox.classList.add('alert-success');
            responseBox.innerHTML = `<strong>✓</strong> ${data.message || 'Շնորհակալություն։ Ձեր հաղորդագրությունը հաջողությամբ ուղարկվեց։'}`;
            responseBox.style.display = 'flex';
          } else {
            alert(data.message || 'Շնորհակալություն։ Ձեր հաղորդագրությունը հաջողությամբ ուղարկվեց։');
          }
          form.reset();

          // If inside a modal, close after 2.5s
          const modalBackdrop = form.closest('.modal-backdrop');
          if (modalBackdrop) {
            setTimeout(() => {
              modalBackdrop.classList.remove('open');
              if (responseBox) responseBox.style.display = 'none';
            }, 2500);
          }
        } else {
          let errorMsg = 'Տեղի ունեցավ սխալ։ Խնդրում ենք ստուգել լրացված տվյալները։';
          if (data.errors) {
            errorMsg = Object.values(data.errors).flat().join('<br>');
          } else if (data.message) {
            errorMsg = data.message;
          }
          if (responseBox) {
            responseBox.classList.add('alert-error');
            responseBox.innerHTML = `<strong>✕</strong> ${errorMsg}`;
            responseBox.style.display = 'flex';
          } else {
            alert(errorMsg);
          }
        }
      } catch (err) {
        if (responseBox) {
          responseBox.classList.add('alert-error');
          responseBox.innerHTML = '<strong>✕</strong> Ցանցային սխալ։ Խնդրում ենք փորձել կրկին։';
          responseBox.style.display = 'flex';
        }
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
        }
      }
    });
  });

  // 6. Quick Service Order Modal
  const orderModal = document.getElementById('orderModal');
  const orderModalClose = document.getElementById('orderModalClose');
  const openModalBtns = document.querySelectorAll('[data-open-modal="orderModal"]');

  if (orderModal) {
    openModalBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const serviceName = btn.getAttribute('data-service') || 'Web Development';
        const projectTypeInput = orderModal.querySelector('select[name="project_type"]') || orderModal.querySelector('input[name="project_type"]');
        if (projectTypeInput) {
          projectTypeInput.value = serviceName;
        }
        orderModal.classList.add('open');
      });
    });

    if (orderModalClose) {
      orderModalClose.addEventListener('click', () => {
        orderModal.classList.remove('open');
      });
    }

    orderModal.addEventListener('click', (e) => {
      if (e.target === orderModal) {
        orderModal.classList.remove('open');
      }
    });
  }
});
