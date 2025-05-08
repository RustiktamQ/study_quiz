document.addEventListener('DOMContentLoaded', function() {
  // Variables
  const header = document.querySelector('header');
  const mobileMenuButton = document.querySelector('.mobile-menu-button');
  const mobileMenu = document.getElementById('mobile-menu');
  const menuIcon = document.querySelector('.menu-icon');
  const barTop = document.querySelector('.bar-top');
  const barMiddle = document.querySelector('.bar-middle');
  const barBottom = document.querySelector('.bar-bottom');
  const navLinks = document.querySelectorAll('.nav-link');
  
  // Initialize header state
  let headerVisible = true;
  
  // Add active class to current page link
  const currentPath = window.location.pathname;
  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (href && currentPath.includes(href.replace('<?= ROOT_URL ?>', '/'))) {
      link.classList.add('text-green-600');
      const underline = link.querySelector('.absolute');
      if (underline) {
        underline.classList.remove('w-0');
        underline.classList.add('w-full');
      }
    }
  });

  // Mobile menu toggle
  function toggleMobileMenu() {
    const isOpen = mobileMenuButton.getAttribute('aria-expanded') === 'true';
    
    if (isOpen) {
      // Close menu
      mobileMenuButton.setAttribute('aria-expanded', 'false');
      mobileMenu.style.maxHeight = '0';
      
      // Animate to hamburger
      gsapMenuIconToHamburger();
      
      // Hide menu after animation
      setTimeout(() => {
        mobileMenu.classList.add('hidden');
      }, 300);
    } else {
      // Open menu
      mobileMenuButton.setAttribute('aria-expanded', 'true');
      mobileMenu.classList.remove('hidden');
      
      // Set max height to allow animation
      setTimeout(() => {
        const height = mobileMenu.scrollHeight;
        mobileMenu.style.maxHeight = `${height}px`;
      }, 10);
      
      // Animate to X
      gsapMenuIconToX();
    }
  }

  // Menu icon animations using GSAP-like vanilla JS
  function gsapMenuIconToX() {
    // Transform hamburger to X
    barTop.setAttribute('d', 'M6 6L18 18');
    barMiddle.style.opacity = '0';
    barBottom.setAttribute('d', 'M6 18L18 6');
  }

  function gsapMenuIconToHamburger() {
    // Transform X back to hamburger
    barTop.setAttribute('d', 'M4 6h16');
    barMiddle.style.opacity = '1';
    barBottom.setAttribute('d', 'M4 18h16');
  }

  function handleScroll() {
    const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
    
    if (currentScroll > 50) {
      header.classList.add('shadow-md', 'bg-white'); // Пример: добавить тень и фон
    } else {
      header.classList.remove('shadow-md', 'bg-white');
    }
  }
  

  // Link hover effects
  navLinks.forEach(link => {
    const underline = link.querySelector('.absolute');
    
    link.addEventListener('mouseenter', () => {
      if (!link.classList.contains('text-green-600')) {
        underline.style.width = '100%';
      }
    });
    
    link.addEventListener('mouseleave', () => {
      if (!link.classList.contains('text-green-600')) {
        underline.style.width = '0';
      }
    });
  });

  // Event listeners
  mobileMenuButton.addEventListener('click', toggleMobileMenu);
  window.addEventListener('scroll', handleScroll);
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 768) {
      // Reset mobile menu styles on desktop
      mobileMenu.style.maxHeight = '';
      mobileMenuButton.setAttribute('aria-expanded', 'false');
      gsapMenuIconToHamburger();
    }
  });
  
  // Initialize scroll effects
  handleScroll();
});

window.addEventListener('load', () => {quicklink.listen();}); document.documentElement.classList.replace('no-js', 'js');


const swup = new Swup({
  plugins: [new SwupProgressPlugin()]
});

let wordChangeInterval = null;
let pricingInitialized = false;
let beforeAfterToggleInitialized = false;

function cleanupPageResources() {
  if (wordChangeInterval) {
    clearInterval(wordChangeInterval);
    wordChangeInterval = null;
  }
  pricingInitialized = false;
  beforeAfterToggleInitialized = false;
}

function initPageFunctionality() {
  initPricingCalculator();
  initTextAnimation();
  initBeforeAfterToggle();
  initGeneralAnimations();
}

function initPricingCalculator() {
  const slider = document.getElementById('student-slider');
  const studentCountDisplay = document.getElementById('student-count');
  const monthlyBtn = document.getElementById('monthly-btn');
  const annualBtn = document.getElementById('annual-btn');
  const paidPlanPriceDisplay = document.getElementById('paid-plan-price');
  const paidPlanUnitDisplay = document.getElementById('paid-plan-unit');
  const paidPlanStudentsDisplay = document.getElementById('paid-plan-students');
  const annualPriceNote = document.getElementById('annual-price-note');
  const freePlanCard = document.getElementById('free-plan-card');
  const freePlanButton = document.getElementById('free-plan-button');
  const paidPlanCard = document.getElementById('paid-plan-card');

  if (!slider || !studentCountDisplay || !monthlyBtn || !annualBtn || !paidPlanPriceDisplay) {
    return;
  }

  if (pricingInitialized) {
      return;
  }

  const basePricePerStudent = 700;
  const annualDiscountFactor = 0.8;
  const freePlanMaxStudents = 5;
  let isAnnual = annualBtn.classList.contains('bg-green-500');

  function formatPrice(price) {
    return Math.round(price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
  }

  function updatePrice() {
      const currentStudentCount = parseInt(slider.value);
      studentCountDisplay.textContent = currentStudentCount;
      if (paidPlanStudentsDisplay) paidPlanStudentsDisplay.textContent = currentStudentCount;

      if (freePlanCard && freePlanButton) {
          if (currentStudentCount > freePlanMaxStudents) {
              freePlanCard.classList.add('opacity-50', 'pointer-events-none');
              freePlanButton.textContent = `Больше ${freePlanMaxStudents} учеников`;
              freePlanButton.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
              freePlanButton.classList.remove('bg-gray-100', 'text-gray-800', 'hover:bg-gray-200');
          } else {
              freePlanCard.classList.remove('opacity-50', 'pointer-events-none');
              freePlanButton.textContent = 'Начать бесплатно';
              freePlanButton.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
              freePlanButton.classList.add('bg-gray-100', 'text-gray-800', 'hover:bg-gray-200');
          }
      }

      let pricePerStudent = basePricePerStudent;
      let totalDisplayPrice = currentStudentCount * pricePerStudent;
      let unitText = '/ месяц';

      if (annualPriceNote) annualPriceNote.textContent = '';

      if (isAnnual) {
          pricePerStudent = basePricePerStudent * annualDiscountFactor;
          totalDisplayPrice = currentStudentCount * pricePerStudent;
          unitText = '/ месяц (годовая оплата)';
          const totalAnnualCost = totalDisplayPrice * 12;
          if (annualPriceNote) annualPriceNote.textContent = `Итого ${formatPrice(totalAnnualCost)} ₸ в год`;
      }

      if (paidPlanPriceDisplay) {
          gsap.to(paidPlanPriceDisplay, {
              duration: 0.3,
              opacity: 0.5,
              y: -10,
              ease: 'power1.out',
              onComplete: () => {
                  paidPlanPriceDisplay.textContent = formatPrice(totalDisplayPrice);
                  gsap.to(paidPlanPriceDisplay, {
                      duration: 0.3,
                      opacity: 1,
                      y: 0,
                      ease: 'power1.out'
                  });
              }
          });
      }

      if (paidPlanUnitDisplay) {
          paidPlanUnitDisplay.textContent = unitText;
      }
  }

  const handleSliderInput = () => updatePrice();
  const handleMonthlyClick = () => {
      if (!isAnnual) return;
      isAnnual = false;
      monthlyBtn.classList.add('bg-green-500', 'text-white');
      monthlyBtn.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
      annualBtn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
      annualBtn.classList.remove('bg-green-500', 'text-white');
      updatePrice();
  };
  const handleAnnualClick = () => {
      if (isAnnual) return;
      isAnnual = true;
      annualBtn.classList.add('bg-green-500', 'text-white');
      annualBtn.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
      monthlyBtn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
      monthlyBtn.classList.remove('bg-green-500', 'text-white');
      updatePrice();
  };

  slider.addEventListener('input', handleSliderInput);
  monthlyBtn.addEventListener('click', handleMonthlyClick);
  annualBtn.addEventListener('click', handleAnnualClick);

  pricingInitialized = true;

  updatePrice();
}

function initTextAnimation() {
  if (typeof Flip === 'undefined') {
      return;
  }
  gsap.registerPlugin(Flip);

  const textElement = document.getElementById("text");
  const aiBadge = document.getElementById("ai-badge");

  if (!textElement || !aiBadge) {
      return;
  }

  if (wordChangeInterval) {
      clearInterval(wordChangeInterval);
      wordChangeInterval = null;
  }

  const words = [
    "Инновационное",
    "Революционное",
    "Прогрессивное",
    "Невероятное",
    "Увлекательное"
  ];

  let currentIndex = words.indexOf(textElement.textContent.trim());
  if (currentIndex === -1) {
      currentIndex = 0;
      textElement.textContent = words[0];
  }

  function splitTextIntoLetters(element) {
    const text = element.textContent.trim();
    const letters = text.split("");
    element.innerHTML = "";
    letters.forEach(letter => {
      const span = document.createElement("span");
      span.textContent = letter;
      span.style.display = "inline-block";
      span.style.opacity = "0";
      span.style.transform = "translateY(-10px)";
      element.appendChild(span);
    });
    return element.querySelectorAll("span");
  }

  function changeWord() {
    const currentLetters = textElement.querySelectorAll("span");
    gsap.to(currentLetters, {
      y: 10,
      opacity: 0,
      stagger: 0.05,
      duration: 0.4,
      ease: "power2.in",
      onComplete: () => {
        const state = Flip.getState(aiBadge);
        currentIndex = (currentIndex + 1) % words.length;
        textElement.textContent = words[currentIndex];
        Flip.from(state, {
          duration: 0.5,
          ease: "power2.out",
        });
        const newLetters = splitTextIntoLetters(textElement);
        gsap.to(newLetters, {
          y: 0,
          opacity: 1,
          stagger: 0.05,
          duration: 0.4,
          ease: "power2.out",
          delay: 0.1
        });
      }
    });
  }

  const initialLetters = splitTextIntoLetters(textElement);
  gsap.to(initialLetters, {
    y: 0,
    opacity: 1,
    stagger: 0.05,
    duration: 0.7,
    ease: "expo.out",
    delay: 0.5
  });

  gsap.set(aiBadge, { autoAlpha: 1 });

  wordChangeInterval = setInterval(changeWord, 3000);
}

function initBeforeAfterToggle() {
  const btnBefore = document.getElementById('btn-before');
  const btnAfter = document.getElementById('btn-after');
  const contentBefore = document.getElementById('content-before');
  const contentAfter = document.getElementById('content-after');

  if (!btnBefore || !btnAfter || !contentBefore || !contentAfter) {
      return;
  }

  if (beforeAfterToggleInitialized) {
      return;
  }

  const activeBtnClasses = ['bg-green-500', 'text-white'];
  const inactiveBtnClasses = ['bg-white', 'text-gray-700', 'hover:bg-gray-50'];

  const hiddenContentClasses = ['opacity-0', 'scale-95'];
  const visibleContentClasses = ['opacity-100', 'scale-100'];
  const transitionDuration = 300;

  let currentView = 'before';

  function switchView(targetView) {
      if (targetView === currentView) return;

      const btnToShow = targetView === 'before' ? btnBefore : btnAfter;
      const btnToHide = targetView === 'before' ? btnAfter : btnBefore;
      const contentToShow = targetView === 'before' ? contentBefore : contentAfter;
      const contentToHide = targetView === 'before' ? contentAfter : contentBefore;

      contentToHide.classList.remove(...visibleContentClasses);
      contentToHide.classList.add(...hiddenContentClasses);

      btnToShow.classList.remove(...inactiveBtnClasses);
      btnToShow.classList.add(...activeBtnClasses);
      btnToHide.classList.remove(...activeBtnClasses);
      btnToHide.classList.add(...inactiveBtnClasses);

      setTimeout(() => {
          contentToHide.classList.add('hidden');
          contentToShow.classList.remove('hidden');
          requestAnimationFrame(() => {
              contentToShow.classList.remove(...hiddenContentClasses);
              contentToShow.classList.add(...visibleContentClasses);
          });
      }, transitionDuration);

      currentView = targetView;
  }

  function initializeView() {
      const initialActiveBtn = currentView === 'before' ? btnBefore : btnAfter;
      const initialInactiveBtn = currentView === 'before' ? btnAfter : btnBefore;
      const initialVisibleContent = currentView === 'before' ? contentBefore : contentAfter;
      const initialHiddenContent = currentView === 'before' ? contentAfter : contentBefore;

      initialActiveBtn.classList.add(...activeBtnClasses);
      initialActiveBtn.classList.remove(...inactiveBtnClasses);
      initialInactiveBtn.classList.add(...inactiveBtnClasses);
      initialInactiveBtn.classList.remove(...activeBtnClasses);

      initialVisibleContent.classList.remove('hidden', ...hiddenContentClasses);
      initialVisibleContent.classList.add(...visibleContentClasses);

      initialHiddenContent.classList.add('hidden', ...hiddenContentClasses);
      initialHiddenContent.classList.remove(...visibleContentClasses);
  }

  btnBefore.addEventListener('click', () => switchView('before'));
  btnAfter.addEventListener('click', () => switchView('after'));

  initializeView();

  beforeAfterToggleInitialized = true;
}

function initGeneralAnimations() {
  gsap.from('.animate-on-load', {
      opacity: 0,
      y: 30,
      duration: 0.6,
      stagger: 0.1,
      ease: 'power2.out',
      clearProps: "all"
  });

  const planCards = document.querySelectorAll('.plan-card');
  planCards.forEach(card => {
      card.addEventListener('mouseenter', () => {
          if (!card.classList.contains('opacity-50')) {
              gsap.to(card, {
                  scale: 1.03,
                  boxShadow: "0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)",
                  duration: 0.3,
                  ease: 'power1.out'
              });
          }
      });
      card.addEventListener('mouseleave', () => {
          gsap.to(card, {
              scale: 1,
              boxShadow: card.id === 'paid-plan-card'
                         ? "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)"
                         : "0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)",
              duration: 0.3,
              ease: 'power1.out'
          });
      });
  });
}

swup.hooks.on('visit:start', () => {
  cleanupPageResources();
});

swup.hooks.on('content:replace', () => {
  initPageFunctionality();
});

document.addEventListener('DOMContentLoaded', () => {
    initPageFunctionality();
});