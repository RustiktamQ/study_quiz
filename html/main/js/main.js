document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('header');
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    // const menuIcon = document.querySelector('.menu-icon'); // Not used directly in this block if toggleMobileMenu is self-contained
    const barTop = document.querySelector('.bar-top');
    const barMiddle = document.querySelector('.bar-middle');
    const barBottom = document.querySelector('.bar-bottom');
    const navLinks = document.querySelectorAll('.nav-link');

    // let headerVisible = true; // Not used in provided code

    const currentPath = window.location.pathname;
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        // Assuming ROOT_URL is defined elsewhere or this part is for a specific PHP setup
        // For a pure JS context, adjust this comparison if needed.
        if (href && currentPath.includes(href.replace('<?= ROOT_URL ?>', '/'))) {
            link.classList.add('text-green-600');
            const underline = link.querySelector('.absolute');
            if (underline) {
                underline.classList.remove('w-0');
                underline.classList.add('w-full');
            }
        }
    });

    function toggleMobileMenu() {
        if (!mobileMenuButton || !mobileMenu) return; // Guard
        const isOpen = mobileMenuButton.getAttribute('aria-expanded') === 'true';

        if (isOpen) {
            mobileMenuButton.setAttribute('aria-expanded', 'false');
            mobileMenu.style.maxHeight = '0';
            if (barTop && barMiddle && barBottom) gsapMenuIconToHamburger();
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
            }, 300);
        } else {
            mobileMenuButton.setAttribute('aria-expanded', 'true');
            mobileMenu.classList.remove('hidden');
            setTimeout(() => {
                const height = mobileMenu.scrollHeight;
                mobileMenu.style.maxHeight = `${height}px`;
            }, 10);
            if (barTop && barMiddle && barBottom) gsapMenuIconToX();
        }
    }

    function gsapMenuIconToX() {
        if (!barTop || !barMiddle || !barBottom) return;
        barTop.setAttribute('d', 'M6 6L18 18');
        barMiddle.style.opacity = '0';
        barBottom.setAttribute('d', 'M6 18L18 6');
    }

    function gsapMenuIconToHamburger() {
        if (!barTop || !barMiddle || !barBottom) return;
        barTop.setAttribute('d', 'M4 6h16');
        barMiddle.style.opacity = '1';
        barBottom.setAttribute('d', 'M4 18h16');
    }

    function handleScroll() {
        // const currentScroll = window.pageYOffset || document.documentElement.scrollTop; // Not used
    }

    navLinks.forEach(link => {
        const underline = link.querySelector('.absolute');
        if (!underline) return;

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

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', toggleMobileMenu);
    }
    window.addEventListener('scroll', handleScroll);
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768 && mobileMenu && mobileMenuButton) {
            mobileMenu.style.maxHeight = '';
            mobileMenuButton.setAttribute('aria-expanded', 'false');
            if (barTop && barMiddle && barBottom) {
                gsapMenuIconToHamburger();
            }
        }
    });

    handleScroll(); // Call once for initial setup if needed
});

window.addEventListener('load', () => {
    if (typeof quicklink !== 'undefined') {
        quicklink.listen();
    }
    hidePreloader();
});
document.documentElement.classList.replace('no-js', 'js');

function initPreloader() {
    const preloader = document.getElementById('preloader');
    if (!preloader) return;

    preloader.style.position = 'fixed';
    preloader.style.top = '0';
    preloader.style.left = '0';
    preloader.style.width = '100%';
    preloader.style.height = '100%';
    preloader.style.display = 'flex';
    preloader.style.alignItems = 'center';
    preloader.style.justifyContent = 'center';
    preloader.style.backgroundColor = '#ffffff';
    preloader.style.zIndex = '9999';
    preloader.style.transition = 'opacity 0.5s ease-out';

    const spinner = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    spinner.setAttribute('width', '50');
    spinner.setAttribute('height', '50');
    spinner.setAttribute('viewBox', '0 0 50 50');

    const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
    circle.setAttribute('cx', '25');
    circle.setAttribute('cy', '25');
    circle.setAttribute('r', '20');
    circle.setAttribute('fill', 'none');
    circle.setAttribute('stroke', '#16a34a');
    circle.setAttribute('stroke-width', '4');
    circle.setAttribute('stroke-linecap', 'round');

    const animateTransform = document.createElementNS('http://www.w3.org/2000/svg', 'animateTransform');
    animateTransform.setAttribute('attributeName', 'transform');
    animateTransform.setAttribute('type', 'rotate');
    animateTransform.setAttribute('repeatCount', 'indefinite');
    animateTransform.setAttribute('dur', '1s');
    animateTransform.setAttribute('from', '0 25 25');
    animateTransform.setAttribute('to', '360 25 25');

    const animate = document.createElementNS('http://www.w3.org/2000/svg', 'animate');
    animate.setAttribute('attributeName', 'stroke-dasharray');
    animate.setAttribute('repeatCount', 'indefinite');
    animate.setAttribute('dur', '1.5s');
    animate.setAttribute('values', '0 150;120 150;120 150');
    animate.setAttribute('keyTimes', '0;0.5;1');

    circle.appendChild(animateTransform);
    circle.appendChild(animate);
    spinner.appendChild(circle);
    preloader.appendChild(spinner);
}

function hidePreloader() {
    const preloader = document.getElementById('preloader');
    if (!preloader) return;

    initPageFunctionality();

    preloader.style.opacity = '0';

    setTimeout(() => {
        if (preloader.parentNode) {
            preloader.parentNode.removeChild(preloader);
        }
    }, 600);
}

const swup = new Swup({
    plugins: [new SwupProgressPlugin()]
});

let wordChangeInterval = null;
let pricingInitialized = false;
let beforeAfterToggleInitialized = false;
let currentTextAnimationVisibilityHandler = null;

function cleanupTextAnimationSpecificResources() {
    if (wordChangeInterval) {
        clearInterval(wordChangeInterval);
        wordChangeInterval = null;
    }
    if (currentTextAnimationVisibilityHandler) {
        document.removeEventListener("visibilitychange", currentTextAnimationVisibilityHandler);
        currentTextAnimationVisibilityHandler = null;
    }

    const textElement = document.getElementById("text");
    const aiBadge = document.getElementById("ai-badge");

    if (textElement && typeof gsap !== 'undefined') {
        const letters = textElement.querySelectorAll("span");
        if (letters.length > 0) gsap.killTweensOf(letters);
    }
    if (aiBadge && document.body.contains(aiBadge) && typeof gsap !== 'undefined') {
        gsap.killTweensOf(aiBadge);
    }
}

function cleanupPageResources() {
    cleanupTextAnimationSpecificResources();
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
    // const paidPlanCard = document.getElementById('paid-plan-card'); // Not used in function

    if (!slider || !studentCountDisplay || !monthlyBtn || !annualBtn || !paidPlanPriceDisplay) {
        return;
    }
    if (pricingInitialized) return;

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

        let totalDisplayPrice = currentStudentCount * basePricePerStudent;
        let unitText = '/ месяц';

        if (annualPriceNote) annualPriceNote.textContent = '';

        if (isAnnual) {
            totalDisplayPrice = currentStudentCount * (basePricePerStudent * annualDiscountFactor);
            unitText = '/ месяц (годовая оплата)';
            const totalAnnualCost = totalDisplayPrice * 12;
            if (annualPriceNote) annualPriceNote.textContent = `Итого ${formatPrice(totalAnnualCost)} ₸ в год`;
        }

        if (paidPlanPriceDisplay && typeof gsap !== 'undefined') {
            gsap.to(paidPlanPriceDisplay, {
                duration: 0.3, opacity: 0.5, y: -10, ease: 'power1.out',
                onComplete: () => {
                    paidPlanPriceDisplay.textContent = formatPrice(totalDisplayPrice);
                    gsap.to(paidPlanPriceDisplay, { duration: 0.3, opacity: 1, y: 0, ease: 'power1.out' });
                }
            });
        } else if (paidPlanPriceDisplay) {
            paidPlanPriceDisplay.textContent = formatPrice(totalDisplayPrice);
        }
        if (paidPlanUnitDisplay) paidPlanUnitDisplay.textContent = unitText;
    }

    slider.addEventListener('input', updatePrice);
    monthlyBtn.addEventListener('click', () => {
        if (!isAnnual) return; isAnnual = false;
        monthlyBtn.classList.add('bg-green-500', 'text-white');
        monthlyBtn.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
        annualBtn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
        annualBtn.classList.remove('bg-green-500', 'text-white');
        updatePrice();
    });
    annualBtn.addEventListener('click', () => {
        if (isAnnual) return; isAnnual = true;
        annualBtn.classList.add('bg-green-500', 'text-white');
        annualBtn.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
        monthlyBtn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
        monthlyBtn.classList.remove('bg-green-500', 'text-white');
        updatePrice();
    });
    pricingInitialized = true; updatePrice();
}

function initTextAnimation() {
    if (typeof gsap === 'undefined' || typeof Flip === 'undefined') return;
    gsap.registerPlugin(Flip);

    const textElement = document.getElementById("text");
    const aiBadge = document.getElementById("ai-badge");
    if (!textElement || !aiBadge) return;

    cleanupTextAnimationSpecificResources(); // Clean up any previous instance

    const words = ["Инновационное", "Революционное", "Прогрессивное", "Невероятное", "Увлекательное"];
    let currentIndex = words.indexOf(textElement.textContent.trim());
    if (currentIndex === -1 || textElement.textContent.trim() === "") currentIndex = 0;

    function splitTextIntoLetters(element) {
        const text = element.textContent.trim();
        element.innerHTML = "";
        return text.split("").map(letter => {
            const span = document.createElement("span");
            span.textContent = letter;
            span.style.display = "inline-block";
            span.style.opacity = "0";
            span.style.transform = "translateY(-10px)";
            element.appendChild(span);
            return span;
        });
    }

    function changeWord() {
        if (!textElement || !document.body.contains(textElement) || !aiBadge || !document.body.contains(aiBadge)) {
            if (wordChangeInterval) clearInterval(wordChangeInterval);
            return;
        }
        const currentLetters = Array.from(textElement.querySelectorAll("span"));
        gsap.to(currentLetters, {
            y: 10, opacity: 0, stagger: 0.05, duration: 0.4, ease: "power2.in",
            onComplete: () => {
                if (!textElement || !document.body.contains(textElement) || !aiBadge || !document.body.contains(aiBadge)) return;
                const state = Flip.getState(aiBadge);
                currentIndex = (currentIndex + 1) % words.length;
                textElement.textContent = words[currentIndex];
                if (document.body.contains(aiBadge)) Flip.from(state, { duration: 0.5, ease: "power2.out" });
                const newLetters = splitTextIntoLetters(textElement);
                gsap.to(newLetters, { y: 0, opacity: 1, stagger: 0.05, duration: 0.4, ease: "power2.out", delay: 0.1 });
            }
        });
    }

    function startWordCycle() {
        if (wordChangeInterval) clearInterval(wordChangeInterval);
        if (!document.hidden && textElement && document.body.contains(textElement)) {
            wordChangeInterval = setInterval(changeWord, 3000);
        }
    }

    currentTextAnimationVisibilityHandler = () => {
        if (!textElement || !document.body.contains(textElement)) {
            cleanupTextAnimationSpecificResources(); return;
        }
        if (document.hidden) {
            if (wordChangeInterval) { clearInterval(wordChangeInterval); wordChangeInterval = null; }
            const letters = textElement.querySelectorAll("span");
            if (letters.length > 0) gsap.killTweensOf(letters);
            if (aiBadge && document.body.contains(aiBadge)) gsap.killTweensOf(aiBadge);
        } else {
            if (!textElement || !document.body.contains(textElement) || !aiBadge || !document.body.contains(aiBadge)) {
                cleanupTextAnimationSpecificResources(); return;
            }
            if (wordChangeInterval) { clearInterval(wordChangeInterval); wordChangeInterval = null; }
            gsap.killTweensOf(textElement.querySelectorAll("span"));
            gsap.killTweensOf(aiBadge);

            textElement.textContent = words[currentIndex];
            const staticLetters = splitTextIntoLetters(textElement);
            gsap.set(staticLetters, { y: 0, opacity: 1, clearProps: "transform,opacity" });
            gsap.set(aiBadge, { autoAlpha: 1 });

            requestAnimationFrame(() => {
                if (aiBadge && document.body.contains(aiBadge)) {
                    const state = Flip.getState(aiBadge);
                    Flip.from(state, { duration: 0, ease: "none", immediateRender: true });
                }
                setTimeout(() => {
                    if (document.hidden || !textElement || !document.body.contains(textElement)) return;
                    changeWord();
                    startWordCycle();
                }, 50);
            });
        }
    };
    document.addEventListener("visibilitychange", currentTextAnimationVisibilityHandler);

    textElement.textContent = words[currentIndex];
    const initialLetters = splitTextIntoLetters(textElement);
    gsap.set(aiBadge, { autoAlpha: 1 });
    gsap.to(initialLetters, {
        y: 0, opacity: 1, stagger: 0.05, duration: 0.7, ease: "expo.out", delay: 0.5,
        onComplete: () => { if (!document.hidden) startWordCycle(); }
    });
}

function initBeforeAfterToggle() {
    const btnBefore = document.getElementById('btn-before');
    const btnAfter = document.getElementById('btn-after');
    const contentBefore = document.getElementById('content-before');
    const contentAfter = document.getElementById('content-after');

    if (!btnBefore || !btnAfter || !contentBefore || !contentAfter) return;
    if (beforeAfterToggleInitialized) return;

    const activeBtnClasses = ['bg-green-500', 'text-white'];
    const inactiveBtnClasses = ['bg-white', 'text-gray-700', 'hover:bg-gray-50'];
    const hiddenContentClasses = ['opacity-0', 'scale-95'];
    const visibleContentClasses = ['opacity-100', 'scale-100'];
    const transitionDuration = 300; // milliseconds
    let currentView = 'before';

    function switchView(targetView) {
        if (targetView === currentView) return;
        const btnToShow = targetView === 'before' ? btnBefore : btnAfter;
        const btnToHide = targetView === 'before' ? btnAfter : btnBefore;
        const contentToShow = targetView === 'before' ? contentBefore : contentAfter;
        const contentToHide = targetView === 'before' ? contentAfter : contentBefore;

        contentToHide.classList.remove(...visibleContentClasses);
        contentToHide.classList.add(...hiddenContentClasses);
        btnToShow.classList.remove(...inactiveBtnClasses); btnToShow.classList.add(...activeBtnClasses);
        btnToHide.classList.remove(...activeBtnClasses); btnToHide.classList.add(...inactiveBtnClasses);

        setTimeout(() => {
            contentToHide.classList.add('hidden');
            contentToShow.classList.remove('hidden');
            requestAnimationFrame(() => { // Ensure classes are applied for transition
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

        initialActiveBtn.classList.add(...activeBtnClasses); initialActiveBtn.classList.remove(...inactiveBtnClasses);
        initialInactiveBtn.classList.add(...inactiveBtnClasses); initialInactiveBtn.classList.remove(...activeBtnClasses);
        initialVisibleContent.classList.remove('hidden', ...hiddenContentClasses); initialVisibleContent.classList.add(...visibleContentClasses);
        initialHiddenContent.classList.add('hidden', ...hiddenContentClasses); initialHiddenContent.classList.remove(...visibleContentClasses);
    }
    btnBefore.addEventListener('click', () => switchView('before'));
    btnAfter.addEventListener('click', () => switchView('after'));
    initializeView();
    beforeAfterToggleInitialized = true;
}

function initGeneralAnimations() {
    if (typeof gsap === 'undefined') return;
    gsap.from('.animate-on-load', {
        opacity: 0, y: 30, duration: 0.6, stagger: 0.1, ease: 'power2.out', clearProps: "all"
    });
    const planCards = document.querySelectorAll('.plan-card');
    planCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            if (!card.classList.contains('opacity-50')) {
                gsap.to(card, { scale: 1.03, boxShadow: "0 10px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04)", duration: 0.3, ease: 'power1.out' });
            }
        });
        card.addEventListener('mouseleave', () => {
            gsap.to(card, { scale: 1, boxShadow: card.id === 'paid-plan-card' ? "0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06)" : "0 1px 3px 0 rgba(0,0,0,0.1), 0 1px 2px 0 rgba(0,0,0,0.06)", duration: 0.3, ease: 'power1.out' });
        });
    });
}

swup.hooks.on('visit:start', cleanupPageResources);
swup.hooks.on('content:replace', initPageFunctionality);
document.addEventListener('DOMContentLoaded', () => {
    initPreloader();
});