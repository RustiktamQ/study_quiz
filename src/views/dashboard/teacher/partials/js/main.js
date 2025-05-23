document.addEventListener('DOMContentLoaded', function() {
  const skeletonElements = document.querySelectorAll('.skeleton');
  const chartSkeletons = document.querySelectorAll('.sm-loading');
  const hiddenJsElements = document.querySelectorAll('.hiddenjs');

  function replaceSkeleton(element, contentLoaded = true) {
    if (contentLoaded) {
      element.classList.add('skeleton-fade-out');
      
      setTimeout(() => {
        element.classList.remove('skeleton', 'skeleton-fade-out');
        element.classList.add('hidden');
      }, 500);
    }
  }

  function setHidden(element) {
    element.classList.add('hidden');
  }

  const contentLoadTime = 1500;
  
  setTimeout(() => {
    skeletonElements.forEach(element => {
      replaceSkeleton(element);
    });
  }, contentLoadTime);
  
  window.addEventListener('load', () => {
    // Handle chart skeletons
    setTimeout(() => {
      chartSkeletons.forEach(element => {
        replaceSkeleton(element);
        setHidden(element);
      });
    }, 500);
    
    // Handle hiddenjs elements
    hiddenJsElements.forEach(element => {
      setHidden(element);
    });
  });
});

document.addEventListener('DOMContentLoaded', () => {
  // --- Elements ---
  const sidebar = document.getElementById('sidebar');
  const sidebarHeader = document.getElementById('sidebar-header');
  const sidebarLogoFull = document.getElementById('sidebar-logo-full');
  const sidebarLogoIcon = document.getElementById('sidebar-logo-icon');
  const sidebarPromo = document.getElementById('sidebar-promo');
  const sidebarMenuItemsText = document.querySelectorAll('.menu-item-text');
  const sidebarMenuDropdowns = document.querySelectorAll('.menu-dropdown');
  const sidebarArrows = document.querySelectorAll('.js-sidebar-arrow');

  const hamburgerToggle = document.getElementById('hamburger-toggle');
  const hamburgerLgIcon = document.getElementById('hamburger-lg-icon');
  const hamburgerOpenIcon = document.getElementById('hamburger-open-icon');

  const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
  const mobileMenuContent = document.getElementById('mobile-menu-content');

  const notificationButton = document.getElementById('notification-button');
  const notificationDropdown = document.getElementById('notification-dropdown');
  const notificationCloseButton = document.getElementById('notification-close-button');

  const userButton = document.getElementById('user-button');
  const userDropdown = document.getElementById('user-dropdown');
  const userChevron = document.getElementById('user-chevron');

  const accordionTriggers = document.querySelectorAll('[data-accordion-trigger]');
  let isSidebarCollapsed = false; // Initial state assumption

  // Animation duration (should match Tailwind duration)
  const animationDuration = 300; // e.g., duration-300

  // --- Functions ---

  // Function to toggle sidebar collapsed state
  const toggleSidebar = () => {
    isSidebarCollapsed = !isSidebarCollapsed;
    sidebar.classList.toggle('-translate-x-full', !isSidebarCollapsed);
    sidebar.classList.toggle('lg:w-[90px]', isSidebarCollapsed);
    sidebar.classList.toggle('w-[290px]', !isSidebarCollapsed);

    sidebarHeader.classList.toggle('justify-center', isSidebarCollapsed);
    sidebarHeader.classList.toggle('justify-between', !isSidebarCollapsed);

    sidebarLogoFull.classList.toggle('hidden', isSidebarCollapsed);
    sidebarLogoIcon.classList.toggle('hidden', !isSidebarCollapsed);
    sidebarLogoIcon.classList.toggle('lg:block', isSidebarCollapsed);

    sidebarMenuItemsText.forEach(text => text.classList.toggle('lg:hidden', isSidebarCollapsed));
    sidebarMenuDropdowns.forEach(ul => ul.classList.toggle('lg:hidden', isSidebarCollapsed));
    sidebarArrows.forEach(arrow => arrow.classList.toggle('lg:hidden', isSidebarCollapsed));

    if (sidebarPromo) {
        sidebarPromo.classList.toggle('lg:hidden', isSidebarCollapsed);
    }

    hamburgerToggle.classList.toggle('bg-gray-100', !isSidebarCollapsed && window.innerWidth < 1024);
    hamburgerToggle.classList.toggle('lg:bg-transparent', true);

     if (isSidebarCollapsed) {
         closeAllAccordions();
     }
  };

  // Function to toggle mobile header menu
  const toggleMobileMenu = () => {
    const isHidden = mobileMenuContent.classList.contains('hidden');
    mobileMenuContent.classList.toggle('hidden');
    mobileMenuContent.classList.toggle('flex', !isHidden);
    mobileMenuToggle.classList.toggle('bg-gray-100');
  };

  // --- Dropdown Logic ---

  // Helper function to close notification dropdown WITH animation
  const closeNotificationDropdown = () => {
      if (!notificationDropdown.classList.contains('hidden')) {
          notificationDropdown.classList.remove('opacity-100', 'scale-100');
          notificationDropdown.classList.add('opacity-0', 'scale-95');
          setTimeout(() => {
              notificationDropdown.classList.add('hidden');
          }, animationDuration); // Use duration from Tailwind
          document.removeEventListener('click', handleOutsideNotification);
      }
  }

  // Helper function to close user dropdown WITH animation
  const closeUserDropdown = () => {
      if (!userDropdown.classList.contains('hidden')) {
          userDropdown.classList.remove('opacity-100', 'scale-100');
          userDropdown.classList.add('opacity-0', 'scale-95');
          if(userChevron) userChevron.classList.remove('rotate-180');
           setTimeout(() => {
               userDropdown.classList.add('hidden');
           }, animationDuration); // Use duration from Tailwind
          document.removeEventListener('click', handleOutsideUser);
      }
  }

  // Click outside handlers using the new close functions
  const handleOutsideNotification = (event) => {
      if (!notificationDropdown.contains(event.target) && !notificationButton.contains(event.target)) {
          closeNotificationDropdown();
      }
  };
  const handleOutsideUser = (event) => {
      if (!userDropdown.contains(event.target) && !userButton.contains(event.target)) {
          closeUserDropdown();
      }
  };

  // --- Accordion Logic ---
  const closeAllAccordions = (exceptTrigger = null) => {
      accordionTriggers.forEach(trigger => {
          if (trigger === exceptTrigger) return;
          const contentId = trigger.dataset.accordionTrigger;
          const content = document.querySelector(`[data-accordion-content="${contentId}"]`);
          const arrow = trigger.querySelector('.js-sidebar-arrow');
          if (content && !content.classList.contains('hidden')) {
              trigger.classList.remove('menu-item-active');
              trigger.classList.add('menu-item-inactive');
              if(arrow) arrow.classList.remove('menu-item-arrow-active');
              if(arrow) arrow.classList.add('menu-item-arrow-inactive');
              content.style.maxHeight = '0px';
              setTimeout(() => {
                  content.classList.add('hidden');
              }, 300);
          }
      });
  }

  const toggleAccordion = (trigger) => {
    const contentId = trigger.dataset.accordionTrigger;
    const content = document.querySelector(`[data-accordion-content="${contentId}"]`);
    const arrow = trigger.querySelector('.js-sidebar-arrow');
    if (!content) return;
    const isOpen = !content.classList.contains('hidden');
    closeAllAccordions(trigger);
    if (isOpen) {
      trigger.classList.remove('menu-item-active');
      trigger.classList.add('menu-item-inactive');
      if(arrow) arrow.classList.remove('menu-item-arrow-active');
      if(arrow) arrow.classList.add('menu-item-arrow-inactive');
      content.style.maxHeight = '0px';
      setTimeout(() => { content.classList.add('hidden'); }, 300);
    } else {
      trigger.classList.remove('menu-item-inactive');
      trigger.classList.add('menu-item-active');
      if(arrow) arrow.classList.remove('menu-item-arrow-inactive');
      if(arrow) arrow.classList.add('menu-item-arrow-active');
      content.classList.remove('hidden');
      content.style.maxHeight = content.scrollHeight + 'px';
    }
  };

  // --- Event Listeners ---

  if (hamburgerToggle) {
    hamburgerToggle.addEventListener('click', (e) => { e.stopPropagation(); toggleSidebar(); });
  }

  if (mobileMenuToggle) {
    mobileMenuToggle.addEventListener('click', (e) => { e.stopPropagation(); toggleMobileMenu(); });
  }

  // --- notification dropdown listener ---
  if (notificationButton && notificationDropdown) {
      notificationButton.addEventListener('click', (event) => {
          event.stopPropagation();
          const isOpen = !notificationDropdown.classList.contains('hidden');

          closeUserDropdown();

          if (isOpen) {
              closeNotificationDropdown();
          } else {
              notificationDropdown.classList.remove('hidden');
              requestAnimationFrame(() => {
                  notificationDropdown.classList.remove('opacity-0', 'scale-95');
                  notificationDropdown.classList.add('opacity-100', 'scale-100');
              });
              setTimeout(() => document.addEventListener('click', handleOutsideNotification), 0);
          }
      });

      if (notificationCloseButton) {
          notificationCloseButton.addEventListener('click', (event) => {
              event.stopPropagation();
              closeNotificationDropdown();
          });
      }
  }

  // --- user dropdown listener ---
  if (userButton && userDropdown) {
      userButton.addEventListener('click', (event) => {
          event.stopPropagation();
          const isOpen = !userDropdown.classList.contains('hidden');

          closeNotificationDropdown();

          if (isOpen) {
              closeUserDropdown();
          } else {
              userDropdown.classList.remove('hidden');
              if(userChevron) userChevron.classList.add('rotate-180');
              requestAnimationFrame(() => {
                  userDropdown.classList.remove('opacity-0', 'scale-95');
                  userDropdown.classList.add('opacity-100', 'scale-100');
              });
              setTimeout(() => document.addEventListener('click', handleOutsideUser), 0);
          }
      });
  }

  // --- sidebar accordion ---
  accordionTriggers.forEach(trigger => {
    trigger.addEventListener('click', (e) => {
      e.preventDefault(); e.stopPropagation();
      if (isSidebarCollapsed && window.innerWidth >= 1024) return;
      toggleAccordion(e.currentTarget);
    });
  });

  // --- click outside for mobile menus ---
  document.addEventListener('click', (e) => {
      if (window.innerWidth < 1024 && !sidebar.contains(e.target) && !hamburgerToggle.contains(e.target) && !sidebar.classList.contains('-translate-x-full')) {
          toggleSidebar();
      }
      if (window.innerWidth < 1024 && !mobileMenuContent.contains(e.target) && !mobileMenuToggle.contains(e.target) && !mobileMenuContent.classList.contains('hidden')) {
          toggleMobileMenu();
      }
  });

  // --- setup ---
  sidebar.classList.add('-translate-x-full');
  sidebar.classList.remove('lg:w-[90px]');
  isSidebarCollapsed = false;

});