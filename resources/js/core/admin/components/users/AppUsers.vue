<template>
  <v-container>
    <div class="mb-3">
        <v-btn
          small
          color="primary"
          @click="handleCreateUser"
        >
          <v-icon left>
            mdi-plus
          </v-icon>
          Add
        </v-btn>
    </div>
    <AppTable
      :title="table.title"
      :headers="table.headers"
      :data="users"
      :action="getUsers"
      :mutation="setUsers"
      :loading="isLoadingGetUsers"
      :orderByDefault="table.orderBy"
      :orderDirectionDefault="table.orderDirection"
    >
      <template #action="{ item }">
        <v-tooltip top>
          <template #activator="{ on, attrs }">
            <v-btn
              color="secondary"
              @click="handleUserView(item)"
              v-bind="attrs"
              v-on="on"
              x-small
              icon
            >
              <v-icon>mdi-eye</v-icon>
            </v-btn>
          </template>
          <span>View user</span>
        </v-tooltip>
        <v-tooltip top>
          <template #activator="{ on, attrs }">
            <v-btn
              color="primary"
              @click="handleUserUpdate(item)"
              v-bind="attrs"
              v-on="on"
              x-small
              icon
            >
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
          </template>
          <span>Update user</span>
        </v-tooltip>
      </template>
    </AppTable>
  </v-container>
</template>

<script>
  import { mapGetters, mapActions, mapMutations } from 'vuex';
  import AppTable from 'base/components/ui/tables/AppTable';

  export default {
    name: 'AppUsers',
    components: { AppTable },
    data(){
      return {
        table: {
          title: 'Users',
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
        'isLoadingGetUsers',
      ]),
    },
    methods: {
      ...mapActions('admin.users', ['getUsers']),
      ...mapMutations('admin.users', ['setUsers']),
      /**
       *  Triggered when create button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleCreateUser(){
        this.$router.push({ name: 'user-create' });
      },
      /**
       * Triggered when view button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleUserView(user){
        this.$router.push({ name: 'user-view', params: { id: user.id } });
      },
       /**
       * Triggered when update button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleUserUpdate(user){
        this.$router.push({ name: 'user-update', params: { id: user.id } });
      },
    },
    created(){
      this.setUsers(null);
    },
    mounted(){
      this.getUsers();
    }
  }
</script>

<style scoped>
</style>