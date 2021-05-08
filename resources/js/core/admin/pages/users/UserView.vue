<template>
  <UserViewComponent/>
</template>

<script>
  import UserView from '../../components/users/UserView';

  export default {
    name: 'UserView',
    components: { 'UserViewComponent': UserView }
  }
</script>