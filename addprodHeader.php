<header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
      <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
        <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
        <span class="fs-4">Mimi's Pet Shop</span>
      </a>

      <ul class="nav nav-pills">
        <li class="nav-item"><a href="/home.php" class="nav-link">Home</a></li>
        <?php
        if($_SESSION["emp_type"]=="Admin")
        {
          echo '<li class="nav-item"><a href="/manage_emp.php" class="nav-link">Manage Employee</a></li>';
        }
        ?>
        <li class="nav-item"><a href="/add.php"  class="nav-link" aria-current="page">Add Product</a></li>
        <li class="nav-item"><a href="/InsertSup.php" class="nav-link">Supplier</a></li>
        <li class="nav-item"><a href="RequestProd.php" class="nav-link">RequestProd</a></li>
        <li class="nav-item"><a href="Request.php" class="nav-link">Confirm PO</a></li>
        <li class="nav-item"><a href="order.php" class="nav-link">add order</a></li>
        <li class="nav-item"><a href="/logout.php" class="nav-link">Logout</a></li>
      </ul>
    </header>
