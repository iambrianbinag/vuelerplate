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
      title="Users"
      :headers="table.headers"
      :data="users"
      :action="getUsers"
      :mutation="setUsers"
      :loading="isLoadingUsers"
      :orderByDefault="table.orderBy"
      :orderDirectionDefault="table.orderDirection"
    >
      <template #action="{ item }">
        <v-btn
            icon
            x-small
            color="secondary"
            @click="handleUserView(item)"
          >
          <v-icon>mdi-eye</v-icon>
        </v-btn>
        <v-btn
            icon
            x-small
            color="primary"
            @click="handleUserView(item)"
          >
          <v-icon>mdi-pencil</v-icon>
        </v-btn>
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
      /**
       *  Triggered when create button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleCreateUser(){
        this.$router.push({ name: 'users-create' });
      },
      /**
       * Triggered when view button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleUserView(user){
        this.$router.push({ name: 'users-view', params: { id: user.id } });
      },
       /**
       * Triggered when update button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleUserView(user){
        this.$router.push({ name: 'users-view', params: { id: user.id } });
      },
    },
    mounted(){
      this.getUsers();
    }
  }
</script>

<style scoped>
</style>