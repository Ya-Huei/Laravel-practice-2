<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
        <CCard>
          <CCardHeader>
            <CRow>
              <CCol col="6">
                <h4>Roles</h4>
              </CCol>
              <CCol col="6" class="d-flex justify-content-end">
                <CButton color="primary" @click="createRole()"
                  >Create Role</CButton
                >
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
                    v-if="adminName != item.name"
                    color="primary"
                    @click="editRole(item.id)"
                    >Edit</CButton
                  >
                  <CButton
                    v-if="adminName != item.name"
                    color="danger"
                    class="ml-1"
                    @click="deleteRole(item.id)"
                    >Delete</CButton
                  >
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
  name: "Roles",
  data: () => {
    return {
      items: [],
      fields: ["id", "name", "updated", "registered", "operate"],
      currentPage: 1,
      perPage: 6,
      totalRows: 0,
      adminName: "admin",
      showDismissibleAlert: false,
    };
  },
  methods: {
    editLink(id) {
      return `roles/${id.toString()}/edit`;
    },
    createRole() {
      this.$router.push({ path: "roles/create" });
    },
    editRole(id) {
      const editLink = this.editLink(id);
      this.$router.push({ path: editLink });
    },
    deleteRole(id) {
      let self = this;
      let userId = id;
      axios
        .post(
          "/api/roles/" + id + "?token=" + localStorage.getItem("api_token"),
          {
            _method: "DELETE",
          }
        )
        .then(function(response) {
          if (response.data.status == "403") {
            return;
          }
          if (response.data.status == "success") {
            self.message = "Successfully deleted role.";
          } else {
            self.message = response.data.message;
          }
          self.showAlert();
          self.getRoles();
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
    getRoles() {
      let self = this;
      axios
        .get("/api/roles?token=" + localStorage.getItem("api_token"))
        .then(function(response) {
          self.items = response.data;
        })
        .catch(function(error) {
          console.log(error);
          self.$router.push({ path: "/login" });
        });
    },
  },
  mounted: function() {
    this.getRoles();
  },
};
</script>
