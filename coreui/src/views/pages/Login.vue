<template>
  <CContainer class="d-flex content-center min-vh-100">
    <CRow>
      <CCol>
          <CCard class="p-4">
            <CCardBody>
              <CForm @submit.prevent="login" method="POST">
                <h1>Login</h1>
                <p class="text-muted">Sign In to your account</p>
                <p class="text-danger">{{message}}</p>
                <CInput
                  v-model="email"
                  prependHtml="<i class='cui-user'></i>"
                  placeholder="Username"
                  autocomplete="username email"
                >
                  <template #prepend-content><CIcon name="cil-user"/></template>
                </CInput>
                <CInput
                  v-model="password"
                  prependHtml="<i class='cui-lock-locked'></i>"
                  placeholder="Password"
                  type="password"
                  autocomplete="curent-password"
                >
                  <template #prepend-content><CIcon name="cil-lock-locked"/></template>
                </CInput>
                <CRow>
                  <CCol col="12" class="d-flex justify-content-end">
                    <CButton type="submit" color="primary" class="px-4">Login</CButton>
                  </CCol>
                </CRow>
              </CForm>
            </CCardBody>
          </CCard>
      </CCol>
    </CRow>
  </CContainer>
</template>

<script>

import axios from "axios";

    export default {
      name: 'Login',
      data() {
        return {
          email: '',
          password: '',
          showMessage: false,
          message: '',
        }
      },
      methods: {
        login() {
          let self = this;
          axios.post(  '/api/login', {
            email: self.email,
            password: self.password,
          }).then(function (response) {
            self.email = '';
            self.password = '';
            localStorage.setItem("api_token", response.data.access_token);
            self.$router.push({ path: 'users' });
          })
          .catch(function (error) {
            self.message = 'Incorrect E-mail or password';
            self.showMessage = true;
            console.log(error);
          });
  
        }
      }
    }

</script>
