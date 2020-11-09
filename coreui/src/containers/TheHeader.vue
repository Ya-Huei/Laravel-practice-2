<template>
  <CHeader fixed with-subheader light>
    <CToggler
      in-header
      class="ml-3 d-lg-none"
      @click="$store.commit('toggleSidebarMobile')"
    />
    <CToggler
      in-header
      class="ml-3 d-md-down-none"
      @click="$store.commit('toggleSidebarDesktop')"
    />
    <CHeaderBrand class="mx-auto d-lg-none" to="/">
      <CIcon name="logo" height="48" alt="Logo"/>
    </CHeaderBrand>

    <CMenu/>

    <CHeaderNav class="mr-4">
      <CHeaderNavItem class="mx-2">
        <CHeaderNavLink  @click.native="logout()">
          <CIcon name="cil-lock-locked" /> Logout
        </CHeaderNavLink>
      </CHeaderNavItem>
    </CHeaderNav>
    <CSubheader class="px-3">
      <CBreadcrumbRouter class="border-0 mb-0"/>
    </CSubheader>
  </CHeader>
</template>

<script>
import axios from 'axios'
import CMenu from './Menu'

export default {
  name: 'TheHeader',
  components: {
    CMenu
  },
  methods:{
    logout(){
      let self = this;
      axios.post('/api/logout?token=' + localStorage.getItem("api_token"),{})
      .then(function (response) {
        self.$router.push({ path: '/login' });
      }).catch(function (error) {
        console.log(error); 
      });
    }
  }
}
</script>