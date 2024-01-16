<header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
      <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
        <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
        <div>
        <img src="pics/mimis_logo.jpg" width="45" height="45" alt="Logo">
        <span class="fs-4">Mimi's Pet Shop</span>
        </div>
      </a>

      <ul class="nav nav-pills">
        <li class="nav-item"><a href="/home.php" class="nav-link">Home</a></li>
        <?php
        if($_SESSION["emp_type"]=="Admin")
        {
          echo '<li class="nav-item"><a href="/manage_emp.php" class="nav-link">Manage Employee</a></li>';
        }
        ?>
        <li class="nav-item"><a href="/ViewProduct.php" class="nav-link">Requisition</a></li>
        <li class="nav-item"><a href="order.php" class="nav-link">Add Order</a></li>
        <li class="nav-item"><a href="/logout.php" class="nav-link">Logout</a></li>
      </ul>
    </header>
