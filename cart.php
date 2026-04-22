<?php
require_once 'auth_check.php'; // must be logged in
$page = 'cart';
include 'config/db_config.php';
include 'header.php';
?>
<style>

</style>

<!-- Start Hero Section -->
<div class="hero">
  <div class="container">
    <div class="row justify-content-between">
      <div class="col-lg-5">
        <div class="intro-excerpt">
          <h1>Cart</h1>
        </div>
      </div>
      <div class="col-lg-7">

      </div>
    </div>
  </div>
</div>
<!-- End Hero Section -->



<div class="untree_co-section before-footer-section">
  <div class="container">
    <div class="row mb-5">
      <form class="col-md-12" method="post">
        <div class="site-blocks-table">
          <table class="table">
            <thead>
              <tr>
                <th class="product-thumbnail">Image</th>
                <th class="product-name">Product</th>
                <th class="product-price">Price</th>
                <th class="product-quantity">Quantity</th>
                <th class="product-total">Total</th>
                <th class="product-remove">Remove</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $subtotal = 0;
              $session_id = session_id();
              $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

              if ($user_id) {
                $cart_query = "SELECT id FROM carts WHERE user_id = $user_id LIMIT 1";
              } else {
                $safe_session = mysqli_real_escape_string($connection, $session_id);
                $cart_query = "SELECT id FROM carts WHERE session_id = '$safe_session' LIMIT 1";
              }
              $cart_res = mysqli_query($connection, $cart_query);

              if ($cart_res && mysqli_num_rows($cart_res) > 0) {
                $cart_row = mysqli_fetch_assoc($cart_res);
                $cart_id = $cart_row['id'];

                $query = "SELECT ci.*, p.product_name, p.image, p.price FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = $cart_id";
                $res = mysqli_query($connection, $query);

                if ($res && mysqli_num_rows($res) > 0) {
                  while ($item = mysqli_fetch_assoc($res)) {
                    $product_id = $item['product_id'];
                    $price = floatval($item['price']);
                    $qty = intval($item['qty']);
                    $total = $price * $qty;
                    $subtotal += $total;
                    ?>
                    <tr class="cart-products" data-id="<?php echo $product_id; ?>"
                      data-color="<?php echo htmlspecialchars($item['color']); ?>">
                      <td class="product-thumbnail">
                        <img src="images/<?php echo htmlspecialchars($item['image']); ?>" alt="Image" class="img-fluid">
                      </td>
                      <td class="product-name">
                        <h2 class="h5 text-black"><?php echo htmlspecialchars($item['product_name']); ?></h2>
                        <?php if (!empty($item['color'])): ?>
                          <small>Color: <?php echo htmlspecialchars($item['color']); ?></small>
                        <?php endif; ?>
                      </td>
                      <td class="price">$<?php echo number_format($price, 2); ?></td>
                      <td>
                        <div class="input-group mb-3 d-flex align-items-center" style="max-width: 120px; margin: 0 auto;">
                          <div class="input-group-prepend">
                            <button class="btn btn-outline-black decrease minus" type="button">&minus;</button>
                          </div>
                          <input type="text" class="form-control text-center quantity" value="<?php echo $qty; ?>"
                            placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
                          <div class="input-group-append">
                            <button class="btn btn-outline-black increase plus" type="button">&plus;</button>
                          </div>
                        </div>
                      </td>
                      <td class="total">$<?php echo number_format($total, 2); ?></td>
                      <td><button type="button" class="btn btn-black btn-sm remove-item">X</button></td>
                    </tr>
                    <?php
                  }
                } else {
                  echo "<tr><td colspan='6' class='text-center'>Your cart is empty.</td></tr>";
                }
              } else {
                echo "<tr><td colspan='6' class='text-center'>Your cart is empty.</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </form>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="row mb-5">
          <div class="col-md-6 mb-3 mb-md-0">
            <button class="btn btn-black btn-sm btn-block">Update Cart</button>
          </div>
          <div class="col-md-6">
            <a href="shop.php" class="btn btn-outline-black btn-sm btn-block">Continue Shopping</a>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <label class="text-black h4" for="coupon">Coupon</label>
            <p>Enter your coupon code if you have one.</p>
          </div>
          <div class="col-md-8 mb-3 mb-md-0">
            <input type="text" class="form-control py-3" id="coupon" placeholder="Coupon Code">
          </div>
          <div class="col-md-4">
            <button class="btn btn-black">Apply Coupon</button>
          </div>
        </div>
      </div>
      <div class="col-md-6 pl-5">
        <div class="row justify-content-end">
          <div class="col-md-7">
            <div class="row">
              <div class="col-md-12 text-right border-bottom mb-5">
                <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <span class="text-black">Subtotal</span>
              </div>
              <div class="col-md-6 text-right">
                <strong class="text-black" id="cart-subtotal">$230.00</strong>
              </div>
            </div>
            <div class="row mb-5">
              <div class="col-md-6">
                <span class="text-black">Total</span>
              </div>
              <div class="col-md-6 text-right">
                <strong class="text-black" id="cart-total">$230.00</strong>
              </div>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="col-md-12">
        <button class="btn btn-primary btn-lg py-3 btn-block mb-2" id="place-order-btn">Place Order</button>

      </div>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', () => {

      const parseCurrency = (text) => {
        const cleanString = text.replace(/[$,]/g, '').trim();
        const value = parseFloat(cleanString);
        return isNaN(value) ? 0 : value;
      };

      const subtotalEl = document.getElementById('cart-subtotal');
      const totalEl = document.getElementById('cart-total');

      const updateGrandTotal = () => {
        const products = document.querySelectorAll('.cart-products');
        let grandTotal = 0;
        products.forEach(product => {
          const rowTotalElement = product.querySelector('.total');
          if (rowTotalElement) {
            const rowTotal = parseCurrency(rowTotalElement.innerText);
            grandTotal += rowTotal;
          }
        });
        if (subtotalEl) subtotalEl.innerText = '$' + grandTotal.toFixed(2);
        if (totalEl) totalEl.innerText = '$' + grandTotal.toFixed(2);
      };

      const updateCartAjax = (id, color, qty, row) => {
        $.ajax({
          url: 'backend/cart_action.php',
          type: 'POST',
          dataType: 'json',
          data: { action: 'update', id: id, color: color, qty: qty },
          success: function (response) {
            if (!response.success) {
              alert(response.message);
            }
          }
        });
      };

      const removeCartAjax = (id, color, row) => {
        $.ajax({
          url: 'backend/cart_action.php',
          type: 'POST',
          dataType: 'json',
          data: { action: 'remove', id: id, color: color },
          success: function (response) {
            if (response.success) {
              row.remove();
              updateGrandTotal();
            } else {
              alert(response.message);
            }
          }
        });
      };

      // Use event delegation for dynamic cart
      const tbody = document.querySelector('tbody');
      if (tbody) {
        tbody.addEventListener('click', function (e) {
          if (e.target.classList.contains('minus') || e.target.classList.contains('plus')) {
            const row = e.target.closest('tr');
            const quantityElement = row.querySelector('.quantity');
            const priceElement = row.querySelector('.price');
            const totalElement = row.querySelector('.total');
            const id = row.getAttribute('data-id');
            const color = row.getAttribute('data-color');

            let price = parseCurrency(priceElement.innerText);
            let quantity = parseInt(quantityElement.value);

            if (e.target.classList.contains('plus')) {
              quantity++;
            } else if (e.target.classList.contains('minus') && quantity > 1) {
              quantity--;
            }

            quantityElement.value = quantity;
            totalElement.innerText = '$' + (price * quantity).toFixed(2);

            updateGrandTotal();
            updateCartAjax(id, color, quantity, row);
          }

          if (e.target.classList.contains('remove-item')) {
            const row = e.target.closest('tr');
            const id = row.getAttribute('data-id');
            const color = row.getAttribute('data-color');
            removeCartAjax(id, color, row);
          }
        });

        tbody.addEventListener('change', function (e) {
          if (e.target.classList.contains('quantity')) {
            const row = e.target.closest('tr');
            const totalElement = row.querySelector('.total');
            const priceElement = row.querySelector('.price');
            const id = row.getAttribute('data-id');
            const color = row.getAttribute('data-color');

            let price = parseCurrency(priceElement.innerText);
            let val = parseInt(e.target.value);
            if (val < 1 || isNaN(val)) val = 1;
            e.target.value = val;

            totalElement.innerText = '$' + (price * val).toFixed(2);
            updateGrandTotal();
            updateCartAjax(id, color, val, row);
          }
        });
      }


      const placeOrderBtn = document.getElementById('place-order-btn');
      if (placeOrderBtn) {
        $("#place-order-btn").click(function () {
          placeOrderBtn.disabled = true;
          placeOrderBtn.innerText = 'Placing Order...';

          $.ajax({
            url: 'backend/place_order.php',
            type: 'POST',
            dataType: 'json',
            success: function (response) {
              if (response.success) {
                alert(response.message);
                window.location.reload();
              } else {
                alert(response.message);
                placeOrderBtn.disabled = false;
                placeOrderBtn.innerText = 'Place Order';
              }
            },
            error: function () {
              alert('An error occurred while placing the order.');
              placeOrderBtn.disabled = false;
              placeOrderBtn.innerText = 'Place Order';
            }
          });
        });
      }

      updateGrandTotal(); // Initial computation
    });
  </script>
  <?php include 'footer.php'; ?>