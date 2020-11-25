<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
        <CCard>
          <CCardHeader>
            <h4>Repairs</h4>
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
              :tableFilter='{ external: false, lazy: false }'
              pagination
            >
              <template #operate="{item}">
                <td>
                  <CButton color="primary" @click="editDevice(item.id)"
                    >Edit</CButton
                  >
                  <CButton color="danger" @click="repairDevice(item.id)"
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
  name: "Repairs",
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
