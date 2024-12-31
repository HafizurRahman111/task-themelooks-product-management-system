<template>
    <DashboardLayout>
        <div class="product-form-container">
            <div class="product-form-card">
                <h2>Add New Variation</h2>
                <form @submit.prevent="addProduct">

                    <!-- Product Details Section -->
                    <div class="form-section">
                        <div class="form-row">

                            <div class="form-group">
                                <label for="Product">Product <span class="required">*</span></label>
                                <input type="text" id="Product" v-model="searchQuery" @input="searchProducts"
                                    placeholder="Enter product name or SKU" required />
                                <small v-if="formErrors.name" class="error-text">{{ formErrors.name }}</small>
                            </div>

                            <!-- Displaying the Search Results -->
                            <table v-if="products.length > 0" class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>SKU</th>
                                        <th>Price</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in products" :key="product.id" @click="selectProduct(product)">
                                        <td>{{ product.name }}</td>
                                        <td>{{ product.sku }}</td>
                                        <td>{{ product.price }}</td>
                                        <td>{{ product.description }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- If no results found -->
                            <div v-if="noResultsFound" class="alert alert-warning">
                                No products found.
                            </div>

                            <div class="form-group">
                                <label for="sku">Product SKU <span class="required">*</span></label>
                                <input type="text" id="sku" v-model="product.sku" placeholder="Enter SKU" required />
                                <small v-if="formErrors.sku" class="error-text">{{ formErrors.sku }}</small>
                            </div>

                        </div>
                    </div>

                    <!-- Unit and Quantity Section -->
                    <div class="form-section">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="unit">Product Unit <span class="required">*</span></label>
                                <select id="unit" v-model="product.unit" required>
                                    <option disabled value="">Select unit</option>
                                    <option value="kg">Kilogram (kg)</option>
                                    <option value="liter">Liters</option>
                                    <option value="piece">Pieces</option>
                                    <option value="pair">Pairs</option>
                                </select>
                                <small v-if="formErrors.unit" class="error-text">{{ formErrors.unit }}</small>
                            </div>
                            <div class="form-group">
                                <label for="unit_value">Unit Value <span class="required">*</span></label>
                                <input type="number" id="unit_value" v-model="product.unit_value"
                                    placeholder="Enter unit value" required min="0" @input="validateUnitValue" />
                                <small v-if="unitValueError" class="error-text">Unit value cannot be negative.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Details Section -->
                    <div class="form-section">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="selling_price">Selling Price ($)</label>
                                <input type="number" id="selling_price" v-model="product.selling_price"
                                    placeholder="Enter selling price" required min="0" />
                                <small v-if="formErrors.selling_price" class="error-text">{{ formErrors.selling_price
                                    }}</small>
                            </div>
                            <div class="form-group">
                                <label for="purchase_price">Purchase Price ($)</label>
                                <input type="number" id="purchase_price" v-model="product.purchase_price"
                                    placeholder="Enter purchase price" required min="0" />
                                <small v-if="formErrors.purchase_price" class="error-text">{{ formErrors.purchase_price
                                    }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Discount and Tax Section -->
                    <div class="form-section">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="discount_percentage">Discount Percentage (%)</label>
                                <input type="number" id="discount_percentage" v-model="product.discount_percentage"
                                    placeholder="Enter discount percentage" min="0" />
                                <small v-if="formErrors.discount_percentage" class="error-text">{{
                                    formErrors.discount_percentage }}</small>
                            </div>
                            <div class="form-group">
                                <label for="tax_percentage">Tax Percentage (%)</label>
                                <input type="number" id="tax_percentage" v-model="product.tax_percentage"
                                    placeholder="Enter tax percentage" min="0" />
                                <small v-if="formErrors.tax_percentage" class="error-text">{{ formErrors.tax_percentage
                                    }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload Section -->
                    <div class="form-section">
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <input type="file" id="image" @change="handleImageUpload" />
                            <small v-if="product.image">Selected Image: {{ product.image.name }}</small>
                            <img v-if="product.imagePreview" :src="product.imagePreview" alt="Image preview"
                                class="image-preview" />
                        </div>
                    </div>

                    <!-- Product Variations Section -->
                    <div class="form-section">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="variation_name">Variation Name <span class="required">*</span></label>
                                <input type="text" id="variation_name" v-model="variation.name"
                                    placeholder="Enter variation name (e.g., Size, Color)" required />
                                <small v-if="formErrors.variation_name" class="error-text">{{ formErrors.variation_name
                                    }}</small>
                            </div>
                        </div>

                        <!-- Add Variations Button -->
                        <div class="form-row">
                            <button type="button" @click="addVariation" class="add-variation-button">Add
                                Variation</button>
                        </div>

                        <!-- Variations List -->
                        <div class="variations-list">
                            <div v-for="(variation, index) in product.variations" :key="index" class="variation-item">
                                <p><strong>{{ variation.name }}</strong></p>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="variation_price">Price ($)</label>
                                        <input type="number" v-model="variation.price"
                                            placeholder="Enter price for this variation" required min="0" />
                                    </div>
                                    <div class="form-group">
                                        <label for="variation_stock">Stock</label>
                                        <input type="number" v-model="variation.stock"
                                            placeholder="Enter stock for this variation" required min="0" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-button">Add Product</button>
                </form>
            </div>
        </div>
    </DashboardLayout>
</template>

<script>
export default {
    data() {
        return {
            searchQuery: '',
            products: [], // To store search results
            formErrors: {},
            noResultsFound: false, // To show alert when no products are found
            selectedProduct: null, // To store the selected product details

            product: {
                name: '',
                sku: '',
                unit: '',
                unit_value: '',
                selling_price: '',
                purchase_price: '',
                tax_percentage: '',
                discount_percentage: '',
                image: null,
                imagePreview: null,
                variations: [] // Array to hold product variations
            },
            variation: {
                name: '',
                price: '',
                stock: ''
            },
            unitValueError: false,
            formErrors: {},
        };
    },
    methods: {
        handleImageUpload(event) {
            this.product.image = event.target.files[0];
            if (this.product.image) {
                this.product.imagePreview = URL.createObjectURL(this.product.image);
            }
        },
        validateUnitValue() {
            this.unitValueError = this.product.unit_value < 0;
        },
        validateForm() {
            this.formErrors = {};
            let isValid = true;

            if (!this.product.name) {
                this.formErrors.name = 'Product name is required.';
                isValid = false;
            }

            if (!this.product.sku) {
                this.formErrors.sku = 'Product SKU is required.';
                isValid = false;
            }

            if (!this.product.unit) {
                this.formErrors.unit = 'Product unit is required.';
                isValid = false;
            }

            if (this.product.unit_value <= 0) {
                this.formErrors.unit_value = 'Unit value must be greater than 0.';
                isValid = false;
            }

            if (this.product.selling_price < 0) {
                this.formErrors.selling_price = 'Selling price cannot be negative.';
                isValid = false;
            }

            if (this.product.purchase_price < 0) {
                this.formErrors.purchase_price = 'Purchase price cannot be negative.';
                isValid = false;
            }

            if (this.product.tax_percentage < 0) {
                this.formErrors.tax_percentage = 'Tax percentage cannot be negative.';
                isValid = false;
            }

            if (this.product.discount_percentage < 0) {
                this.formErrors.discount_percentage = 'Discount percentage cannot be negative.';
                isValid = false;
            }

            return isValid;
        },
        async addProduct() {
            if (!this.validateForm()) {
                return;
            }

            const formData = new FormData();
            for (const key in this.product) {
                formData.append(key, this.product[key]);
            }

            try {
                const response = await fetch('http://127.0.0.1:8000/api/products', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });

                console.log(response);


                if (response.ok) {
                    alert('Product added successfully!');
                    this.resetForm();
                } else {
                    alert('Error adding product.');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        },

        searchProducts() {
            if (!this.searchQuery.trim()) {
                this.products = [];
                this.noResultsFound = false;
                return;
            }

            fetch(`http://127.0.0.1:8000/api/products/get?query=${this.searchQuery}`)
                .then(response => response.json())
                .then(data => {
                    this.products = data.products || [];
                    this.noResultsFound = this.products.length === 0;
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                    this.noResultsFound = true;
                });
        },

        selectProduct(product) {
            this.product.name = product.name;
            this.product.sku = product.sku;
            this.product.selling_price = product.price;
            this.product.purchase_price = product.price;
            this.product.imagePreview = product.image_url;
            this.products = []; // Clear search results
        },

        addVariation() {
            if (this.variation.name && this.variation.price && this.variation.stock) {
                this.product.variations.push({ ...this.variation });
                this.variation = { name: '', price: '', stock: '' };
            }
        },

        resetForm() {
            this.product = {
                name: '',
                sku: '',
                unit: '',
                unit_value: '',
                selling_price: '',
                purchase_price: '',
                tax_percentage: '',
                discount_percentage: '',
                image: null,
                imagePreview: null,
                variations: [],
            };
        },
    },
};
</script>

<style scoped>
/* Add appropriate styles for the form, table, and sections */
</style>
