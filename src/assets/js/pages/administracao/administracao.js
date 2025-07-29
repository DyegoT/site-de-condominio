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
        document.getElementById('toggle-theme').addEventListener('click', function() {
            document.body.classList.toggle('tema-escuro');
            // Troca o ícone do botão
            const icon = this.querySelector('i');
            if(document.body.classList.contains('tema-escuro')) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
        });
            document.addEventListener('DOMContentLoaded', function() {
            const cadastrarMoradorBtn = document.getElementById('cadastrar_morador');
            const cadastroPopup = document.getElementById('cadastro-popup');
            const fecharPopupBtn = document.getElementById('fechar-popup');
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            const popupContent = document.querySelector('.popup-content');

            // Elements for pet quantity
            const possuiPetCheckbox = document.getElementById('possui-pet');
            const quantidadePetsInput = document.getElementById('quantidade-pets');

            // Elements for vehicle quantity
            const possuiVeiculoCheckbox = document.getElementById('possui-veiculo');
            const quantidadeVeiculosInput = document.getElementById('quantidade-veiculos');

            // Initial state for pet quantity input
            if (!possuiPetCheckbox.checked) {
                quantidadePetsInput.setAttribute('disabled', 'true');
                quantidadePetsInput.value = 0;
            }

            // Initial state for vehicle quantity input
            if (!possuiVeiculoCheckbox.checked) {
                quantidadeVeiculosInput.setAttribute('disabled', 'true');
                quantidadeVeiculosInput.value = 0;
            }

            // Event listener for pet checkbox
            possuiPetCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    quantidadePetsInput.removeAttribute('disabled');
                    quantidadePetsInput.value = 1; // Default to 1 if checked
                } else {
                    quantidadePetsInput.setAttribute('disabled', 'true');
                    quantidadePetsInput.value = 0; // Reset to 0 if unchecked
                }
            });

            // Event listener for vehicle checkbox
            possuiVeiculoCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    quantidadeVeiculosInput.removeAttribute('disabled');
                    quantidadeVeiculosInput.value = 1; // Default to 1 if checked
                } else {
                    quantidadeVeiculosInput.setAttribute('disabled', 'true');
                    quantidadeVeiculosInput.value = 0; // Reset to 0 if unchecked
                }
            });


            cadastrarMoradorBtn.addEventListener('click', function() {
                cadastroPopup.style.display = 'flex';
            });

            fecharPopupBtn.addEventListener('click', function() {
                cadastroPopup.style.display = 'none';
            });

            // Fechar pop-up ao clicar fora do popup-content
            cadastroPopup.addEventListener('click', function(event) {
                if (!popupContent.contains(event.target) && event.target === cadastroPopup) {
                    cadastroPopup.style.display = 'none';
                }
            });

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));

                    this.classList.add('active');
                    const targetTab = this.dataset.tab;
                    document.getElementById(targetTab).classList.add('active');
                });
            });

            // Handle file upload button clicks for all file inputs
            document.querySelectorAll('.file-upload-wrapper .btn-outline-secondary').forEach(button => {
                button.addEventListener('click', function() {
                    // Find the closest input[type="file"]
                    const fileInput = this.closest('.file-upload-wrapper').querySelector('input[type="file"]');
                    if (fileInput) {
                        if (this.textContent.includes('Carregar')) {
                            fileInput.click();
                        } else if (this.textContent.includes('Remover')) {
                            fileInput.value = ''; // Clear the selected file
                            // Optionally, update UI to show no file selected or a default preview
                        }
                    }
                });
            });

            document.querySelectorAll('.document-actions .upload-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const fileInput = this.closest('.document-actions').querySelector('input[type="file"]');
                    if (fileInput) {
                        fileInput.click();
                    }
                });
            });

            document.querySelectorAll('.document-actions .remove-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const fileInput = this.closest('.document-actions').querySelector('input[type="file"]');
                    if (fileInput) {
                        fileInput.value = ''; // Clear the selected file
                        // Optionally, update the image preview to a default or blank state
                        const previewImg = this.closest('.document-upload-container').querySelector('.document-preview img');
                        if (previewImg) {
                            previewImg.src = "https://via.placeholder.com/150x100?text=Preview"; // Set to a default placeholder
                        }
                    }
                });
            });

            // Optional: Preview selected image for document uploads
            document.querySelectorAll('.document-upload-container input[type="file"]').forEach(input => {
                input.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const previewImg = this.closest('.document-upload-container').querySelector('.document-preview img');
                            if (previewImg) {
                                previewImg.src = e.target.result;
                            }
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                });
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
