const files = require.context('./', true, /\.js$/);
const modules = {};

files.keys().forEach(key => {
  if(key === './index.js') return;

  const keyName = key
    .replace('./', '') // Remove leading directory name
    .replace(/\//g, '.') // Replace slashes with dots
    .replace('.js', ''); // Remove file extension
  
  modules[keyName] = files(key).default;
});

export default modules;