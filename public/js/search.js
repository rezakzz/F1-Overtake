window.openCartModal = function() {
    document.getElementById('cart-modal').style.display = 'flex';
}

window.closeCartModal = function() {
    document.getElementById('cart-modal').style.display = 'none';
}

window.openSearchModal = function() {
    document.getElementById('search-modal').style.display = 'flex';
    document.getElementById('search-input').focus();
}

window.closeSearchModal = function() {
    document.getElementById('search-modal').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    window.onclick = function(event) {
        const loginModal = document.getElementById('login-modal');
        const cartModal = document.getElementById('cart-modal'); 
        const searchModal = document.getElementById('search-modal');

        if (event.target === loginModal) {
            window.closeModal(); 
        }
        if (event.target === cartModal) { 
            window.closeCartModal();
        }
        if (event.target === searchModal) { 
            window.closeSearchModal();
        }
    };
});

document.addEventListener('DOMContentLoaded', () => {
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(event) {
            event.preventDefault(); 
            const query = document.getElementById('search-input').value.toLowerCase();
            const resultsContainer = document.getElementById('search-results-container');
            
            resultsContainer.innerHTML = ''; 

            if (query.length < 3) {
                resultsContainer.innerHTML = '<div class="search-tip">Masukkan minimal 3 karakter untuk mencari.</div>';
                return;
            }

            const productData = [
                { name: 'Kaus Polo Scuderia Ferrari 2025', team: 'Ferrari', link: 'KatalogItem_Kaus-Polo-Scuderia-Ferrari-2025-Team-Pria.html' },
                { name: 'Topi Max Verstappen 2025', team: 'Red Bull Racing', link: 'KatalogItem_TopiMax.html' },
                { name: '2025 George Russell W16 Model', team: 'Mercedes', link: 'KatalogItem_Mercedesw16.html' },
                { name: 'Puma 2025 Team Softshell Jacket', team: 'Mercedes', link: 'KatalogItem_Puma2025TeamSoftshellJacket.html' },
                { name: 'Kaus McLaren Lando Norris', team: 'McLaren', link: '#' },
            ];

            const filteredResults = productData.filter(item => 
                item.name.toLowerCase().includes(query) || 
                item.team.toLowerCase().includes(query)
            );

            if (filteredResults.length > 0) {
                filteredResults.forEach(item => {
                    const resultDiv = document.createElement('div');
                    resultDiv.classList.add('search-result-item');
                    resultDiv.innerHTML = `
                        <p><strong>${item.team}</strong>: ${item.name}</p>
                        <a href="${item.link}" class="result-link">Lihat Produk</a>
                    `;
                    resultsContainer.appendChild(resultDiv);
                });
            } else {
                resultsContainer.innerHTML = `<div class="search-tip">Tidak ada hasil ditemukan untuk "<strong>${query}</strong>".</div>`;
            }
        });
    }

    const cartItemsContainer = document.getElementById('cart-items-container');
    if (cartItemsContainer) {
        cartItemsContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item-btn')) {
                e.target.closest('.cart-item').remove();
                alert('Item dihapus dari keranjang (simulasi).');
            }
        });
    }
});


