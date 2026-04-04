<script>
    $(document).on('click', '.add-to-cart', function() {
        console.log('123');
        
        let productId = $(this).data('id');

        $.ajax({
            url: "{{ route('cart.store') }}",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                product_id: productId
            },
            success: function(res) {
                if (res.success) {
                    $('#cart-count').text(res.count);
                    Toast.fire({
                        icon: 'success',
                        title: 'Product Added To Cart'
                    });
                }
            }
        });
    });

    function updateQty(id, qty) {
        const btns = $('[data-id="' + id + '"]');
        btns.prop('disabled', true);
        $.ajax({
            url: "/cart-items/" + id,
            type: "PATCH",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                qty: qty
            },
            success: function(res) {
                if (res.success) {
                    $('#qty-' + id).text(res.qty);
                    $('#item-total-' + id).text('$' + Number(res.itemSubtotal).toFixed(2));
                    $('#cart-subtotal').text('$' + Number(res.cartSubtotal).toFixed(2));
                    $('#cart-total').text('$' + Number(res.cartTotal).toFixed(2));
                    $('#cart-count').text(res.cartCount);
                    Toast.fire({
                        icon: 'success',
                        title: 'Product Quantity Updated'
                    });
                }
            },
            complete: function() {
                btns.prop('disabled', false);
            }
        });
    }

    $(document).on('click', '.remove-cart-item', function() {
        let id = $(this).data('id');

        $.ajax({
            url: "/cart-items/" + id,
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(res) {
                if (res.success) {
                    // remove card
                    $('#cart-item-' + id).fadeOut(300, function() {
                        $(this).remove();
                    });

                    // update totals
                    $('#cart-subtotal').text('$' + Number(res.cartSubtotal).toFixed(2));
                    $('#cart-total').text('$' + Number(res.cartTotal).toFixed(2));
                    $('#cart-count').text(res.cartCount);

                    Toast.fire({
                        icon: 'success',
                        title: 'Item removed from cart'
                    });

                    // agar cart empty ho gai
                    if (res.cartCount === 0) {
                        location.reload(); // ya custom empty UI
                    }
                }
            }
        });
    });


    $(document).on('click', '.qty-plus', function() {
        let id = $(this).data('id');
        let qtyEl = $('#qty-' + id);
        let qty = parseInt(qtyEl.text()) + 1;

        updateQty(id, qty);
    });

    $(document).on('click', '.qty-minus', function() {
        let id = $(this).data('id');
        let qtyEl = $('#qty-' + id);
        let qty = Math.max(1, parseInt(qtyEl.text()) - 1);

        updateQty(id, qty);
    });

   
</script>
