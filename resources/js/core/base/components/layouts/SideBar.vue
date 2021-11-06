<template>
  <v-navigation-drawer 
    :value="isSidebarOpen"
    @input="handleNavigationDrawer"
    app
  >
    <v-sheet
      color="white"
      class="pa-2"
    >
      <div class="text-h6 text-center">{{ appName }}</div>
    </v-sheet>
    <v-divider></v-divider>
    <v-list
      dense
      expand
      nav
    >
      <!-- ENTIRE list is wrapped in a template -->
      <template v-for="(item, indexOfItem) in allRoutes">
        <!-- use v-if to determine if 2nd level nesting is needed -->
        <!-- if it's a menu item with no children -->
        <v-list-item
          v-if="!item.children"
          color="indigo"
          :key="`subheader-${indexOfItem}`"
          :to="item.path"
        >
          <v-list-item-icon>
            <v-icon>{{ `mdi-${item.icon}` }}</v-icon>
          </v-list-item-icon>
          <v-list-item-title>{{ item.label }}</v-list-item-title>
        </v-list-item>
        <!-- else if it has children -->
        <v-list-group
          v-else
          :key="`list-group-item-${indexOfItem}`"
          :group="item.path"
          color="indigo"
        >
          <!-- this template is for the title of top-level items with children -->
          <template #activator>
            <v-list-item-content>
              <v-list-item-title>
                <v-icon>{{ `mdi-${item.icon}` }}</v-icon>
                {{ item.label }}
              </v-list-item-title>
            </v-list-item-content>
          </template>
          <!-- this template is for the children/sub-items (2nd level) -->
          <template v-for="(subItem, indexOfSubItem) in item.children">
            <!-- another v-if to determine if there's a 3rd level -->
            <!-- if there is NOT a 3rd level -->
            <v-list-item
              v-if="!subItem.children"
              class="ml-5"
              :key="`subheader-${indexOfSubItem}`"
              :to="item.path + subItem.path"
            >
              <v-list-item-icon class="mr-4">
                <v-icon v-text="`mdi-${subItem.icon}`"/>
              </v-list-item-icon>
              <v-list-item-title class="ml-0">
                {{ subItem.label }}
              </v-list-item-title>
            </v-list-item>
            <!-- if there is a 3rd level -->
            <v-list-group
              v-else
              :key="`list-group-sub-item-${indexOfSubItem}`"
              color="indigo"
              :group="item.path + subItem.path"
              sub-group
            >
              <template #activator>
                <v-list-item-content>
                  <v-list-item-title>
                    <v-icon>{{ `mdi-${subItem.icon}` }}</v-icon>
                    {{ subItem.label }}
                  </v-list-item-title>
                </v-list-item-content>
              </template>
              <template v-for="(subSubItem, indexOfSubSubItem) in subItem.children">
                <v-list-item
                  :key="`subheader-${indexOfSubSubItem}`"
                  color="indigo"
                  :value="true"
                  :to="item.path + subItem.path + subSubItem.path"
                >
                  <v-list-item-title>
                    {{ subItem.label }}
                  </v-list-item-title>
                  <v-list-item-icon>
                    <v-icon>{{ `mdi-${subSubItem.icon}` }}</v-icon>
                  </v-list-item-icon>
                </v-list-item>
              </template>
            </v-list-group>
          </template>
        </v-list-group>
      </template>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';

  export default {
    name: 'SideBar',
    data(){
      return {
        allRoutes: [
          /**
           * DASHBOARD
           */
          {
            path: '/home',
            label: 'Dashboard',
            icon: 'home',
          },
          /**
           * USERS
           */
          {
            path: '/users',
            label: 'System Users',
            icon: 'account-group',
            children: [
              {
                path: '/list',
                label: 'Users',
                icon: 'account-group-outline',
              },
              {
                path: '/roles',
                label: 'Roles',
                icon: 'account-lock-outline',
              },
              {
                path: '/permissions',
                label: 'Permissions',
                icon: 'account-check-outline',
              },
            ]
          },
          /**
           * AUDIT TRAIL
           */
          {
            path: '/audit-trail',
            label: 'Audit Trail',
            icon: 'magnify-plus',
            children: [
              {
                path: '/system-log',
                label: 'System Log',
                icon: 'magnify-minus-outline',
              }
            ],
          },
          /**
           * CONTROL PANEL
           */
          {
            path: '/control-panel',
            label: 'Control Panel',
            icon: 'solar-panel',
            children: [
              {
                path: '/settings',
                label: 'Settings',
                icon: 'cogs',
              },
            ]
          },
        ],
      }
    },
    computed: {
      ...mapGetters('base.system', ['isSidebarOpen']),
      ...mapGetters('base.settings', ['getSettingValueByName']),
      appName: function(){
        return this.getSettingValueByName('name');
      },
    },
    watch: {
      //
    },
    methods: {
      ...mapMutations('base.system', ['setIsSidebarOpen']),
      /**
       * Triggered when nagivation drawer open/close change
       * 
       * @event click
       * @type {event}
       */
      handleNavigationDrawer(isOpen){
        this.setIsSidebarOpen(isOpen);
      },
    },
    mounted(){
      //
    }
  }
</script>