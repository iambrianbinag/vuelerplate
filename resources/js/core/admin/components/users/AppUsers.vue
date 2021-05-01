<template>
  <v-container>
    <AppTable
      :headers="table.headers"
      :data="users"
      :action="getUsers"
      :mutation="setUsers"
      :loading="isLoadingUsers"
      :orderByDefault="table.orderBy"
      :orderDirectionDefault="table.orderDirection"
    />
  </v-container>
</template>

<script>
  import { mapGetters, mapActions, mapMutations } from 'vuex';
  import AppTable from '../../../base/components/ui/tables/AppTable';

  export default {
    name: 'AppUsers',
    components: { AppTable },
    data(){
      return {
        table: {
          headers: [
            { text: 'ID', value: 'id' },
            { text: 'Name', value: 'name' },
            { text: 'Email', value: 'email' },
            {
              text: 'Action',
              align: 'start',
              sortable: false,
              value: 'action',
            },
          ],
          orderBy: 'id',
          orderDirection: 'desc'
        },
      }
    },
    computed: {
      ...mapGetters('admin.users', [
        'users',
        'isLoadingUsers',
      ]),
    },
    methods: {
      ...mapActions('admin.users', ['getUsers']),
      ...mapMutations('admin.users', ['setUsers']),
    },
    mounted(){
      this.getUsers();
    }
  }
</script>

<style scoped>
</style>