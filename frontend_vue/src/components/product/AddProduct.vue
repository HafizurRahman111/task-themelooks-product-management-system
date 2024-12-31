<template>
    <DashboardLayout>
        <div class="product-form-container">
            <div class="product-form-card">
                <h2>Add New Product</h2>
                <form @submit.prevent="addProduct">

                    <!-- Product Details Section -->
                    <div class="form-section">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Product Name <span class="required">*</span></label>
                                <input type="text" id="name" v-model="product.name" placeholder="Enter product name"
                                    required />
                                <small v-if="formErrors.name" class="error-text">{{ formErrors.name }}</small>
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
                                <label for="selling_price">Selling Price ($) </label>
                                <input type="number" id="selling_price" v-model="product.selling_price"
                                    placeholder="Enter selling price" required min="0" />
                                <small v-if="formErrors.selling_price" class="error-text">{{ formErrors.selling_price
                                    }}</small>
                            </div>
                            <div class="form-group">
                                <label for="purchase_price">Purchase Price ($) </label>
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

                if (response.ok) {
                    alert('Product added successfully!');
                    this.resetForm();
                } else {
                    alert('Failed to add product.');
                }
            } catch (error) {
                alert('An error occurred while adding the product.');
            }
        },
        resetForm() {
            this.product = { name: '', sku: '', unit: '', unit_value: '', selling_price: '', purchase_price: '', tax_percentage: '', discount_percentage: '', image: null, imagePreview: null };
        },
    },
};
</script>

<style scoped>
/* Importing the external CSS file */
@import "@/assets/css/product-form.css";

/* Optional: Style the image preview */
.image-preview {
    max-width: 150px;
    margin-top: 10px;
}
</style>
