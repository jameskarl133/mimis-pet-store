<div class="profile">
<?php
    $profilePicPath = isset($_SESSION["profile_pic"]) ? $_SESSION["profile_pic"] : "pics/profile-pic-default.png";
    ?>
    
    <img src="<?php echo $profilePicPath; ?>" alt="Profile Picture">

    <p>User Type: <?php echo $user_type; ?></p>
    <p>Username: <?php echo $user_name; ?></p>
    <button onclick="location.href='edit_profile.php'" type="button">Edit Profile</button>
</div>
