<section class="customer-detail-section">
    <h2 class="title">About <?php echo $data["name"] ?></h2>
    <form action="">
        <div class="customer-img">
            <img src="../images/User/<?php echo $data["image"] ?>" alt="">
        </div>
        <div class="grid-form">
            <div class="group">
                <label for="name">User Name</label>
                <input type="text" id="name" class="disable-input" name="name" value=" <?php echo $data["name"] ?>">
            </div>
            <div class="group">
                <label for="email">Email</label>
                <input type="email" id="email" class="disable-input" name="email" value=" <?php echo $data["email"] ?>">
            </div>
            <div class="group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" class="disable-input" name="phone" value=" <?php echo $data["phone"] ?>">
            </div>
            <div class="group">
                <label for="address">Address</label>
                <input type="text" id="address" class="disable-input" name="address" value=" <?php echo  empty($data["address"]) ? "Nothing" : $data["address"]; ?>
">
            </div>
        </div>

        <a id="customer-delete-btn" href="./controller/user_controller.php?remove_user_id=<?php echo $row['id']; ?>" onclick="return confirmDelete();">Delete account</a>
        <a id="customer-back-btn" href="./user_manager.php">Back</a>

    </form>
</section>
<script>
    function confirmDelete() {
        var confirmation = confirm("Are you sure you want to delete this account?");

        if (confirmation) {
            // Proceed with the link navigation
            return true;
        } else {
            // Cancel the link navigation
            return false;
        }
    }
</script>