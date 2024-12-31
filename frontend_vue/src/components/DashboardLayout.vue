<template>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1>Dashboard</h1>
            </div>
            <nav>
                <ul>
                    <li>
                        <router-link to="/" class="sidebar-link" active-class="active-link">Home</router-link>
                    </li>
                    <li>
                        <router-link to="/products" class="sidebar-link" active-class="active-link">
                            Products
                        </router-link>
                        <ul>
                            <li>
                                <router-link to="/products/create" class="sidebar-sublink"
                                    active-class="active-sublink">
                                    Add Product
                                </router-link>
                            </li>
                        </ul>
                        <ul>
                            <li>
                                <router-link to="/variations/create" class="sidebar-sublink"
                                    active-class="active-sublink">
                                    Add Variations
                                </router-link>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <router-link to="/orders" class="sidebar-link" active-class="active-link">Orders</router-link>
                    </li>
                    <li>
                        <router-link to="/customers" class="sidebar-link"
                            active-class="active-link">Customers</router-link>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <header class="topbar">
                <div class="topbar-title">
                    <h2>{{ pageTitle }}</h2>
                </div>
            </header>

            <!-- Dynamic Content -->
            <section class="content">
                <!-- Render dynamic content here -->
                <router-view></router-view> <!-- This will render the component based on the active route -->
            </section>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            pageTitle: 'Dashboard', // Default title
        };
    },
    watch: {
        // Watch for route changes and update the title accordingly
        $route(to) {
            this.updatePageTitle(to);
        },
    },
    methods: {
        updatePageTitle(route) {
            if (route.name === 'products') {
                this.pageTitle = 'Products';
            } else if (route.name === 'add-product') {
                this.pageTitle = 'Add Product';
            } else if (route.name === 'orders') {
                this.pageTitle = 'Orders';
            } else if (route.name === 'customers') {
                this.pageTitle = 'Customers';
            } else {
                this.pageTitle = 'Dashboard'; // Default title
            }
        },
        goToHomePage() {
            this.$router.push('/'); // Navigate to the homepage
        },
    },
};
</script>

<style src="../assets/css/Dashboard.css"></style>
