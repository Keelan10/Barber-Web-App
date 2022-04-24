<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>

    <link rel="stylesheet" href="styles/shop.css">
    <script src="https://kit.fontawesome.com/65310733dc.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./js/shop.js"></script>
</head>

<body>
    <nav>
        <div class="nav-center">
            <a href="<?php echo $_SERVER["PHP_SELF"] ?>">Shop all</a>
            <a href="#">
                <img id="img-logo" src="./images/logo.png" alt="">
            </a>
            <div class="nav-links">
                <ul>
                    <li><i class="fas fa-search open-search"></i></li>
                    <li>
                        <a href="#" id="open-cart">
                            <i class="fas fa-shopping-cart"></i>
                            <span id="logoNum">0</span>
                        </a>
                    </li>
                </ul>

            </div>
        </div>
        <div class="search-center">
            <div class="input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search" class="search-bar" onkeyup="search()">
            </div>
            <i class="fas fa-times close-search"></i>
        </div>
    </nav>

    <div class="container">
        <section class="tags">
            <ul>
                <li>browse by category</li>
                <?php
                require_once 'includes/database.php';

                $query = "SELECT DISTINCT(category) from product";
                $results = $conn->query($query);

                while ($row = $results->fetch()) :
                ?>
                    <li style="text-transform:capitalize" class="prod-category">
                        <a href="#" style="line-height:2.3rem;"><?php echo $row['category'] ?></a>
                    </li>

                <?php endwhile; ?>
            </ul>
        </section>
        <div style="width:100%;height:100%;">
            <h2 id="category-title" style="font-size:1.5rem;line-height:1.35rem;">shop all</h2>
            <div class="filter">
                <p style="color:#666666;letter-spacing:0.75px;">
                    <?php
                    $sql = "SELECT * FROM product";
                    $result = $conn->query($sql);
                    $rowCount = $result->rowCount();
                    echo '<span id="results">' . $rowCount . "</span>";
                    ?> results</p>
                <select name="" id="" class="filter-options">
                    <option value="price-desc">Price (High-Low)</option>
                    <option value="price-asc">Price (Low-High)</option>
                    <option value="alpha-asc">Alphabetical (A-Z)</option>
                    <option value="alpha-desc">Alphabetical (Z-A)</option>
                </select>
            </div>
            <section class="products">
                <?php

                if ($rowCount) {
                    while ($row = $result->fetch()) :
                ?>

                        <article class="<?php if ($row["quantity"] != 0) echo "addToCart";?>">
                            <div class="product-image-container">
                                <img width="100%" height="100%" src="images/product/<?php echo $row['image_name']; ?> " alt="">
                            </div>
                            <p style="font-size:15px;line-height:1.5em;letter-spacing:0.5px"><?= $row['product_name']; ?></p>
                            <h4 style="letter-spacing:0.5px">Rs<span id="price"> <?php echo $row["price"] ?></span></h4>
                            <?php
                            if ($row["quantity"] == 0) {
                                echo '<h4 style="color:red;font-style:italic;letter-spacing:-0.01rem;font-weight:400;">Out of stock</h4>';
                            } else {
                                // echo '<i class="addToCart fas fa-plus"></i>';
                                // echo '<div>Add to cart</div>';
                            } ?>
                        </article>
                <?php endwhile;
                } ?>
            </section>
        </div>
        <?php

        if (!$rowCount) {
            echo '<h1 style="margin:auto;">No product yet!<h1>';
        }
        ?>
    </div>


    <div class="cart-overlay"></div>
    <aside class="sidebar">
        <div class="heading-content">
            <h1 class="sidebar-header">Shopping cart</h1>
            <button>
                <i class="fas fa-times" id="close-cart"></i>
            </button>

        </div>
        <div class="cart-items"></div>

        <div class="sidebar-footer">
            <h3>Your total: Rs <span id="cart-total"></span></h3>
            <div class="button-container">
                <button class="checkout">Checkout</button>
                <button class="clear-cart">Clear Cart</button>
            </div>
        </div>

    </aside>



    <footer>
        <div class="footer-center">
            <a href="#"><img src="./images/logo.png" width=100 height=auto alt=""></a>
            <p>Copyright <span id="year"></span></p>
            <div class="social-media">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>

            </div>
        </div>
    </footer>
    <script>
        const span = document.querySelector("#year");
        const date = new Date();
        span.textContent = date.getFullYear();
    </script>

</body>
</html>