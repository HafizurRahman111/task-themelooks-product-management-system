<template>
  <div class="home">
    <h1>Welcome to the Home Page</h1>
    <p>Choose an option to proceed:</p>
    <div class="button-container">
      <router-link to="/pos">
        <button class="btn">Go to POS</button>
      </router-link>
      <router-link to="/dashboard">
        <button class="btn">Go to Dashboard</button>
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Home',
};
</script>

<style>
.home {
  padding-top: 40px;
  min-height: 100vh;
  text-align: center;
  margin-top: 20px;
}

.button-container {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 20px;
}

.btn {
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
  background-color: #3498db;
  color: white;
  border: none;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.btn:hover {
  background-color: #2980b9;
}
</style>