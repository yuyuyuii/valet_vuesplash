<!-- index.blade.phpの<div id="app"></div>にこのコンポーネントが描写される -->
<!-- URLによって<RouteView />の部分が他のコンポーネントに切り替わる-->
<template>
  <div>
    <main>
      <div class="container">
        <RouterView />
      </div>
    </main>
  </div>
</template>