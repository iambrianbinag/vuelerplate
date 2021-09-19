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
        <v-tooltip top>
          <template #activator="{ on, attrs }">
            <v-btn
              color="secondary"
              @click="handleRoleView(item)"
              v-bind="attrs"
              v-on="on"
              x-small
              icon
            >
              <v-icon>mdi-eye</v-icon>
            </v-btn>
          </template>
          <span>View role</span>
        </v-tooltip>
        <v-tooltip top>
          <template #activator="{ on, attrs }">
            <v-btn
              color="primary"
              @click="handleRoleUpdate(item)"
              v-bind="attrs"
              v-on="on"
              x-small
              icon
            >
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
          </template>
          <span>Update role</span>
        </v-tooltip>
        <v-tooltip top>
          <template #activator="{ on, attrs }">
            <v-btn
              color="info"
              @click="handleRolePermissions(item)"
              v-bind="attrs"
              v-on="on"
              x-small
              icon
            >
              <v-icon>mdi-account-check</v-icon>
            </v-btn>
          </template>
          <span>Permissions</span>
        </v-tooltip>
      </template>
    </AppTable>
    <template v-if="action.isVisible">
      <RoleViewDialog
        v-if="action.type == 'VIEW'"
        :visible.sync="action.isVisible"
        :role="action.role"
      />
      <RolePermissionsFormDialog
        v-else-if="action.type == 'ROLE PERMISSIONS'"
        :visible.sync="action.isVisible"
        :role="action.role"
      />
      <RoleFormDialog 
        v-else
        :visible.sync="action.isVisible" 
        :role="action.role"
        :successCallback="getRoles"
      />
    </template>
  </v-container>
</template>

<script>
  import { mapGetters, mapActions, mapMutations } from 'vuex';
  import AppTable from 'base/components/ui/tables/AppTable';
  import RoleFormDialog from './dialogs/RoleFormDialog';
  import RoleViewDialog from './dialogs/RoleViewDialog';
  import RolePermissionsFormDialog from './dialogs/RolePermissionsFormDialog';

  export default {
    name: 'AppRoles',
    components: { 
      AppTable, 
      RoleFormDialog, 
      RoleViewDialog, 
      RolePermissionsFormDialog,
    },
    data(){
      return {
        action: {
          type: null, // ADD, UPDATE, VIEW, or ROLE PERMISSIONS
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
      'action.isVisible': function(value){
        if(!value){
          this.action = {
            type: null,
            isVisible: false,
            role: null,
          }
        }
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
        this.action = {
          type: 'ADD',
          isVisible: true,
          role: null,
        }
      },
      /**
       * Triggered when view button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleRoleView(role){
        this.action = {
          type: 'VIEW',
          isVisible: true,
          role,
        }
      },
       /**
       * Triggered when update button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleRoleUpdate(role){
        this.action = {
          type: 'UPDATE',
          isVisible: true,
          role,
        }
      },
       /**
       * Triggered when role's permissions button is clicked
       * 
       * @event click
       * @type {event}
       */
      handleRolePermissions(role){
        this.action = {
          type: 'ROLE PERMISSIONS',
          isVisible: true,
          role,
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