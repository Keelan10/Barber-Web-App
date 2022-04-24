$(document).ready(function() {

    updateCartRelatedInfoOnPage();
    filterProducts();

    //open cart
    $("#open-cart").click(openSidebar);

    //close cart
    $("#close-cart").click(closeSidebar);

    //close cart when overlay is double clicked
    $(".cart-overlay").dblclick(closeSidebar);

    //open search bar when search button is clicked
    $(".open-search").click(() => {
        //use fade in, fade out later
        $(".nav-center").css({ "display": "none" });
        $(".search-center").css({ "display": "flex" });
        $(".input-wrapper input").focus(); //put cursor in search input field
    });

    //close search bar
    $(".close-search").click(() => {
        $(".search-center").css({ "display": "none" }); //
        $(".nav-center").css({ "display": "flex" });

    });

    //clear cart
    $(".clear-cart").click(function() {
        localStorage.clear();
        updateCartRelatedInfoOnPage();
    });

    //adding to cart
    $(".addToCart").click(addToCart);

    //filter in price/name order
    $(".filter-options").change(function() {
        filterProducts();
    })





});

function openSidebar() {
    $(".sidebar").addClass("show-sidebar");
    $(".cart-overlay").addClass("show-overlay");
}

function closeSidebar() {
    $(".sidebar").removeClass("show-sidebar");
    $(".cart-overlay").removeClass("show-overlay");
}



function search() {
    var input = document.querySelector(".search-bar").value;

    var lowercaseInput = input.toLowerCase(); //to later compare lowercase strings for case insensitive search
    var products = document.querySelector(".products").querySelectorAll("article");
    var NumberOfProducts = products.length;

    var productNames = document.querySelector(".products").querySelectorAll("p");
    console.log(NumberOfProducts);

    //loop through all products and hide those which don't match query
    for (var i = 0; i < NumberOfProducts; i++) {
        var productName = productNames[i].innerText;

        if (productName.toLowerCase().indexOf(lowercaseInput) > -1) {
            products[i].style.display = "";
        } else {
            products[i].style.display = "none";
        }
    }

    //Number of products whose display is not none
    NumberOfProducts = $(".products>article").filter(function() {
        return $(this).css('display') != "none";
    }).length;

    $("#results").text(NumberOfProducts);

}

function appendToLocalStorage(productName, img, price) {
    var existingProducts = JSON.parse(localStorage.getItem("cart")); //grab products in cart from localStorage
    if (existingProducts == null) existingProducts = [];

    if (!AlreadyInCart(existingProducts, productName)) { //add product to localStorage if not already in cart
        const entry = {
            "name": productName,
            "image": img,
            "price": price,
            // "qty":0
        }
        existingProducts.push(entry);
        localStorage.setItem("cart", JSON.stringify(existingProducts));

    } else { //otherwise alert the customer that product is already in cart
        alert("Product is already in cart!");
    }
}

function AlreadyInCart(existingProducts, productName) {
    for (var i = 0; i < existingProducts.length; i++) {
        if (productName == existingProducts[i].name) {
            return true;
        }
    }
    return false;
}

function updateCartRelatedInfoOnPage() {
    refreshCart();
    updateLogoNumber();
    updateCartTotal();
}

function addToCart() {
    openSidebar();

    // const article = event.target.parentElement;
    const article = event.currentTarget;
    // console.log(article)
    const productName = article.getElementsByTagName("p")[0].textContent;
    const img = article.getElementsByTagName("img")[0].src;
    const price = parseFloat(article.querySelector("#price").innerText);

    appendToLocalStorage(productName, img, price);
    updateCartRelatedInfoOnPage();
}

function refreshCart() {

    $(".cart-items").html("");

    var Products = JSON.parse(localStorage.getItem("cart"));

    if (Products == null || Products.length == 0) {
        $(".cart-items").html(`You don't have any item in your cart.`);

    } else {
        //TO DO! IF QTY=0 IN DATABASE, ITEM SHOULD BE REMOVED FROM CART
        //Should qty be saved? probably. To do later

        for (var j = 0; j < Products.length; j++) {

            var CartRow = document.createElement("article");
            CartRow.classList.add("productInCart");
            CartRow.innerHTML =
                `<img src="${Products[j].image}" style="width:100px;height:auto;" alt="">
                <div class="product-info">
                    <div class="item-header">
                        <h5 style="margin">${Products[j].name}</h5>
                        <p>Rs <span class="item-price">${Products[j].price}</span></p>
                    </div>
                    <div class="second-row">
                        <input min="1" class="item-qty" value="1" type="number" placeholder="Quantity" name="" id="" style="width:10ch;">
                        <button class="remove-item">Remove</button>
                    </div>
                </div>`;
            var cartItems = document.querySelector(".cart-items");
            cartItems.append(CartRow);

            CartRow.querySelector(".remove-item").addEventListener("click", addRemoveAction);
            CartRow.querySelector("input").addEventListener("input", updateCartTotal);
            CartRow.querySelector("input").addEventListener("input", updatePricePerRow);

        }

    }
}

function addRemoveAction(button) {
    var buttonClicked = event.target;
    var productName = buttonClicked.parentElement.parentElement.querySelector("h5").innerText; //choosing productName
    removeFromLocalStorage(productName);
    updateCartRelatedInfoOnPage();
}

//function to update price per row
function updatePricePerRow(event) {
    const inputField = $(event.currentTarget)
    const productInfo = $(event.currentTarget).parentsUntil("article")
    const productName = $(productInfo).find("h5").text()
    var qty = $(event.currentTarget).val();


    if (qty < 0) {
        $(event.currentTarget).val(1)
    }

    $.ajax("getItemPrice.php", {
        data: { name: productName },
        success: function(data) {

            data = JSON.parse(data)
            if (qty > data.quantity) {
                qty = data.quantity
                inputField.val((qty))
            }

            const price = data.price;
            if (qty == "") {
                $(productInfo).find(".item-price").text(Math.round(price))
            } else {
                $(productInfo).find(".item-price").text(price * qty)
                updateCartTotal()
            }
        }
    })
}

//function to update total price
function updateCartTotal() { //TO DO! Should grab price from db instead!+UPDATE PRICE PER ROW
    var prices = $(".item-price");
    var total = 0;
    for (var i = 0; i < prices.length; i++) {
        total += parseFloat(prices[i].innerText);
    }
    document.getElementById("cart-total").innerText = total;

}

//update number of items near logo
function updateLogoNumber() {
    var num = document.getElementsByClassName("productInCart").length;
    document.getElementById("logoNum").innerText = num;
}

// remove from localStorage
function removeFromLocalStorage(productName) {

    var existingProducts = JSON.parse(localStorage.getItem("cart"));
    for (var i = 0; i < existingProducts.length; i++) {
        if (productName == existingProducts[i].name) {
            existingProducts.splice(i, 1);
            // console.log(existingProducts);
            break;
        }
    }

    localStorage.setItem("cart", JSON.stringify(existingProducts));
}

//Filter by price and name
/*
1. Grab the information of each product and store them in an array/list of products
2. Sorted using the sort function 
3. change the order
*/
function filterProducts() {

    var products = getProducts();
    // console.log(products);
    const value = $(".filter-options")[0].value;

    if (value == "price-desc") {
        products.sort(DescendingPrice);
    } else if (value == "price-asc") {
        products.sort(AscendingPrice);
    } else if (value == "alpha-asc") {
        products.sort(AscendingName);
    } else if (value == "alpha-desc") {
        products.sort(DescendingName);
    }
    displayInOrder(products);
}

function AscendingPrice(a, b) {
    if (a.price > b.price) return 1;
    if (a.price < b.price) return -1;
    return 0;
}

function DescendingPrice(a, b) {
    if (a.price > b.price) return -1;
    if (a.price < b.price) return 1;
    return 0;
}

function AscendingName(a, b) {
    if (a.name > b.name) return 1;
    if (a.name < b.name) return -1;
    return 0;
}

function DescendingName(a, b) {
    if (a.name > b.name) return -1;
    if (a.name < b.name) return 1;
    return 0;
}

function getProducts() { //getProducts
    var products = [];
    $.each($(".products>article"), function(key, value) {

        var productName, price, image, entry;
        productName = value.querySelector("p").innerText;
        price = value.querySelector("#price").innerText;
        image = value.querySelector("img").src;

        entry = { "name": productName, "price": price, "image": image }; //can add popularity afterwards with XML
        products.push(entry);

    });
    return products;
}

function displayInOrder(products) {
    // console.log(products);

    //assign order to articles in terms of their position in the array
    for (var i = 0; i < products.length; i++) { //for each product on page
        for (var j = 0; j < products.length; j++) { //for each product in array

            if ($(".products>article>p")[i].innerText == products[j].name) {
                var order = j;
                $(".products>article")[i].style.order = order;
            }

        }
    }
}

//Display by category
/*
  1. When a category is clicked, grab the category
  2. Create XMLHttpRequest and send request to server
  3. Web server returns the result containing XML document
  4. The XMLHttpRequest calls the callback function and processes the result
  5. The HTML DOM is updated
 */
$(".prod-category").click(function() {

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            var products = JSON.parse(this.responseText);
            console.log(products);
            displayCategoryProducts(products);

        }
    };

    xmlhttp.open("GET", "prod-category.php?q=" + $.trim($(this).text(), true));
    xmlhttp.send();

});

function displayCategoryProducts(products) {
    $(".products").html(""); //erase content
    $("#results").text(products.length); // update number of results

    //rewrite content
    for (var i = 0; i < products.length; i++) {
        var productRow = document.createElement("article");

        if (products[i].qty == 0) {
            // productRow.classList.add("outOfStock")
            productRow.innerHTML =
                `<div class="product-image-container">
                <img width="100%" height="100%" src="images/product/${products[i].image}" alt=""> 
                </div>
                <p style="font-size:15px;line-height:1.5em;letter-spacing:0.5px">${products[i].name}</p>
                <h4 style="letter-spacing:0.5px">Rs <span id="price">${products[i].price}</span></h4>
                <h4 style="color:red;font-style:italic;letter-spacing:-0.01rem;font-weight:400;">Out of stock</h4>`;

            $(".products").append(productRow);
            continue;

        }


        productRow.classList.add("addToCart")
        productRow.innerHTML =
            `<div class="product-image-container">
             <img width="100%" height="100%" src="images/product/${products[i].image}" alt=""> 
             </div>
             <p style="font-size:15px;line-height:1.5em;letter-spacing:0.5px">${products[i].name}</p>
             <h4 style="letter-spacing:0.5px">Rs <span id="price">${products[i].price}</span></h4>`;

        $(".products").append(productRow);

        productRow.addEventListener("click", addToCart);

    }

    filterProducts(); //after display, products should be filtered according to the filter option selected

}

//TO DO! IF QTY=0 IN DATABASE, ITEM SHOULD BE REMOVED FROM CART
//Should qty be saved? probably. To do later (LIMITS OF QTY) 
//TO DO! Should grab price from db instead!+UPDATE PRICE PER ROW
//popularity order