<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
        <CCard>
          <CCardHeader>
            <h4>Devices</h4>
          </CCardHeader>
          <CCardBody>
            <CDataTable
              hover
              striped
              :items="items"
              :fields="fields"
              :items-per-page="6"
              :tableFilter="{ external: false, lazy: false }"
              pagination
            >
              <template #status="{item}">
                <td>
                  <CBadge :color="item.status.class">{{
                    item.status.name
                  }}</CBadge>
                </td>
              </template>

              <template #operate="{item}">
                <td>
                  <CButton color="primary" @click="editDevice(item.id)"
                    >Edit</CButton
                  >
                  <CButton
                    color="danger"
                    @click="repairDevice(item.id)"
                    v-if="item.status.name != 'Repair'"
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
      fields: [],
      currentPage: 1,
      perPage: 6,
      totalRows: 0,
      horizontal: { label: "col-4", input: "col-8" },
    };
  },
  methods: {
    editLink(id) {
      return `devices/${id.toString()}/edit`;
    },
    repairLink(id) {
      return `devices/${id.toString()}/repair`;
    },
    repairDevice(id) {
      const repairLink = this.repairLink(id);
      this.$router.push({ path: repairLink });
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
    getFields() {
      let self = this;
      if (localStorage.getItem("user_location") !== "null") {
        self.fields = [
          "serial_no",
          "address",
          "status",
          "enabled",
          "registered",
          "updated",
          "operate",
        ];
        return false;
      }

      if (localStorage.getItem("user_firm") !== "null") {
        self.fields = [
          "serial_no",
          "region",
          "address",
          "status",
          "enabled",
          "registered",
          "updated",
          "operate",
        ];
        return false;
      }

      self.fields = [
        "serial_no",
        "region",
        "address",
        "firm",
        "status",
        "enabled",
        "registered",
        "updated",
        "operate",
      ];
    },
    getDevices() {
      let self = this;
      axios
        .get("/api/devices?token=" + localStorage.getItem("api_token"))
        .then(function(response) {
          self.getFields();
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
