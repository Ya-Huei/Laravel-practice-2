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
        "reason",
        "worker",
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
      return `repairs/${id.toString()}/edit`;
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
    getRepairs() {
      let self = this;
      axios
        .get("/api/repairs?token=" + localStorage.getItem("api_token"))
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
    this.getRepairs();
  },
};
</script>
