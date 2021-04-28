const files = require.context('./', true, /\.js$/);
let routes = [];

files.keys().forEach(key => {
  if(key === './index.js') return;
  const fileRoutes = files(key).default;
  routes = routes.concat(fileRoutes);
});

// Reinsert 404 not found route at last index if any
routes.forEach((route, index) => {
  if(route.path == '*'){
    routes.splice(index, 1);
    routes.push(route);
  }
});

export default routes;