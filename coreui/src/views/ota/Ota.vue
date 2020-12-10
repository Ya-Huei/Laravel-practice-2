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
                <CButton color="primary" @click="otaUpdate()"
                  >OTA Update</CButton
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
              <template #status="{item}">
                <td>
                  <CBadge :color="item.status.class">{{
                    item.status.name
                  }}</CBadge>
                </td>
              </template>

              <!-- <template #operate="{item}">
                <td>
                  <CButton color="primary" @click="showOta(item.id)"
                    >Detail</CButton
                  >
                </td>
              </template> -->
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
  name: "Ota",
  data: () => {
    return {
      items: [],
      fields: [
        "id",
        "serial_no",
        "type",
        "version",
        "status",
        "registered",
        "updated",
      ],
      currentPage: 1,
      perPage: 6,
      totalRows: 0,
      horizontal: { label: "col-4", input: "col-8" },
    };
  },
  methods: {
    otaLink(id) {
      return `otas/${id.toString()}/show`;
    },
    // showOta(id) {
    //   const otaLink = this.otaLink(id);
    //   this.$router.push({ path: otaLink });
    // },
    otaUpdate() {
      this.$router.push({ path: `otas/update` });
    },
    getOta() {
      let self = this;
      axios
        .get("/api/otas?token=" + localStorage.getItem("api_token"))
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
    this.getOta();
  },
};
</script>
