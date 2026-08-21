<template>
  <div class="kiosk-layout">
    <router-view></router-view>
  </div>
</template>

<style scoped>
.kiosk-layout {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--primary-navy);
  color: white;
}
</style>
