<template>
  <CCard>
    <CCardHeader>
      <h4>Ota Record Detail : {{ item.id }}</h4>
    </CCardHeader>
    <CCardBody>
      <p>
        <strong>serial_no:{{ item.device.serial_no }}</strong>
      </p>
      <p>type: {{ item.type }}</p>
      <p>detail: {{ item.detail }}</p>
      <p>
        status:
        <CBadge :color="item.status.class">{{ item.status.name }}</CBadge>
      </p>
      <p>registered: {{ item.registered }}</p>
      <p>updated: {{ item.updated }}</p>
    </CCardBody>
  </CCard>
</template>

<script>
import axios from "axios";

export default {
  name: "ShowOta",
  data: () => {
    return {
      item: {
        id: "",
        device: "",
        type: "",
        detail: "",
        status: "",
        registered: "",
        updated: "",
      },
    };
  },
  methods: {
    getOtaDetail() {
      let self = this;
      axios
        .get(
          "/api/ota/" +
            self.$route.params.id +
            "show?token=" +
            localStorage.getItem("api_token")
        )
        .then(function(response) {
          self.item = response.data;
        })
        .catch(function(error) {
          console.log(error);
          //   self.$router.push({ path: "/login" });
        });
    },
  },
  mounted: function() {
    this.getOtaDetail();
  },
};
</script>
