<template>
  <v-navigation-drawer app>
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
      <template v-for="(item, i) in allRoutes">
        <template v-if="isShowInSidebar(item)">
          <!-- use v-if to determine if 2nd level nesting is needed -->
          <!-- if it's a menu item with no children -->
          <v-list-item
            v-if="!item.children"
            color="indigo"
            :key="`subheader-${i}`"
            :to="item.path"
          >
            <v-list-item-icon>
              <v-icon v-if="isIconExistsInMeta(item)">{{ `mdi-${item.meta.icon}` }}</v-icon>
            </v-list-item-icon>
            <v-list-item-title v-if="isLabelExistsInMeta(item)">{{ item.meta.label }}</v-list-item-title>
          </v-list-item>
          <!-- else if it has children -->
          <v-list-group
            v-else
            :key="i"
            :group="item.path"
            color="indigo"
          >
            <!-- this template is for the title of top-level items with children -->
            <template #activator>
              <v-list-item-content>
                <v-list-item-title>
                  <v-icon v-if="isIconExistsInMeta(item)">{{ `mdi-${item.meta.icon}` }}</v-icon>
                  {{ isLabelExistsInMeta(item) ? item.meta.label : ''}}
                </v-list-item-title>
              </v-list-item-content>
            </template>
            <!-- this template is for the children/sub-items (2nd level) -->
            <template v-for="(subItem, j) in item.children">
                <template v-if="isShowInSidebar(subItem)">
                <!-- another v-if to determine if there's a 3rd level -->
                <!-- if there is NOT a 3rd level -->
                <v-list-item
                  v-if="!subItem.children"
                  class="ml-5"
                  :key="`subheader-${j}`"
                  :to="`${item.path}/${subItem.path}`"
                >
                  <v-list-item-icon class="mr-4">
                    <v-icon
                      v-if="isIconExistsInMeta(subItem)" 
                      v-text="`mdi-${subItem.meta.icon}`" 
                    />
                  </v-list-item-icon>
                  <v-list-item-title class="ml-0">
                    {{ isLabelExistsInMeta(subItem) ? subItem.meta.label : ''}}
                  </v-list-item-title>
                </v-list-item>
                <!-- if there is a 3rd level -->
                <v-list-group
                  v-else
                  :key="j"
                  color="indigo"
                  :group="subItem.path"
                  sub-group
                >
                  <template #activator>
                    <v-list-item-content>
                      <v-list-item-title>
                        <v-icon v-if="isIconExistsInMeta(subItem)">{{ `mdi-${subItem.meta.icon}` }}</v-icon>
                        {{ isLabelExistsInMeta(subItem) ? subItem.meta.label : ''}}
                      </v-list-item-title>
                    </v-list-item-content>
                  </template>
                  <template v-for="(subSubItem, k) in subItem.children">
                    <template v-if="isShowInSidebar(subSubItem)">
                      <v-list-item
                        :key="`subheader-${k}`"
                        color="indigo"
                        :value="true"
                        :to="`${item.path}/${subItem.path}/${subSubItem.path}`"
                      >
                        <v-list-item-title>
                          {{ isLabelExistsInMeta(subItem) ? subItem.meta.label : ''}}
                        </v-list-item-title>
                        <v-list-item-icon>
                          <v-icon v-if="isIconExistsInMeta(subSubItem)">{{ `mdi-${subSubItem.meta.icon}` }}</v-icon>
                        </v-list-item-icon>
                      </v-list-item>
                    </template>
                  </template>
                </v-list-group>
              </template>
            </template>
          </v-list-group>
        </template>
      </template>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    name: 'SideBar',
    data(){
      return {
        allRoutes: this.$router.options.routes,
        allRoutess: [
          {
            name: 'No Children (1 level)',
            path: '/no-children',
            meta: {
              icon: 'baby-carriage-off',
              showInSideBar: true,
            },
          },
          {
            name: 'Attractions (2 levels)',
            path: '/attractions',
            meta: {
              icon: 'airballoon',
              showInSideBar: true,
            },
            children: [
              {
                name: 'Carnivals',
                path: '/carnivals',
                meta: {
                  icon: 'drama-masks',
                  showInSideBar: false,
                },
              },
              {
                name: 'Museums',
                path: '/museums',
                meta: {
                  icon: 'bank',
                  showInSideBar: true,
                },
              },
            ]
          },
          {
            name: 'Restaurants (3 levels)',
            path: '/restaurants',
            meta: {
              icon: 'silverware-fork-knife',
              showInSideBar: true,
            },
            children: [
              {
                name: 'Japanese',
                path: '/japanese',
                meta: {
                  icon: 'map-marker-radius-outline',
                  showInSideBar: true,
                },
                children: [
                  {
                    name: 'Hikari Sushi',
                    path: '/hikari-sushi',
                    meta: {
                      icon: 'food-croissant',
                      showInSideBar: true,
                    },
                  },
                  {
                    name: 'Late Night Ramen',
                    path: '/late-night-ramen',
                    meta: {
                      icon: 'noodles',
                      showInSideBar: true,
                    },
                  },
                ]
              },
              {
                name: 'Italian',
                path: '/italian',
                meta: {
                  icon: 'map',
                  showInSideBar: true,
                },
                children: [
                  {
                    name: 'Jersey Pizza',
                    path: '/jersey-pizza',
                    meta: {
                      icon: 'pizza',
                      showInSideBar: true,
                    },
                  },
                  {
                    name: 'Im-pasta-ble',
                    path: '/im-pasta-ble',
                    meta: {
                      icon: 'pasta',
                      showInSideBar: false,
                    },
                  },
                ]
              },
              {
                name: 'Mexican',
                path: '/mexican',
                meta: {
                  icon: 'map-marker',
                },
                children: [
                  {
                    name: 'Taco Gato',
                    path: '/taco-gato',
                    meta: {
                      icon: 'taco',
                    },
                  },
                  {
                    name: 'A-maize-ing',
                    path: '/a-maize-ing',
                    meta: {
                      icon: 'corn',
                    },
                  },
                ]
              },
            ]
          },
        ]
      }
    },
    computed: {
      ...mapGetters('base.system', ['appName']),
    },
    methods: {
      /**
       * Check if icon property exists in meta property of route
       * 
       * @param {object} route object to be checked
       * @returns {boolean}
       */
      isIconExistsInMeta(route){
        if(!route.meta){
          return false;
        }
        if(route.meta.icon){
          return true;
        }
      },
      /**
       * Check if label property exists in meta property of route
       * 
       * @param {object} route object to be checked
       * @returns {boolean}
       */
      isLabelExistsInMeta(route)
      {
        if(!route.meta){
          return false;
        }
        if(route.meta.label){
          return true;
        }
      },
      /**
       * Check if showInSideBar property exists in meta property of route and value is true
       * 
       * @param {object} route
       * @returns {boolean}
       */
      isShowInSidebar(route){
        if(!route.meta){
          return false;
        }
        if(route.meta.showInSideBar){
          return true;
        }
      }
    },
    mounted(){
      //
    }
  }
</script>