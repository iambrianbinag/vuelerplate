<template>
  <AppPermissionsComponent/>
</template>

<script>
  import AppPermissions from '../../components/permissions/AppPermissions';

  export default {
    name: 'AppPermissions',
    components: { 'AppPermissionsComponent': AppPermissions }
  }
</script>
