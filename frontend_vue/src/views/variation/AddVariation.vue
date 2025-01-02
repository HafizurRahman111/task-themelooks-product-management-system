<template>
    <Dashboard>
        <div class="product-form-container">
            <div class="product-form-card">
                <h2 class="form-title">Add New Variation</h2>

                <form @submit.prevent="addProduct">
                    <!-- Product Details Section (Two Columns) -->
                    <div class="form-section">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="Product">Product <span class="required">*</span></label>
                                <input type="text" id="Product" v-model="searchQuery" @input="searchProducts"
                                    placeholder="Enter product name or SKU" required />
                                <small v-if="formErrors.name" class="error-text">{{ formErrors.name }}</small>
                            </div>

                            <!-- Hidden Input for Product ID -->
                            <input type="text" v-model="product_id" disabled />
                        </div>

                        <!-- Displaying the Search Results -->
                        <div v-if="products.length > 0" class="search-results">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>SKU</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in products" :key="product.id" @click="selectProduct(product)">
                                        <td><img :src="product.image" alt="Product Image" class="product-image" /></td>
                                        <td>{{ product.name }}</td>
                                        <td>{{ product.sku }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-if="noResultsFound" class="alert alert-warning">
                            No products found.
                        </div>
                    </div>

                    <!-- Pricing Details Section (Two Columns) -->
                    <div class="form-section">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="purchase_price">Purchase Price ($)</label>
                                <input type="number" id="purchase_price" v-model="product.purchase_price"
                                    placeholder="Enter purchase price" required min="0" />
                                <small v-if="formErrors.purchase_price" class="error-text">{{ formErrors.purchase_price
                                    }}</small>
                            </div>

                            <div class="form-group">
                                <label for="selling_price">Selling Price ($)</label>
                                <input type="number" id="selling_price" v-model="product.selling_price"
                                    placeholder="Enter selling price" required min="0" />
                                <small v-if="formErrors.selling_price" class="error-text">{{ formErrors.selling_price
                                    }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Product Variations Section (Two Columns) -->
                    <div class="form-section">
                        <h3 class="variation-title">Variations</h3>
                        <div class="variation-form">
                            <div v-for="(attr, index) in product.attributes" :key="index" class="form-row">
                                <!-- Attribute and Value Fields in Two Columns -->
                                <div class="form-group">
                                    <label :for="'attributeName_' + index">Attribute</label>
                                    <input type="text" :id="'attributeName_' + index" v-model="attr.name"
                                        placeholder="Enter attribute name (e.g., color, size)" />
                                </div>

                                <div class="form-group">
                                    <label :for="'attributeValue_' + index">Value</label>
                                    <input type="text" :id="'attributeValue_' + index" v-model="attr.value"
                                        placeholder="Enter value" />
                                </div>

                                <!-- Remove Button -->
                                <div v-if="product.attributes.length > 1" class="remove-btn-container">
                                    <button type="button" @click="removeAttribute(index)" class="remove-button">
                                        <span>-</span> Remove
                                    </button>
                                </div>

                                <!-- Add New Attribute Button -->
                                <div v-if="index === product.attributes.length - 1" class="add-btn-container">
                                    <button type="button" @click="addNewAttribute" class="add-button">
                                        <span>+</span> Add Attribute
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save Variation Button -->
                    <button type="submit" class="save-button" :disabled="!isVariationValid || !product_id">
                        Save Variation
                    </button>
                </form>
            </div>
        </div>
    </Dashboard>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            searchQuery: '',
            products: [],
            selectedProduct: null,
            formErrors: {},
            noResultsFound: false,
            product_id: '',
            product: {
                name: '',
                sku: '',
                selling_price: '',
                purchase_price: '',
                variations: [],
                attributes: [{ name: '', value: '' }],
            },
        };
    },
    computed: {
        isVariationValid() {
            return this.product.attributes.every(attr => attr.name.trim() && attr.value.trim());
        }
    },
    methods: {
        async searchProducts() {
            if (!this.searchQuery) {
                this.products = [];
                this.noResultsFound = false;
                return;
            }

            try {
                const response = await axios.get('http://127.0.0.1:8000/api/products/get', {
                    params: { search: this.searchQuery },
                });

                this.products = response.data.products;
                this.noResultsFound = this.products.length === 0;
            } catch (error) {
                console.error('Error fetching products:', error.response ? error.response.data : error.message);
                this.noResultsFound = true;
                this.products = [];
            }
        },

        selectProduct(product) {
            this.selectedProduct = product;
            this.product.name = product.name;
            this.product.sku = product.sku;
            this.product_id = product.id;
            this.product.variations = [];
            this.product.attributes = [{ name: '', value: '' }];
        },

        addNewAttribute() {
            this.product.attributes.push({ name: '', value: '' });
        },

        removeAttribute(index) {
            this.product.attributes.splice(index, 1);
        },

        addVariation() {
            if (this.isVariationValid) {
                const variation = this.product.attributes.reduce((acc, attr) => {
                    acc[attr.name] = attr.value;
                    return acc;
                }, {});
                this.product.variations.push(variation);
                this.product.attributes = [{ name: '', value: '' }];
            } else {
                alert('Please add valid attributes to the variation.');
            }
        },

        async addProduct() {
            if (!this.product.name || !this.product.selling_price || !this.product.purchase_price) {
                this.formErrors = {
                    name: 'Product name is required.',
                    selling_price: 'Selling price is required.',
                    purchase_price: 'Purchase price is required.',
                };
                return;
            }

            try {
                const response = await axios.post('http://127.0.0.1:8000/api/variations', this.product);
                console.log('Product added successfully:', response.data);

                this.resetForm();
            } catch (error) {
                console.error('Error adding product:', error.response ? error.response.data : error.message);
            }
        },

        resetForm() {
            this.product_id = '';
            this.product.name = '';
            this.product.sku = '';
            this.product.purchase_price = '';
            this.product.selling_price = '';
            this.product.variations = [];
            this.product.attributes = [{ name: '', value: '' }];
        }
    },
};
</script>

<style scoped>
.product-form-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 10px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.product-form-card {
    padding: 20px;
}

.form-title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
}

.form-section {
    margin-bottom: 10px;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.form-group {
    flex: 1 1 45%;
    min-width: 200px;
}

label {
    font-weight: 500;
    margin-bottom: 5px;
    display: block;
}

input[type="text"],
input[type="number"] {
    width: 90%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

input:focus {
    outline: none;
    border-color: #007bff;
}

.required {
    color: red;
}

.error-text {
    color: red;
    font-size: 12px;
}

.search-results table {
    width: 95%;
    border-collapse: collapse;
}

.search-results th,
.search-results td {
    padding: 8px;
    text-align: left;
}

.search-results th {
    background-color: #f4f4f4;
}

.product-image {
    width: 200px;
    height: auto;
    object-fit: cover;
}

.alert {
    color: #856404;
    background-color: #fff3cd;
    padding: 10px;
    border-radius: 4px;
    margin-top: 10px;
}

.variation-form .form-row {
    margin-bottom: 15px;
}

.add-button,
.remove-button {
    cursor: pointer;
    padding: 5px 10px;
    font-size: 14px;
}

.add-button {
    background-color: #28a745;
    color: #fff;
}

.remove-button {
    background-color: #dc3545;
    color: #fff;
}

.save-button {
    background-color: #007bff;
    color: #fff;
    padding: 10px;
    font-size: 16px;
    width: 100%;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.save-button:disabled {
    background-color: #ccc;
}
</style>
