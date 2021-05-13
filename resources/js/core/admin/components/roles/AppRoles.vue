<template>
  <v-container>
    <div class="mb-3">
        <v-btn
          small
          color="primary"
          @click="handleCreateRole"
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
      :data="roles"
      :action="getRoles"
      :mutation="setRoles"
      :loading="isLoadingGetRoles"
    >
      <template #action="{ item }">
        <v-btn
            icon
            x-small
            color="secondary"
            @click="handleRoleView(item)"
          >
          <v-icon>mdi-eye</v-icon>
        </v-btn>
        <v-btn
            icon
            x-small
            color="primary"
            @click="handleRoleUpdate(item)"
          >
          <v-icon>mdi-pencil</v-icon>
        </v-btn>
      </template>
    </AppTable>
    <template v-if="action.isVisible">
      <RoleFormDialog 
        :visible.sync="action.isVisible" 
        :role="action.role"
        :successCallback="getRoles"
      />
    </template>
  </v-container>
</template>

<script>
  import { mapGetters, mapActions, mapMutations } from 'vuex';
  import AppTable from '../../../base/components/ui/tables/AppTable';
  import RoleFormDialog from './dialogs/RoleFormDialog';
  import LoadingDialog from '../../../base/components/ui/loading/LoadingDialog';

  export default {
    name: 'AppRoles',
    components: { AppTable, RoleFormDialog, LoadingDialog },
    data(){
      return {
        action: {
          isVisible: false,
          role: null,
        },
        table: {
          title: 'Roles',
          headers: [
            { text: 'Name', value: 'name' },
            {
              text: 'Action',
              align: 'start',
              sortable: false,
              value: 'action',
            },
          ],
        },
      }
    },
    computed: {
      ...mapGetters('admin.roles', [
        'roles',
        'isLoadingGetRoles',
      ]),
    },
    watch: {
      action: {
        handler(value){
          if(!value.isVisible){
            this.action.role = null;
          }
        },
        deep: true,
      },
    },
    methods: {
      ...mapActions('admin.roles', ['getRoles']),
      ...mapMutations('admin.roles', ['setRoles']),
      /**
       *  Triggered when create button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleCreateRole(){
        this.action.isVisible = true;
      },
      /**
       * Triggered when view button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleRoleView(user){
        // this.$router.push({ name: 'user-view', params: { id: user.id } });
      },
       /**
       * Triggered when update button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleRoleUpdate(role){
        this.action = {
          isVisible: true,
          role: role,
        }
      },
    },
    created(){
      this.setRoles(null);
    },
    mounted(){
      this.getRoles();
    }
  }
</script>

<style scoped>
</style>