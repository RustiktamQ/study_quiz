import { ApiController } from "/scripts/libs/apiController.js";

const tabsContainer = document.getElementById('grade-tabs');
const tabButtons = tabsContainer.querySelectorAll('.tab-button');
const contentArea = document.getElementById('tab-content-area');
const skillsContainer = document.getElementById('skills-container');
const loadingIndicator = document.getElementById('loading-indicator');

const apiController = new ApiController();

function getGradeContent(data) {
    let subjectsMap = {};

    data.forEach(item => {
        if (!subjectsMap[item.subject]) {
            subjectsMap[item.subject] = [];
        }
        subjectsMap[item.subject].push(item);
    });

    let subjectSections = '';

    Object.keys(subjectsMap).forEach(subject => {
        let skills = subjectsMap[subject].map(item => {
            return `
                <a href="/quiz/${item.id}" class="skill-link flex items-center justify-between py-2 px-3 rounded-md text-gray-700 font-medium text-sm">
                    <span class="flex items-center gap-2">
                        <span class="font-mono text-xs bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">${item.id}.${item.grade}</span>
                        <span>${item.quiz}</span>
                    </span>
                    <span class="status-icon text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </a>`;
        }).join('');

        subjectSections += `
            <section class="subject-section space-y-6">
                <h2 class="text-2xl font-semibold text-gray-800 border-b-2 border-blue-400 pb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 4a1 1 0 100 2H7a1 1 0 100-2h6zm-3 4a1 1 0 100 2H7a1 1 0 100-2h3z" clip-rule="evenodd" />
                    </svg>
                    ${subject}
                </h2>
                <div class="skill-category space-y-2">
                    <h3 class="skill-category-heading text-sm font-semibold text-indigo-700 uppercase tracking-wider py-1 px-3 rounded-md inline-block">Core Concepts</h3>
                    ${skills}
                </div>
            </section>`;
    });

    return `<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-10">${subjectSections}</div>`;
}


function showLoading(isLoading) {
    if (isLoading) {
        skillsContainer.innerHTML = '';
        loadingIndicator.classList.remove('hidden');
        contentArea.classList.add('loading');
    } else {
        loadingIndicator.classList.add('hidden');
        contentArea.classList.remove('loading');
    }
}

async function loadGradeContent(grade) {
    if (!grade) return;

    showLoading(true);
    tabButtons.forEach(t => t.classList.remove('active'));
    const currentTab = tabsContainer.querySelector(`.tab-button[data-grade="${grade}"]`);
    if (currentTab) {
        currentTab.classList.add('active');
    }

    await new Promise(resolve => setTimeout(resolve, 300 + Math.random() * 400));
    const data = await apiController.getGradeQuizzes(grade);

    if (data.error) {
        showLoading(false);
        return;
    }

    const contentHTML = getGradeContent(data);
    skillsContainer.innerHTML = contentHTML;
    showLoading(false);
}

tabsContainer.addEventListener('click', (e) => {
    const targetButton = e.target.closest('.tab-button');
    if (targetButton && !targetButton.classList.contains('cursor-not-allowed')) {
        const grade = targetButton.getAttribute('data-grade');
        loadGradeContent(grade);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    loadGradeContent(1);
});


/// search logic
const searchInput = document.getElementById('skill-search-input');
function handleSkillSearch() {
    const searchTerm = searchInput.value.trim().toLowerCase();
    const skillLinks = document.querySelectorAll('.skill-link');

    skillLinks.forEach(link => {
        const skillText = link.textContent.toLowerCase();
        link.style.display = skillText.includes(searchTerm) ? 'flex' : 'none';
    });
}

let searchTimeout;
searchInput.addEventListener('input', (e) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => handleSkillSearch(), 300);
});

document.querySelectorAll('.tab-button').forEach(btn => {
    btn.addEventListener('click', () => {
        searchInput.value = '';
        handleSkillSearch();
    });
});

const tabs = document.getElementsByClassName('tab-button');
tabs[0].classList.add('text-green-500', 'border-b-2', 'border-green-500', 'font-semibold');
Array.from(tabs).forEach(btn => {
    btn.addEventListener('click', function () {
        Array.from(tabs).forEach(btn => {
            btn.classList.remove('text-green-500', 'border-b-2', 'border-green-500', 'font-semibold');
        })
        this.classList.add('text-green-500', 'border-b-2', 'border-green-500', 'font-semibold');
    });
});
