function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  }
  
  function openSearchModal() {
    const modal = document.getElementById('search-modal');
    if (modal) {
      modal.style.display = 'block';
      setTimeout(() => document.getElementById('search-input')?.focus(), 100);
    }
  }
  
  function closeSearchModal() {
    const modal = document.getElementById('search-modal');
    if (modal) modal.style.display = 'none';
  }
  
  function openCartModal() {
    const modal = document.getElementById('cart-modal');
    if (modal) {
      modal.style.display = 'block';
      if (typeof fetchCartData === 'function') fetchCartData();
    }
  }
  
  function closeCartModal() {
    const modal = document.getElementById('cart-modal');
    if (modal) modal.style.display = 'none';
  }
  
  function addToCart(productId, qty = 1) {
    const loginModal = document.getElementById('login-modal');
  
    fetch('/cart/add', {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken(),
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify({
        product_id: productId,
        quantity: qty
      }),
    })
      .then(async (res) => {
        if (res.status === 401 || res.redirected) {
          if (loginModal) loginModal.style.display = 'block';
          return null;
        }
        return res.json();
      })
      .then((data) => {
        if (!data) return;
  
        if (data.success) {
          // ✅ NOTIFIKASI SEPERTI "HAPUS ITEM"
          alert(data.message || 'Produk berhasil masuk keranjang! 🛒');
          fetchCartData(); // tetap refresh cart
        } else {
          alert(data.message || 'Gagal menambahkan ke keranjang');
        }
      })
      .catch((err) => {
        console.error(err);
        alert('Terjadi kesalahan saat menambahkan ke keranjang');
      });
  }
  
  window.onclick = function (event) {
    const searchModal = document.getElementById('search-modal');
    const cartModal = document.getElementById('cart-modal');
    const loginModal = document.getElementById('login-modal');
  
    if (event.target === searchModal) closeSearchModal();
    if (event.target === cartModal) closeCartModal();
    if (event.target === loginModal && typeof closeModal === 'function') closeModal();
  };
  
  document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const resultsContainer = document.getElementById('search-results-container');
    const searchForm = document.getElementById('search-form');
    let timeout = null;
  
    if (searchForm) searchForm.addEventListener('submit', (e) => e.preventDefault());
  
    if (searchInput && resultsContainer) {
      searchInput.addEventListener('keyup', function () {
        const query = searchInput.value;
        clearTimeout(timeout);
  
        if (query.length === 0) {
          resultsContainer.innerHTML = '<div class="search-tip">Masukkan kata kunci...</div>';
          return;
        }
  
        timeout = setTimeout(() => performSearch(query), 300);
      });
    }
  
    function performSearch(query) {
      resultsContainer.innerHTML = '<div class="search-tip">Sedang mencari...</div>';
      fetch(`/search?query=${encodeURIComponent(query)}`)
        .then((res) => res.json())
        .then((data) => renderSearchResults(data))
        .catch((err) => {
          console.error(err);
          resultsContainer.innerHTML = '<div class="search-tip text-danger">Error memuat data.</div>';
        });
    }
  
    function renderSearchResults(data) {
      resultsContainer.innerHTML = '';
      if (!data || data.length === 0) {
        resultsContainer.innerHTML = '<div class="search-tip">Tidak ada hasil ditemukan.</div>';
        return;
      }
  
      data.forEach((item) => {
        const html = `
          <a href="${item.url}" style="text-decoration: none; color: inherit; display: block;">
            <div style="display:flex; align-items:center; padding:10px; border-bottom:1px solid #333; gap:15px;">
              <img src="${item.image}" style="width:50px; height:50px; object-fit:contain; background:#fff; border-radius:4px;">
              <div style="flex:1;">
                <h5 style="margin:0; font-size:1rem; font-weight:bold; color:white;">${item.name}</h5>
                <small style="color:#aaa;">${item.desc}</small>
              </div>
              <span style="font-size:.7rem; padding:2px 6px; background:#e10600; color:white; border-radius:4px;">${item.type}</span>
            </div>
          </a>`;
        resultsContainer.insertAdjacentHTML('beforeend', html);
      });
    }
  
    window.fetchCartData = function () {
      const cartContainer = document.getElementById('cart-items-container');
      const cartTotalEl = document.querySelector('.cart-summary .item-price');
      const loginModal = document.getElementById('login-modal');
  
      if (!cartContainer) return;
  
      cartContainer.innerHTML = '<p style="text-align:center; padding:20px;">Memuat keranjang...</p>';
  
      fetch('/cart/view', {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
      })
        .then(async (res) => {
          // kalau belum login (karena route /cart/view pakai middleware auth)
          if (res.status === 401 || res.redirected) {
            closeCartModal();
            if (loginModal) loginModal.style.display = 'block';
            return null;
          }
  
          const ct = res.headers.get('content-type') || '';
          if (!ct.includes('application/json')) {
            // ini yang sebelumnya bikin kamu selalu dianggap belum login,
            // karena controller return HTML view.
            console.error('Response /cart/view bukan JSON:', await res.text());
            return null;
          }
  
          return await res.json();
        })
        .then((data) => {
          if (!data) {
            cartContainer.innerHTML = '<p style="text-align:center; padding:20px;">Gagal memuat keranjang.</p>';
            return;
          }
  
          const items = data.items || [];
          const total = data.total || 'Rp 0';
          renderCartItems(items, cartContainer);
          if (cartTotalEl) cartTotalEl.innerText = total;
        })
        .catch((err) => {
          console.error('Error /cart/view:', err);
          cartContainer.innerHTML = '<p style="text-align:center; padding:20px;">Gagal memuat keranjang.</p>';
        });
    };
  
    function renderCartItems(items, container) {
        container.innerHTML = '';
    
        if (!items || items.length === 0) {
            container.innerHTML =
                '<p style="text-align:center; padding:20px;">Keranjang kamu masih kosong.</p>';
            return;
        }
    
        items.forEach(item => {
            const html = `
            <div class="cart-item" style="
                display:flex;
                gap:15px;
                margin-bottom:15px;
                border-bottom:1px solid #333;
                padding-bottom:10px;
                align-items:center;
            ">
                <img src="${item.image}" style="
                    width:70px;
                    height:70px;
                    object-fit:cover;
                    border-radius:6px;
                    background:#fff;
                ">
    
                <div style="flex:1;">
                    <h4 style="margin:0;color:#fff;">${item.name}</h4>
    
                    <div class="qty-control">
                        <span class="qty-btn" onclick="updateQty(${item.id}, ${item.qty - 1})">−</span>
                        <span class="qty-box">${item.qty}</span>
                        <span class="qty-btn" onclick="updateQty(${item.id}, ${item.qty + 1})">+</span>
                    </div>
                </div>
    
                <div style="text-align:right;">
                    <div style="font-weight:bold;color:#e10600;">
                        ${item.price}
                    </div>
                    <button onclick="removeItem(${item.id})"
                        style="background:none;border:none;color:#999;font-size:.8rem;">
                        Hapus
                    </button>
                </div>
            </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        });
    }
  
    window.removeItem = function (id) {
      if (!confirm('Hapus item ini dari keranjang?')) return;
  
      fetch('/cart/remove', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': getCsrfToken(),
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ id }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            fetchCartData();
          } else {
            alert(data.message || 'Gagal menghapus item.');
          }
        })
        .catch((err) => console.error('Error /cart/remove:', err));
    };

    window.updateQty = function (id, qty) {
        if (qty < 1) return;
    
        fetch('/cart/update', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ id, qty })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                fetchCartData(); // refresh keranjang
            } else {
                alert(data.message || 'Gagal update jumlah');
            }
        })
        .catch(err => console.error(err));
    };
    
  });
  