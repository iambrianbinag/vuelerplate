<template>
  <v-container>
    <AppTable
      title="Users"
      :backButton="true"
      :headers="table.headers"
      :data="users"
      :action="getUsers"
      :mutation="setUsers"
      :loading="isLoadingUsers"
      :orderByDefault="table.orderBy"
      :orderDirectionDefault="table.orderDirection"
    >
      <template #action="{ item }">
        <div class="primary">Hello {{ item.name }}, your ID is {{ item.id }}</div>
      </template>
    </AppTable>
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