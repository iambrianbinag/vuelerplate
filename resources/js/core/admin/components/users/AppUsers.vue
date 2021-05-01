<template>
  <v-container>
    <AppTable
      :headers="
        [
            { text: 'ID', value: 'id' },
            { text: 'Name', value: 'name' },
            { text: 'Email', value: 'email' },
            {
              text: 'Action',
              align: 'start',
              sortable: false,
              value: 'action',
            },
          ]
        "
      :data="users"
      :action="getUsers"
      :loading="isLoadingUsers"
      orderByDefault="id"
      orderDirectionDefault="asc"
    />
  </v-container>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import AppTable from '../../../base/components/ui/tables/AppTable';

  export default {
    name: 'AppUsers',
    components: { AppTable },
    computed: {
      ...mapGetters('admin.users', [
        'users',
        'isLoadingUsers',
      ]),
    },
    methods: {
      ...mapActions('admin.users', ['getUsers']),
    },
    mounted(){
      this.getUsers();
    }
  }
</script>

<style scoped>
</style>