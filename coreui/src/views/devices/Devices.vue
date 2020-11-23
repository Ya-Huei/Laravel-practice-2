<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
        <CCard>
          <CCardHeader>
            <CRow>
              <CCol col="5">
                <CInput label="Serial_no" horizontal class="mb-2" />
              </CCol>
              <CCol col="5">
                <CInput label="Region" horizontal class="mb-2" />
              </CCol>
            </CRow>
            <CRow>
              <CCol col="5">
                <CInput label="Firm" horizontal class="mb-0" />
              </CCol>
              <CCol col="5">
                <CInput label="Status" horizontal class="mb-0" />
              </CCol>
              <CCol col="2" class="d-flex justify-content-end">
                <CButton color="success" @click="editDevice(item.id)"
                  >Search</CButton
                >
              </CCol>
            </CRow>
          </CCardHeader>
          <CCardBody>
            <CAlert :show.sync="dismissCountDown" color="danger" fade>
              ({{ dismissCountDown }}) {{ message }}
            </CAlert>
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
                    color="primary"
                    @click="editDevice(item.id)"
                    >Edit</CButton
                  >
                  <CButton
                    color="danger"
                    @click="repairDevice(item.id)"
                    >Repair</CButton
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
  name: "Devices",
  data: () => {
    return {
      items: [],
      fields: [
        "id",
        "serial_no",
        "region",
        "address",
        "firm",
        "status",
        "registered",
        "updated",
        "operate",
      ],
      currentPage: 1,
      perPage: 6,
      totalRows: 0,
      message: "",
      showMessage: false,
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false,
      horizontal: { label: "col-4", input: "col-8" },
    };
  },
  paginationProps: {
    align: "center",
    doubleArrows: false,
    previousButtonHtml: "prev",
    nextButtonHtml: "next",
  },
  methods: {
    editLink(id) {
      return `devices/${id.toString()}/edit`;
    },
    repairDevice(id) {
      console.log(id + " should be repaired!!!");
      // this.$router.push({ path: "roles/create" });
    },
    editDevice(id) {
      const editLink = this.editLink(id);
      this.$router.push({ path: editLink });
    },
    // deleteRole(id) {
    //   let self = this;
    //   let userId = id;
    //   axios
    //     .post(
    //       "/api/roles/" + id + "?token=" + localStorage.getItem("api_token"),
    //       {
    //         _method: "DELETE",
    //       }
    //     )
    //     .then(function(response) {
    //       if (response.data.status == "403") {
    //         return;
    //       }
    //       if (response.data.status == "success") {
    //         self.message = "Successfully deleted role.";
    //       } else {
    //         self.message = response.data.message;
    //       }
    //       self.showAlert();
    //       self.getRoles();
    //     })
    //     .catch(function(error) {
    //       console.log(error);
    //       self.$router.push({ path: "/login" });
    //     });
    // },
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown;
    },
    showAlert() {
      this.dismissCountDown = this.dismissSecs;
    },
    getDevices() {
      let self = this;
      axios
        .get("/api/devices?token=" + localStorage.getItem("api_token"))
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
    this.getDevices();
  },
};
</script>
