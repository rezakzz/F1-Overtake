const teamsBtn = document.getElementById('teams-btn');
const teamsDropdown = document.getElementById('teams-dropdown');
const teamsItems = teamsDropdown.querySelectorAll('li');


const closeDriverDropdown = () => {
    const driverDropdown = document.getElementById('driver-dropdown');
    if (driverDropdown) {
        driverDropdown.style.display = 'none';
    }
};

teamsBtn.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();

    const isOpen = teamsDropdown.style.display === 'block';

    if (!isOpen) {
        closeDriverDropdown();
    }
    
    teamsDropdown.style.display = isOpen ? 'none' : 'block';

    if (!isOpen) {
        teamsItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(-10px)';
            item.style.animation = 'none';

            setTimeout(() => {
                item.style.animation = `fadeDown 0.4s ease forwards`;
                item.style.animationDelay = `${index * 0.05}s`; 
            }, 50);
        });
    }
});

window.addEventListener('click', (e) => {
    if (!teamsDropdown.contains(e.target) && e.target !== teamsBtn) {
        teamsDropdown.style.display = 'none';
    }
});