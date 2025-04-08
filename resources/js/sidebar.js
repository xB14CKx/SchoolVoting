document.addEventListener('DOMContentLoaded', function () {
    const openBtn = document.querySelector('.largesb-icon'); // Small sidebar's chevron
    const closeBtn = document.getElementById('closeSidebar');
    const smallSidebar = document.querySelector('.sidebar-small');
    const largeSidebar = document.getElementById('sidebarLarge');
    const mainContent = document.getElementById('mainContent');

    // Debugging: Check if elements are found
    console.log('openBtn:', openBtn);
    console.log('closeBtn:', closeBtn);
    console.log('smallSidebar:', smallSidebar);
    console.log('largeSidebar:', largeSidebar);
    console.log('mainContent:', mainContent);

    if (openBtn) {
        openBtn.addEventListener('click', () => {
            if (smallSidebar) smallSidebar.style.display = 'none';
            if (largeSidebar) largeSidebar.classList.add('show');
            if (mainContent) mainContent.style.marginLeft = '250px'; // Adjust to match sidebar width
        });
    } else {
        console.error('Open button (.largesb-icon) not found in the DOM.');
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            if (smallSidebar) smallSidebar.style.display = 'block';
            if (largeSidebar) largeSidebar.classList.remove('show');
            if (mainContent) mainContent.style.marginLeft = '0px'; // Reset margin
        });
    } else {
        console.error('Close button (#closeSidebar) not found in the DOM.');
    }
});
