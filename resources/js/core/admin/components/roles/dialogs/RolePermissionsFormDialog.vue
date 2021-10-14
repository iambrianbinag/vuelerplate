<template>
  <v-row
    justify="center"
  >
    <v-dialog
      v-model="visible"
      persistent
      max-width="700px"
    >
      <LoadingDialog v-if="isLoadingGetRolePermissions || isLoadingGetPermissions" />
      <v-card v-else>
        <v-toolbar
          color="primary"
          dark
          flat
          dense
        >
          <v-toolbar-title>{{ title }}</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-row dense>
            <v-col
              cols="12"
              md="6"
            >
              <v-treeview
                v-model="form.permissions"
                :items="permissions || []"
                selected-color="primary"
                @input="handleRolePermissions"
                selectable
                return-object
                dense
              />
            </v-col>
            <v-spacer class="hidden-sm-and-down">
              <v-divider vertical />
            </v-spacer>
            <v-col
              class="hidden-md-and-up"
              cols="12"
            >
              <v-divider/>
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <div
                v-if="form.permisions && form.permissions.length === 0"
                class="font-weight-light grey--text text-center mt-md-2"
              >
                Select permissions
              </div>
              <v-scroll-x-transition
                group
                hide-on-leave
              >
                <v-chip
                  v-for="(permission, index) in form.permissions"
                  :key="index"
                  color="grey"
                  dark
                  small
                  class="ma-1"
                >
                  <v-icon
                    left
                    small
                  >
                    mdi-account-check
                  </v-icon>
                  {{ permission.name }}
                </v-chip>
              </v-scroll-x-transition>
            </v-col>
            <v-col
              cols="12"
            >
              <v-divider></v-divider>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color='secondary'
            @click="closeDialog"
            small
          >
              Cancel
          </v-btn>
          <v-btn
            :loading="isLoadingSyncRolePermissions"
            color='primary'
            type='submit'
            @click="handleSave"
            small
          >
              Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import LoadingDialog from 'base/components/ui/loading/LoadingDialog';

  export default {
    name: 'RolePermissionsFormDialog',
    components: {
      LoadingDialog,
    },
    props: {
      /**
       * The role details
       */
      role: {
        type: Object,
        default: null,
      },
      /**
       * Visible status of dialog
       */
      visible: {
        type: Boolean,
        default: false,
      },
      /**
       * Get called when action is successful
       */
      successCallback: {
        type: Function,
        default: () => {},
      }
    },
    data(){
      return {
        title: "Role's permissions",
        form: {
          role: null,
          permissions: [],
        }
      }
    },
    computed: {
      ...mapGetters('admin.roles', [
        'rolePermissions',
        'isLoadingGetRolePermissions',
        'isLoadingSyncRolePermissions',
      ]),
      ...mapGetters('admin.permissions', [
        'permissions',
        'isLoadingGetPermissions',
      ]),
    },
    watch: {
      rolePermissions: function(value){
        if(value){
          const { id, name, permissions } = value;
          this.form = {
            role: {
              id,
              name,
            },
            permissions,
          };
        }
      },
    },
    methods: {
      ...mapActions('base.system', [
        'showSnackbar'
      ]),
      ...mapActions('admin.roles', [
        'getRolePermissions',
        'syncRolePermissions'
      ]),
      ...mapActions('admin.permissions', [
        'getPermissions',
      ]),
      /**
       * Close dialog
       */
      closeDialog(){
        this.$emit('update:visible', false);
      },
      /**
       * Get role's permissions
       */
      fetchRolePermissions(){
        if(this.role.id){
          this.getRolePermissions({ id: this.role.id });
        }
      },
      /**
       * Get all permissions
       */
      fetchPermissions(){
        this.getPermissions({ not_paginated: 1 });
      },
      /**
       * Triggered when role's permision in treeview changes
       * 
       * @event input
       * @type {event}
       */
      handleRolePermissions(permissions){
        this.form.permissions = this.sortArrayByKey(permissions, 'order', 'desc');
      },
      /**
       *  Triggered when save button is submitted
       * 
       * @event click
       * @type {event}
       */
      handleSave(){
        const params = {...this.form};

        params.id = params.role.id;
        params.permission_ids = this.pluckArrayByKey(params.permissions, 'id');
        delete params.role;
        delete params.permissions;

        this.syncRolePermissions(params)
          .then((response) => {
            this.showSnackbar({
              message: `Role's permissions updated successfully`
            });
            this.successCallback();
            this.closeDialog();
          });
      },
    },
    mounted(){
      this.fetchRolePermissions();
      this.fetchPermissions();
    }
  }
</script>

<style scoped>
</style>