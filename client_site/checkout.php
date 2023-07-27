<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<section class="checkout-form-section" id="checkout-anchor">
    <h2 class=" title"> Checkout Form </h2>
    <form action="./controller/order_controller.php" method="POST" enctype="multipart/form-data" class="checkout-form" id="checkout-form">
        <table>

            <tr>
                <td><label for="">Shipping address</label></td>
                <td>
                    <input type="text" name="street" placeholder="Street" class="shipping-address" required>
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <input type="text" name="township" placeholder="Township/Quarter" class="shipping-address" required>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="text" name="city" placeholder="City" class="shipping-address" required>
                </td>
            </tr>
            <tr>
                <td><label for="zipcode">Zipcode/Postal Code</label></td>
                <td>
                    <input type="number" name="zipcode" id="zipcode" required>
                </td>
            </tr>
            <tr>
                <td><label for="additional-req">Additional request</label></td>
                <td>
                    <textarea name="add-req" id="additional-req"" cols=" 30" rows="10"></textarea>
                </td>
            </tr>
            <tr>
                <td><label for="payment">Payment method: </label></td>
                <td>
                    <label for="Cash-on-delivery">Cash On Delivery</label>
                    <input type="radio" name="payment" id="Cash-on-delivery" value="Cash on delivery" onclick="hidePaymentFields()" checked>
                    <label for="k-pay">KBZ-Pay</label>
                    <input type="radio" name="payment" id="k-pay" value="K-pay" onclick="showPaymentFields()">
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <div class="kPayFields" style="display: none;">
                        <label for="k-pay-img">Please use Kpay app and scan to pay</label>
                        <img src="../images/Pay/k-pay-barcode.jpg" alt="payment barcode" id="k-pay-barcode-img" required>
                    </div>

                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <div class="kPayFields" style="display: none;">
                        <label for="transaction-img">Please upload your transaction Image</label>
                        <input type="file" name="transaction-img" id="transaction-img">
                    </div>

                </td>
            </tr>

        </table>

        <div class="checkout-btn-div">
            <input type="submit" name="checkout" value="Checkout" class="checkout-btn success-bg" id="checkout-btn">
        </div>
    </form>

</section>
<script src="scripts/check-out.js"></script>