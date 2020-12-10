<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
        <CCard>
          <CCardHeader>
            <CRow>
              <CCol col="4">
                <CSelect
                  label="Type"
                  :options="otaOptions"
                  :value.sync="ota"
                  @change="loadDetail()"
                  horizontal
                  class="mb-0"
                />
              </CCol>
              <CCol col="4"
                ><CSelect
                  label="Name"
                  :options="detailOptions"
                  :value.sync="detail"
                  horizontal
                  class="mb-0"
              /></CCol>
              <CCol col="4" class="d-flex justify-content-end">
                <CButton
                  color="secondary"
                  @click="checkedAll()"
                  v-model="selectAll"
                  class="mr-2"
                  >Select All
                </CButton>
                <CButton
                  :disabled="!isUpdateOta"
                  color="primary"
                  @click="update()"
                >
                  <span v-if="isUpdateOta">Update</span>
                  <CSpinner v-if="!isUpdateOta" color="info" size="sm" />
                </CButton>
              </CCol>
            </CRow>
          </CCardHeader>
          <CCardBody>
            <span v-if="showMessage">
              <CAlert v-for="message in messages" :key="message" color="danger">
                {{ message }}
              </CAlert>
            </span>
            <CDataTable
              hover
              striped
              :items="items"
              :fields="fields"
              :items-per-page="6"
              pagination
              :tableFilter="{ external: false, lazy: false }"
              @filtered-items-change="setFilterItem"
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
                  <CInputCheckbox
                    color="primary"
                    :checked="select.includes(item.id)"
                    @update:checked="checked(item.id)"
                    >Edit</CInputCheckbox
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
import format from "../mixins/Format.vue";

export default {
  mixins: [format],
  name: "UpdateOta",
  data: () => {
    return {
      items: [],
      fields: [],
      messages: [],
      horizontal: { label: "col-4", input: "col-8" },
      isUpdateOta: true,
      showMessage: false,
      otaOptions: [
        { value: "1", label: "firmware" },
        {
          value: "2",
          label: "recipe",
        },
      ],
      detailOptions: [],
      ota: "1",
      detail: "1",
      firmware: [],
      recipe: [],
      select: [],
      selectAll: false,
      filterItem: [],
    };
  },
  methods: {
    setFilterItem(value) {
      let self = this;
      self.selectAll = false;
      self.filterItem = value;
    },
    goBack() {
      this.$router.go(-1);
    },
    loadDetail() {
      let self = this;
      if (self.ota == "1") {
        self.detailOptions = self.firmware;
      } else if (self.ota == "2") {
        self.detailOptions = self.recipe;
      }
      self.detail = self.detailOptions[0].value;
    },
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown;
    },
    showAlert() {
      this.dismissCountDown = this.dismissSecs;
    },
    checkedAll() {
      let self = this;
      self.selectAll = !self.selectAll;
      if (self.selectAll) {
        for (let i in self.filterItem) {
          self.select.push(self.filterItem[i].id);
        }
      } else {
        self.select = [];
      }
    },
    checked(id) {
      let self = this;
      let temp = self.select.indexOf(id);
      if (temp > -1) {
        self.select.splice(temp, 1);
      } else {
        self.select.push(id);
      }
    },
    update() {
      let self = this;
      self.isUpdateOta = false;
      axios
        .post(
          "/api/otas/saveOtaUpdate?token=" + localStorage.getItem("api_token"),
          {
            _method: "POST",
            type: self.ota,
            name: self.detail,
            devices: self.select,
          }
        )
        .then(function(response) {
          self.goBack();
        })
        .catch(function(error) {
          self.isUpdateOta = true;
          self.messages = self.formResponseFormat(error);
          self.showMessage = true;
        });
    },
    getFields() {
      let self = this;
      if (localStorage.getItem("user_location") !== "null") {
        self.fields = ["operate", "serial_no", "address", "status"];
        return false;
      }
      if (localStorage.getItem("user_firm") !== "null") {
        self.fields = ["operate", "serial_no", "region", "address", "status"];
        return false;
      }
      self.fields = [
        "operate",
        "serial_no",
        "region",
        "address",
        "firm",
        "status",
      ];
    },
    getInfo() {
      let self = this;
      axios
        .get(
          "/api/otas/getOtaUpdateInfo?token=" +
            localStorage.getItem("api_token")
        )
        .then(function(response) {
          self.getFields();
          self.items = response.data.devices;
          self.firmware = response.data.firmware;
          self.recipe = response.data.recipe;
          self.detailOptions = self.firmware;
          self.detail = self.detailOptions[0].value;
        })
        .catch(function(error) {
          console.log(error);
          self.$router.push({ path: "/login" });
        });
    },
  },
  mounted: function() {
    this.getInfo();
  },
};
</script>
