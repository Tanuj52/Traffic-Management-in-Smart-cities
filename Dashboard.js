// Example of updating the counts dynamically (replace with real data as needed)
document.getElementById('services-count').innerText = 3; // Example count for Services Requested
document.getElementById('jobs-count').innerText = 2; // Example count for Jobs Applied
document.getElementById('products-count').innerText = 5; // Example count for Products Cart

function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    if (sidebar.style.left === '-250px') {
        sidebar.style.left = '0';
        content.style.marginLeft = '250px';
    } else {
        sidebar.style.left = '-250px';
        content.style.marginLeft = '0';
    }
}

function showContent(section) {
    const sections = document.getElementsByClassName('content-section');
    for (let i = 0; i < sections.length; i++) {
        sections[i].classList.remove('active');
    }
    document.getElementById(section).classList.add('active');
}
