document.addEventListener('DOMContentLoaded', () => {
            const sidebarLinks = document.querySelectorAll('.sidebar nav ul li a');
            const sections = document.querySelectorAll('.content-section');
            const menuToggle = document.querySelector('.menu-toggle');
            const sidebar = document.querySelector('.sidebar');

            // Function to activate the correct section
            function showSection(id) {
                sections.forEach(section => {
                    section.classList.remove('active');
                });
                // Only activate if the section exists
                const targetSection = document.getElementById(id);
                if (targetSection) {
                    targetSection.classList.add('active');
                }


                sidebarLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${id}`) {
                        link.classList.add('active');
                    }
                });

                // Scroll to the top of the activated section if on mobile
                if (window.innerWidth <= 768) {
                    const mainContent = document.querySelector('.content');
                    if (mainContent) {
                        mainContent.scrollTop = 0;
                    }
                }
            }

            // Event listeners for the sidebar menu
            sidebarLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const targetId = link.getAttribute('href').substring(1);
                    showSection(targetId);
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('active'); // Close sidebar on mobile
                    }
                });
            });

            // Activate the 'moradores' section by default
            showSection('moradores');

            // Toggle sidebar for mobile
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });

            // Close sidebar when clicking outside (mobile)
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !menuToggle.contains(e.target) && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            });
        });

document.getElementById('cadastrar_morador').addEventListener('click', function() {
    document.getElementById('cadastro-popup-morador').style.display = 'flex';
});

document.getElementById('fechar-popup-morador').addEventListener('click', function() {
    document.getElementById('cadastro-popup-morador').style.display = 'none';
});

document.getElementById('cadastrar_ata').addEventListener('click', function() {
    document.getElementById('cadastro-popup-atas').style.display = 'flex';
});

document.getElementById('fechar-popup-atas').addEventListener('click', function() {
    document.getElementById('cadastro-popup-atas').style.display = 'none';
});

document.querySelectorAll('button[aria-label="Excluir Morador"]').forEach(btn => {
    btn.addEventListener('click', function() {
        const tr = this.closest('tr');
        if (tr) {
            tr.remove();
        }
    });
});



