<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
        <CCard>
          <CCardHeader class="container-fluid">
            <CRow>
              <CCol col="6">
                <h4>Users</h4>
              </CCol>
              <CCol col="6" class="d-flex justify-content-end">
                <CButton color="primary" @click="createUser()"
                  ><CIcon name="cil-plus"
                /></CButton>
              </CCol>
            </CRow>
          </CCardHeader>
          <CCardBody>
            <CDataTable
              hover
              striped
              :items="items"
              :fields="fields"
              :items-per-page="6"
              pagination
            >
              <template #operate="{item}">
                <td>
                  <CButton
                    v-if="
                      adminName != item.name &&
                        (highestRole == 'admin' ||
                          (highestRole == 'firm owner' &&
                            highestRole != item.roles) ||
                          you == item.id)
                    "
                    color="primary"
                    @click="editUser(item.id)"
                    ><CIcon name="cil-pencil"
                  /></CButton>
                  <CButton
                    v-if="
                      adminName != item.name &&
                        (highestRole == 'admin' ||
                          (highestRole == 'firm owner' &&
                            highestRole != item.roles)) &&
                        you != item.id
                    "
                    color="danger"
                    class="ml-1"
                    @click="deleteUser(item.id)"
                    ><CIcon name="cil-trash"
                  /></CButton>
                </td>
              </template>
            </CDataTable>
          </CCardBody>
        </CCard>
      </transition>
    </CCol>
  </CRow>
</template>

<script>
import axios from "axios";

export default {
  name: "Users",
  data: () => {
    return {
      items: [],
      fields: [],
      adminName: "admin",
      currentPage: 1,
      perPage: 6,
      totalRows: 0,
      you: null,
      showDismissibleAlert: false,
      highestRole: "",
    };
  },
  methods: {
    editLink(id) {
      return `users/${id.toString()}/edit`;
    },
    createUser() {
      this.$router.push({ path: `users/create` });
    },
    editUser(id) {
      const editLink = this.editLink(id);
      this.$router.push({ path: editLink });
    },
    deleteUser(id) {
      let self = this;
      let userId = id;
      axios
        .post(
          "/api/users/" + id + "?token=" + localStorage.getItem("api_token"),
          {
            _method: "DELETE",
          }
        )
        .then(function(response) {
          if (response.data.status == "success") {
            self.message = "Successfully deleted user.";
          } else {
            self.message = response.data.message;
          }
          self.showAlert();
          self.getUsers();
        })
        .catch(function(error) {
          console.log(error);
          self.$router.push({ path: "/login" });
        });
    },
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown;
    },
    showAlert() {
      this.dismissCountDown = this.dismissSecs;
    },
    getFields() {
      let self = this;
      if (localStorage.getItem("user_location") !== "null") {
        self.fields = [
          "id",
          "name",
          "email",
          "roles",
          "updated",
          "registered",
          "operate",
        ];
        return false;
      }
      if (localStorage.getItem("user_firm") !== "null") {
        self.fields = [
          "id",
          "name",
          "email",
          "roles",
          "region",
          "updated",
          "registered",
          "operate",
        ];
        self.highestRole = "firm owner";
        return false;
      }
      self.fields = [
        "id",
        "name",
        "email",
        "roles",
        "region",
        "firm",
        "updated",
        "registered",
        "operate",
      ];
      self.highestRole = "admin";
    },
    getUsers() {
      let self = this;
      axios
        .get("/api/users?token=" + localStorage.getItem("api_token"))
        .then(function(response) {
          self.getFields();
          self.items = response.data.users;
          self.you = response.data.you;
        })
        .catch(function(error) {
          console.log(error);
          self.$router.push({ path: "/login" });
        });
    },
  },
  mounted: function() {
    this.getUsers();
  },
};
</script>
