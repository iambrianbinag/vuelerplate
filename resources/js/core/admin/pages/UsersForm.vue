<template>
  <UsersFormComponent/>
</template>

<script>
  import UsersForm from '../components/users/UsersForm';

  export default {
    name: 'UsersForm',
    components: { 'UsersFormComponent': UsersForm }
  }
</script>