<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard>
        <CCardHeader>
          <h4>Edit Role</h4>
        </CCardHeader>
        <CCardBody>
          <span v-if="showMessage">
            <CAlert v-for="message in messages" :key="message" color="danger">
              {{ message }}
            </CAlert>
          </span>
          <CForm>
            <CInput label="Name" v-model="role.name" horizontal disabled />
            <div class="form-group form-row">
              <CCol tag="label" sm="3" class="col-form-label">
                Permissions
              </CCol>
              <CCol sm="9">
                <CInputCheckbox
                  v-for="optionPermission in optionPermissions"
                  :key="optionPermission.id"
                  :label="optionPermission.name"
                  name="selectRoles"
                  :custom="true"
                  :inline="true"
                  :checked="role.permissions.includes(optionPermission.id)"
                  @update:checked="selectRoles(optionPermission.id)"
                />
              </CCol>
            </div>
          </CForm>
        </CCardBody>
        <CCardFooter class="text-right">
          <CButton :disabled="!isEditedRole" color="primary" @click="update()">
            <span v-if="isEditedRole">Save</span>
            <CSpinner v-if="!isEditedRole" color="info" size="sm" />
          </CButton>
          <CButton color="danger" class="ml-2" @click="goBack">Back</CButton>
        </CCardFooter>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from "axios";
import format from "../mixins/Format.vue";
export default {
  mixins: [format],
  name: "EditUser",
  data: () => {
    return {
      role: {
        name: "",
        permissions: [],
      },
      messages: [],
      horizontal: { label: "col-3", input: "col-9" },
      optionPermissions: [],
      isEditedRole: true,
      showMessage: false,
    };
  },
  methods: {
    goBack() {
      this.$router.go(-1);
    },
    update() {
      let self = this;
      self.isEditedRole = false;
      axios
        .post(
          "/api/roles/" +
            self.$route.params.id +
            "?token=" +
            localStorage.getItem("api_token"),
          {
            _method: "PUT",
            permissions: self.role.permissions,
          }
        )
        .then(function(response) {
          self.goBack();
        })
        .catch(function(error) {
          self.isEditedRole = true;
          self.messages = self.formResponseFormat(error);
          self.showMessage = true;
        });
    },
    selectRoles(menu_id) {
      let temp = this.role.permissions.indexOf(menu_id);
      if (temp > -1) {
        this.role.permissions.splice(temp, 1);
      } else {
        this.role.permissions.push(menu_id);
      }
    },
  },
  beforeCreate: function() {
    let self = this;
    axios
      .get("/api/menu/getAllMenu?token=" + localStorage.getItem("api_token"))
      .then(function(response) {
        self.optionPermissions = response.data;
      })
      .catch(function(error) {
        console.log(error);
        self.$router.push({ path: "/login" });
      });
  },
  mounted: function() {
    let self = this;
    axios
      .get(
        "/api/roles/" +
          self.$route.params.id +
          "/edit?token=" +
          localStorage.getItem("api_token")
      )
      .then(function(response) {
        if (response.data.status == "403") {
          self.$router.push({ path: "/roles" });
          return;
        }
        self.role.name = response.data.name;
        self.role.permissions = response.data.permissions;
      })
      .catch(function(error) {
        console.log(error);
        self.$router.push({ path: "/login" });
      });
  },
};
</script>
