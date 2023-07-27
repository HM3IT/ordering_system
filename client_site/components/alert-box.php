     <div id="quantity-limit-overlay"></div>
     <div id="quantity-limit-alert-box">
       <div>
         <i class="fa-solid fa-face-grin-beam-sweat" id="sad-emoji"></i>
       </div>
       <h2 class="info">Sorry, Limit Exceeded</h2>
       <p class="message">We kindly ask that you do not exceed a maximum quantity of <span class="warning"> 5 per product.</span> This helps us ensure that all our customers have an equal opportunity to purchase. Thank you for your understanding.</p>
       <div>
         <button onclick="closeQuantityForm()">Ok</button>
       </div>
     </div>

     <div id="out-of-stock-box">
       <div>
         <i class="fa-solid fa-face-laugh-beam" id="pleasure-emoji"></i>
       </div>
       <h2 class="info">Sorry, The item is out of stock</h2>
       <p class="message">The requested quantity is greater than the available stock. Available stock: <span> </span></p>
       <div>
         <button onclick="closeStockInfoForm()">Ok</button>
       </div>
     </div>
     <script>
       function closeQuantityForm() {
         let quantityOverlay = document.getElementById("quantity-limit-overlay");
         let quantityAlertBox = document.getElementById("quantity-limit-alert-box");
         quantityOverlay.style.display = "none";
         quantityAlertBox.style.display = "none";
       }
       function closeStockInfoForm() {
         let quantityOverlay = document.getElementById("quantity-limit-overlay");
         let outOfStockBox = document.getElementById("out-of-stock-box");
         quantityOverlay.style.display = "none";
         outOfStockBox.style.display = "none";
       }
     </script>