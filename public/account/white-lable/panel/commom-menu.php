<ul class="nav nav-tabs">
    <!--<li class="nav-item">
        <a href="add-dealer.php" class="nav-link">Basic Data </span></a>
    </li>-->
    <li class="nav-item">
        <a href="about-us.php?edit_id=<?php echo $id ?>" class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'about-us.php') echo 'active'; ?>">About-Us </a>
    </li>
    <li class="nav-item">
        <a href="slider-management.php?edit_id=<?php echo $id ?>" class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'slider-management.php') echo 'active'; ?>">Slider</span></a>
    </li>

    <li class="nav-item">
        <a href="add-theme.php?edit_id=<?php echo $id ?>" class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'add-theme.php') echo 'active'; ?>">Theme</a>
    </li>
    <li class="nav-item">
        <a href="testimonail.php?edit_id=<?php echo $id ?>"
           class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'testimonail.php') echo 'active'; ?>">Testimonial</a>
    </li>
    <li class="nav-item">
        <a href="team-member.php?edit_id=<?php echo $id ?>"
           class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'team-member.php') echo 'active'; ?>">Team</span></a>
    </li>
    <li class="nav-item">
        <a href="plan-pricing.php?edit_id=<?php echo $id ?>" class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'plan-pricing.php') echo 'active'; ?>">
            Plan & Pricing</a>
    </li>
    <li class="nav-item">
        <a href="contact.php?edit_id=<?php echo $id ?>" class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'contact.php') echo 'active'; ?>">Contact</a>
    </li>
    <li class="nav-item">
        <a href="social-links.php?edit_id=<?php echo $id ?>"
           class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'social-links.php') echo 'active'; ?>">Social</a>
    </li>
    <li class="nav-item">
        <a href="email-config.php?edit_id=<?php echo $id ?>" class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'email-config.php') echo 'active'; ?>">Email
            Config</a>
    </li>
    <li class="nav-item">
        <a href="styling.php?edit_id=<?php echo $id ?>" class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'styling.php') echo 'active'; ?>">Styling</a>
    </li>
    <li class="nav-item">
        <a href="franchise-price.php?edit_id=<?php echo $id ?>"
           class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'franchise-price.php') echo 'active'; ?>">Franchise Price</a>
    </li>
    <li class="nav-item">
        <a href="demo-card.php?edit_id=<?php echo $id ?>"
           class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'demo-card.php') echo 'active'; ?>">Demo card</a>
    </li>
    <li class="nav-item">
        <a href="privacy-policy.php?edit_id=<?php echo $id ?>"
           class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'privacy-policy.php') echo 'active'; ?>">Privacy policy</a>
    </li>
    <li class="nav-item">
        <a href="add-services.php?edit_id=<?php echo $id ?>"
           class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'add-services.php') echo 'active'; ?>">Services</a>
    </li>
</ul>