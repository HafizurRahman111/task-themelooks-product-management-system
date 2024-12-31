<template>
    <DashboardLayout>
        <div class="product-view">
            <!-- Product Header -->
            <div class="header">
                <h2>{{ product ? product.name : 'Loading...' }}</h2>
                <button @click="goBack" class="btn-back">Back to List</button>
            </div>

            <!-- Loading and Error Messages -->
            <div v-if="isLoading" class="loading">Loading...</div>
            <div v-if="errorMessage" class="error">{{ errorMessage }}</div>

            <!-- Product Details -->
            <div v-if="product && !isLoading" class="product-details">

                <!-- Single Row Layout -->
                <div class="product-info-row">
                    <!-- Product Image -->
                    <div class="image-container">
                        <img :src="product.image" :alt="product.name" class="product-image" @click="zoomImage" />
                        <div v-if="isZoomed" class="zoomed-overlay" @click="closeZoom">
                            <img :src="product.image" :alt="product.name" class="zoomed-image" />
                        </div>
                    </div>

                    <!-- Price and SKU Details -->
                    <div class="price-details">
                        <table class="product-table">
                            <thead>
                                <tr>
                                    <th colspan="2">Price Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>SKU:</strong></td>
                                    <td>{{ product.sku }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Unit:</strong></td>
                                    <td>{{ product.unit }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Unit Value:</strong></td>
                                    <td>{{ product.unit_value }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Selling Price:</strong></td>
                                    <td>${{ product.selling_price }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Purchase Price:</strong></td>
                                    <td>${{ product.purchase_price }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Discount:</strong></td>
                                    <td>{{ product.discount }}%</td>
                                </tr>
                                <tr>
                                    <td><strong>Tax:</strong></td>
                                    <td>{{ product.tax }}%</td>
                                </tr>

                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ formatDate(product.created_at) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated:</strong></td>
                                    <td>{{ formatDate(product.updated_at) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Product Variations -->
                    <div v-if="product.variations?.length" class="variations">
                        <h3>Available Variations</h3>
                        <table class="product-table">
                            <thead>
                                <tr>
                                    <th v-for="(attribute, index) in getAttributes(product.variations)" :key="index">{{
                                        attribute }}</th>
                                    <th>Purchase Price</th>
                                    <th>Selling Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(variation, index) in product.variations" :key="index">
                                    <td v-for="(attribute, idx) in getAttributes(product.variations)" :key="idx">
                                        {{ variation.variation_attributes[attribute.toLowerCase()] || 'N/A' }}
                                    </td>
                                    <td>${{ variation.purchase_price }}</td>
                                    <td>${{ variation.selling_price }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- No Product Found -->
            <p v-else class="no-product">Product not found.</p>
        </div>
    </DashboardLayout>
</template>

<script>
export default {
    data() {
        return {
            product: null,
            errorMessage: null,
            isLoading: true,
            isZoomed: false, // Track image zoom state
        };
    },
    mounted() {
        this.fetchProduct();
    },
    methods: {
        async fetchProduct() {
            const productId = this.$route.params.id;
            try {
                const response = await fetch(`http://127.0.0.1:8000/api/products/${productId}`);
                const data = await response.json();
                this.product = data.success ? data.product : null;
                this.errorMessage = data.success ? null : 'Product not found';
            } catch {
                this.errorMessage = 'Error fetching product';
            } finally {
                this.isLoading = false;
            }
        },
        getAttributes(variations) {
            const attributes = new Set();
            variations.forEach(variation => {
                Object.keys(variation.variation_attributes).forEach(attr => {
                    attributes.add(attr.charAt(0).toUpperCase() + attr.slice(1));
                });
            });
            return Array.from(attributes);
        },
        zoomImage() {
            this.isZoomed = true;
        },
        closeZoom() {
            this.isZoomed = false;
        },
        formatDate(date) {
            const options = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false, // To ensure the time is in 24-hour format
            };

            const formattedDate = new Date(date).toLocaleString('en-GB', options);
            return formattedDate.replace(',', ' -');
        },
        goBack() {
            this.$router.push('/products');
        }
    }
};
</script>

<style scoped>
.product-view {
    margin: 10px;
    font-family: 'Arial', sans-serif;
}

.loading,
.error,
.no-product {
    color: red;
    font-size: 1em;
    font-weight: bold;
    text-align: center;
}

.product-details {
    margin-top: 10px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 5px;
}

.header h2 {
    margin: 0;
}

.btn-back {
    padding: 10px 15px;
    background-color: #5f5f5f;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
    margin-top: 0;
}

.btn-back:hover {
    background-color: #504f4f;
}

/* Product Info Row */
.product-info-row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 20px;
}

.image-container {
    position: relative;
    flex: 1 1 20%;
}

.product-image {
    width: 100%;
    cursor: pointer;
    border-radius: 10px;
}

.zoomed-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
}

.zoomed-image {
    max-width: 90%;
    max-height: 90%;
}

.price-details {
    flex: 1 1 30%;
}

.product-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
    background-color: rgb(255, 248, 248);
}

.product-table th,
.product-table td {
    padding: 6px;
    text-align: left;
    border: 1px solid #ddd;

}

.product-table th {
    background-color: #007bff;
    color: white;
}

.variations {
    flex: 1 1 35%;
    margin-top: 10px;
}

.variations h3 {
    margin-top: -10px;
    margin-bottom: 5px;
}

@media (max-width: 768px) {
    .product-info-row {
        flex-direction: column;
    }

    .image-container,
    .price-details,
    .variations {
        max-width: 100%;
        flex: 1 1 100%;
    }
}

@media (max-width: 480px) {

    .product-table th,
    .product-table td {
        font-size: 0.85em;
        padding: 8px;
    }

    .btn-back {
        width: 100%;
    }
}
</style>
