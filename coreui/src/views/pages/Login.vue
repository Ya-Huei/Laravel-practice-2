<template>
  <CContainer class="d-flex content-center min-vh-100">
    <CRow>
      <CCol>
        <CCard class="p-4">
          <CCardBody>
            <CForm @submit.prevent="login" method="POST">
              <h1>{{ $t("base.login.index") }}</h1>
              <p class="text-muted">{{ $t("base.login.description") }}</p>
              <p class="text-danger">{{ message }}</p>
              <CInput
                v-model="email"
                prependHtml="<i class='cui-user'></i>"
                :placeholder="$t('base.login.username')"
                autocomplete="username email"
              >
                <template #prepend-content><CIcon name="cil-user"/></template>
              </CInput>
              <CInput
                v-model="password"
                prependHtml="<i class='cui-lock-locked'></i>"
                :placeholder="$t('base.login.password')"
                type="password"
                autocomplete="curent-password"
              >
                <template #prepend-content
                  ><CIcon name="cil-lock-locked"
                /></template>
              </CInput>
              <CRow>
                <CCol col="12" class="d-flex justify-content-end">
                  <CButton type="submit" color="primary" class="px-4">{{
                    $t("base.login.button")
                  }}</CButton>
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
  name: "Login",
  data() {
    return {
      email: "",
      password: "",
      showMessage: false,
      message: "",
    };
  },
  methods: {
    login() {
      let self = this;
      axios
        .post("/api/login", {
          email: self.email,
          password: self.password,
        })
        .then(function(response) {
          self.email = "";
          self.password = "";
          localStorage.setItem("api_token", response.data.access_token);
          localStorage.setItem("user_name", response.data.user_name);
          localStorage.setItem("user_firm", response.data.user_firm);
          localStorage.setItem("user_location", response.data.user_location);
          self.$router.push({ path: response.data.default_page });
        })
        .catch(function(error) {
          self.message = "Incorrect E-mail or password";
          self.showMessage = true;
          console.log(error);
        });
    },
  },
};
</script>
