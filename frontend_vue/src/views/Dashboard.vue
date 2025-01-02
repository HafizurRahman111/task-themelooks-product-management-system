<template>
  <div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <h1>Dashboard</h1>
      </div>
      <nav>
        <ul>
          <!-- Home Link -->
          <li>
            <router-link to="/" class="sidebar-link" active-class="active-link">Home</router-link>
          </li>

          <!-- Products Section -->
          <li>
            <router-link to="#" class="sidebar-link" active-class="active-link">
              Products
            </router-link>
            <ul>
              <li>
                <router-link to="/products" class="sidebar-sublink" active-class="active-sublink">
                  Product List
                </router-link>
              </li>
              <li>
                <router-link to="/products/create" class="sidebar-sublink" active-class="active-sublink">
                  Add Product
                </router-link>
              </li>
            </ul>
          </li>

          <!-- Variations Section -->
          <li>
            <router-link to="#" class="sidebar-link" active-class="active-link">
              Variations
            </router-link>
            <ul>
              <li>
                <router-link to="/variations" class="sidebar-sublink" active-class="active-sublink">
                  Variation List
                </router-link>
              </li>
              <li>
                <router-link to="/variations/create" class="sidebar-sublink" active-class="active-sublink">
                  Add Variations
                </router-link>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Top Bar -->
      <header class="topbar">
        <h2>{{ pageTitle }}</h2>
      </header>

      <!-- Dynamic Content -->
      <section class="content">
        <router-view></router-view>
      </section>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      pageTitle: 'Dashboard',
    };
  },
  watch: {
    $route: {
      immediate: true,
      handler(to) {
        this.updatePageTitle(to.name);
      },
    },
  },
  methods: {
    
    updatePageTitle(routeName) {
      const titles = {
        products: 'Products',
        'add-product': 'Add Product',
        variations: 'Variations',
        'add-variation': 'Add Variation',
      };
      this.pageTitle = titles[routeName] || 'Dashboard';
    },
  },
};
</script>

<style scoped>
@import "@/assets/css/dashboard.css";
</style>
