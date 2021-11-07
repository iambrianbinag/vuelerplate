<template>
  <UserAccountComponent/>
</template>

<script>
  import UserAccount from '../../components/accounts/UserAccount';

  export default {
    name: 'UserAccount',
    components: { 'UserAccountComponent':  UserAccount }
  }
</script>
