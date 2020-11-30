<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
        <CCard>
          <CCardHeader>
            <h4>Firmware</h4>
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
  name: "Firmware",
  data: () => {
    return {
      items: [],
      fields: [
        "id",
        "version",
        "registered",
      ],
      currentPage: 1,
      perPage: 6,
      totalRows: 0,
    };
  },
  methods: {
    getFirmware() {
      let self = this;
      axios
        .get("/api/firmware?token=" + localStorage.getItem("api_token"))
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
    this.getFirmware();
  },
};
</script>
