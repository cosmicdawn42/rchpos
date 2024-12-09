<?php
include 'header.php';
include 'navbar.php';
?>

<div class="main-container">
    <div class="selectedprod-container">
        <table class="prod-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody id="prod-table-body">
                <!-- Selected products will be dynamically added here -->
            </tbody>
        </table>
        <div class="left-group">
            <table class="lg-table">
                <tr>
                    <th>Total:</th>
                    <td><span id="total">0.00</span></td>
                </tr>
                <tr>
                    <th>Cash:</th>
                    <td><input type="number" id="cash-input"></td>
                </tr>
                <tr>
                    <th>Change:</th>
                    <td><span id="change">0.00</span></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="menu-container">
        <button id="hot-btn">Hot</button>
        <button id="cold-btn">Cold</button>
        <div id="product-buttons">
            <?php
            //$conn = new mysqli('localhost', 'root', '', 'rch_db');
            $conn = new mysqli('sql.freedb.tech', 'freedb_etheria2024', 'EXH$fvdNh78zv*J', 'freedb_rch_db');

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM productstbl";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<button 
                            class='product-btn' 
                            data-id='{$row['product_id']}' 
                            data-name='{$row['product_name']}' 
                            data-price-hot='{$row['price_hot']}' 
                            data-price-cold='{$row['price_cold']}'
                            data-quantity='{$row['quantity']}'>
                            {$row['product_name']}
                          </button>";
                }
            } else {
                echo "No products found.";
            }

            $conn->close();
            ?>
        </div>
        <button id="clear-btn">Clear Items</button>
        <button id="print-btn">Print Receipt</button>
        <button id="enter-btn">Enter</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        let mode = null; // Tracks if 'Hot' or 'Cold' is selected
        let selectedProducts = {}; // Stores selected products with quantities

        const totalEl = document.getElementById("total");
        const changeEl = document.getElementById("change");
        const prodTableBody = document.getElementById("prod-table-body");
        const cashInput = document.getElementById("cash-input");

        // Event listeners for "Hot" and "Cold" buttons
        document.getElementById("hot-btn").addEventListener("click", () => {
            mode = "Hot";
        });

        document.getElementById("cold-btn").addEventListener("click", () => {
            mode = "Cold";
        });

        // Product Buttons
        document.querySelectorAll(".product-btn").forEach((btn) =>
            btn.addEventListener("click", () => {
                if (!mode) {
                    alert("Please select Hot or Cold first!");
                    return;
                }

                const id = btn.dataset.id;
                const name = `${btn.dataset.name} (${mode})`;
                const price = parseFloat(mode === "Hot" ? btn.dataset.priceHot : btn.dataset.priceCold);

                // Check stock via AJAX
                $.ajax({
                    url: "check_stock.php",
                    method: "POST",
                    data: { product_id: id },
                    dataType: "json",
                    success: (response) => {
                        if (response.success) {
                            if (!selectedProducts[id]) {
                                selectedProducts[id] = { name, price, qty: 1, subtotal: price };
                            } else {
                                selectedProducts[id].qty += 1;
                                selectedProducts[id].subtotal = selectedProducts[id].qty * price;
                            }

                            // Deduct stock via AJAX
                            $.ajax({
                                url: "deduct_stock.php",
                                method: "POST",
                                data: { product_id: id },
                                success: () => {
                                    updateTable();
                                    mode = null; // Reset mode
                                },
                            });
                        } else {
                            alert(response.message);
                        }
                    },
                });
            })
        );

        // Clear Items
        document.getElementById("clear-btn").addEventListener("click", () => {
            selectedProducts = {};
            updateTable();
            cashInput.value = "";
            changeEl.textContent = "0.00";
        });

        // Print Receipt
        document.getElementById("print-btn").addEventListener("click", () => {
    const cash = parseFloat(cashInput.value) || 0;
    const total = calculateTotal();

    if (cash < total) {
        alert("Cash must be greater than or equal to total!");
        return;
    }

    const change = cash - total;

    // Prepare order details as a JSON string
    const orderDetails = Object.values(selectedProducts).map((prod) => ({
        product_name: prod.name,
        price: prod.price,
        quantity: prod.qty,
        total_price: prod.subtotal,
    }));

    $.ajax({
        url: "save_order.php",
        method: "POST",
        data: {
            orderDetails: JSON.stringify(orderDetails),
            cash_given: cash,
            change: change,
        },
        success: (response) => {
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    alert("Order saved and receipt printed!");
                    selectedProducts = {};
                    updateTable();
                    cashInput.value = "";
                    changeEl.textContent = "0.00";
                } else {
                    alert("Failed to save order: " + result.message);
                }
            } catch (e) {
                alert("Unexpected error: " + response);
            }
        },
        error: () => {
            alert("An error occurred while saving the order.");
        },
    });
});


        // Enter Button
        document.getElementById("enter-btn").addEventListener("click", () => {
            const cash = parseFloat(cashInput.value) || 0;
            const total = calculateTotal();

            if (cash < total) {
                alert("Cash must be greater than or equal to total!");
                return;
            }

            const change = cash - total;
            changeEl.textContent = change.toFixed(2);
        });

        // Update Table
        function updateTable() {
            prodTableBody.innerHTML = "";
            Object.values(selectedProducts).forEach((prod) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${prod.name}</td>
                    <td>${prod.price.toFixed(2)}</td>
                    <td>${prod.qty}</td>
                    <td>${prod.subtotal.toFixed(2)}</td>
                `;
                prodTableBody.appendChild(row);
            });
            totalEl.textContent = calculateTotal().toFixed(2);
        }

        // Calculate Total
        function calculateTotal() {
            return Object.values(selectedProducts).reduce((sum, prod) => sum + prod.subtotal, 0);
        }
    });
</script>

<?php include 'footer.php'; ?>