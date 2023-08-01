<section class="cart-list-section">
    <h2>Order List</h2>
    <table id="cart-list-table">
        <thead>
            <tr>
                <th>No.</th>
                <th class="hide-col">Item</th>
                <th>Name</th>
                <th class="hide-col">Price</th>
                <th>Quantity</th>
                <th>Subtotal (ks)</th>
                <th>Action</th>
            </tr>
        </thead>
        <?php
        $serial = 1;
        $total_cost = 0;
        foreach ($_SESSION["cart"] as $key => $value) {
            $item_id = $value['id'];
            $price = $value["price"];
            $quantity = $value["Quantity"];
            $subtotal = $price * $quantity;
            $total_cost += $subtotal;
            $formattedSubtotal = number_format($subtotal, 2, ',');
        ?>
            <tr class="card-list-row">
                <td><?php echo  $serial++  ?></td>
                <td class="hide-col">
                    <img src="../images/Menu_items/<?php echo $value["category"] ?>/<?php echo $value["image"]  ?>" alt="photo" class="product-tbl-img">
                </td>
                <td><?php echo $value["name"]  ?></td>
                <td class="hide-col"><?php echo  $price   ?></td>
                <td>
                    <div class="product-quantity-wrapper">
                        <input type="hidden" class="cart-id" data-item-id="<?php echo $value['id']; ?>">
                        <span class="minus" data-item-index="<?php echo $key; ?>">-</span>
                        <span class="quantity" data-item-index="<?php echo $key; ?>">
                            <?php echo $value["Quantity"] ?>
                        </span>
                        <span class="plus" data-item-index="<?php echo $key; ?>">+</span>
                    </div>
                </td>

                <td class="subtotal-col item-price" data-base-price="<?php echo $price ?>"><?php echo  $formattedSubtotal  ?></td>
                <td>
                    <a href="item_detail.php?view-item-id=<?php echo $item_id  ?>" class="view-cart-a information-border">View</a>
                    <a class="remove-cart-a danger-border" data-item-id="<?php echo $item_id; ?>">Remove</a>
                </td>
            </tr>
        <?php
        }
        ?>
        <tr id="total-cost-row" class="information">
            <td colspan="5">Total Cost</td>
            <td><?php
                $_SESSION["total_cost"] =  $total_cost;
                echo  number_format($total_cost, 2, ',') . ' Ks';
                ?></td>
            <td>
                <?php
                if (isset($_SESSION["cart"]) && count($_SESSION["cart"]) > 0) {
                ?>
                    <a onclick="showOrderNowForm()" id="hide-order-btn" class="checkout-btn success-bg">Order Now</a>
                <?php
                }
                ?>
            </td>
        </tr>
    </table>
</section>
<div id="overlay"></div>
<div id="order-form">
    <div>
        <i class="fa-solid fa-utensils" id="order-now-emoji"></i>
        <h2>Order Now </h2>
    </div>

    <div>
        <form method="post" id="order-now-form">
            <div class="form-box-group">
                <label for="table-num">Table number</label>
                <input type="number" id="table-num" name="table-num" placeholder="e.g No. 7" required>
            </div>
            <div class="form-box-group">
                <label for="table-num">Additional request (<i>Optional</i>)</label>
                <textarea name="additional-req" id="additional-req" cols="60" rows="10"></textarea>
            </div>
            <div class="button-flex">
                <input type="reset" class="warning-bg" id="cancel-order-btn" value="Cancel">
                <input type="submit" class="information-bg" id="order-now-btn" name="order-now" value="Submit">
            </div>
        </form>
    </div>
</div>

<div id="checkout-noti-form">
    <div>
        <i class="fa-regular fa-face-laugh-beam" id="smilly-emoji"></i>
        <h2>Your order has been submitted. Please wait a few minutes.</h2>
    </div>
    <a class="information-bg" id="check-out-noti-close-btn">OK</a>
</div>
<!-- <script src="scripts/order-now.js"></script> -->
<script>
    $(document).ready(function() {

        $("#check-out-noti-close-btn").on("click", function(e) {
            hideOrderSubmitNoti();
        });


        $("#cancel-order-btn").on("click", function(e) {
            hideOrderNowForm();
        });
    });

    function showOrderSubmitNoti() {
        $("#overlay").show();
        $("#checkout-noti-form").show();
    }

    function hideOrderSubmitNoti() {
        $("#overlay").hide();
        $("#checkout-noti-form").hide();
        location.reload();
    }

    function showOrderNowForm() {
        $("#overlay").show();
        $("#order-form").show();
    }

    function hideOrderNowForm() {
        $("#overlay").hide();
        $("#order-form").hide();
    }


    $(document).on("submit", "#order-now-form", function(e) {
        e.preventDefault();
        let formData = new FormData($(this)[0]);

        $.ajax({
            url: "./controller/order_controller.php?order-now",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(data) {
                hideOrderNowForm();
                showOrderSubmitNoti();
  
            },
            error: function(error) {
                console.log("fail");
            },
        });
    });
</script>