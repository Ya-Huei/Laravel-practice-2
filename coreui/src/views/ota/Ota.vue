<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
        <CCard>
          <CCardHeader>
            <CRow>
              <CCol col="6">
                <h4>OTA Records</h4>
              </CCol>
              <CCol col="6" class="d-flex justify-content-end">
                <CButton color="primary" @click="createUser()"
                  >OTA Update</CButton
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
            >
              <template #status="{item}">
                <td>
                  <CBadge :color="item.status.class">{{
                    item.status.name
                  }}</CBadge>
                </td>
              </template>

              <template #operate="{item}">
                <td class="d-flex justify-content-center">
                  <CInputCheckbox> </CInputCheckbox>
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
      fields: ["operate", "serial_no", "region", "address", "firm", "status"],
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
  methods: {
    editLink(id) {
      return `devices/${id.toString()}/edit`;
    },
    repairDevice(id) {
      console.log(id + " should be repaired!!!");
    },
    editDevice(id) {
      const editLink = this.editLink(id);
      this.$router.push({ path: editLink });
    },
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
