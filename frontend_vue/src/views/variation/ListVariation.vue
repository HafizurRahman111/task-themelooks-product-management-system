<template>
  <Dashboard>
    <div class="product-list-container">
      <h2>Variation List</h2>

      <!-- Product Cards Grid -->
      <div class="product-cards">
        <div v-if="products.length" class="product-card" v-for="product in paginatedProducts" :key="product.id">
          <div class="product-card-content">
            <div class="product-info">
              <h3>{{ product.product_name }}</h3>
              <p><strong>Variations:</strong></p>
              <ul>
                <li v-for="(value, key) in product.variation_attributes" :key="key">
                  {{ key }}: {{ value }}
                </li>
              </ul>
              <p><strong>Purchase Price:</strong> ${{ product.purchase_price }}</p>
              <p><strong>Selling Price:</strong> ${{ product.selling_price }}</p>
            </div>
          </div>
          <div class="product-actions">
            <button @click="editProduct(product.id)" class="action-button">Edit</button>
            <button @click="deleteProduct(product.id)" class="action-button delete-button">Delete</button>
          </div>
        </div>
      </div>

      <!-- Pagination Controls -->
      <div class="pagination-controls">
        <button v-if="pagination.current_page > 1" @click="changePage(pagination.current_page - 1)"
          class="pagination-button">
          Previous
        </button>
        <span>{{ pagination.current_page }} / {{ pagination.last_page }}</span>
        <button v-if="pagination.current_page < pagination.last_page" @click="changePage(pagination.current_page + 1)"
          class="pagination-button">
          Next
        </button>
      </div>
    </div>
  </Dashboard>
</template>

<script>
export default {
  data() {
    return {
      products: [],
      pagination: {
        current_page: 1,
        last_page: 1,
        products_per_page: 4,
      },
      errorMessage: null,
    };
  },
  mounted() {
    this.fetchProducts();
  },
  computed: {

    paginatedProducts() {
      const start = (this.pagination.current_page - 1) * this.pagination.products_per_page;
      const end = start + this.pagination.products_per_page;
      return this.products.slice(start, end);
    },
  },
  methods: {
    async fetchProducts() {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/variations`);

        if (response.ok) {
          const data = await response.json();
          if (data.status === 'success') {
            this.products = data.data;
            this.pagination.last_page = Math.ceil(this.products.length / this.pagination.products_per_page);
          } else {
            this.errorMessage = 'Failed to fetch products.';
          }
        } else {
          this.errorMessage = 'Failed to fetch products from the server.';
        }
      } catch (error) {
        this.errorMessage = 'An error occurred while fetching products.';
      }
    },

    // Change page based on the selected page number
    changePage(page) {
      if (page >= 1 && page <= this.pagination.last_page) {
        this.pagination.current_page = page;
      }
    },

    viewProduct(id) {
      console.log('Navigating to product page with ID:', id);
      this.$router.push(`/products/${id}`);
    },

    editProduct(id) {

    },

    deleteProduct(id) {

    }

  }
};
</script>

<style scoped>
/* Basic Styling and Layout */
.product-list-container {
  margin: 10px;
  text-align: left;
  font-family: 'Arial', sans-serif;
}

h2 {
  font-size: 1.5em;
  margin-bottom: 10px;
  color: #333;
  font-weight: bold;
  text-transform: uppercase;
}

/* Card Layout for Products */
.product-cards {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 15px;
  margin-top: 15px;
}

.product-card {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.product-card-content {
  padding: 15px;
  text-align: left;
}

.product-image {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 10px;
}

.product-info {
  margin-top: 10px;
  color: #555;
}

.product-info p {
  margin: 5px 0;
  font-size: .85em;
}

.unavailable {
  color: red;
  font-weight: bold;
}

.product-actions {
  display: flex;
  justify-content: space-between;
  padding: 10px;
  background: #f9f9f9;
  border-top: 1px solid #ddd;
}

.action-button {
  padding: 10px 15px;
  font-size: .9em;
  border: none;
  border-radius: 5px;
  background-color: #007bff;
  color: white;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.action-button:hover {
  background-color: #0056b3;
  transform: scale(1.05);
}

.view-button {
  background-color: #28a745;
}

.view-button:hover {
  background-color: #218838;
}

.delete-button {
  background-color: #dc3545;
}

.delete-button:hover {
  background-color: #c82333;
}

/* Pagination Controls */
.pagination-controls {
  display: flex;
  justify-content: center;
  margin-top: 20px;
  font-size: 1em;
  font-weight: bold;
}

.pagination-button {
  background: #007bff;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin: 0 10px;
  transition: background-color 0.3s, transform 0.3s;
}

.pagination-button:disabled {
  background-color: #d3d3d3;
  cursor: not-allowed;
}

.pagination-button:hover:not(:disabled) {
  background-color: #0056b3;
  transform: scale(1.05);
}

.pagination-button:focus {
  outline: none;
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

@media (max-width: 768px) {
  .product-card {
    font-size: 0.9em;
  }

  .product-image {
    height: 160px;
  }

  .pagination-button {
    padding: 10px 15px;
  }
}

@media (max-width: 480px) {
  .product-cards {
    grid-template-columns: 1fr;
  }

  .pagination-button {
    padding: 8px 12px;
  }
}
</style>
