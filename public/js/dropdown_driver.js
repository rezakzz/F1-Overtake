const driverBtn = document.getElementById('driver-btn');
const dropdown = document.getElementById('driver-dropdown');
const driverItems = dropdown.querySelectorAll('li');

const closeTeamsDropdown = () => {
    const teamsDropdown = document.getElementById('teams-dropdown');
    if (teamsDropdown) {
        teamsDropdown.style.display = 'none';
    }
};

driverBtn.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();

    const isOpen = dropdown.style.display === 'block';

    if (!isOpen) {
        closeTeamsDropdown(); 
    }

    dropdown.style.display = isOpen ? 'none' : 'block';

    if (!isOpen) {
        
        driverItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(-10px)';
            item.style.animation = 'none';

            setTimeout(() => {
                item.style.animation = `fadeDown 0.4s ease forwards`;
                item.style.animationDelay = `${index * 0.1}s`; 
            }, 50);
        });
    }
});


window.addEventListener('click', (e) => {
    if (!dropdown.contains(e.target) && e.target !== driverBtn) {
        dropdown.style.display = 'none';
    }
});