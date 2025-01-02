<template>
    <div class="pos-page">
        <!-- Left Section: Product Section -->
        <div class="left-section">
            <h1 class="section-title">Product Selection</h1>

            <!-- Search Field -->
            <div class="search-bar">
                <input type="text" v-model="searchTerm" placeholder="Search products by name or SKU..."
                    @input="filterProducts" />
            </div>

            <!-- Product Grid -->
            <div class="product-grid">
                <div class="product-card" v-for="product in paginatedProducts" :key="product.id">
                    <img :src="product.image" alt="Product Image" class="product-image" />
                    <h3 class="product-name">{{ product.name }}</h3>
                    <div class="prices">
                        <p class="discounted-price">{{ product.discounted_price | currency }}</p>
                        <p class="original-price" v-if="product.discounted_price !== product.selling_price">
                            {{ product.selling_price | currency }}
                        </p>
                    </div>
                    <button class="add-to-cart-button" @click="addToCart(product)">
                        Add to Cart
                    </button>
                </div>
            </div>

            <!-- Pagination Controls -->
            <div class="pagination">
                <button @click="prevPage" :disabled="currentPage === 1">
                    &laquo; Previous
                </button>

                <!-- Page Numbers -->
                <button v-for="page in totalPages" :key="page" :class="{ active: page === currentPage }"
                    @click="goToPage(page)">
                    {{ page }}
                </button>

                <button @click="nextPage" :disabled="currentPage === totalPages">
                    Next &raquo;
                </button>
            </div>
        </div>

        <!-- Right Section: Selling Section -->
        <div class="right-section">
            <h1 class="section-title">Selling Section</h1>

            <!-- Order Details Table -->
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in cart" :key="item.id">
                        <td>{{ item.name }}</td>
                        <td>
                            <input type="number" v-model.number="item.quantity" @change="updateCart" min="1" />
                        </td>
                        <td>{{ (item.quantity * item.discounted_price).toFixed(2) }}</td>
                        <td>
                            <button @click="removeFromCart(item.id)">Remove</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Summary Section -->
            <div class="summary-details">
                <p>Subtotal: <span>{{ subtotal | currency }}</span></p>
                <p>Tax: <span>{{ tax | currency }}</span></p>
                <p>Total: <span class="total-price">{{ total | currency }}</span></p>
            </div>

            <!-- Place Order Button -->
            <button class="place-order-button" @click="placeOrder">
                Place Order
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Pos",
    data() {
        return {
            searchTerm: "",
            products: [],
            cart: [],
            currentPage: 1,
            itemsPerPage: 6,
            errorMessage: "",
            pagination: {},
        };
    },
    computed: {
        filteredProducts() {
            const term = this.searchTerm.toLowerCase();
            return this.products.filter(
                (product) =>
                    product.name.toLowerCase().includes(term) ||
                    product.sku.toLowerCase().includes(term)
            );
        },
        totalPages() {
            return Math.ceil(this.filteredProducts.length / this.itemsPerPage);
        },
        paginatedProducts() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = this.currentPage * this.itemsPerPage;
            return this.filteredProducts.slice(start, end);
        },
        subtotal() {
            return this.cart.reduce(
                (sum, item) => sum + item.quantity * item.discounted_price,
                0
            );
        },
        tax() {
            // If the cart is empty, return 0 tax
            if (this.cart.length === 0) {
                return 0;
            }
            // Calculate tax by summing the tax amounts for each item in the cart
            return this.cart.reduce((total, item) => {
                const itemTax = item.tax_amount;
                return total + itemTax;
            }, 0);
        },

        total() {

            return this.subtotal + this.tax; // Add tax to the subtotal
        }
    },
    methods: {
        async fetchProducts() {
            try {
                const response = await fetch(`http://127.0.0.1:8000/api/products`);
                if (response.ok) {
                    const data = await response.json();
                    if (data.status === "success") {
                        this.products = data.data;
                        this.pagination = data.pagination;
                    } else {
                        this.errorMessage = `Failed to fetch products. Status: ${data.status}`;
                    }
                } else {
                    this.errorMessage = `Failed to fetch products. Status Code: ${response.status}`;
                }
            } catch (error) {
                this.errorMessage = `An error occurred while fetching products: ${error.message}`;
            }
        },

        filterProducts() {
            this.currentPage = 1; // Reset to the first page when filtering
        },
        prevPage() {
            if (this.currentPage > 1) this.currentPage--;
        },
        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        },
        goToPage(page) {
            this.currentPage = page;
        },
        addToCart(product) {
            const existing = this.cart.find((item) => item.id === product.id);
            if (existing) {
                existing.quantity++;
            } else {
                this.cart.push({ ...product, quantity: 1 });
            }
        },
        updateCart() {
            this.cart = this.cart.filter((item) => item.quantity > 0);
        },
        removeFromCart(id) {
            this.cart = this.cart.filter((item) => item.id !== id);
        },
        placeOrder() {
            alert("Order placed!");
            this.cart = []; // Empty the cart after placing the order
        },
    },
    mounted() {
        this.fetchProducts();
    },
};
</script>

<style scoped>
/* General Layout */
.pos-page {
    display: flex;
    justify-content: space-between;
    padding: 20px;
    background-color: #f4f4f4;
}

.left-section {
    width: 60%;
    background-color: white;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.right-section {
    width: 40%;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-left: 10px;
}

.section-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
}

/* Product Section */
.search-bar {
    margin-bottom: 10px;
}

.search-bar input {
    width: 90%;
    padding: 8px;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-top: 5px;
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.product-card {
    background-color: #f3f3f3;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 10px;
    text-align: center;
    transition: all 0.3s ease;
}

.product-card:hover {
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.product-image {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
}

.product-name {
    font-size: 1rem;
    font-weight: 500;
    margin-top: 5px;
    margin-bottom: 5px;
}

.prices {
    margin: 5px 0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 5px;
    margin-bottom: 5px;
}

.discounted-price {
    font-size: 1.2rem;
    color: #e74c3c;
    font-weight: 600;
    margin-right: 10px;
}

.original-price {
    text-decoration: line-through;
    color: #888;
    font-size: 0.9rem;
}

.add-to-cart-button {
    padding: 8px 10px;
    background-color: #2ecc71;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background-color 0.3s ease;
}

.add-to-cart-button:hover {
    background-color: #27ae60;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
}

.pagination button {
    padding: 8px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

.pagination button.active,
.pagination button:hover {
    background-color: #2980b9;
}

.pagination button:disabled {
    background-color: #bdc3c7;
    cursor: not-allowed;
}

/* Order Section */
.order-table {
    width: 100%;
    border-collapse: collapse;
}

.order-table th,
.order-table td {
    padding: 10px;
    text-align: left;
}

.order-table th {
    background-color: #3498db;
    color: white;
}

.summary-details {
    margin-top: 20px;
    font-weight: 600;
}

.place-order-button {
    margin-top: 20px;
    padding: 12px 20px;
    background-color: #2ecc71;
    color: white;
    border: none;
    border-radius: 5px;
    width: 100%;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.place-order-button:hover {
    background-color: #27ae60;
}
</style>