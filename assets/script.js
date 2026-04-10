$(function () {

    $('.add-to-cart').on('click', function () {
        const button = $(this);
        const productId = button.data('id');
        const card = button.closest('.card');

        // Simpan teks awal
        const originalText = button.text();

        // Animasi tombol loading
        button
            .prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm"></span>');

        // Animasi card (pulse)
        card.addClass('cart-animate');

        $.ajax({
            url: '../ajax/add-to-cart.php',
            type: 'POST',
            dataType: 'json',
            data: {
                product_id: productId
            },
            success: function (res) {
                if (res.success) {

                    // Animasi badge cart
                    if ($('.cart-count').length) {
                        $('.cart-count')
                            .text(res.cart_count)
                            .addClass('cart-bounce');

                        setTimeout(() => {
                            $('.cart-count').removeClass('cart-bounce');
                        }, 600);
                    }

                    showToast('Produk ditambahkan ke keranjang ✔', 'success');

                } else {
                    showToast(res.message || 'Gagal menambahkan produk', 'danger');
                }
            },
            error: function () {
                showToast('Koneksi bermasalah', 'danger');
            },
            complete: function () {
                setTimeout(() => {
                    button.prop('disabled', false).text(originalText);
                    card.removeClass('cart-animate');
                }, 700);
            }
        });
    });

    // Toast animation
    function showToast(message, type) {
        const toast = $(`
            <div class="cart-toast bg-${type}">
                ${message}
            </div>
        `);

        $('body').append(toast);

        setTimeout(() => toast.addClass('show'), 100);

        setTimeout(() => {
            toast.removeClass('show');
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }

});
