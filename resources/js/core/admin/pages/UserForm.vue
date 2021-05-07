<template>
  <UserFormComponent/>
</template>

<script>
  import UserForm from '../components/users/UserForm';

  export default {
    name: 'UserForm',
    components: { 'UserFormComponent': UserForm }
  }
</script>